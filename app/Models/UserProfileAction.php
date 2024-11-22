<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserProfileAction extends Model
{
    protected $table = 'user_profile_actions';

    protected bool $timestamp = false;

    const CREATED_AT = 'created_at_timestamp';
    const UPDATED_AT = 'updated_at_timestamp';

    protected $fillable = [
        'user_id',
        'profile_id',
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function setCreatedAt($value): void
    {
        $this->attributes[self::CREATED_AT] = $value instanceof Carbon ? $value->timestamp : Carbon::parse($value)->timestamp;
    }

    public function setUpdatedAt($value): void
    {
        $this->attributes[self::UPDATED_AT] = $value instanceof Carbon ? $value->timestamp : Carbon::parse($value)->timestamp;
    }
}
