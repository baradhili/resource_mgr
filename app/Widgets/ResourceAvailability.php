<?php

namespace App\Widgets;

use App\Models\Resource;
use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;

class ResourceAvailability extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        // work out availability
        $nextThreeMonths = [];

        for ($i = 0; $i < 3; $i++) {
            $date = Carbon::now()->addMonthsNoOverflow($i);
            $nextThreeMonths[] = [
                'year' => $date->year,
                'month' => $date->month,
                'monthName' => $date->format('F'),
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

                foreach ($nextThreeMonths as $month) {
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
                        $key = $month['year'].'-'.str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                        // Add the calculated base availability to the resource availability array
                        $resourceAvailability[$resource->id]['availability'][$key] = $baseAvailability;
                    }
                    // now check for leave
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
                            $key = $month['year'].'-'.str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                            // Add the calculated base availability to the resource availability array
                            $resourceAvailability[$resource->id]['availability'][$key] = $resourceAvailability[$resource->id]['availability'][$key] - $leaveAvailability;

                        }
                    }
                }
            }

        }

        // Initialize the array with all year-months and 1.00 as default availability
        $yearMonthSums = [];

        foreach ($resourceAvailability as $resourceId => $resourceInfo) {
            foreach ($resourceInfo['availability'] as $yearMonth => $availability) {
                $date = Carbon::createFromFormat('Y-m', $yearMonth);
                $yearMonthShortName = $date->format('M');

                if (! isset($yearMonthSums[$yearMonthShortName])) {
                    $yearMonthSums[$yearMonthShortName] = 0;
                }
                $yearMonthSums[$yearMonthShortName] += $availability;
            }
        }

        return view('widgets.resource_availability', [
            'config' => $this->config,
            'yearMonthSums' => $yearMonthSums,
        ]);
    }
}
