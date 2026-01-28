<?php

namespace Modules\OrgAllocReport\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Project;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Models\User;
use App\Models\Allocation;
use App\Services\CacheService;
use App\Services\ResourceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;

class OrgAllocReportController extends Controller
{
    protected $cacheService;

    protected $resourceService;

    public function __construct(CacheService $cacheService, ResourceService $resourceService)
    {
        $this->cacheService = $cacheService;
        $this->resourceService = $resourceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        // check if they are asking for a region
        $regionID = $request->input('region_id');
        // Collect our resources who have a current contract
        $resources = $this->resourceService->getResourceList($regionID);
        // collect teh regions from teh resources->region
        $regions = $resources->pluck('region')->filter()->unique()->values()->all();
        // insert resource_type object into resource
        foreach ($resources as $resource) {
            $resource->resource_type_obj = ResourceType::find($resource->resource_type);
            $resource->user_obj = $resource->user;
            // update the $resource->user->reports to teh associated user object
            $resource->user->reports_to = User::find($resource->user->reports);
            // calculate tenure in years between contract start and end date, round to 1 decimal place
            if ($resource->contracts && count($resource->contracts) > 0) {
                //add [c] to the end of thie full_name
                if ($resource->contracts[0]->permanent == 0) $resource->full_name = $resource->full_name . ' [c]';
                $firstContract = $resource->contracts[0];
                if ($firstContract->start_date && $firstContract->end_date) {
                    $startDate = \Carbon\Carbon::parse($firstContract->start_date);
                    $endDate = \Carbon\Carbon::parse($firstContract->end_date);
                    $resource->tenure = round($endDate->diffInDays($startDate) / 365.25, 1);
                }
            }
            // find the project ids from teh resource's current allocations
            $allocations = $resource->allocations()->whereBetween('allocation_date', [\Carbon\Carbon::now()->startOfMonth(), $resource->contracts->first()->end_date])->get();
            $uniqueProjectIds = $allocations->pluck('projects_id')->unique()->values()->all();
            $projects = Project::whereIn('id', $uniqueProjectIds)->get();
            $currentProjects = $projects;

            // get this month's allocation per project and attach to the currentProjects object

            foreach ($currentProjects as $project) {
                $allocatedThisMonth = $project->allocations()
                    ->where('resources_id', '=', $resource->id)
                    ->whereBetween('allocation_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->pluck('fte')
                    ->first();
                $project->allocatedThisMonth = $allocatedThisMonth;
            }
            $resource->currentProjects = $currentProjects;
Log::info("resource: " . json_encode($resource));
        }

        // sort by tenure decending
        // $resources = $resources->sortByDesc('tenure');
        // remove any resources where tenure is < 1.5 years or their top contract in contracts has permanent = 1
        // $resources = $resources->filter(function ($resource) {
        //     return $resource->tenure >= 1.5 && !$resource->contracts->first()->permanent;
        // });
        // Log::info("resource: " . json_encode($resources));
        return view('orgallocreport::index', compact('resources', 'regions'));
    }
}
