<?php

namespace App\Models;

use App\Enums\UserProfileActionTypeEnum;
use App\Services\DTO\Profile\CreateProfileDto;
use App\Services\DTO\Profile\UpdateProfileDto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


/**
 * App\Models\Profile
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $birthday
 * @property string $gender
 * @property string $description
 * @property array $geo_position
 * @property array $interests
 * @property string $type
 * @property string $last_online
 */
class Profile extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const PHOTOS = 'photos';
    public const RELATIONS = [
        self::PHOTOS,
    ];

    protected $table = 'profiles';

    const CREATED_AT = 'created_at_timestamp';
    const UPDATED_AT = 'updated_at_timestamp';

    protected bool $timestamp = false;

    protected $fillable = [
        'user_id',
        'name',
        'birthday',
        'gender',
        'description',
        'geo_position',
        'interests',
        'type',
        'last_online',
    ];

    public static function createModel(CreateProfileDto $dto): array
    {
        return [
            'name' => $dto->name,
            'birthday' => $dto->birthday,
            'user_id' => $dto->userId,
            'gender' => $dto->gender,
            'description' => $dto->description,
            'geo_position' => json_encode($dto->geoPosition),
            'interests' => json_encode($dto->interests),
            'last_online' => Carbon::now()->timestamp,
            'type' => $dto->type,
        ];
    }

    public static function updateModel(UpdateProfileDto $dto): array
    {
        $data = [];

        add_value_to_array($dto->name, 'name', $data);
        add_value_to_array($dto->description, 'description', $data);
        add_value_to_array($dto->gender, 'gender', $data);
        add_value_to_array($dto->type, 'type', $data);
        add_value_to_array($dto->birthday, 'birthday', $data);

        $data['updated_at_timestamp'] = Carbon::now()->timestamp;
        $data['last_online'] = Carbon::now()->timestamp;

        if (isset($dto->interests)) {
            $data['interests'] = json_encode($dto->interests);
        }

        if (isset($dto->geoPosition['latitude']) && isset($dto->geoPosition['longitude'])) {
            $data['geo_position'] = json_encode($dto->geoPosition);
        }

        return $data;
    }

    public function setCreatedAt($value): void
    {
        $this->attributes[self::CREATED_AT] = $value instanceof Carbon ? $value->timestamp : Carbon::parse($value)->timestamp;
    }

    public function setUpdatedAt($value): void
    {
        $this->attributes[self::UPDATED_AT] = $value instanceof Carbon ? $value->timestamp : Carbon::parse($value)->timestamp;
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', '=', 'default');
    }

    public function actions(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_profile_actions', 'profile_id', 'user_id')
            ->withPivot(['*']);
    }
    public function notAnyAction(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_profile_actions', 'profile_id', 'user_id')
            ->wherePivot('type', UserProfileActionTypeEnum::NO->value);
    }
}
