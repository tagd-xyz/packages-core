<?php

namespace Tagd\Core\Support\Repository\Providers;

use Illuminate\Support\ServiceProvider;

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
        dd('repo boot');

        /**
         * Config files
         */
        $this->publishes([
            __DIR__ . '/../../config/repositories.php' => config_path('repositories.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        dd('repo config');
        /**
         * Config file
         */
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/repository.php',
            'repository'
        );
    }
}
