<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Glorand\Model\Settings\Traits\HasSettingsField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Mpociot\Teamwork\Traits\UserHasTeams;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, HasSettingsField, Notifiable, UserHasTeams;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'current_team_id',
        'resource_id',
        'password',
        'reports',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the team users associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamUsers()
    {
        return $this->hasMany(TeamUser::class, 'user_id');
    }

    /**
     * Get the reporting line of the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reportingLine()
    {
        return $this->hasOne(User::class, 'id', 'reports');
    }

    /**
     * Get the people who report to this manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reportees()
    {
        return $this->hasMany(User::class, 'reports', 'id');
    }

    /**
     * Get the resource linked to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }
}
