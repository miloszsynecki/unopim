<?php

return [
    /**
     * Top-level "Orders" section, placed right after Catalog so ops land on a
     * dedicated orders area like they had in BaseLinker.
     */
    [
        'key'   => 'sales',
        'name'  => 'orders::app.menu.orders',
        'route' => 'admin.sales.orders.index',
        'sort'  => 4,
        'icon'  => 'icon-catalog',
    ], [
        'key'   => 'sales.orders',
        'name'  => 'orders::app.menu.all-orders',
        'route' => 'admin.sales.orders.index',
        'sort'  => 1,
        'icon'  => '',
    ],
];
