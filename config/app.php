<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Spatie\Menu\Laravel\MenuServiceProvider::class,
        Yajra\DataTables\DataTablesServiceProvider::class,
        App\Providers\ModuleServiceProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
        'Menu' => Spatie\Menu\Laravel\Facades\Menu::class,
        'Markdown' => GrahamCampbell\Markdown\Facades\Markdown::class,
        'DataTables' => Yajra\DataTables\Facades\DataTables::class,
    ])->toArray(),

];
