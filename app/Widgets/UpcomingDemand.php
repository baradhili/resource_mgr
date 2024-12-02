<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Carbon\Carbon;
use App\Models\Demand;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class UpcomingDemand extends AbstractWidget
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

        //  Start and end dates for the period
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->addMonths(3)->startOfMonth();

        // Collect the projects_id from demands in our window
        $demandIDs = Demand::whereBetween('demand_date', [$startDate, $endDate])
            ->pluck('projects_id')
            ->unique()
            ->values()
            ->all();

        // Eager load the projects with their names
        $projects = Project::whereIn('id', $demandIDs)
            ->with('demands') // Eager load the demands relationship
            ->paginate();

        $demandArray = [];
        // For each project - find the allocations for the period

        foreach ($projects as $project) {

            $demandArray[$project->id] = [];

            foreach ($nextThreeMonths as $month) {

                $monthStartDate = Carbon::create($month['year'], $month['month'], 1);
                $totalAllocation = Demand::where('demand_date', '=', $monthStartDate)
                    ->where('projects_id', '=', $project->id)
                    ->pluck('fte')
                    ->first();
                $key = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);

                // Add the calculated base availability to the resource availability array - only if not zero
                if ($totalAllocation > 0) {
                    $demandArray[$project->id]['demand'][$key] = $totalAllocation;
                }
            }
        }

        $yearMonthSums = [];
        foreach ($nextThreeMonths as $month) {
            $key = $month['year'] . '-' . str_pad($month['month'], 2, '0', STR_PAD_LEFT);
            $yearMonthSums[$key] = 0;
        }
        foreach ($demandArray as $projectId => $projectInfo) {

            foreach ($projectInfo['demand'] ?? [] as $yearMonth => $demand) {
                // $date = Carbon::createFromFormat('Y-m', $yearMonth);
                // $yearMonthShortName = $date->format('M');

                if (!isset($yearMonthSums[$yearMonth])) {
                    $yearMonthSums[$yearMonth] = 0;
                }
                $yearMonthSums[$yearMonth] += (float) $demand;
            }
        }

        //sort by month and then convert to month short name
        ksort($yearMonthSums);
        foreach ($yearMonthSums as $yearMonth => $sum) {
            $date = Carbon::createFromFormat('Y-m', $yearMonth);
            $yearMonthShortName = $date->format('M');

            $yearMonthSums[$yearMonthShortName] = $sum;
            unset($yearMonthSums[$yearMonth]);
        }

        return view('widgets.upcoming_demand', [
            'config' => $this->config,
            'yearMonthSums' => $yearMonthSums,
        ]);
    }
}
