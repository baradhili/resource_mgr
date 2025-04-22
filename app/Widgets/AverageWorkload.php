<?php

namespace App\Widgets;

use App\Services\CacheService;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AverageWorkload extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        // grab or update allocations
        if (! Cache::has('resourceAllocation')) {
            $this->cacheService->cacheResourceAllocation();
            $resourceAllocation = Cache::get('resourceAllocation');
        } else {
            $resourceAllocation = Cache::get('resourceAllocation');
        }
        // Get the current month and the previous month in 'Y-m' format
        $currentMonth = Carbon::now()->format('Y-m');
        $previousMonth = Carbon::now()->subMonth()->format('Y-m');

        // Initialize variables to store the sums and counts for the current and previous months
        $currentMonthSum = 0;
        $currentMonthCount = 0;
        $previousMonthSum = 0;
        $previousMonthCount = 0;

        // Iterate through each item in the $resourceAllocation object
        foreach ($resourceAllocation as $item) {
            // Check if the 'allocation' key exists
            if (isset($item['allocation'])) {
                // Get the allocation data for the current item
                $allocation = $item['allocation'];

                // Check if the current month allocation exists and add to the sum and count
                if (isset($allocation[$currentMonth])) {
                    $currentMonthSum += $allocation[$currentMonth];
                    $currentMonthCount++;
                }

                // Check if the previous month allocation exists and add to the sum and count
                if (isset($allocation[$previousMonth])) {
                    $previousMonthSum += $allocation[$previousMonth];
                    $previousMonthCount++;
                }
            }
        }

        // Calculate the average availability for the current month
        $currentMonthAverage = $currentMonthCount > 0 ? $currentMonthSum / $currentMonthCount : 0;
        $currentMonthAverage = (int) $currentMonthAverage;

        // Calculate the average availability for the previous month
        $delta = $currentMonthAverage - ($previousMonthCount > 0 ? $previousMonthSum / $previousMonthCount : 0);

        return view('widgets.average_workload', [
            'config' => $this->config,
            'currentMonthAverage' => $currentMonthAverage,
            'delta' => $delta,
        ]);
    }
}
