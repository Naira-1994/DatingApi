<?php

namespace App\Services\Profile;

use App\Enums\UserProfileActionTypeEnum;
use App\Http\Resources\Media\MediaResource;
use App\Http\Resources\Profile\ProfileResource;
use App\Http\Resources\Profile\UserPairProfileResource;
use App\Models\Profile;
use App\Repositories\Profile\ProfileRepositoryInterface;
use App\Repositories\UserProfileAction\UserProfileActionInterface;
use App\Services\DTO\Profile\CreateProfileDto;
use App\Services\DTO\Profile\GetUserPairsDto;
use App\Services\DTO\Profile\SearchProfilesDto;
use App\Services\DTO\Profile\UpdateProfileDto;
use App\Services\DTO\Profile\UploadProfilePhotoDto;
use App\Services\DTO\Profile\UserProfileActionTypeDto;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

use function Symfony\Component\String\u;

class ProfileService
{
    public function __construct(
        protected ProfileRepositoryInterface $profileRepository,
        protected UserProfileActionInterface $userProfileAction
    ) {
    }

    /**
     * @throws Exception
     */
    public function createProfile(CreateProfileDto $dto): ProfileResource
    {
        try {
            /**
             * @var Profile $profile
             */
            $profile = Profile::query()
                ->create(Profile::createModel($dto));

            return new ProfileResource($profile->load(Profile::RELATIONS));
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws Exception
     */
    public function updateProfile(UpdateProfileDto $dto): ProfileResource
    {
        $profile = $this->profileRepository->getProfileByUserId($dto->userId);

        try {
            $this->profileRepository->update(Profile::updateModel($dto), $profile->id);

            $profile->refresh();

            return new ProfileResource($profile->load(Profile::RELATIONS));
        } catch (Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     * @throws FileCannotBeAdded
     */
    public function uploadPhoto(UploadProfilePhotoDto $dto): AnonymousResourceCollection
    {
       $profile = $this->profileRepository->getProfileByUserId($dto->userId);

       $photos = [];
        foreach ($dto->photos as $photo) {
           $photos[] = $profile->addMedia($photo)
                ->toMediaCollection();
        }

        return MediaResource::collection($photos);
    }

    /**
     * @throws Exception
     */
    public function deletePhotoById(string $photoId): bool
    {
        $photo = Media::query()
            ->where('id', $photoId)
            ->firstOrFail();

        if (!$photo->delete()) {
            throw new Exception('Failed delete photo');
        }

        return true;
    }

    public function searchProfiles(SearchProfilesDto $dto): AnonymousResourceCollection
    {
        return ProfileResource::collection($this->profileRepository->searchProfiles($dto));
    }

    public function getProfileByUserId(int $userId): ?Profile
    {
        return $this->profileRepository->getProfileByUserId($userId);
    }

    /**
     * @throws Exception
     */
    public function setReaction(UserProfileActionTypeDto $dto): void
    {
        if ($dto->userId == $dto->profileUserId) {
            throw new Exception('User cannot like its profile.', 500);
        }

        try {
            if ($dto->actionType->value === UserProfileActionTypeEnum::NO->value) {
                $this->profileRepository->unsetReaction($dto);

                return;
            }

            $this->profileRepository->setReaction($dto);
        } catch (Throwable $e) {
            throw new $e;
        }

    }

    public function getCountLikedProfiles(int $userId): int
    {
        return $this->profileRepository->getCountLikedProfiles($userId);
    }

    public function getUserPairs(GetUserPairsDto $dto): AnonymousResourceCollection
    {
        $userLikedProfileIds = $this->profileRepository->getUserLikedProfileIds($dto->userId)
            ->toArray();

        $userIds = $this->profileRepository->getUserIdsByProfileIds($userLikedProfileIds)
            ->toArray();

        $userPairs = $this->userProfileAction->getUserPairs($userIds, $dto);

        $data = [];

        foreach ($userPairs as $userPair) {

            $data[] = [
                'user_id' => $dto->userId,
                'pair_user_id' => $userPair->user_id,
                'last_message' => $userPair->last_message,
                'last_message_date' => $userPair->last_message_date,
            ];
        }

        return UserPairProfileResource::collection(
            $this->paginateData($data, $dto->pagination->page, $dto->pagination->perPage)
        );
    }

    private function paginateData(array $data, int $page, int $perPage): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $data, // Items for the current page
            collect($data)->count(), // Total number of items
            $perPage, // Items per page
            $page, // Current page
            ['path' => LengthAwarePaginator::resolveCurrentPath()] // Path for pagination links
        );
    }
}
