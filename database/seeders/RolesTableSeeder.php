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
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'super-admin',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 00:43:02',
                'updated_at' => '2024-11-21 00:45:41',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'test',
                'guard_name' => 'web',
                'created_at' => '2024-11-21 01:55:51',
                'updated_at' => '2024-11-21 01:55:51',
            ),
        ));
        
        
    }
}