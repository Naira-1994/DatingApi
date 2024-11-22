<?php

namespace App\Repositories\Profile;

use App\Models\Profile;
use App\Models\UserProfileAction;
use App\Services\DTO\Profile\SearchProfilesDto;
use App\Services\DTO\Profile\UserProfileActionTypeDto;
use Illuminate\Pagination\LengthAwarePaginator;
use \Illuminate\Support\Collection as SupportCollection;

interface ProfileRepositoryInterface
{
    public function getProfileByUserId(int $userId): Profile;

    public function searchProfiles(SearchProfilesDto $dto): LengthAwarePaginator;

    public function update(array $data, int $id): bool;

    public function setReaction(UserProfileActionTypeDto $dto): UserProfileAction;

    public function unsetReaction(UserProfileActionTypeDto $dto): bool;

    public function getCountLikedProfiles(int $userId): int;

    public function setNewLastOnlineTime(int $id): int;

    public function getUserLikedProfileIds(int $userId): SupportCollection;

    public function getUserIdsByProfileIds(array $profileIds): SupportCollection;
}
