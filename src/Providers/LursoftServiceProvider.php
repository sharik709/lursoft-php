<?php

namespace Lursoft\LursoftPhp\Providers;

use Illuminate\Support\ServiceProvider;
use Lursoft\LursoftPhp\Services\LursoftService;

class LursoftServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/lursoft.php', 'lursoft'
        );

        $this->app->singleton(LursoftService::class, function ($app) {
            return new LursoftService(
                config('lursoft.api_key'),
                config('lursoft.base_url', 'https://api.lursoft.lv')
            );
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/lursoft.php' => config_path('lursoft.php'),
            ], 'config');
        }
    }
}
