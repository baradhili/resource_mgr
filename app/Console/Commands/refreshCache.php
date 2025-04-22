<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class refreshCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes the various cached data';

    /**
     * The cache service instance.
     *
     * @var CacheService
     */
    protected $cacheService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CacheService $cacheService)
    {
        parent::__construct();
        $this->cacheService = $cacheService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->cacheService->cacheResourceAvailability();
        $this->cacheService->cacheResourceAllocation();
        $this->info('Cache refreshed successfully.');
    }
}
