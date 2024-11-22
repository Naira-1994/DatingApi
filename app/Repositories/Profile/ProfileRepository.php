<?php

namespace App\Repositories\Profile;

use App\Enums\UserProfileActionTypeEnum;
use App\Models\Profile;
use App\Models\UserProfileAction;
use App\Services\DTO\Profile\SearchProfilesDto;
use App\Services\DTO\Profile\UserProfileActionTypeDto;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use \Illuminate\Support\Collection as SupportCollection;

use function Symfony\Component\String\s;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function getProfileByUserId(int $userId): Profile
    {
        /**
         * @var Profile
         */
        return Profile::query()->with('photos')
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function searchProfiles(SearchProfilesDto $dto): LengthAwarePaginator
    {
        $query = Profile::query()
            ->doesntHave('notAnyAction');

        if (!empty($dto->geoPosition['latitude']) && !empty($dto->geoPosition['longitude'])) {
            $latitude = $dto->geoPosition['latitude'];
            $longitude = $dto->geoPosition['longitude'];
            $radius = $dto->radius;

            // Calculating distance using the provided formula
            $query->whereRaw('(
                POW((profiles.geo_position->>\'latitude\')::numeric - ?, 2) +
                POW((profiles.geo_position->>\'longitude\')::numeric - ?, 2)
            ) <= POW(?, 2)', [$latitude, $longitude, $radius]);
        }

        if (!empty($dto->interests)) {
            $query->where(function ($query) use ($dto) {
                foreach ($dto->interests as $interest) {
                    $query->orWhereJsonContains('interests', $interest);
                }
            });
        }

        if (!empty($dto->gender)) {
            $query->where('gender', $dto->gender);
        }

        return $query->paginate(
            $dto->pagination->perPage,
            ['*'],
            'page',
            $dto->pagination->page
        );
    }
    /**
     * @throws Exception
     */
    public function update(array $data, int $id): bool
    {
        $status =  Profile::query()
            ->where('id', $id)
            ->update($data);

        if (!$status) {
            throw new Exception('Failed update profile!');
        }

        return $status;
    }

    /**
     * @throws Exception
     */
    public function setReaction(UserProfileActionTypeDto $dto): UserProfileAction
    {
        $userProfile = Profile::query()
            ->where('user_id', $dto->profileUserId)
            ->firstOrFail();

        /**
         * @var UserProfileAction
         */
       return UserProfileAction::query()
            ->updateOrCreate(
                ['user_id' => $dto->userId, 'profile_id' => $userProfile->id],
                ['type' => $dto->actionType->value]
            );
    }

    /**
     * @throws Exception
     */
    public function unsetReaction(UserProfileActionTypeDto $dto): bool
    {
        $userProfile = Profile::query()
            ->where('user_id', $dto->profileUserId)
            ->firstOrFail();

        $status = UserProfileAction::query()
            ->where('user_id', $dto->userId)
            ->where('profile_id', $userProfile->id)
            ->delete();

        if (!$status) {
            throw new Exception('Failed unset reaction');
        }

        return $status;
    }

    public function getCountLikedProfiles(int $userId): int
    {
        return UserProfileAction::query()->where('user_id', $userId)
            ->where('type', UserProfileActionTypeEnum::LIKE->value)
            ->count();
    }

    public function setNewLastOnlineTime(int $id): int
    {
      return Profile::query()
            ->where('id', $id)
            ->update(['last_online' => Carbon::now()]);
    }

    public function getUserLikedProfileIds(int $userId): SupportCollection
    {
        return Profile::query()
            ->whereHas('actions', function (Builder $query)  use ($userId) {
                $query->where('user_id', $userId)
                    ->where('type', UserProfileActionTypeEnum::LIKE->value);
            })
            ->pluck('id');
    }

    public function getUserIdsByProfileIds(array $profileIds): SupportCollection
    {
        return Profile::query()->whereIn('id', $profileIds)
            ->pluck('user_id');
    }
}
