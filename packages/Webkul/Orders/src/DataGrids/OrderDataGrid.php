<?php

namespace Webkul\Orders\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Orders\Enums\OrderStatus;

class OrderDataGrid extends DataGrid
{
    protected $primaryColumn = 'id';

    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('orders')
            ->select(
                'orders.id',
                'orders.channel',
                'orders.channel_order_number',
                'orders.shop_order_number',
                'orders.source',
                'orders.customer_name',
                'orders.customer_login',
                'orders.status',
                'orders.total',
                'orders.currency',
                'orders.delivery_method',
                'orders.ordered_at',
            )
            // One correlated aggregate, not a per-row query: "12x NAME, 3x NAME".
            ->selectSub(
                DB::table('order_items')
                    ->selectRaw("string_agg(qty || 'x ' || name, ', ')")
                    ->whereColumn('order_items.order_id', 'orders.id'),
                'items_summary',
            );

        // BaseLinker-style rails: ?status= and ?channel= params on the grid src
        // scope the whole grid, independent of the column filter UI.
        $status = request('status');

        if (in_array($status, array_column(OrderStatus::cases(), 'value'), true)) {
            $queryBuilder->where('orders.status', $status);
        }

        if ($channel = request('channel')) {
            $queryBuilder->where('orders.channel', $channel);
        }

        $this->addFilter('channel', 'orders.channel');
        $this->addFilter('status', 'orders.status');
        $this->addFilter('channel_order_number', 'orders.channel_order_number');
        $this->addFilter('customer_name', 'orders.customer_name');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'channel_order_number',
            'label'      => trans('orders::app.datagrid.number'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row): string {
                $shop = $row->shop_order_number
                    ? '<span class="text-xs text-gray-500 dark:text-gray-400">('.e($row->shop_order_number).')</span>'
                    : '';

                return '<div class="flex flex-col"><span class="font-medium">'.e($row->channel_order_number).'</span>'.$shop.'</div>';
            },
        ]);

        $this->addColumn([
            'index'      => 'customer_name',
            'label'      => trans('orders::app.datagrid.customer'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row): string {
                $login = $row->customer_login ? ' ('.e($row->customer_login).')' : '';
                $source = $row->source ? '<span class="text-xs text-gray-500 dark:text-gray-400">'.e($row->source).'</span>' : '';

                return '<div class="flex items-center gap-2">'
                    .$this->channelBadge($row->channel)
                    .'<div class="flex flex-col"><span>'.e($row->customer_name).$login.'</span>'.$source.'</div>'
                    .'</div>';
            },
        ]);

        $this->addColumn([
            'index'      => 'items_summary',
            'label'      => trans('orders::app.datagrid.items'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
            'closure'    => fn ($row): string => '<span class="italic text-gray-600 dark:text-gray-300">'.e($row->items_summary ?? '').'</span>',
        ]);

        $this->addColumn([
            'index'      => 'total',
            'label'      => trans('orders::app.datagrid.amount'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
            'closure'    => fn ($row): string => number_format((float) $row->total, 2, ',', ' ').' '.e($row->currency),
        ]);

        $this->addColumn([
            'index'      => 'delivery_method',
            'label'      => trans('orders::app.datagrid.delivery'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('orders::app.datagrid.status'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'closure'    => function ($row): string {
                $status = OrderStatus::tryFrom($row->status) ?? OrderStatus::New;

                return '<span class="'.$status->labelClass().'">'.e(trans($status->label())).'</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'ordered_at',
            'label'      => trans('orders::app.datagrid.ordered-at'),
            'type'       => 'datetime',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
        ]);
    }

    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('sales.orders.view')) {
            $this->addAction([
                'index'  => 'view',
                'icon'   => 'icon-view',
                'title'  => trans('orders::app.datagrid.view'),
                'method' => 'GET',
                'url'    => fn ($row): string => route('admin.sales.orders.view', $row->id),
            ]);
        }
    }

    /**
     * Small channel glyph next to the buyer, so ops read the marketplace at a
     * glance like BaseLinker's source icons. Brand colours are inline (raw grid
     * HTML), so Tailwind's purge never strips them.
     */
    private function channelBadge(?string $channel): string
    {
        [$bg, $letter] = match ($channel) {
            'allegro' => ['#ff5a00', 'A'],
            'presta'  => ['#df0067', 'P'],
            default   => ['#9ca3af', strtoupper(substr((string) $channel, 0, 1) ?: '?')],
        };

        $title = e(ucfirst((string) $channel));

        return '<span title="'.$title.'" style="display:inline-flex;align-items:center;justify-content:center;'
            .'width:20px;height:20px;border-radius:5px;background:'.$bg.';color:#fff;font-size:11px;'
            .'font-weight:700;flex-shrink:0;">'.e($letter).'</span>';
    }
}
