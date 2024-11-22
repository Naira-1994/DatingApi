<?php

namespace App\Repositories\UserProfileAction;

use App\Services\DTO\Profile\GetUserPairsDto;
use Illuminate\Support\Collection;

interface UserProfileActionInterface
{
    public function getUserPairs(array $userIds, GetUserPairsDto $dto): Collection;
}
