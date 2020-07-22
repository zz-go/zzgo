<?php

namespace ZZGo;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use ZZGo\Console\ZZGo;
use ZZGo\Console\ZZSeed;
use ZZGo\Http\Middleware\DefaultReturnJson;


/**
 * Class ZZGoServiceProvider
 *
 * @package ZZGo
 */
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
                                ZZGo::class,
                                ZZSeed::class,
                            ]);
        }

        //Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        //Routes
        /** @var Router $router */
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('zzgo', DefaultReturnJson::class);

        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Load the config file and merge it with the user's (should it get published)
        $this->mergeConfigFrom(__DIR__ . '/../config/filesystems.disks.php', 'filesystems.disks');
    }

    /**
     * Get the Nova route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'namespace'  => 'ZZGo\Http\Controllers',
            'domain'     => null,
            'as'         => 'zzgo.api.',
            'prefix'     => 'zzgo',
            'middleware' => ['zzgo', 'api'],
        ];
    }
}
