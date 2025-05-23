<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TeamInvite
 *
 * @property int $id
 * @property int $user_id
 * @property int $team_id
 * @property string $type
 * @property string $email
 * @property string $accept_token
 * @property string $deny_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TeamInvite extends Model
{
    protected $fillable = [
        'user_id',
        'team_id',
        'type',
        'email',
        'accept_token',
        'deny_token',
    ];

    /**
     * Get the user associated with the team invite.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the team associated with the team invite.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Team::class);
    }
}
