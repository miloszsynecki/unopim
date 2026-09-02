@php
    use Webkul\Orders\Enums\OrderStatus;

    // Status chips preserve the active channel scope; channel chips preserve status.
    $base = $activeChannel ? ['channel' => $activeChannel] : [];

    $statusChips = [[
        'value' => null,
        'label' => trans('orders::app.index.all'),
        'count' => $total,
        'class' => 'label-info',
    ]];

    foreach (OrderStatus::cases() as $case) {
        $statusChips[] = [
            'value' => $case->value,
            'label' => trans($case->label()),
            'count' => (int) ($counts[$case->value] ?? 0),
            'class' => $case->labelClass(),
        ];
    }

    $channelBadge = fn (string $c) => match ($c) {
        'allegro' => '#ff5a00',
        'presta'  => '#df0067',
        default   => '#9ca3af',
    };
@endphp

<x-admin::layouts>
    <x-slot:title>
        @lang('orders::app.index.title')
    </x-slot>

    <x-admin::page-header :title="trans('orders::app.index.title')" />

    {{-- Status rail: quick status scopes with live counts. --}}
    <div class="flex flex-wrap items-center gap-2 mb-3">
        @foreach ($statusChips as $chip)
            @php
                $isActive = $active === $chip['value'];
                $params = $chip['value'] ? array_merge($base, ['status' => $chip['value']]) : $base;
            @endphp

            <a
                href="{{ route('admin.sales.orders.index', $params) }}"
                @class([
                    'flex items-center gap-2 px-3 py-1.5 rounded-lg border text-sm no-underline transition-all',
                    'border-primary-500 bg-primary-50 dark:bg-cherry-900 text-primary-600 font-semibold' => $isActive,
                    'border-gray-200 dark:border-cherry-700 text-gray-600 dark:text-gray-300 hover:border-primary-300' => ! $isActive,
                ])
            >
                <span>{{ $chip['label'] }}</span>
                <span class="{{ $chip['class'] }} !px-1.5 !py-0 text-xs">{{ $chip['count'] }}</span>
            </a>
        @endforeach
    </div>

    {{-- Channel (marketplace) rail — the "MARKETPLACE" group from BaseLinker. --}}
    @if ($channelCounts->count() > 1)
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500 mr-1">@lang('orders::app.index.marketplace')</span>

            @php $statusParam = $active ? ['status' => $active] : []; @endphp

            <a
                href="{{ route('admin.sales.orders.index', $statusParam) }}"
                @class([
                    'flex items-center gap-2 px-2.5 py-1 rounded-lg border text-xs no-underline transition-all',
                    'border-primary-500 bg-primary-50 dark:bg-cherry-900 text-primary-600 font-semibold' => ! $activeChannel,
                    'border-gray-200 dark:border-cherry-700 text-gray-600 dark:text-gray-300 hover:border-primary-300' => (bool) $activeChannel,
                ])
            >
                @lang('orders::app.index.all')
            </a>

            @foreach ($channelCounts as $channel => $count)
                @php $isActive = $activeChannel === $channel; @endphp
                <a
                    href="{{ route('admin.sales.orders.index', array_merge($statusParam, ['channel' => $channel])) }}"
                    @class([
                        'flex items-center gap-2 px-2.5 py-1 rounded-lg border text-xs no-underline transition-all',
                        'border-primary-500 bg-primary-50 dark:bg-cherry-900 text-primary-600 font-semibold' => $isActive,
                        'border-gray-200 dark:border-cherry-700 text-gray-600 dark:text-gray-300 hover:border-primary-300' => ! $isActive,
                    ])
                >
                    <span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:{{ $channelBadge($channel) }};"></span>
                    <span>{{ ucfirst($channel) }}</span>
                    <span class="text-gray-400">{{ $count }}</span>
                </a>
            @endforeach
        </div>
    @endif

    <x-admin::datagrid :src="route('admin.sales.orders.index', array_filter(['status' => $active, 'channel' => $activeChannel]))" />
</x-admin::layouts>
