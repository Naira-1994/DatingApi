<?php

namespace App\Services\DTO\Profile;

use App\Http\Requests\Profile\SearchProfilesRequest;
use App\Services\DTO\PaginationParamsDto;
use Spatie\LaravelData\Data;

class SearchProfilesDto extends Data
{
    public ?array $geoPosition;
    public ?array $interests;
    public ?float $radius;
    public ?string $gender;
    public PaginationParamsDto $pagination;

    public static function fromRequest(SearchProfilesRequest $request): self
    {
        $latitude = $request->getGeoPositionLatitude() ?? null;
        $longitude = $request->getGeoPositionLongitude() ?? null;
        $radius = $request->getRadius();

        return self::from([
            'geoPosition' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ] ?? [],
            'interests' => $request->getInterests() ?? [],
            'radius' => $radius ?? 10,
            'gender' => $request->getGender() ?? null,
            'pagination' => PaginationParamsDto::fromParams($request->getPage(), $request->getPerPage()),
        ]);
    }

}
