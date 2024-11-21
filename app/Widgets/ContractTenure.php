<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ContractTenure extends AbstractWidget
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

        $oneYearFromNow = Carbon::now()->addYear();

        $resources = Resource::whereHas('contracts', function ($query) use ($oneYearFromNow) {
            $query->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->where('end_date', '<=', $oneYearFromNow);
        })
            ->with([
                'contracts' => function ($query) use ($oneYearFromNow) {
                    $query->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->where('end_date', '<=', $oneYearFromNow)
                        ->orderBy('end_date', 'asc');
                }
            ])
            ->get()
            ->sortBy(function ($resource) {
                return $resource->contracts->last()->end_date ?? now();
            });

        $threeMonthsFromNow = Carbon::now()->addMonthsNoOverflow(3);
        $resourcesEndingSoonCount = $resources->filter(function ($resource) use ($threeMonthsFromNow) {
            return $resource->contracts->last()->end_date <= $threeMonthsFromNow;
        })->count();

    $firstResource = $resources->first();  
        $nextPersonOutDate = Carbon::parse($firstResource->contracts->last()->end_date)->format('Y-m-d');  
    $nextPersonOut = "{$firstResource->full_name} on {$nextPersonOutDate}";  

        return view('widgets.contract_tenure', [
            'config' => $this->config,
            'resourcesEndingSoonCount' => $resourcesEndingSoonCount,
            'nextPersonOut' => $nextPersonOut
        ]);
    }
}
