<?php

namespace App\Providers;

use App\View\Compilers\SafeBladeCompiler;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ViewServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Override Blade compiler to prevent forElseCounter from going negative
        // This must run after ViewServiceProvider, so we use boot() instead
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Override Blade compiler singleton after ViewServiceProvider has registered it
        $this->app->singleton('blade.compiler', function ($app) {
            return tap(new SafeBladeCompiler(
                $app['files'],
                $app['config']['view.compiled'],
                $app['config']->get('view.relative_hash', false) ? $app->basePath() : '',
                $app['config']->get('view.cache', true),
                $app['config']->get('view.compiled_extension', 'php'),
                $app['config']->get('view.check_cache_timestamps', true),
            ), function ($blade) {
                $blade->component('dynamic-component', \Illuminate\View\DynamicComponent::class);
            });
        });
    }
}
