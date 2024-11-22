<?php

namespace App\Http\Requests\Profile;

use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UploadProfilePhotoRequest extends FormRequest
{
    public const PHOTOS = 'photos';
    public const PHOTO = 'photos.*';
    public function rules(): array
    {
        return [
            self::PHOTOS => [
                'array',
                'required',
            ],

            self::PHOTO => [
                'file',
            ],
        ];
    }

    public function getPhotos(): array
    {
        return $this->input(self::PHOTOS);
    }

}
