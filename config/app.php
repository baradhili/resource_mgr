<?php

use Illuminate\Support\Facades\Facade;

return [

    'tenure' => env('APP_TENURE'),

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
        'Menu' => Spatie\Menu\Laravel\Facades\Menu::class,
        'Markdown' => GrahamCampbell\Markdown\Facades\Markdown::class,
    ])->toArray(),

];
