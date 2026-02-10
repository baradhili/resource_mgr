<?php

namespace App\Widgets;

use App\Models\Resource;
use App\Services\CacheService;
use App\Services\ResourceService;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ResourceAvailability extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    protected $cacheService;

    protected $resourceService;

    public function __construct(CacheService $cacheService, ResourceService $resourceService)
    {
        $this->cacheService = $cacheService;
        $this->resourceService = $resourceService;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {

        // create array nextThreeMonths
        $nextThreeMonths = [];

        for ($i = 0; $i < 3; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextThreeMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F'),
            ];
        }

        // Collect our resources who have a current contract
        $resources = $this->resourceService->getResourceList(null, false);

        if (! Cache::has('resourceAvailability')) {
            $this->cacheService->cacheResourceAvailability();
            $resourceAvailability = Cache::get('resourceAvailability');
        } else {
            $resourceAvailability = Cache::get('resourceAvailability');
        }

        if (! Cache::has('resourceAllocation')) {
            $this->cacheService->cacheResourceAllocation();
            $resourceAllocation = Cache::get('resourceAllocation');
        } else {
            $resourceAllocation = Cache::get('resourceAllocation');
        }

        // filter resourceAvailability by $resources
        $resourceAvailability = array_intersect_key($resourceAvailability, array_flip($resources->pluck('id')->toArray()));

        // filter resourceAllocation by $resources
        $resourceAllocation = array_intersect_key($resourceAllocation, array_flip($resources->pluck('id')->toArray()));

        // Log::info("resourceAvailability: " . json_encode($resourceAvailability));
        // Log::info("resourceAllocation: " . json_encode($resourceAllocation));
        // create capacity array where for a resource for a month we subtract availability from allocation
        $resourceCapacity = [];
        foreach ($resourceAvailability as $resourceID => $resource) {
            $resourceCapacity[$resourceID] = [
                'name' => $resource['name'],
                'capacity' => [],
                'availability' => [],
                'allocation' => [],
            ];
            foreach ($nextThreeMonths as $month) {
                $monthKey = $month['year'].'-'.str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                $availability = 100 * (float) ($resourceAvailability[$resourceID]['availability'][$monthKey] ?? 0);
                $allocation = (float) ($resourceAllocation[$resourceID]['allocation'][$monthKey] ?? 0);

                $resourceCapacity[$resourceID]['capacity'][$monthKey] = ($availability - $allocation) / 100;
                $resourceCapacity[$resourceID]['availability'][$monthKey] = $availability;
                $resourceCapacity[$resourceID]['allocation'][$monthKey] = $allocation;

            }
        }
        // Log::info("resourceCapacity: " . json_encode($resourceCapacity));
        $resourceCapacity = collect($resourceCapacity);
        foreach ($resourceCapacity as $key => &$capacity) {
            $resource = Resource::find($key);

            if ($resource) {
                $capacity['resource'] = $resource;
            }
        }

        // Initialize the array with all year-months and 1.00 as default availability
        $yearMonthSums = [];

        foreach ($resourceCapacity as $resourceId => $resourceInfo) {
            foreach ($resourceInfo['capacity'] as $yearMonth => $capacity) {
                $date = Carbon::createFromFormat('Y-m', $yearMonth);
                $yearMonthShortName = $date->format('M');

                if (! isset($yearMonthSums[$yearMonthShortName])) {
                    $yearMonthSums[$yearMonthShortName] = 0;
                }
                $yearMonthSums[$yearMonthShortName] += $capacity;
            }
        }

        return view('widgets.resource_availability', [
            'config' => $this->config,
            'yearMonthSums' => $yearMonthSums,
        ]);
    }
}
