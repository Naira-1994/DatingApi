<?php

namespace App\Http\Requests\Profile;

use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;

class GetProfileRequest extends FormRequest
{
    public const PROFILE_USER_ID = 'profileUserId';

    public function rules(): array
    {
        return [];
    }

    public function getProfileUserId(): int
    {
        return $this->route(self::PROFILE_USER_ID);
    }


}
