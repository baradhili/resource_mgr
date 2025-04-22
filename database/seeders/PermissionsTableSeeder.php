<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     */
    public function run(): void
    {
        $excludedPaths = [
            'filament.',
            'generated::',
            'ignition.',
            'horizon.',
            'nova.',
            'sanctum.',
            'debugbar',
            'laravel-erd',
            'livewire',
            'saml',
        ];

        $permissions = [];
        \DB::table('permissions')->delete();
        $routes = \Route::getRoutes()->getRoutesByName();

        foreach ($routes as $route) {
            $name = $route->getName();
            $shouldExclude = false;

            foreach ($excludedPaths as $path) {
                if (strpos($name, $path) === 0) {
                    $shouldExclude = true;
                    break;
                }
            }

            if (! $shouldExclude) {
                $permissions[] = [
                    'name' => $name,
                    'guard_name' => 'web',
                ];
            }
        }

        \Spatie\Permission\Models\Permission::insertOrIgnore($permissions);

    }
}
