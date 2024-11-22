<?php

namespace App\Services\DTO\Profile;

use App\Enums\UserProfileActionTypeEnum;
use App\Http\Requests\Profile\SetReactionRequest;
use Spatie\LaravelData\Data;

class UserProfileActionTypeDto extends Data
{
    public int $userId;
    public UserProfileActionTypeEnum $actionType;
    public int $profileUserId;

    public static function fromRequest(SetReactionRequest $request): self
    {
        return self::from([
            'userId' => $request->user()->id,
            'actionType' => $request->getActionType(),
            'profileUserId' => $request->getProfileUserId(),
        ]);
    }
}
