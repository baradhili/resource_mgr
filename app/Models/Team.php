<?php namespace App;

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
 *
 * @property TeamInvite[] $teamInvites
 * @property TeamUser[] $teamUsers
 * @package App
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
}