<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Plugin;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerModules();
    }

    private function registerModules()
    {
        $modules = \Module::all();

        foreach ($modules as $module) {
            $this->registerModule($module);
        }
    }

    private function registerModule($module)
    {
        $moduleName = $module->getName();
        $moduleType = $module->get('type'); // Assuming each module has a type in its module.json
        $moduleDescription = $module->get('description'); // Assuming each module has a description in its module.json

        if (!\Schema::hasTable('plugins')) {
            return;
        }

        Plugin::updateOrCreate(
            ['name' => $moduleName],
            ['type' => $moduleType, 'description' => $moduleDescription]
        );
    }
}