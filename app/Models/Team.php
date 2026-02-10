<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['owner_id', 'name', 'resource_type'];

    public function parentTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'parent_team_id');
    }

    public function subTeams(): HasMany
    {
        return $this->hasMany(Team::class, 'parent_team_id');
    }

    public function resourceType(): BelongsTo
    {
        return $this->belongsTo(ResourceType::class, 'resource_type');
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
