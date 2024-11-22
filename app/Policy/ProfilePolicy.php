<?php

namespace App\Policy;

use App\Models\Profile;
use App\Models\User;

class ProfilePolicy
{
    public function hasAccess(User $user, int $profileId): bool
    {
        $userId = Profile::query()
            ->where('id', $profileId)
            ->firstOrFail()->user_id;

        return $userId === $user->id;
    }
}
