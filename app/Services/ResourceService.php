<?php

namespace App\Services;

use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResourceService
{
    /**
     * Returns a list of resources that the user is allowed to see.
     * This can be resources directly linked to the user, or resources linked to teams that the user owns or manages.
     * The list of resources is filtered by the start and end dates of the contracts.
     *
     * @param  int|null  $regionID  The ID of the region to filter by. If null, no region filter is applied.
     * @param  bool  $all  If true, all resources in the database are returned. If false, only resources linked to the user are returned.
     * @return \Illuminate\Support\Collection A collection of resources that the user is allowed to see.
     */
    public function getResourceList($regionID = null, $all = false)
    {
        // get user
        $user = Auth::user();
        // Check if the user is an owner of a team
        $resources = collect();
        if ($user->ownedTeams()->count() > 0) {
            // Get the team's resources
            $resource_types = $user->ownedTeams->pluck('resource_type')->toArray();
            // Log::info("User is an owner of a team with resource types: " . json_encode($resource_types));
            $resource_types = ResourceType::whereIn('id', $resource_types)->pluck('id')->toArray();
            // Log::info("resource types: " . json_encode($resource_types));
            $resources = $resources->merge(
                Resource::whereHas('contracts', function ($query) {
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
                                ->where('end_date', '>=', now())
                                ->orderBy('start_date', 'desc')
                                ->orderBy('end_date', 'desc')
                                ->limit(1);
                        },
                        'region',
                    ])->get()
            );
            //  Log::info("resources: " . json_encode($resources));
        }

        if ($user->reportees->count() > 0) {
            // check if the user is a manager
            // Log::info("User is a manager");
            $reportees = $user->reportees;
            $resourceIDs = $user->reportees->pluck('resource.id')->toArray();
            // check if we are higher in the hierarchy
            foreach ($reportees as $reportee) {
                if ($reportee->reportees->count() > 0) {
                    $resourceIDs = array_merge($resourceIDs, $reportee->reportees->pluck('resource.id')->toArray());
                }
            }

            // Log::info("managed resourceids: " . json_encode($resourceIDs));
            $resources = $resources->merge(
                Resource::whereIn('id', $resourceIDs)
                    ->whereHas('contracts', function ($query) {
                        // FIX: Moved region logic OUT of here. Contracts don't have regions.
                        $query->where('start_date', '<=', now())
                            ->where('end_date', '>=', now());
                    })
                    // FIX: Apply region filter to the Resource itself
                    ->when($regionID, function ($query, $regionID) {
                        return $query->whereHas('region', function ($query) use ($regionID) {
                            $query->where('id', $regionID);
                        });
                    })
                    ->with('contracts')->get()
            );

            // Remove duplicates that may result from multiple merge operations
            $resources = $resources->unique('id');
            // Log::info("resources: " . json_encode($resources));
        }

        if ($all) {
            $resources = $resources->merge(
                Resource::whereHas('contracts', function ($query) {
                    $query->where('start_date', '<=', now())
                        ->where('end_date', '>=', now());
                })
                // FIX: Added the missing region filter for the 'All' case
                    ->when($regionID, function ($query, $regionID) {
                        return $query->whereHas('region', function ($query) use ($regionID) {
                            $query->where('id', $regionID);
                        });
                    })
                    ->get()
            );
        } else {
            // FIX: Also add region filter to the 'Self' case for consistency
            $resources = $resources->merge(
                Resource::where('id', $user->resource_id)
                    ->when($regionID, function ($query, $regionID) {
                        return $query->whereHas('region', function ($query) use ($regionID) {
                            $query->where('id', $regionID);
                        });
                    })
                    ->with('contracts')->get()
            );
        }
        // Remove duplicates that may result from multiple merge operations
        $resources = $resources->unique('id');
        // Log::info("resources: " . json_encode($resources));

        // Log::info("resources: " . json_encode($resources));
        // Log::info("resources: " . json_encode($resources));

        return $resources;
    }
}
