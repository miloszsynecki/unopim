<?php

namespace Webkul\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Orders\Contracts\Order as OrderContract;
use Webkul\Orders\Enums\OrderStatus;

class Order extends Model implements OrderContract
{
    protected $table = 'orders';

    protected $fillable = [
        'channel',
        'channel_order_number',
        'shop_order_number',
        'source',
        'external_transaction_id',
        'status',
        'customer_name',
        'customer_login',
        'customer_email',
        'customer_phone',
        'currency',
        'total',
        'paid',
        'payment_method',
        'delivery_method',
        'delivery_cost',
        'cod',
        'smart',
        'delivery_address',
        'invoice_address',
        'pickup_point',
        'symfonia_document_number',
        'symfonia_document_id',
        'ordered_at',
        'status_changed_at',
    ];

    protected function casts(): array
    {
        return [
            'status'            => OrderStatus::class,
            'cod'               => 'boolean',
            'smart'             => 'boolean',
            'total'             => 'decimal:2',
            'paid'              => 'decimal:2',
            'delivery_cost'     => 'decimal:2',
            'delivery_address'  => 'array',
            'invoice_address'   => 'array',
            'pickup_point'      => 'array',
            'ordered_at'        => 'datetime',
            'status_changed_at' => 'datetime',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItemProxy::modelClass(), 'order_id');
    }
}
