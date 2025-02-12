<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Resource;
use App\Models\Allocation;
use Illuminate\Support\Facades\Log;

class CacheService
{
    public function cacheResourceAvailability()
    {
        $nextTwelveMonths = [];

        for ($i = -1; $i < 12; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextTwelveMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F')
            ];
        }

        $resources = Resource::whereHas('contracts', function ($query) {
            $query->where(function($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })->orWhere(function($query) {
                $query->where('start_date', '>', now())
                    ->where('start_date', '<=', Carbon::now()->addMonth());
            });
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
    }

    public function cacheResourceAllocation()
    {
        // Build our next twelve month array
        $nextTwelveMonths = [];

        for ($i = -1; $i < 12; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextTwelveMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('M'),
                'monthFullName' => $date->format('F')
            ];
        }
        //  Start and end dates for the period
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addYear()->startOfMonth();

        // Collect our resources who have a current contract
        $resources = Resource::whereHas('contracts', function ($query) {
            $query->where(function($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })->orWhere(function($query) {
                $query->where('start_date', '>', now())
                    ->where('start_date', '<=', Carbon::now()->addMonth());
            });
        })->paginate();

        //Collect the availability
        $resourceAvailability = Cache::get('resourceAvailability');
        if (!Cache::has('resourceAvailability')) {
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
                $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                // $monthEndDate = $monthStartDate->copy()->endOfMonth();
                // // Delete duplicate records based on allocation_date | fte  | resources_id | projects_id
                // // and leave a single record
                // $duplicates = Allocation::where('resources_id', '=', $resource->id)
                //     ->where('allocation_date', '=', $monthStartDate)
                //     ->groupBy('allocation_date', 'fte', 'resources_id', 'projects_id')
                //     ->havingRaw('count(*) > 1')
                //     ->pluck('id');

                // if ($duplicates->count() > 0) {
                //     Allocation::whereIn('id', $duplicates)->delete();
                //     Allocation::where('resources_id', '=', $resource->id)
                //         ->where('allocation_date', '=', $monthStartDate)
                //         ->groupBy('allocation_date', 'fte', 'resources_id', 'projects_id')
                //         ->havingRaw('count(*) = 1')
                //         ->first()
                //         ->replicate()
                //         ->save();
                // }
                $totalAllocation = Allocation::where('allocation_date', '=', $monthStartDate)
                    ->where('resources_id', '=', $resource->id)
                    ->sum('fte');
                // Use year-month as the key
                $key = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                // Get the availability for the month
                // Get the availability for the current month
                $availability = isset($resourceAvailability[$key]) ? (float)$resourceAvailability[$key] : 0.0;

                // Calculate the percentage of total allocation divided by availability
                $percentage = $availability > 0 ? ($totalAllocation / $availability) * 100 : 0;

                // Add the calculated percentage to the resource allocation array
                if ($percentage > 0) {
                    $resourceAllocation[$resource->id]['allocation'][$key] = (int) $percentage;
                }
            }
        }
        // Log::info("allocations: ".json_encode($resourceAllocation));
        Cache::put('resourceAllocation', $resourceAllocation, now()->addDays(1));
    }
}