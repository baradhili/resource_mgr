<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceRequest;
use App\Models\Contract;
use App\Models\Demand;
use App\Models\Allocation;
use App\Models\Project;
use App\Models\Leave;
use App\Models\Resource;
use App\Models\ResourceSkill;
use App\Models\Skill;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $nextTwelveMonths = [];

        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextTwelveMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F')
            ];
        }

        $resources = Resource::whereHas('contracts', function ($query) {
            $query->where('start_date', '<=', now())
                ->where('end_date', '>=', now());
        })->paginate();

        foreach ($resources as $resource) {

            $resourceAvailability[$resource->id] = [
                'name' => $resource->full_name,
            ];
            $currentContract = $resource->contracts()->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            if ($currentContract) {
                // Calculate base availability for each month
                $contractStartDate = Carbon::parse($currentContract->start_date);
                $contractEndDate = Carbon::parse($currentContract->end_date);

                foreach ($nextTwelveMonths as $month) {
                    $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                    $monthEndDate = $monthStartDate->copy()->endOfMonth();

                    if (
                        $contractStartDate->isBetween($monthStartDate, $monthEndDate) ||
                        $contractEndDate->isBetween($monthStartDate, $monthEndDate) ||
                        ($contractStartDate->lessThanOrEqualTo($monthStartDate) && $contractEndDate->greaterThanOrEqualTo($monthEndDate))
                    ) {
                        // If the contract overlaps with the month, calculate availability
                        if (
                            $contractStartDate->isSameMonth($monthStartDate) ||
                            $contractEndDate->isSameMonth($monthEndDate)
                        ) {
                            // If the contract start_date or end_date lands in this month, calculate the percentage of the month inside the contract
                            $daysInMonth = $monthEndDate->diffInDays($monthStartDate) + 1;
                            $contractDaysInMonth = min($contractEndDate, $monthEndDate)->diffInDays(max($contractStartDate, $monthStartDate)) + 1;
                            $baseAvailability = round(($contractDaysInMonth / $daysInMonth) * $currentContract->availability, 2);
                        } else {
                            // Otherwise, it will be the availability
                            $baseAvailability = $currentContract->availability;
                        }
                        // Use year-month as the key
                        $key = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                        // Add the calculated base availability to the resource availability array
                        $resourceAvailability[$resource->id]['availability'][$key] = $baseAvailability;
                    }
                    //now check for leave
                    foreach ($resource->leaves as $leave) {
                        $leaveStartDate = Carbon::parse($leave->start_date);
                        $leaveEndDate = Carbon::parse($leave->end_date);

                        if (
                            $leaveStartDate->isBetween($monthStartDate, $monthEndDate) ||
                            $leaveEndDate->isBetween($monthStartDate, $monthEndDate) ||
                            ($leaveStartDate->lessThanOrEqualTo($monthStartDate) && $leaveEndDate->greaterThanOrEqualTo($monthEndDate))
                        ) {
                            // If the leave overlaps with the month, calculate availability
                            if (
                                $leaveStartDate->isSameMonth($monthStartDate) ||
                                $leaveEndDate->isSameMonth($monthEndDate)
                            ) {
                                // If the leave start_date or end_date lands in this month, calculate the percentage of the month inside the leave
                                $daysInMonth = $monthEndDate->diffInDays($monthStartDate) + 1;
                                $leaveDaysInMonth = min($leaveEndDate, $monthEndDate)->diffInDays(max($leaveStartDate, $monthStartDate)) + 1;
                                $leaveAvailability = round(($leaveDaysInMonth / $daysInMonth) * 1.00, 2);
                            } else {
                                // Otherwise, it will be 100
                                $leaveAvailability = 1.00;
                            }
                            // Use year-month as the key
                            $key = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                            // Add the calculated base availability to the resource availability array
                            $resourceAvailability[$resource->id]['availability'][$key] = $resourceAvailability[$resource->id]['availability'][$key] - $leaveAvailability;

                        }
                    }
                }
            }

        }
        // Cache the resourceAvailability data
        Cache::put('resourceAvailability', $resourceAvailability, now()->addDays(1));

        //return to the view
        return view('resource.index', compact('resources', 'resourceAvailability', 'nextTwelveMonths'))
            ->with('i', ($request->input('page', 1) - 1) * $resources->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $resource = new Resource();
        $locations = Location::all();
        return view('resource.create', compact('resource', 'locations'));
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
        // Log::info("resource: " . json_encode($resource));
        // Log::info("resourceSkills: " . json_encode($resourceSkills));
        Log::info("all skills: " . json_encode($skills));
        return view('resource.edit', compact('resource', 'locations', 'skills', 'resourceSkills'));
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
        $resource = Resource::with('location')->find($id);

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

        return view('resource.show', compact('resource', 'skills'));
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
                'monthFullName' => $date->format('F')
            ];
        }

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
                $key = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                // Add the calculated base availability to the resource availability array - only if not zero
                if ($totalAllocation > 0) {
                    $allocationArray[$project->id]['allocation'][$key] = $totalAllocation;
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
        Log::info("skills: ".$request->validated()['skills']);
        $skills = [];
        foreach (json_decode($request->validated()['skills'], true) as $skill) {
            $skills[] = [
                'skills_id' => $skill['id'],
                'proficiency_levels' => $skill['proficiency'],
            ];
        }

        $resource->full_name = $request->validated()['full_name'];
        $resource->empowerID = $request->validated()['empowerID'];
        $resource->adID = $request->validated()['adID'];
        $resource->location_id = $request->validated()['location_id'];
        $location = Location::find($request->validated()['location_id']);
        $resource->region_id = $location->region_id;

        $resource->save();

        $resourceSkills = ResourceSkill::where('resources_id', $resource->id)->get();
        Log::info("resourceskills: ".json_encode($resourceSkills));

        $resourceSkillsArray = array_map(function ($skill) use ($resource) {
            return [
                'resources_id' => $resource->id,
                'skills_id' => $skill['skills_id'],
                'proficiency_levels' => $skill['proficiency_levels'],
            ];
        }, $skills);

       

        $resourceSkills = ResourceSkill::where('resources_id', $resource->id)->get();
        Log::info("resourceskills-after: ".json_encode($resourceSkills));

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
                'percentage' => 100 / $period->count()
            ];
        }

        return $result;
    }
}
