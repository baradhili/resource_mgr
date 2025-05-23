<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceRequest;
use App\Models\Allocation;
use App\Models\Contract;
use App\Models\Leave;
use App\Models\Location;
use App\Models\Project;
use App\Models\Resource;
use App\Models\ResourceSkill;
use App\Models\ResourceType;
use App\Models\Skill;
use App\Models\User;
use App\Services\CacheService;
use App\Services\ResourceService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ResourceController extends Controller
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
        $search = $request->query('search');
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

        // Collect our resources who have a current contract
        $resources = $this->resourceService->getResourceList(null, true);

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

        if ($search) {
            $resources = $resources->where('full_name', 'like', "%$search%");
        }
        // filter resourceAvailability by $resources
        $resourceAvailability = array_intersect_key($resourceAvailability, array_flip($resources->pluck('id')->toArray()));

        // Convert the array to a collection
        $resourceAvailabilityCollection = collect($resourceAvailability);

        // Get the current page from the request
        $page = $request->input('page', 1);
        $perPage = 10; // Define the number of items per page

        // Paginate the collection
        $paginatedResourceAvailability = new LengthAwarePaginator(
            $resourceAvailabilityCollection->forPage($page, $perPage),
            $resourceAvailabilityCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // return to the view
        return view('resource.index', compact('resources', 'paginatedResourceAvailability', 'nextTwelveMonths'))
            ->with('i', ($request->input('page', 1) - 1) * $paginatedResourceAvailability->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $resource = new Resource;
        $locations = Location::all();
        $skills = Skill::all()->map(function ($skill) {
            return [
                'value' => $skill->id,
                'name' => $skill->skill_name,
            ];
        })->toArray();
        $resourceSkills = ResourceSkill::where('resources_id', $resource->id)
            ->with('skill')
            ->get();
        $resourceTypes = ResourceType::all();
        $users = User::all();

        return view('resource.create', compact('resource', 'locations', 'skills', 'resourceSkills', 'users', 'resourceTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $resource = Resource::with('location')->find($id);
        $locations = Location::all();
        $skills = Skill::all()->map(function ($skill) {
            return [
                'value' => $skill->id,
                'name' => $skill->skill_name,
            ];
        })->toArray();
        $resourceSkills = ResourceSkill::where('resources_id', $resource->id)
            ->with('skill')
            ->get();

        $users = User::all();
        $resourceTypes = ResourceType::all();

        return view('resource.edit', compact('resource', 'locations', 'skills', 'resourceSkills', 'users', 'resourceTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ResourceRequest $request): RedirectResponse
    {
        Resource::create($request->validated());

        return Redirect::route('resources.index')
            ->with('success', 'Resource created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $resource = Resource::with(['location', 'skills', 'contracts', 'allocations', 'leaves', 'user', 'resourceType'])->find($id);

        // Modify resource names to add [c] if the resource is not permanent
        if (isset($resource->contracts[0]) && ! $resource->contracts[0]->permanent) {
            $resource->full_name .= ' [c]';
        }

        // Get the skills for the resource
        $resourceSkills = ResourceSkill::where('resources_id', $id)
            ->select('skills_id', 'proficiency_levels')
            ->pluck('proficiency_levels', 'skills_id')
            ->toArray();

        $skills = Skill::whereIn('id', array_keys($resourceSkills))->get(['id', 'skill_name']);
        foreach ($resourceSkills as $skillId => $proficiencyLevel) {
            $resourceSkills[$skillId] = [
                'proficiency_level' => $proficiencyLevel,
                'skill_name' => $skills->firstWhere('id', $skillId)->skill_name,
            ];
        }
        $skills = $resourceSkills;

        $projects = Project::whereIn('id', function ($query) use ($resource) {
            $query->select('projects_id')
                ->from('allocations')
                ->where('resources_id', $resource->id)
                ->distinct();
        })->get();

        // Log::info("resource: ".json_encode($resource));
        return view('resource.show', compact('resource', 'skills', 'projects'));
    }

    /**
     * Display the projects allocated to a resource with start dates from now.
     */
    public function allocations($id): View
    {
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

        if (! Cache::has('resourceAvailability')) {
            $this->cacheService->cacheResourceAvailability();
            $resourceAvailability = Cache::get('resourceAvailability');
        } else {
            $resourceAvailability = Cache::get('resourceAvailability');
        }
        // hope that they have viewed the resources in the last day
        // $resourceAvailability = Cache::get('resourceAvailability');
        // pick our resource out
        $resourceAvailability = $resourceAvailability[$id]['availability'];

        $resource = Resource::find($id);

        $allocations = $resource->allocations()->get();

        $projects = $allocations->map(function ($allocation) {
            return $allocation->project;
        })->unique();

        foreach ($projects as $project) {

            // $allocationArray[$project->id] = [
            //     'name' => $project->name,
            // ];

            foreach ($nextTwelveMonths as $month) {
                $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                $totalAllocation = Allocation::where('resources_id', '=', $resource->id)
                    ->where('allocation_date', '=', $monthStartDate)
                    ->where('projects_id', '=', $project->id)
                    ->pluck('fte')
                    ->first();
                // if ($totalAllocation !== null) Log::info(print_r($totalAllocation,true) . "Resource: {$resource->id} Date: {$monthStartDate} Project: {$project->id}");
                $key = $month['year'].'-'.str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                // Get the availability for the current month
                $availability = isset($resourceAvailability[$key]) ? (float) $resourceAvailability[$key] : 0.0;

                // Add the calculated base availability to the resource availability array - only if not zero
                if ($totalAllocation > 0) {
                    // $allocationArray[$project->id]['allocation'][$key] = $totalAllocation;
                    // }
                    // Calculate the percentage allocation
                    if ($availability > 0 && $totalAllocation !== null) {
                        $percentageAllocation = ($totalAllocation / $availability) * 100;

                        // Add the calculated percentage allocation to the resource availability array
                        $allocationArray[$project->id]['allocation'][$key] = [
                            'fte' => $totalAllocation,
                            'percentage' => $percentageAllocation,
                        ];
                    } else {
                        // If no allocation or availability is zero, store the allocation as is
                        $allocationArray[$project->id]['allocation'][$key] = [
                            'fte' => -1 * $totalAllocation,
                            'percentage' => 0.0,
                        ];
                    }
                }
            }

        }

        $projectIds = array_keys($allocationArray);
        $projects = Project::whereIn('id', $projectIds)->get();

        // Log::info("resource: {$resource->name} has allocated projects: " . print_r($allocationArray,true));

        return view('resource.allocations', compact('resource', 'allocationArray', 'projects', 'nextTwelveMonths'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResourceRequest $request, Resource $resource): RedirectResponse
    {
        // Log::info('Validated fields: ' . print_r($request->validated(), true));
        // Parse the input skills
        $skillsData = [];
        foreach (json_decode($request->validated()['skills'], true) as $skill) {
            $skillsData[$skill['id']] = [
                'proficiency_levels' => $skill['proficiency'],
            ];
        }

        //    //Get teh requested resource type
        //    $resourceType = $request->input('resource_type');
        //    //check if they are already a member of a team
        //    $currentTeam = $user->currentTeam->name;
        //    if ($currentTeam !== $resourceType) {
        //        Log::info("change team");
        //    }
        // Update resource details
        $resource->full_name = $request->validated()['full_name'];
        $resource->empowerID = $request->validated()['empowerID'];
        if (array_key_exists('userID', $request->validated()) && $request->validated()['userID'] !== null) {
            $resource->user_id = $request->validated()['userID'];
        }
        // $resource->resource_type = $request->validated()['resource_type'];
        $resource->location_id = $request->validated()['location_id'];
        $location = Location::find($request->validated()['location_id']);
        $resource->region_id = $location->region_id;

        $resource->save();

        // Synchronize ResourceSkill entries
        $resource->skills()->sync($skillsData);

        // $resourceSkills = ResourceSkill::where('resources_id', $resource->id)->get();
        // Log::info("resourceskills-after: " . json_encode($resourceSkills));

        return Redirect::route('resources.index')
            ->with('success', 'Resource updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Resource::find($id)->delete();

        return Redirect::route('resources.index')
            ->with('success', 'Resource deleted successfully');
    }

    private function leaveToMonthlyPercentage(Leave $leave)
    {
        $result = [];
        $start = new DateTime($leave->start_date);
        $end = new DateTime($leave->end_date);
        $interval = new DateInterval('P1M');

        $period = new DatePeriod($start, $interval, $end);

        foreach ($period as $date) {
            $result[] = [
                'year' => $date->format('Y'),
                'month' => $date->format('n'),
                'percentage' => 100 / $period->count(),
            ];
        }

        return $result;
    }
}
