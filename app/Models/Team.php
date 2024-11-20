<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Team
 *
 * @property $id
 * @property $owner_id
 * @property $name
 * @property $created_at
 * @property $updated_at
 *
 * @property TeamInvite[] $teamInvites
 * @property TeamUser[] $teamUsers
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
    protected $fillable = ['owner_id', 'name'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamInvites()
    {
        return $this->hasMany(\App\Models\TeamInvite::class, 'id', 'team_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamUsers()
    {
        return $this->hasMany(\App\Models\TeamUser::class, 'id', 'team_id');
    }
    
}
