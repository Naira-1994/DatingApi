<?php

namespace App\Http\Requests\Profile;

use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProfileRequest extends FormRequest
{
    public const NAME = 'name';
    public const BIRTHDAY = 'birthday';
    public const GENDER = 'gender';
    public const DESCRIPTION = 'description';
    public const GEO_POSITION = 'geo_position';
    public const GEO_POSITION_LATITUDE = 'geo_position.latitude';
    public const GEO_POSITION_LONGITUDE = 'geo_position.longitude';
    public const INTERESTS = 'interests';
    public const INTEREST = 'interests.*';
    public const TYPE = 'type';
    public function rules(): array
    {
        return [
            self::NAME => [
                'string',
                'required',
                'max:255',
            ],

            self::BIRTHDAY => [
                'int',
                'required',
            ],

            self::GENDER => [
                Rule::in(['male', 'female']),
                'required',
            ],

            self::DESCRIPTION => [
                'string',
                'nullable',
            ],

            self::GEO_POSITION => [
                'array',
                'required',
            ],
            self::GEO_POSITION_LONGITUDE => [
                'numeric',
                'required',
            ],
            self::GEO_POSITION_LATITUDE => [
                'numeric',
                'required',
            ],
            self::INTERESTS => [
              'array',
              'required',
            ],

            self::INTEREST => [
                'string',
            ],

            self::TYPE => [
              'int',
            ],
        ];
    }


    public function getName(): string
    {
        return $this->input(self::NAME);
    }

    public function getBirthDay(): string
    {
        return $this->input(self::BIRTHDAY);
    }

    public function getGender(): string
    {
        return $this->input(self::GENDER);
    }

    public function getDescription(): ?string
    {
        return $this->input(self::DESCRIPTION);
    }

    public function getGeoPosition(): array
    {
        return $this->input(self::GEO_POSITION);
    }

    public function getGeoPositionLatitude()
    {
        return $this->input('geo_position.latitude');
    }

    public function getGeoPositionLongitude()
    {
        return $this->input('geo_position.longitude');
    }
    public function getInterests(): array
    {
        return $this->input(self::INTERESTS);
    }

    public function getType(): int
    {
        return $this->input(self::TYPE);
    }
}
