<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Contract;
use App\Models\Leave;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ResourceRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Carbon\Carbon;

use Illuminate\Support\Facades\Log;

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
        Log::info("View: ", [
            'resources' => json_encode($resources),
            'resourceAvailability' => json_encode($resourceAvailability),
            'nextTwelveMonths' => json_encode($nextTwelveMonths)
        ]);
        return view('resource.index', compact('resources', 'nextTwelveMonths'))
            ->with('i', ($request->input('page', 1) - 1) * $resources->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $resource = new Resource();

        return view('resource.create', compact('resource'));
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
        $resource = Resource::find($id);

        return view('resource.show', compact('resource'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $resource = Resource::find($id);

        return view('resource.edit', compact('resource'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ResourceRequest $request, Resource $resource): RedirectResponse
    {
        $resource->update($request->validated());

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
