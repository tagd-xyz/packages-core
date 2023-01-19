<?php

namespace Tagd\Core\Providers;

// use Illuminate\Support\ServiceProvider;
// Use AuthServiceProvider instead, as this package registers some auth policies
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class Service extends ServiceProvider
{
    /**
     * The policy mappings for the package.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    ];

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Config files
         */
        $this->publishes([
            __DIR__ . '/../../config/tagd.php' => config_path('tagd.php'),
        ]);

        /**
         * Migrations
         */
        $this->loadMigrationsFrom(
            __DIR__ . '/../../database/migrations'
        );

        /**
         * Views
         */
        // $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'core');

        /**
         * Artisan commands
         */
        if ($this->app->runningInConsole()) {
            $commands = [
                \Tagd\Core\Console\Commands\Seed\Dev::class,
                \Tagd\Core\Console\Commands\Seed\Qa::class,
                \Tagd\Core\Console\Commands\Seed\Uat::class,
                \Tagd\Core\Console\Commands\Seed\Prod::class,
                \Tagd\Core\Console\Commands\Seed\Database::class,
            ];

            if ($this->app->environment(['local', 'testing'])) {
                $commands[] = \Tagd\Core\Console\Commands\Seed\Testing::class;
            }

            $this->commands($commands);
        }

        /**
         * Polymorph mapping
         */
        \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap([
        ]);

        /**
         * Observers
         */

        /**
         * Policies
         */
        $this->registerPolicies();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        /**
         * Config file
         */
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/tagd.php',
            'tagd'
        );

        /**
         * Listeners
         */
        foreach (
            [
            ] as $listener) {
            Event::subscribe($listener);
        }

        /**
         * Repositories
         */
    }
}
