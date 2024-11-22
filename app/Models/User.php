<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone'
    ];

    const CREATED_AT = 'created_at_timestamp';
    const UPDATED_AT = 'updated_at_timestamp';

    protected bool $timestamp = false;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setCreatedAt($value): void
    {
        $this->attributes[self::CREATED_AT] = $value instanceof Carbon ? $value->timestamp : Carbon::parse($value)->timestamp;
    }

    public function setUpdatedAt($value): void
    {
        $this->attributes[self::UPDATED_AT] = $value instanceof Carbon ? $value->timestamp : Carbon::parse($value)->timestamp;
    }
    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'user_profile_actions', 'user_id', 'profile_id')
            ->withPivot('type');
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }
}
