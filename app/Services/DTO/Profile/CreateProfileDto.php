<?php

namespace App\Services\DTO\Profile;

use App\Http\Requests\Profile\CreateProfileRequest;
use Spatie\LaravelData\Data;

class CreateProfileDto extends Data
{
    public string $name;
    public string $birthday;
    public string $gender;
    public ?string $description;
    public array $geoPosition;
    public array $interests;

    public int $type;
    public int $userId;

    public static function fromRequest(CreateProfileRequest $request): self
    {
        return self::from([
            'name' => $request->getName(),
            'birthday' => $request->getBirthDay(),
            'gender' => $request->getGender(),
            'description' => $request->getDescription(),
            'geoPosition' => [
                'latitude' => $request->getGeoPositionLatitude(),
                'longitude' => $request->getGeoPositionLongitude(),
            ],
            'interests' => $request->getInterests(),
            'type' => $request->getType(),
            'userId' => $request->user()->id,
        ]);
    }
}
