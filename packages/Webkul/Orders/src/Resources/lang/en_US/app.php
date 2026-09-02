<?php

return [
    'menu' => [
        'orders'     => 'Orders',
        'all-orders' => 'All Orders',
    ],

    'acl' => [
        'orders' => 'Orders',
        'view'   => 'View Order',
    ],

    'index' => [
        'title'       => 'All Orders',
        'all'         => 'All',
        'marketplace' => 'Marketplace',
    ],

    'statuses' => [
        'new'       => 'New',
        'sent'      => 'Sent',
        'cancelled' => 'Cancelled',
        'error'     => 'Error',
    ],

    'datagrid' => [
        'number'     => 'Number',
        'customer'   => 'Customer (source)',
        'items'      => 'Items',
        'amount'     => 'Amount',
        'delivery'   => 'Shipping method',
        'status'     => 'Status',
        'ordered-at' => 'Order date',
        'view'       => 'View',
    ],

    'view' => [
        'title'    => 'Order :number',
        'no-items' => 'No items in this order.',
        'yes'      => 'Yes',
        'no'       => 'No',

        'item' => [
            'product-id' => 'Prod. ID',
            'name'       => 'Product name',
            'qty'        => 'Qty',
            'price'      => 'Price',
            'vat'        => 'VAT',
            'weight'     => 'Weight',
        ],

        'info' => [
            'title'           => 'Order information',
            'paid'            => 'Paid',
            'customer'        => 'Customer (login)',
            'email'           => 'Email',
            'phone'           => 'Phone',
            'source'          => 'Source',
            'delivery-method' => 'Shipping method',
            'cod'             => 'Cash on delivery',
            'delivery-cost'   => 'Shipping cost',
            'payment-method'  => 'Payment method',
            'ordered-at'      => 'Order date',
            'status-at'       => 'Status date',
        ],

        'symfonia' => [
            'title'       => 'Symfonia',
            'document'    => 'Document number',
            'transaction' => 'Transaction ID',
            'not-created' => 'Not created',
            'hint'        => 'This order is a read-only mirror. The source document is created in Symfonia, which is what updates stock.',
        ],

        'address' => [
            'delivery' => 'Delivery address',
            'invoice'  => 'Invoice details',
            'pickup'   => 'Pickup point',
        ],
    ],
];
