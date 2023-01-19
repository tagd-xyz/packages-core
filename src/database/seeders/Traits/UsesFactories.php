<?php

namespace Tagd\Core\Database\Seeders\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;

trait UsesFactories
{
    /**
     * Setup factory. Override default name guessing because our models and factories
     * are in this package namespace instead of App's
     *
     * @return void
     */
    private function setupFactories()
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            $namespace = '\\Tagd\\Core\\Database\\Factories\\';
            $modelName = str_replace('Tagd\\Core\\Models\\', '', $modelName);

            return $namespace . $modelName;
        });

        Factory::guessModelNamesUsing(function (Factory $factory) {
            $modelName = str_replace('Tagd\\Core\\Database\\Factories\\', '', get_class($factory));
            $namespace = '\\Tagd\\Core\\Models\\';

            return $namespace . $modelName;
        });
        // }
    }
}
