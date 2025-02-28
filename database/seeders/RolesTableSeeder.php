<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('roles')->delete();

        \Spatie\Permission\Models\Role::findOrCreate('super-admin', 'web');
        \Spatie\Permission\Models\Role::findOrCreate('Resource Manager', 'web');
        \Spatie\Permission\Models\Role::findOrCreate('Resource', 'web');
        \Spatie\Permission\Models\Role::findOrCreate('Chapter Lead', 'web');

        //add all permissions to 'super-admin'
        $role = \Spatie\Permission\Models\Role::findByName('super-admin');
        $role->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        //make user 1 super-admin
        $user = \App\Models\User::find(1);
        $user->assignRole('super-admin');

        //give all other roles access to "login, logout, home,user.profile"
        $role = \Spatie\Permission\Models\Role::findByName('Resource Manager');
        $role->givePermissionTo(['login', 'logout', 'home', 'users.profile']);
        $role->givePermissionTo([
            'allocations.editOne', 'allocations.index', 'allocations.create', 'allocations.store', 'allocations.show', 'allocations.edit', 'allocations.update', 'allocations.destroy', 
            'contracts.index', 'contracts.create', 'contracts.store', 'contracts.show', 'contracts.edit', 'contracts.update', 'contracts.destroy', 
            'demands.export', 'demands.editFullDemand', 'demands.index', 'demands.create', 'demands.store', 'demands.show', 'demands.edit', 'demands.update', 'demands.destroy', 
            'leaves.index', 'leaves.create', 'leaves.store', 'leaves.show', 'leaves.edit', 'leaves.update', 'leaves.destroy', 
            'projects.search', 'projects.index', 'projects.create', 'projects.store', 'projects.show', 'projects.edit', 'projects.update', 'projects.destroy', 
            'resources.allocations', 'resources.index', 'resources.create', 'resources.store', 'resources.show', 'resources.edit', 'resources.update', 'resources.destroy', 
            'skills.index', 'resource-skills.index', 'users.index', 
            'services.index', 'regions.index', 'locations.index', 'sites.index', 
            'change-requests.create', 'change-requests.show', 'change-requests.update', 'change-requests.destroy', 
            'teams.index'
        ]);

        $role = \Spatie\Permission\Models\Role::findByName('Resource');
        $role->givePermissionTo(['login', 'logout', 'home', 'users.profile']);
        $role->givePermissionTo(['allocations.index', 'allocations.show', 'leaves.index', 'leaves.show', 'projects.index', 'projects.show', 'skills.index', 'services.index']);


        $role = \Spatie\Permission\Models\Role::findByName('Chapter Lead');
        $role->givePermissionTo(['login', 'logout', 'home', 'users.profile']);
        $role->givePermissionTo(['allocations.index','resources.allocations', 'contracts.index', 'leaves.index', 'projects.index', 'resources.index', 'resources.show', 'skills.index', 'skills.show', 'users.profile', 'users.index', 'users.show', 'services.index', 'services.show', 'regions.index', 'locations.index', 'sites.index', 'domains.index']);


    }
}