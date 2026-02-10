<?php

namespace App\Services;

use App\Models\Allocation;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CacheService
{
    public function cacheResourceAvailability()
    {
        $nextTwelveMonths = collect(range(-1, 11))->map(function ($i) {
            $date = Carbon::now()->addMonthsNoOverflow($i);

            return [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F'),
            ];
        });

        $resources = Resource::whereHas('contracts', function ($q) {
            $q->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->orWhere(function ($q) {
                    $q->where('start_date', '>', now())
                        ->where('start_date', '<=', Carbon::now()->addMonth());
                });
        })
            ->with(['contracts', 'leaves'])
            ->get();

        $resourceAvailability = [];

        foreach ($resources as $resource) {
            $resourceAvailability[$resource->id] = ['name' => $resource->full_name];

            $currentContract = $resource->contracts->sortby('start_date')->reverse()->first();

            if ($currentContract) {
                $contractStartDate = Carbon::parse($currentContract->start_date);
                $contractEndDate = Carbon::parse($currentContract->end_date);

                $nextTwelveMonths->each(function ($month) use ($resource, $contractStartDate, $contractEndDate, $currentContract, &$resourceAvailability) {
                    $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                    $monthEndDate = $monthStartDate->copy()->endOfMonth();

                    if ($this->datesOverlap($contractStartDate, $contractEndDate, $monthStartDate, $monthEndDate)) {
                        $baseAvailability = $this->calculateBaseAvailability($contractStartDate, $contractEndDate, $monthStartDate, $monthEndDate, $currentContract->availability);
                        $key = $month['year'].'-'.str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                        $resourceAvailability[$resource->id]['availability'][$key] = $baseAvailability;
                    }
                });

                $resource->leaves->each(function ($leave) use ($resource, &$resourceAvailability, $nextTwelveMonths) {
                    $leaveStartDate = Carbon::parse($leave->start_date);
                    $leaveEndDate = Carbon::parse($leave->end_date);

                    $nextTwelveMonths->each(function ($month) use ($resource, $leaveStartDate, $leaveEndDate, &$resourceAvailability) {
                        $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                        $monthEndDate = $monthStartDate->copy()->endOfMonth();
                        $key = $month['year'].'-'.str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                        if ($this->datesOverlap($leaveStartDate, $leaveEndDate, $monthStartDate, $monthEndDate)) {
                            $leaveAvailability = $this->calculateLeaveAvailability($leaveStartDate, $leaveEndDate, $monthStartDate, $monthEndDate);

                            if (isset($resourceAvailability[$resource->id]['availability'][$key])) {
                                $resourceAvailability[$resource->id]['availability'][$key] = max(0, $resourceAvailability[$resource->id]['availability'][$key] - $leaveAvailability);
                            } else {
                                $resourceAvailability[$resource->id]['availability'][$key] = 0;
                            }
                        }
                    });
                });
            }
        }

        Cache::put('resourceAvailability', $resourceAvailability, now()->addDays(1));
    }

    private function datesOverlap($start1, $end1, $start2, $end2)
    {
        return $start1->isBetween($start2, $end2) ||
            $end1->isBetween($start2, $end2) ||
            ($start1->lessThanOrEqualTo($start2) && $end1->greaterThanOrEqualTo($end2));
    }

    private function calculateBaseAvailability($contractStartDate, $contractEndDate, $monthStartDate, $monthEndDate, $availability)
    {
        if ($contractStartDate->isSameMonth($monthStartDate) || $contractEndDate->isSameMonth($monthEndDate)) {
            $daysInMonth = $monthEndDate->diffInDays($monthStartDate) + 1;
            $contractDaysInMonth = min($contractEndDate, $monthEndDate)->diffInDays(max($contractStartDate, $monthStartDate)) + 1;

            return round(($contractDaysInMonth / $daysInMonth) * $availability, 2);
        }

        return $availability;
    }

    private function calculateLeaveAvailability($leaveStartDate, $leaveEndDate, $monthStartDate, $monthEndDate)
    {
        if ($leaveStartDate->isSameMonth($monthStartDate) || $leaveEndDate->isSameMonth($monthEndDate)) {
            $daysInMonth = $monthEndDate->diffInDays($monthStartDate) + 1;
            $leaveDaysInMonth = min($leaveEndDate, $monthEndDate)->diffInDays(max($leaveStartDate, $monthStartDate)) + 1;

            return round(($leaveDaysInMonth / $daysInMonth) * 1.00, 2);
        }

        return 1.00;
    }

    public function cacheResourceAllocation()
    {
        // Build our next twelve month array
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
        //  Start and end dates for the period
        // $startDate = Carbon::now()->startOfMonth();
        // $endDate = Carbon::now()->addYear()->startOfMonth();

        // Collect our resources who have a current contract
        $resources = Resource::whereHas('contracts', function ($query) {
            $query->where(function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })->orWhere(function ($query) {
                $query->where('start_date', '>', now())
                    ->where('start_date', '<=', Carbon::now()->addMonth());
            });
        })->get();

        // Collect the availability
        $resourceAvailability = Cache::get('resourceAvailability');
        if (! Cache::has('resourceAvailability')) {
            $this->cacheResourceAvailability();
            $resourceAvailability = Cache::get('resourceAvailability');
        } else {
            $resourceAvailability = Cache::get('resourceAvailability');
        }
        // For each resource - find teh allocations for the period
        foreach ($resources as $resource) {

            $resourceAllocation[$resource->id] = [
                'name' => $resource->full_name,
            ];

            foreach ($nextTwelveMonths as $month) {
                $monthStartDate = Carbon::create($month['year'], $month['month'], 1)->format('Y-m-d');

                $totalAllocation = Allocation::where('allocation_date', '=', $monthStartDate)
                    ->where('resources_id', '=', $resource->id)
                    ->sum('fte');
                // Use year-month as the key
                $key = $month['year'].'-'.str_pad($month['month'], 2, '0', STR_PAD_LEFT);
                // Get the availability for the month
                // Get the availability for the current month
                $availability = isset($resourceAvailability[$resource->id]['availability'][$key]) ? (float) $resourceAvailability[$resource->id]['availability'][$key] : 0.0;
                // Calculate the percentage of total allocation divided by availability
                $percentage = $availability > 0 ? ($totalAllocation / $availability) * 100 : 0;

                // Add the calculated percentage to the resource allocation array
                if ($percentage > 0) {
                    $resourceAllocation[$resource->id]['allocation'][$key] = (int) $percentage;
                }
                // Log::info("totalAllocation for {$monthStartDate} on resource {$resource->id}: ".json_encode($resourceAllocation));

            }
        }
        // Log::info("allocations: ".json_encode($resourceAllocation));
        Cache::put('resourceAllocation', $resourceAllocation, now()->addDays(1));
    }
}
