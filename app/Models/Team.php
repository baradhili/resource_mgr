<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 *
 * @property $id
 * @property $user_id
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * @property Ability[] $abilities
 * @property Group[] $groups
 * @property Invitation[] $invitations
 * @property Permission[] $permissions
 * @property Role[] $roles
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Team extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'name'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function abilities()
    {
        return $this->hasMany(\App\Models\Ability::class, 'id', 'team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(\App\Models\Group::class, 'id', 'team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations()
    {
        return $this->hasMany(\App\Models\Invitation::class, 'id', 'team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(\App\Models\Permission::class, 'id', 'team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->hasMany(\App\Models\Role::class, 'id', 'team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function teamUsers()
    {
        return $this->hasManyThrough(User::class, TeamUser::class, 'team_id', 'id', 'id', 'user_id');
    }

    /**
     * Get all users associated with the team, including the team owner.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllUsers()
    {
        // Get the team owner
        $users = collect([$this->owner]);

        // Get all users from the team_user pivot table
        $teamUsers = $this->teamUsers()->with('user')->get()->pluck('user');

        // Merge the collections and remove duplicates
        return $users->merge($teamUsers)->unique();
    }
}
