<?php

namespace App\Http\Requests\Profile;

use App\Enums\UserProfileActionTypeEnum;
use App\Models\Profile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SetReactionRequest extends FormRequest
{
    public const ACTION_TYPE = 'action_type';
    public const PROFILE_USER_ID = 'profileUserId';

    public function rules(): array
    {
        return [
            self::ACTION_TYPE => [
                new Enum(UserProfileActionTypeEnum::class),
                'required'
            ],
        ];
    }

    public function getActionType(): UserProfileActionTypeEnum
    {
        return UserProfileActionTypeEnum::from($this->get(self::ACTION_TYPE));
    }

    public function getProfileUserId(): int
    {
        return $this->route(self::PROFILE_USER_ID);
    }
}
