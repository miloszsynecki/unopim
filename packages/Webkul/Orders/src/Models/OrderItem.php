<?php

namespace Webkul\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Orders\Contracts\OrderItem as OrderItemContract;

class OrderItem extends Model implements OrderItemContract
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'external_product_id',
        'sku',
        'ean',
        'name',
        'qty',
        'price',
        'tax_percent',
        'weight',
    ];

    protected function casts(): array
    {
        return [
            'qty'         => 'integer',
            'price'       => 'decimal:2',
            'tax_percent' => 'decimal:2',
            'weight'      => 'decimal:3',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderProxy::modelClass(), 'order_id');
    }
}
