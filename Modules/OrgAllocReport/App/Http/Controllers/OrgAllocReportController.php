<?php

namespace Modules\OrgAllocReport\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Project;
use App\Models\Resource;
use App\Models\ResourceType;
use App\Services\CacheService;
use App\Services\ResourceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
     * Display a listing of active projects with Solution Architect allocations.
     */
    public function index(Request $request): View
    {
        // Define the date range: current month to 3 months ahead (4 months total)
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addMonths(3)->endOfMonth();

        // Generate month labels for the report columns
        $months = [];
        for ($i = 0; $i < 4; $i++) {
            $months[] = Carbon::now()->addMonths($i)->format('M Y');
        }

        // Find the Solution Architect resource type
        $saResourceType = ResourceType::where('name', 'like', '%Solution Architect%')->first();

        if (!$saResourceType) {
            // If no Solution Architect type found, return empty report
            return view('orgallocreport::index', [
                'projectAllocations' => collect(),
                'months' => $months,
            ]);
        }

        // Get all resources with Solution Architect resource type who have current contracts
        $saResources = Resource::where('resource_type', $saResourceType->id)
            ->whereHas('contracts', function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })
            ->get();

        $saResourceIds = $saResources->pluck('id')->toArray();

        // Get active projects that have SA allocations in the date range
        $activeProjects = Project::where('status', 'Active')
            ->whereHas('allocations', function ($query) use ($saResourceIds, $startDate, $endDate) {
                $query->whereIn('resources_id', $saResourceIds)
                    ->whereBetween('allocation_date', [$startDate, $endDate]);
            })
            ->orderBy('name')
            ->get();

        // Build the report data
        $projectAllocations = collect();

        foreach ($activeProjects as $project) {
            // Get all SA allocations for this project in the date range
            $allocations = Allocation::where('projects_id', $project->id)
                ->whereIn('resources_id', $saResourceIds)
                ->whereBetween('allocation_date', [$startDate, $endDate])
                ->get();

            // Group allocations by resource
            $resourceAllocations = $allocations->groupBy('resources_id');

            foreach ($resourceAllocations as $resourceId => $resourceAllocs) {
                $resource = $saResources->firstWhere('id', $resourceId);
                if (!$resource) {
                    continue;
                }

                // Calculate monthly allocations
                $monthlyAllocations = [];
                for ($i = 0; $i < 4; $i++) {
                    $monthStart = Carbon::now()->addMonths($i)->startOfMonth();
                    $monthEnd = Carbon::now()->addMonths($i)->endOfMonth();

                    $monthAlloc = $resourceAllocs
                        ->filter(function ($alloc) use ($monthStart, $monthEnd) {
                            $allocDate = Carbon::parse($alloc->allocation_date);
                            return $allocDate->between($monthStart, $monthEnd);
                        })
                        ->sum('fte');

                    $monthlyAllocations[] = $monthAlloc > 0 ? $monthAlloc : null;
                }

                $projectAllocations->push([
                    'project_id' => $project->id,
                    'project_name' => $project->name,
                    'project_empower_id' => $project->empowerID,
                    'project_manager' => $project->projectManager,
                    'resource_id' => $resource->id,
                    'resource_name' => $resource->full_name,
                    'monthly_allocations' => $monthlyAllocations,
                ]);
            }
        }

        // Sort by project name, then by resource name
        $projectAllocations = $projectAllocations->sortBy([
            ['project_name', 'asc'],
            ['resource_name', 'asc'],
        ])->values();

        return view('orgallocreport::index', compact('projectAllocations', 'months'));
    }
}
