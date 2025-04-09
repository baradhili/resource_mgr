<?php

namespace Modules\OrgReport\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Contract;
use App\Models\Allocation;
use App\Models\Project;
use App\Models\User;
use App\Models\ResourceType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Services\CacheService;
use App\Services\ResourceService;

class OrgReportController extends Controller
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
        //insert resource_type object into resource
        foreach ($resources as $resource) {
            $resource->resource_type_obj = ResourceType::find($resource->resource_type);
            $resource->user_obj = $resource->user;
            //update the $resource->user->reports to teh associated user object
            $resource->user->reports_to = User::find($resource->user->reports);
            //calculate tenure in years between contract start and end date, round to 1 decimal place
            if ($resource->contracts && count($resource->contracts) > 0) {
                $firstContract = $resource->contracts[0];
                if ($firstContract->start_date && $firstContract->end_date) {
                    $startDate = \Carbon\Carbon::parse($firstContract->start_date);
                    $endDate = \Carbon\Carbon::parse($firstContract->end_date);
                    $resource->tenure = round($endDate->diffInDays($startDate) / 365, 1);
                }
            }
            // find the project ids from teh resource's current allocations
            $allocations = $resource->allocations()->whereBetween('allocation_date', [\Carbon\Carbon::now()->startOfMonth(), $resource->contracts->first()->end_date])->get();
            $uniqueProjectIds = $allocations->pluck('projects_id')->unique()->values()->all();
            $projects = Project::whereIn('id', $uniqueProjectIds)->get();
            $currentProjects = $projects;
            //filter all projects where the project end date is after or at the resource's contract end date
            $currentProjects = $currentProjects->filter(function ($project) use ($resource) {
                return $project->end_date >= $resource->contracts->first()->end_date;
            });
            $resource->currentProjects = $currentProjects;

        }
        //sort by tenure decending
        $resources = $resources->sortByDesc('tenure');
        // remove any resources where tenure is < 1.5 years or their top contract in contracts has permanent = 1
        $resources = $resources->filter(function ($resource) {
            return $resource->tenure >= 1.5 && !$resource->contracts->first()->permanent;
        });


        Log::info("resources: " . json_encode($resources));
        return view('orgreport::index', compact('resources', 'regions'));
    }

}
