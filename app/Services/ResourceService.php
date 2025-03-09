<?php
namespace App\Services;

use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ResourceService
{
    public function getResourceList()
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
                ->with('contracts')->paginate();
            // Log::info("resources: " . json_encode($resources));
        } elseif ($user->reportees->count() > 0) {
            // check if the user is a manager
            // Log::info("User is a manager");
            $reportees = $user->reportees;
            //for each linked resource contract, check if the start date is before now and the end date is after now
            $resourceIDs = $user->reportees->pluck('resource.id')->toArray();
            $resources = Resource::whereIn('id', $resourceIDs)->whereHas('contracts', function ($query) {
                $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
            })->with('contracts')->paginate();
        } else {
            // Log::info("User is not an owner of a team or a manager");
            // otherwise just return the user's resource
            $resources = Resource::where('id', $user->resource_id)->with('contracts')->paginate();
        }
        return $resources;
    }
}