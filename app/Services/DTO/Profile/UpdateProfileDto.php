<?php

namespace App\Services\DTO\Profile;

use App\Http\Requests\Profile\UpdateProfileRequest;
use Spatie\LaravelData\Data;

class UpdateProfileDto extends Data
{
    public int $userId;

    public ?string $name;

    public ?string $birthday;
    public ?string $gender;
    public ?string $description;
    public ?array $geoPosition;
    public ?array $interests;

    public ?int $type;

    public static function fromRequest(UpdateProfileRequest $request): self
    {
        return self::from([
            'userId' => $request->user()->id,
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
        ]);
    }
}
