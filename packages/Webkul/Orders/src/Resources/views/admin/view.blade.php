@php
    use Webkul\Orders\Enums\OrderStatus;

    $status = $order->status instanceof OrderStatus ? $order->status : (OrderStatus::tryFrom((string) $order->status) ?? OrderStatus::New);

    $money = fn ($v) => number_format((float) $v, 2, ',', ' ').' '.$order->currency;

    $addr = function (?array $a): string {
        if (empty($a)) {
            return '—';
        }
        $lines = array_filter([
            $a['name'] ?? null,
            $a['company'] ?? null,
            $a['street'] ?? null,
            trim(($a['zip'] ?? '').' '.($a['city'] ?? '')),
            $a['region'] ?? null,
            $a['country'] ?? null,
            isset($a['nip']) ? 'NIP: '.$a['nip'] : null,
        ]);

        return implode('<br>', array_map('e', $lines));
    };
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('orders::app.view.title', ['number' => $order->channel_order_number])
    </x-slot>

    <div class="flex items-center justify-between gap-4 mb-4">
        <div class="flex items-center gap-3">
            <x-admin::back-button :href="route('admin.sales.orders.index')" />

            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('orders::app.view.title', ['number' => $order->channel_order_number])
            </p>

            <span class="{{ $status->labelClass() }}">{{ trans($status->label()) }}</span>
        </div>
    </div>

    {{-- Items --}}
    <div class="bg-white dark:bg-cherry-800 rounded-lg border border-gray-200 dark:border-cherry-800 mb-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-200 dark:border-cherry-800 text-left text-gray-500 dark:text-gray-300">
                <tr>
                    <th class="p-3 font-medium">@lang('orders::app.view.item.product-id')</th>
                    <th class="p-3 font-medium">@lang('orders::app.view.item.name')</th>
                    <th class="p-3 font-medium text-right">@lang('orders::app.view.item.qty')</th>
                    <th class="p-3 font-medium text-right">@lang('orders::app.view.item.price')</th>
                    <th class="p-3 font-medium text-right">@lang('orders::app.view.item.vat')</th>
                    <th class="p-3 font-medium text-right">@lang('orders::app.view.item.weight')</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($order->items as $item)
                    <tr class="border-b border-gray-100 dark:border-cherry-900 last:border-0">
                        <td class="p-3 text-gray-500 dark:text-gray-400">{{ $item->external_product_id ?? $item->product_id ?? '—' }}</td>
                        <td class="p-3">
                            <span class="text-gray-800 dark:text-white">{{ $item->name }}</span>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                @if ($item->ean) EAN {{ $item->ean }} @endif
                                @if ($item->sku) &nbsp;·&nbsp; SKU {{ $item->sku }} @endif
                            </div>
                        </td>
                        <td class="p-3 text-right">{{ $item->qty }}</td>
                        <td class="p-3 text-right">{{ $money($item->price) }}</td>
                        <td class="p-3 text-right">{{ $item->tax_percent !== null ? rtrim(rtrim(number_format((float) $item->tax_percent, 2), '0'), '.').'%' : '—' }}</td>
                        <td class="p-3 text-right">{{ $item->weight !== null ? $item->weight : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-4 text-center text-gray-500">@lang('orders::app.view.no-items')</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        {{-- Order info --}}
        <div class="bg-white dark:bg-cherry-800 rounded-lg border border-gray-200 dark:border-cherry-800 p-4">
            <p class="font-semibold text-gray-800 dark:text-white mb-3">@lang('orders::app.view.info.title')</p>

            <div class="grid grid-cols-[max-content_1fr] gap-x-6 gap-y-2 text-sm">
                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.paid')</span>
                <span class="text-gray-800 dark:text-white">{{ $money($order->paid) }} / {{ $money($order->total) }}</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.customer')</span>
                <span class="text-gray-800 dark:text-white">{{ $order->customer_name }}@if ($order->customer_login) ({{ $order->customer_login }})@endif</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.email')</span>
                <span class="text-gray-800 dark:text-white">{{ $order->customer_email ?? '—' }}</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.phone')</span>
                <span class="text-gray-800 dark:text-white">{{ $order->customer_phone ?? '—' }}</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.source')</span>
                <span class="text-gray-800 dark:text-white">{{ $order->source ?? '—' }} ({{ ucfirst($order->channel) }})</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.delivery-method')</span>
                <span class="text-gray-800 dark:text-white">{{ $order->delivery_method ?? '—' }}</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.cod')</span>
                <span class="text-gray-800 dark:text-white">{{ $order->cod ? trans('orders::app.view.yes') : trans('orders::app.view.no') }}</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.delivery-cost')</span>
                <span class="text-gray-800 dark:text-white">{{ $order->delivery_cost !== null ? $money($order->delivery_cost) : '—' }}</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.payment-method')</span>
                <span class="text-gray-800 dark:text-white">{{ $order->payment_method ?? '—' }}</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.ordered-at')</span>
                <span class="text-gray-800 dark:text-white">{{ $order->ordered_at?->format('d.m.Y H:i') ?? '—' }}</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.info.status-at')</span>
                <span class="text-gray-800 dark:text-white">{{ $order->status_changed_at?->format('d.m.Y H:i') ?? '—' }}</span>
            </div>
        </div>

        {{-- Symfonia link — the source of truth this console mirrors (doc §3) --}}
        <div class="bg-white dark:bg-cherry-800 rounded-lg border border-gray-200 dark:border-cherry-800 p-4">
            <p class="font-semibold text-gray-800 dark:text-white mb-3">@lang('orders::app.view.symfonia.title')</p>

            <div class="grid grid-cols-[max-content_1fr] gap-x-6 gap-y-2 text-sm">
                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.symfonia.document')</span>
                <span class="text-gray-800 dark:text-white font-medium">{{ $order->symfonia_document_number ?? trans('orders::app.view.symfonia.not-created') }}</span>

                <span class="text-gray-500 dark:text-gray-400">@lang('orders::app.view.symfonia.transaction')</span>
                <span class="text-gray-800 dark:text-white break-all">{{ $order->external_transaction_id ?? '—' }}</span>
            </div>

            <p class="text-xs text-gray-400 dark:text-gray-500 mt-4">@lang('orders::app.view.symfonia.hint')</p>
        </div>
    </div>

    {{-- Addresses --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-cherry-800 rounded-lg border border-gray-200 dark:border-cherry-800 p-4">
            <p class="font-semibold text-gray-800 dark:text-white mb-3">@lang('orders::app.view.address.delivery')</p>
            <p class="text-sm text-gray-700 dark:text-gray-200 leading-relaxed">{!! $addr($order->delivery_address) !!}</p>
        </div>

        <div class="bg-white dark:bg-cherry-800 rounded-lg border border-gray-200 dark:border-cherry-800 p-4">
            <p class="font-semibold text-gray-800 dark:text-white mb-3">@lang('orders::app.view.address.invoice')</p>
            <p class="text-sm text-gray-700 dark:text-gray-200 leading-relaxed">{!! $addr($order->invoice_address) !!}</p>
        </div>

        <div class="bg-white dark:bg-cherry-800 rounded-lg border border-gray-200 dark:border-cherry-800 p-4">
            <p class="font-semibold text-gray-800 dark:text-white mb-3">@lang('orders::app.view.address.pickup')</p>
            <p class="text-sm text-gray-700 dark:text-gray-200 leading-relaxed">{!! $addr($order->pickup_point) !!}</p>
        </div>
    </div>
</x-admin::layouts>
