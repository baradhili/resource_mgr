<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Bret Watson',
            'email' => 'bret@ticm.com',
            'password' => bcrypt('password'),
        ]);
        User::factory()->create([
            'id' => 0,
            'name' => 'Importer',
            'email' => 'importer@example.com',
            'password' => Str::random(20),
        ]);

    }
}
