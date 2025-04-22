<?php

namespace App\Services;

use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResourceService
{
    public function getResourceList($regionID = null, $all = false)
    {
        // get user
        $user = Auth::user();
        // Check if the user is an owner of a team
        if ($user->ownedTeams()->count() > 0) {
            // Get the team's resources
            $resource_types = $user->ownedTeams->pluck('resource_type')->toArray();
            // Log::info("User is an owner of a team with resource types: " . json_encode($resource_types));
            $resource_types = ResourceType::whereIn('id', $resource_types)->pluck('id')->toArray();
            // Log::info("resource types: " . json_encode($resource_types));
            $resources = Resource::whereHas('contracts', function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })
                ->whereIn('resource_type', $resource_types)
                ->when($regionID, function ($query, $regionID) {
                    return $query->whereHas('region', function ($query) use ($regionID) {
                        $query->where('id', $regionID);
                    });
                })

                ->with([
                    'contracts' => function ($query) {
                        $query->where('start_date', '<=', now())
                            ->where('end_date', '>=', now());
                    },
                    'region',
                ])->get();
            // Log::info("resources: " . json_encode($resources));
        } elseif ($user->reportees->count() > 0) {
            // check if the user is a manager
            // Log::info("User is a manager");
            $reportees = $user->reportees;
            $resourceIDs = $user->reportees->pluck('resource.id')->toArray();
            foreach ($reportees as $reportee) {
                if ($reportee->reportees->count() > 0) {
                    $resourceIDs = array_merge($resourceIDs, $reportee->reportees->pluck('resource.id')->toArray());
                }
            }

            // Log::info("resourceids: " . json_encode($resourceIDs));
            // for each linked resource contract, check if the start date is before now and the end date is after now

            $resources = Resource::whereIn('id', $resourceIDs)
                ->whereHas('contracts', function ($query) use ($regionID) {
                    $query->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->when($regionID, function ($query) use ($regionID) {
                            return $query->whereHas('region', function ($query) use ($regionID) {
                                $query->where('id', $regionID);
                            });
                        });
                })
                ->with('contracts')->get();
            // Log::info("resources: " . json_encode($resources));
        } else {
            // Log::info("User is not an owner of a team or a manager");
            // otherwise just return the user's resource
            if ($all) {
                $resources = Resource::whereHas('contracts', function ($query) {
                    $query->where('start_date', '<=', now())
                        ->where('end_date', '>=', now());
                })->get();
            } else {
                $resources = Resource::where('id', $user->resource_id)->with('contracts')->get();
            }
            // Log::info("resources: " . json_encode($resources));
        }

        return $resources;
    }
}
