<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Contract;
use App\Models\Location;
use App\Models\Resource;
use App\Services\CacheService;
use App\Services\ResourceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CapacityController extends Controller
{
    protected $cacheService;

    protected $resourceService;

    public function __construct(CacheService $cacheService, ResourceService $resourceService)
    {
        $this->cacheService = $cacheService;
        $this->resourceService = $resourceService;
    }

    /**
     * Display paginated resource capacity with region filtering.
     *
     * `@param`  \Illuminate\Http\Request  $request
     * `@return` \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // check if they are asking for a region
        $regionID = $request->input('region_id');
        $page = max(1, (int) $request->input('page', 1));
        $page = max(1, (int) $request->input('page', 1));
        $perPage = max(1, min((int) $request->input('perPage', 10), 100));
        $nextTwelveMonths = [];
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextTwelveMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F'),
            ];
        }

        // Collect our resources from resourceService
        $resources = $this->resourceService->getResourceList($regionID, true);

        // collect the regions from the resources->region
        $regions = $resources->pluck('region')->filter()->unique()->values()->all();

        // Modify resource names to add [c] if the resource is not permanent
        foreach ($resources as $resource) {
            if (isset($resource->contracts[0]) && ! $resource->contracts[0]->permanent) {
                $resource->full_name .= ' [c]';
            }
        }

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

        foreach ($resourceAllocation as &$resource) {
            if (isset($resource['allocation'])) {
                foreach ($resource['allocation'] as &$value) {
                    $value = (float) $value;
                    if ($value > 100) {
                        $value = 100;
                    }
                }
            }
        }
        unset($resource);

        // create capacity array where for a resource for a month we subtract availability from allocation
        $resourceCapacity = [];
        foreach ($resourceAvailability as $resourceID => $resource) {
            $resourceCapacity[$resourceID] = [
                'name' => $resource['name'],
                'capacity' => [],
            ];
            foreach ($nextTwelveMonths as $month) {
                $monthKey = $month['year'].'-'.str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                $availability = 100 * (float) ($resourceAvailability[$resourceID]['availability'][$monthKey] ?? 0);
                $allocation = (float) ($resourceAllocation[$resourceID]['allocation'][$monthKey] ?? 0);

                $resourceCapacity[$resourceID]['capacity'][$monthKey] = $availability - $allocation;
            }
        }

        $resourceCapacity = collect($resourceCapacity);
        foreach ($resourceCapacity as $key => &$capacity) {
            $resource = Resource::find($key);

            // check if region_id is set
            if (! $resource->region_id) {
                // if not then check if location_id is set
                if ($resource->location_id) {
                    $location = Location::find($resource->location_id);
                    // if $location then collect region_id from Location and insert into resourceCapacity
                    if ($location) {
                        $resource->region_id = $location->region_id;
                    }
                }
            }

            if ($resource) {
                $capacity['resource'] = $resource;
            }
        }

        // Paginate the collection
        $paginatedResourceCapacity = new LengthAwarePaginator(
            $resourceCapacity->forPage($page, $perPage),
            $resourceCapacity->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('capacity.index', compact('paginatedResourceCapacity', 'nextTwelveMonths', 'regions'))
            ->with('i', ($page - 1) * $perPage);

    }

    public function exportCapacity(Request $request)
    {
        $user = auth()->user();
        // check if they are asking for a region
        $regionID = $request->input('region_id');
        $search = $request->query('search');
        $nextTwelveMonths = [];

        // start labelling
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Resource Name');
        $sheet->setCellValue('B1', 'Resource Type');
        $sheet->setCellValue('C1', 'Region');
        $sheet->setCellValue('D1', 'Month');

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextTwelveMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F'),
            ];
            $sheet->setCellValue([$i + 4, 1], $date->format('M').' '.$date->year);
        }

        // Collect our resources who have a current contract
        $resources = $this->resourceService->getResourceList(null, true);

        // collect teh regions from teh resources->region
        $regions = $resources->pluck('region')->filter()->unique()->values()->all();

        // Modify resource names to add [c] if the resource is not permanent
        foreach ($resources as $resource) {
            if (isset($resource->contracts[0]) && ! $resource->contracts[0]->permanent) {
                $resource->full_name .= ' [c]';
            }
        }

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

        foreach ($resourceAllocation as &$resource) {
            if (isset($resource['allocation'])) {
                foreach ($resource['allocation'] as &$value) {
                    $value = (float) $value;
                    if ($value > 100) {
                        $value = 100;
                    }
                }
            }
        }
        unset($resource);

        // create capacity array where for a resource for a month we subtract availability from allocation
        $resourceCapacity = [];
        foreach ($resourceAvailability as $resourceID => $resource) {
            $resourceCapacity[$resourceID] = [
                'name' => $resource['name'],
                'capacity' => [],
            ];
            foreach ($nextTwelveMonths as $month) {
                $monthKey = $month['year'].'-'.str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                $availability = 100 * (float) ($resourceAvailability[$resourceID]['availability'][$monthKey] ?? 0);
                $allocation = (float) ($resourceAllocation[$resourceID]['allocation'][$monthKey] ?? 0);

                $resourceCapacity[$resourceID]['capacity'][$monthKey] = $availability - $allocation;
            }
        }

        $resourceCapacity = collect($resourceCapacity)->map(function ($resource, $resourceID) {
            $resourceModel = Resource::find($resourceID);

            return array_merge($resource, [
                'resource_type' => $resourceModel->resourceType->name ?? 'Unknown Resource Type',
                'region' => $resourceModel->location->region ? $resourceModel->location->region->name : 'Unknown Region',
            ]);
        });

        // Log::info("resourceCapacity " . json_encode($resourceCapacity));
        // walk the resourceCapacity array output to spreadsheet
        $row = 2;
        foreach ($resourceCapacity as $resourceID => $resource) {
            $sheet->setCellValue('A'.$row, $resource['name']);
            $sheet->setCellValue('B'.$row, $resource['resource_type']);
            $sheet->setCellValue('C'.$row, $resource['region']);
            $col = 'D';
            foreach ($nextTwelveMonths as $month) {
                $monthKey = $month['year'].'-'.str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                $sheet->setCellValue($col.$row, $resource['capacity'][$monthKey] ?? 0);
                $col++;
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="capacity.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');

        return Redirect::route('capacity.index')
            ->with('success', 'Capacity exported successfully');

    }
}
