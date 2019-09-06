<?php

namespace ZZGo;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ZZGo\Console\ZZGo;
use ZZGo\Models\DataDefinition;

class ZZGoServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ZZGo::class
            ]);
        }



        Route::model('data_definition', DataDefinition::class);



        //Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Routes
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the Nova route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'namespace' => 'ZZGo\Http\Controllers',
            'domain' => config('nova.domain', null),
            'as' => 'zzgo.api.',
            'prefix' => 'zzgo',
            'middleware' => 'api',
        ];
    }
}
