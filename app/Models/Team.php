<?php

namespace App\Models;

use Mpociot\Teamwork\TeamworkTeam;

/**
 * Class Team
 *
 * @property $id
 * @property $owner_id
 * @property $name
 * @property $resource_type
 * @property $created_at
 * @property $updated_at
 * @property $parent_team_id
 * @property TeamInvite[] $teamInvites
 * @property TeamUser[] $teamUsers
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Team extends TeamworkTeam
{
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['owner_id', 'name', 'resource_type'];

    public function parentTeam()
    {
        return $this->belongsTo(Team::class, 'parent_team_id');
    }

    public function subTeams()
    {
        return $this->hasMany(Team::class, 'parent_team_id');
    }

    public static function allSubTeamResourceTypes(Team $parentTeam): array
    {
        $subTeams = $parentTeam->subTeams;
        $subTeamResourceTypes = collect($subTeams->pluck('resource_type')->filter()->toArray());
        if ($parentTeam->resource_type !== null) {
            $subTeamResourceTypes->push($parentTeam->resource_type);
        }

        foreach ($subTeams as $subTeam) {
            $subTeamResourceTypes = $subTeamResourceTypes->merge(self::allSubTeamResourceTypes($subTeam));
        }

        return $subTeamResourceTypes->unique()->toArray();
    }
}
