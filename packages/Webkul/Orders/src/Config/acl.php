<?php

return [
    [
        'key'   => 'sales',
        'name'  => 'orders::app.acl.orders',
        'route' => 'admin.sales.orders.index',
        'sort'  => 4,
    ], [
        'key'   => 'sales.orders',
        'name'  => 'orders::app.acl.orders',
        'route' => 'admin.sales.orders.index',
        'sort'  => 1,
    ], [
        'key'   => 'sales.orders.view',
        'name'  => 'orders::app.acl.view',
        'route' => 'admin.sales.orders.index',
        'sort'  => 1,
    ], [
        'key'   => 'sales.orders.view',
        'name'  => 'orders::app.acl.view',
        'route' => 'admin.sales.orders.view',
        'sort'  => 1,
    ],
];
