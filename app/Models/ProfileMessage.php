<?php

namespace App\Models;

use App\Services\DTO\Profile\ProfileMessage\SendProfileMessageDto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProfileMessage extends Model
{
    use HasFactory;

    protected $table = 'profile_messages';

    protected $fillable = [
        'id_from',
        'id_to',
        'message_text',
    ];

//    protected $timestamp = false;
    const CREATED_AT = 'created_at_timestamp';
    const UPDATED_AT = 'updated_at_timestamp';

    public static function createModel(SendProfileMessageDto $dto): array
    {
        return [
            'id_from' => $dto->idFrom,
            'id_to' => $dto->idTo,
            'message_text'=> $dto->messageText,
        ];
    }

    public function setCreatedAt($value): void
    {
        $this->attributes[self::CREATED_AT] = $value instanceof Carbon ? $value->timestamp : Carbon::parse($value)->timestamp;
    }

    public function setUpdatedAt($value): void
    {
        $this->attributes[self::UPDATED_AT] = $value instanceof Carbon ? $value->timestamp : Carbon::parse($value)->timestamp;
    }

    public function getCreatedAtAttribute($value): Carbon
    {
        return Carbon::createFromTimestamp($value);
    }

    public function getUpdatedAtAttribute($value): Carbon
    {
        return Carbon::createFromTimestamp($value);
    }
}
