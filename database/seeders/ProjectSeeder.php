<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use JeroenZwart\CsvSeeder\CsvSeeder;

class ProjectSeeder extends CsvSeeder
{
    public function __construct()
    {
        $this->file = '/database/seeders/projects.csv';
        $this->delimiter = ',';
        $this->foreignKeyCheck = true;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Recommended when importing larger CSVs
        DB::disableQueryLog();
        // Schema::disableForeignKeyConstraints();
        parent::run();
        // Schema::enableForeignKeyConstraints();
    }
}
