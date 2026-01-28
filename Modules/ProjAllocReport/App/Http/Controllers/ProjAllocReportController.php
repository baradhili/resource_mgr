<?php

namespace Modules\ProjAllocReport\App\Http\Controllers;

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
use Illuminate\Support\Facades\Log;

class ProjAllocReportController extends Controller
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
        Log::info("months: " . json_encode($months));
        // Find the Solution Architect resource type
        $saResourceType = ResourceType::where('name', 'like', '%Solution Architect%')->first();
        Log::info("saResourceType: " . json_encode($saResourceType));
        if (!$saResourceType) {
            // If no Solution Architect type found, return empty report
            $monthHeaders = [];
            for ($i = 0; $i < 4; $i++) {
                $monthHeaders[] = Carbon::now()->addMonths($i)->format('M-y');
            }
            return view('projallocreport::index', [
                'headers' => ['Project Name', 'Resource Name', ...$monthHeaders],
                'rows' => [],
                'hasData' => false,
            ]);
        }

        //Find all Allocations they are running during the date range
        $allocations = Allocation::whereBetween('allocation_date', [$startDate, $endDate])
            ->get();
        Log::info("allocations: " . json_encode($allocations));

        //Get all projects from allocations
        $projectIds = $allocations->pluck('projects_id')->toArray();
        Log::info("projectIds: " . json_encode($projectIds));
        $projects = Project::whereIn('id', $projectIds)->get();
        Log::info("projects: " . json_encode($projects));

        // Collect all resources allocated from Allocation
        $resourceIds = $allocations->pluck('resources_id')->toArray();
        Log::info("resourceIds: " . json_encode($resourceIds));
        $resources = Resource::whereIn('id', $resourceIds)->get();
        Log::info("resources: " . json_encode($resources));
        // Filter $resources by resource type = saResourceType
        $resources = $resources->filter(function ($resource) use ($saResourceType) {
            return $resource->resource_type === $saResourceType->id;
        });
        Log::info("filtered resources: " . json_encode($resources));
        // filter resources by current contracts
        $resources = $resources->filter(function ($resource) {
            return $resource->contracts()->where('end_date', '>=', now())->exists();
        });
        Log::info("filtered contract resources: " . json_encode($resources));

        // Now get all allocations matching the $resources from Allocations in the date range
        $allocations = Allocation::whereBetween('allocation_date', [$startDate, $endDate])
            ->whereIn('resources_id', $resources->pluck('id')->toArray())
            ->get();
        Log::info("allocations: " . json_encode($allocations));

        // Generate CORRECT month keys (Y-m) and headers (M-y) for 4-month window
        $monthKeys = [];    // Grouping keys: ['2026-01', '2026-02', ...]
        $monthHeaders = []; // Display headers: ['Jan-26', 'Feb-26', ...]
        for ($i = 0; $i < 4; $i++) {
            $date = Carbon::now()->addMonths($i);
            $monthKeys[] = $date->format('Y-m');
            $monthHeaders[] = $date->format('M-y'); // CRITICAL: Matches "mmm-yy" requirement
        }
        Log::info("Pivot month keys: " . json_encode($monthKeys));
        Log::info("Pivot headers: " . json_encode($monthHeaders));

        // Exit early if no allocations after filtering
        if ($allocations->isEmpty()) {
            return view('projallocreport::index', [
                'headers' => ['Project Name', 'Resource Name', ...$monthHeaders],
                'rows' => [],
                'hasData' => false
            ]);
        }

        // Build efficient lookups from FINAL allocations (fixes stale project/resource references)
        $projectIds = $allocations->pluck('projects_id')->unique();
        $projectLookup = Project::whereIn('id', $projectIds)->get()->keyBy('id');
        $resourceLookup = $resources->keyBy('id'); // Uses your pre-filtered $resources collection
        Log::info("projectLookup: " . json_encode($projectLookup));
        Log::info("resourceLookup: " . json_encode($resourceLookup));
        // Aggregate FTE: [project_id][resource_id][month_key] = SUM(fte)
        $dataMatrix = [];
        foreach ($allocations as $alloc) {
            // Skip invalid dates (defensive)
            try {
                $monthKey = Carbon::parse($alloc->allocation_date)->format('Y-m');
            } catch (\Exception $e) {
                Log::warning("Invalid allocation_date skipped: {$alloc->allocation_date}", ['id' => $alloc->id]);
                continue;
            }

            // Skip months outside our 4-month window (shouldn't happen, but safe)
            if (!in_array($monthKey, $monthKeys))
                continue;

            $pid = $alloc->projects_id;
            $rid = $alloc->resources_id;
            $fte = floatval($alloc->fte ?? 0);
            if ($fte > 0) {
                $dataMatrix[$pid][$rid][$monthKey] = ($dataMatrix[$pid][$rid][$monthKey] ?? 0) + $fte;
            }
        }

        // Build unique project-resource rows with sorted names
        $rows = [];
        foreach ($allocations->unique(fn($a) => "{$a->projects_id}_{$a->resources_id}")->values() as $alloc) {
            $pid = $alloc->projects_id;
            $rid = $alloc->resources_id;

            // Get names with fallbacks (handles missing relationships)
            $projectName = $projectLookup[$pid]->name ?? "Project #{$pid}";
            $resourceName = ($resourceLookup[$rid] ?? null)?->full_name ?? "Resource #{$rid}";

            // Build FTE values for all 4 months (0.00 if no allocation)
            $values = array_map(function ($key) use ($pid, $rid, $dataMatrix) {
                $val = $dataMatrix[$pid][$rid][$key] ?? 0;
                return number_format($val, 2, '.', ''); // Consistent 2-decimal formatting
            }, $monthKeys);


            if (array_reduce($values, fn($carry, $val) => $carry && $val === '0.00', true)) {
                continue;
            }

            $rows[] = [
                'project_name' => $projectName,
                'resource_name' => $resourceName,
                'values' => $values
            ];


        }

        // Sort rows: Project A-Z → Resource A-Z
        usort($rows, function ($a, $b) {
            return [$a['project_name'], $a['resource_name']] <=> [$b['project_name'], $b['resource_name']];
        });

        // Prepare final view data
        $headers = ['Project Name', 'Resource Name', ...$monthHeaders];

        return view('projallocreport::index', [
            'headers' => $headers,
            'rows' => $rows,
            'hasData' => true,
            // Optional: Pass raw data for JS processing if needed
            // 'rawAllocations' => $allocations 
        ]);

    }
}
