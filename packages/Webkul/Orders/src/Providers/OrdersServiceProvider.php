<?php

namespace Webkul\Orders\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OrdersServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/acl.php', 'acl');
        $this->mergeConfigFrom(__DIR__.'/../Config/menu.php', 'menu.admin');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'orders');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'orders');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        Route::middleware('web')->group(__DIR__.'/../Routes/admin.php');

        $this->app->register(ModuleServiceProvider::class);
    }
}
