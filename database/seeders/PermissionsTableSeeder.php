<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'role:update',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 01:11:26',
                'updated_at' => '2024-11-21 03:39:11',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'role:delete',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 01:28:46',
                'updated_at' => '2024-11-21 01:28:46',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'permission:view',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:36:39',
                'updated_at' => '2024-11-21 03:37:55',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'permission:create',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:37:09',
                'updated_at' => '2024-11-21 03:37:46',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'permission:update',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:37:37',
                'updated_at' => '2024-11-21 03:37:37',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'permission:delete',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:38:10',
                'updated_at' => '2024-11-21 03:38:10',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'role:create',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:39:25',
                'updated_at' => '2024-11-21 03:39:25',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'role:view',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:39:48',
                'updated_at' => '2024-11-21 03:39:48',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'allocation:view',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:42:35',
                'updated_at' => '2024-11-21 03:42:35',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'allocation:create',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:42:46',
                'updated_at' => '2024-11-21 03:42:46',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'allocation:update',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:42:58',
                'updated_at' => '2024-11-21 03:42:58',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'allocation:delete',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:43:10',
                'updated_at' => '2024-11-21 03:43:10',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'contract:view',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:44:17',
                'updated_at' => '2024-11-21 03:44:17',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'contract:create',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:44:29',
                'updated_at' => '2024-11-21 03:44:29',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'contract:update',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:44:41',
                'updated_at' => '2024-11-21 03:44:41',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'contract:delete',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 03:44:52',
                'updated_at' => '2024-11-21 03:44:52',
            ),
        ));
        
        
    }
}