<?php

namespace Modules\ProjAllocReport\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use App\Models\Client;
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
        // 1. Define the date range: current month to 3 months ahead (4 months total)
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addMonths(3)->endOfMonth();

        // 2. Generate month labels for the report columns
        $months = [];
        for ($i = 0; $i < 4; $i++) {
            $months[] = Carbon::now()->addMonths($i)->format('M Y');
        }

        // 3. Get Clients for the filter dropdown
        $clients = Client::orderBy('name')->get();

        // 4. Find the Solution Architect resource type
        $saResourceType = ResourceType::where('name', 'like', '%Solution Architect%')->first();

        if (!$saResourceType) {
            $monthHeaders = [];
            for ($i = 0; $i < 4; $i++) {
                $monthHeaders[] = Carbon::now()->addMonths($i)->format('M-y');
            }
            return view('projallocreport::index', [
                'headers' => ['Client Name', 'Project Name', 'Resource Name', ...$monthHeaders],
                'rows' => [],
                'hasData' => false,
                'clients' => $clients,
                'selectedClientId' => $request->client_id,
            ]);
        }

        // 5. Find all Allocations running during the date range (Initial broad search)
        $allocations = Allocation::whereBetween('allocation_date', [$startDate, $endDate])
            ->get();

        // 6. Get all projects from these allocations
        $projectIds = $allocations->pluck('projects_id')->unique();

        // 7. Filter Projects by Client ID if provided
        $projectsQuery = Project::whereIn('id', $projectIds);

        if ($request->filled('client_id')) {
            $projectsQuery->where('client_id', $request->client_id);
        }

        // Eager load client relationship to avoid N+1 queries later
        $projects = $projectsQuery->with('client')->get();
        $validProjectIds = $projects->pluck('id');

        // 8. Re-filter allocations to ONLY those belonging to the valid (and client-filtered) projects
        $allocations = $allocations->whereIn('projects_id', $validProjectIds);

        // 9. Collect all resources allocated from the filtered Allocations
        $resourceIds = $allocations->pluck('resources_id')->unique();
        $resources = Resource::whereIn('id', $resourceIds)->get();

        // 10. Filter $resources by resource type = saResourceType
        $resources = $resources->filter(function ($resource) use ($saResourceType) {
            return $resource->resource_type === $saResourceType->id;
        });

        // 11. Filter resources by current contracts
        $resources = $resources->filter(function ($resource) {
            return $resource->contracts()->where('end_date', '>=', now())->exists();
        });

        // 12. Get FINAL allocations matching the $resources AND $validProjects in the date range
        $allocations = Allocation::whereBetween('allocation_date', [$startDate, $endDate])
            ->whereIn('resources_id', $resources->pluck('id')->toArray())
            ->whereIn('projects_id', $validProjectIds) // Ensure we stick to the client's projects
            ->get();

        // 13. Generate CORRECT month keys (Y-m) and headers (M-y) for 4-month window
        $monthKeys = [];    // Grouping keys: ['2026-01', '2026-02', ...]
        $monthHeaders = []; // Display headers: ['Jan-26', 'Feb-26', ...]
        for ($i = 0; $i < 4; $i++) {
            $date = Carbon::now()->addMonths($i);
            $monthKeys[] = $date->format('Y-m');
            $monthHeaders[] = $date->format('M-y');
        }

        // Exit early if no allocations after filtering
        if ($allocations->isEmpty()) {
            return view('projallocreport::index', [
                'headers' => ['Client Name', 'Project Name', 'Resource Name', ...$monthHeaders],
                'rows' => [],
                'hasData' => false,
                'clients' => $clients,
                'selectedClientId' => $request->client_id,
            ]);
        }

        // 14. Build efficient lookups from FINAL allocations
        // $projectLookup already contains the Client relationship due to eager loading
        $projectLookup = $projects->keyBy('id');
        $resourceLookup = $resources->keyBy('id');

        // 15. Aggregate FTE: [project_id][resource_id][month_key] = SUM(fte)
        $dataMatrix = [];
        foreach ($allocations as $alloc) {
            try {
                $monthKey = Carbon::parse($alloc->allocation_date)->format('Y-m');
            } catch (\Exception $e) {
                Log::warning("Invalid allocation_date skipped: {$alloc->allocation_date}", ['id' => $alloc->id]);
                continue;
            }

            if (!in_array($monthKey, $monthKeys))
                continue;

            $pid = $alloc->projects_id;
            $rid = $alloc->resources_id;
            $fte = floatval($alloc->fte ?? 0);
            if ($fte > 0) {
                $dataMatrix[$pid][$rid][$monthKey] = ($dataMatrix[$pid][$rid][$monthKey] ?? 0) + $fte;
            }
        }

        // 16. Build unique project-resource rows with sorted names
        $rows = [];
        foreach ($allocations->unique(fn($a) => "{$a->projects_id}_{$a->resources_id}")->values() as $alloc) {
            $pid = $alloc->projects_id;
            $rid = $alloc->resources_id;

            $project = $projectLookup[$pid] ?? null;
            $resource = $resourceLookup[$rid] ?? null;

            // Skip if project or resource was somehow filtered out of lookup
            if (!$project || !$resource)
                continue;

            $projectName = $project->name ?? "Project #{$pid}";
            $resourceName = $resource->full_name ?? "Resource #{$rid}";

            // Get Client Name safely
            $clientName = $project->client->name ?? '-';

            // Build FTE values for all 4 months (0.00 if no allocation)
            $values = array_map(function ($key) use ($pid, $rid, $dataMatrix) {
                $val = $dataMatrix[$pid][$rid][$key] ?? 0;
                return number_format($val, 2, '.', '');
            }, $monthKeys);

            // Filter out rows that are entirely zero
            if (array_reduce($values, fn($carry, $val) => $carry && $val === '0.00', true)) {
                continue;
            }

            $rows[] = [
                'client_name' => $clientName,
                'project_name' => $projectName,
                'resource_name' => $resourceName,
                'values' => $values
            ];
        }

        // 17. Sort rows: Client A-Z -> Project A-Z -> Resource A-Z
        usort($rows, function ($a, $b) {
            return [$a['client_name'], $a['project_name'], $a['resource_name']]
                <=> [$b['client_name'], $b['project_name'], $b['resource_name']];
        });

        // Prepare final view data
        $headers = ['Client Name', 'Project Name', 'Resource Name', ...$monthHeaders];

        return view('projallocreport::index', [
            'headers' => $headers,
            'rows' => $rows,
            'hasData' => true,
            'clients' => $clients,
            'selectedClientId' => $request->client_id,
        ]);
    }
}