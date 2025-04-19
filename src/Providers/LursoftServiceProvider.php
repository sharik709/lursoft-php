<?php

namespace Lursoft\LursoftPhp\Providers;

use Illuminate\Support\ServiceProvider;
use Lursoft\LursoftPhp\Services\LursoftService;

class LursoftServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/lursoft.php', 'lursoft'
        );

        $this->app->singleton(LursoftService::class, function ($app) {
            return new LursoftService(
                config('lursoft.api_key'),
                config('lursoft.base_url')
            );
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/lursoft.php' => config_path('lursoft.php'),
            ], 'config');
        }
    }
}
