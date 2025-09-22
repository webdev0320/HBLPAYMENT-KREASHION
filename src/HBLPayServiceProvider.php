<?php

namespace Kreashion\HBLPay;

use Illuminate\Support\ServiceProvider;

class HBLPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/hblpay.php', 'hblpay');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/hblpay.php' => config_path('hblpay.php'),
        ], 'config');

    }
}
