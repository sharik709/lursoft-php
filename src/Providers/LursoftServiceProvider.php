<?php

namespace Sharik709\LursoftPhp\Providers;

use Illuminate\Support\ServiceProvider;
use Psr\SimpleCache\CacheInterface;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Facades\Cache;
use Sharik709\LursoftPhp\Services\LursoftService;

class LursoftServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/lursoft.php', 'lursoft'
        );

        $this->app->singleton(LursoftService::class, function ($app) {
            // Get cache store that implements PSR-16 SimpleCache
            $cache = $app->make(CacheInterface::class);

            // If Laravel's cache isn't bound to CacheInterface, use Laravel's cache store
            if (!$cache && $app->bound(Repository::class)) {
                $cache = $app->make(Repository::class);
            }

            // Fallback to the default cache store
            if (!$cache) {
                $cache = Cache::store();
            }

            return new LursoftService(
                $cache,
                config('lursoft.base_url', 'https://b2b.lursoft.lv')
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
