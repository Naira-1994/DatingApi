<?php

namespace App\Http\Requests\Profile;

use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DeletePhotoRequest extends FormRequest
{
    public const ID = 'id';

    public function authorize(): bool
    {
        $profileId = Media::query()
            ->where('id', $this->getPhotoId())
            ->firstOrFail()->model_id;

        return $this->user()->can('hasAccess', [Profile::class, $profileId]);
    }

    public function rules(): array
    {
        return [];
    }

    public function getPhotoId(): int
    {
        return $this->route(self::ID);
    }
}
