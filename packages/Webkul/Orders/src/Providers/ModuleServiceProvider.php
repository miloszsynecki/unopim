<?php

namespace Webkul\Orders\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\Orders\Models\Order;
use Webkul\Orders\Models\OrderItem;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        Order::class,
        OrderItem::class,
    ];
}
