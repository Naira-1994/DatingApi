<?php

namespace App\Http\Requests\Profile;

use App\Http\Requests\ListRequest;

class SearchProfilesRequest extends ListRequest
{
    public const INTERESTS = 'interests';
    public const INTEREST = 'interests.*';
    public const GEO_POSITION = 'geo_position';
    public const GEO_POSITION_LATITUDE = 'geo_position.latitude';
    public const GEO_POSITION_LONGITUDE = 'geo_position.longitude';
    public const RADIUS = 'radius';
    public const GENDER = 'gender';

    public function rules(): array
    {
        return [
            self::GEO_POSITION => [
                'array',
                'nullable',
            ],
            self::GEO_POSITION_LATITUDE => [
                'nullable',
                'numeric',
            ],
            self::GEO_POSITION_LONGITUDE => [
                'nullable',
                'numeric',
            ],
            self::INTERESTS => [
                'array',
                'nullable',
            ],
            self::INTEREST => [
                'string',
            ],
            self::RADIUS => [
                'numeric',
                'nullable',
            ],
            self::GENDER => [
                'string',
                'nullable',
            ],
        ];
    }


    public function getGeoPosition(): ?array
    {
        return $this->input(self::GEO_POSITION);
    }

    public function getGeoPositionLatitude(): ?string
    {
        return $this->input(self::GEO_POSITION . '.latitude');
    }

    public function getGeoPositionLongitude(): ?string
    {
        return $this->input(self::GEO_POSITION . '.longitude');
    }

    public function getInterests(): ?array
    {
        return $this->input(self::INTERESTS);
    }

    public function getRadius(): ?float
    {
        return $this->input(self::RADIUS);
    }

    public function getGender(): ?string
    {
        return $this->input(self::GENDER);
    }
}
