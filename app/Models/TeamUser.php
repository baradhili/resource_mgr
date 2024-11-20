<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TeamUser
 *
 * @property $user_id
 * @property $team_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TeamUser extends Model
{
    protected $fillable = ['user_id', 'team_id'];
    protected $table = 'team_user';

    /**
     * Get the user associated with the team user.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the team associated with the team user.
     */
    public function team()
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}
