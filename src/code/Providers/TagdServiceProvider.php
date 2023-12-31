<?php

namespace Tagd\Core\Providers;

// use Illuminate\Support\ServiceProvider;
// Use AuthServiceProvider instead, as this package registers some auth policies
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class TagdServiceProvider extends ServiceProvider
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
                \Tagd\Core\Console\Commands\Seed\Swatch::class,
                \Tagd\Core\Console\Commands\Seed\Database::class,
                \Tagd\Core\Console\Commands\Seed\Support\Sale::class,
                \Tagd\Core\Console\Commands\Seed\Support\Resale::class,
                \Tagd\Core\Console\Commands\Seed\Support\Activate::class,
                \Tagd\Core\Console\Commands\Seed\Support\Role::class,
                \Tagd\Core\Console\Commands\Ref\TrustSettings\Brand::class,
                \Tagd\Core\Console\Commands\Support\Tree::class,
                \Tagd\Core\Console\Commands\Support\BuildStats::class,
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
            // 'affiliate' => \IFAC\Core\Models\Affiliate\Affiliate::class,
        ]);

        /**
         * Observers
         */
        \Tagd\Core\Models\Actor\Consumer::observe(\Tagd\Core\Observers\Actors\Consumer::class);
        \Tagd\Core\Models\Item\Item::observe(\Tagd\Core\Observers\Items\Item::class);
        \Tagd\Core\Models\Item\Tagd::observe(\Tagd\Core\Observers\Items\Tagd::class);
        \Tagd\Core\Models\Resale\AccessRequest::observe(\Tagd\Core\Observers\Resales\AccessRequest::class);

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
                \Tagd\Core\Listeners\Models\Item::class,
                \Tagd\Core\Listeners\Models\Tagd::class,
                \Tagd\Core\Listeners\Models\AccessRequest::class,
                \Tagd\Core\Listeners\TrustScores\Tagd::class,
            ] as $listener) {
            Event::subscribe($listener);
        }

        /**
         * Repositories
         */
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Users\Users::class, \Tagd\Core\Repositories\Users\Users::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Items\Items::class, \Tagd\Core\Repositories\Items\Items::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Items\Stock::class, \Tagd\Core\Repositories\Items\Stock::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Items\Tagds::class, \Tagd\Core\Repositories\Items\Tagds::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Resales\AccessRequests::class, \Tagd\Core\Repositories\Resales\AccessRequests::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Actors\Retailers::class, \Tagd\Core\Repositories\Actors\Retailers::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Actors\Resellers::class, \Tagd\Core\Repositories\Actors\Resellers::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Actors\Consumers::class, \Tagd\Core\Repositories\Actors\Consumers::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Actors\Admins::class, \Tagd\Core\Repositories\Actors\Admins::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Uploads\Resellers::class, \Tagd\Core\Repositories\Uploads\Resellers::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Uploads\Stocks::class, \Tagd\Core\Repositories\Uploads\Stocks::class);
        $this->app->bind(\Tagd\Core\Repositories\Interfaces\Ref\TrustSettings::class, \Tagd\Core\Repositories\Ref\TrustSettings::class);

        /**
         * Services
         */
        $this->app->bind(\Tagd\Core\Services\Interfaces\RetailerSales::class, \Tagd\Core\Services\RetailerSales\Service::class);
        $this->app->bind(\Tagd\Core\Services\Interfaces\ResellerSales::class, \Tagd\Core\Services\ResellerSales\Service::class);
        $this->app->bind(\Tagd\Core\Services\Interfaces\TrustScores::class, \Tagd\Core\Services\TrustScores\Service::class);
    }
}
