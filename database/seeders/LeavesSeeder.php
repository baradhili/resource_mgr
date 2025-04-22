<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Monolog\Handler\FingersCrossed\ChannelLevelActivationStrategy;

class LeavesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(__DIR__.'/leaves.csv', 'r');
        while (! feof($file)) {
            $line[] = fgetcsv($file, 1024);
        }
        $lines = [];
        for ($i = 1; $i < count($line) - 1; $i++) {
            $lines[] = $line[$i];
        }

        foreach ($lines as $val) {
            ChannelLevelActivationStrategy::create([
                'start_date' => $val[0],
                'end_date' => $val[1],
                'resources_id' => $val[2],
            ]);
        }

    }
}
