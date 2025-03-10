<?php
namespace App\Services;

use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResourceService
{
    public function getResourceList($regionID = null)
    {
        //get user 
        $user = Auth::user();
        // Check if the user is an owner of a team
        if ($user->ownedTeams()->count() > 0) {
            // Get the team's resources
            // Log::info("User is an owner of a team with resource type: " . $user->ownedTeams()->first()->resource_type);
            $resource_type = ResourceType::where('name', $user->ownedTeams()->first()->resource_type)->first()->id;
            // Log::info("resource type: " . json_encode($resource_type));
            $resources = Resource::whereHas('contracts', function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })
                ->where('resource_type', $resource_type)
                ->when($regionID, function ($query, $regionID) {
                    return $query->whereHas('region', function ($query) use ($regionID) {
                        $query->where('id', $regionID);
                    });
                })
                
                ->with(['contracts' => function ($query) {
                    $query->where('start_date', '<=', now())
                        ->where('end_date', '>=', now());
                }, 'region'])->paginate();
            // Log::info("resources: " . json_encode($resources));
        } elseif ($user->reportees->count() > 0) {
            // check if the user is a manager
            Log::info("User is a manager");
            $reportees = $user->reportees;
            $resourceIDs = $user->reportees->pluck('resource.id')->toArray();
            foreach ($reportees as $reportee) {
                if ($reportee->reportees->count() > 0) {
                    $resourceIDs = array_merge($resourceIDs, $reportee->reportees->pluck('resource.id')->toArray());
                }
            }
            
            Log::info("resourceids: " . json_encode($resourceIDs));
            //for each linked resource contract, check if the start date is before now and the end date is after now

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
                ->with('contracts')->paginate();
            Log::info("resources: " . json_encode($resources));
        } else {
            // Log::info("User is not an owner of a team or a manager");
            // otherwise just return the user's resource
            $resources = Resource::where('id', $user->resource_id)->with('contracts')->paginate();
        }
        return $resources;
    }
}