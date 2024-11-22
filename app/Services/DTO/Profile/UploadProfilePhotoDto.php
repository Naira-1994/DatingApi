<?php

namespace App\Services\DTO\Profile;

use App\Http\Requests\Profile\UploadProfilePhotoRequest;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class UploadProfilePhotoDto extends Data
{
    public int $userId;

    public array $photos;

    public static function fromRequest(UploadProfilePhotoRequest $request): self
    {
        $photos = $request->hasFile('photos') ? $request->file('photos') : [];

        return self::from([
            'userId' => $request->user()->id,
            'photos' => $photos,
        ]);
    }
}
