<?php

namespace Database\Seeders;

use App\Models\Contract;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $file = fopen(__DIR__.'/contracts.csv', 'r');
        while (! feof($file)) {
            $line[] = fgetcsv($file, 1024);
        }
        $lines = [];
        for ($i = 1; $i < count($line) - 1; $i++) {
            $lines[] = $line[$i];
        }

        foreach ($lines as $val) {
            Contract::create([
                'start_date' => $val[0],
                'end_date' => $val[1],
                'resources_id' => $val[2],
            ]);
        }

    }
}
