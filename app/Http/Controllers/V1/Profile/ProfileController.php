<?php

namespace App\Http\Controllers\V1\Profile;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\CreateProfileRequest;
use App\Http\Requests\Profile\DeletePhotoRequest;
use App\Http\Requests\Profile\GetProfileRequest;
use App\Http\Requests\Profile\SearchProfilesRequest;
use App\Http\Requests\Profile\SetReactionRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Requests\Profile\UploadProfilePhotoRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Models\Profile;
use App\Services\DTO\Profile\CreateProfileDto;
use App\Services\DTO\Profile\SearchProfilesDto;
use App\Services\DTO\Profile\UpdateProfileDto;
use App\Services\DTO\Profile\UploadProfilePhotoDto;
use App\Services\DTO\Profile\UserProfileActionTypeDto;
use App\Services\Profile\ProfileService;
use Dflydev\DotAccessData\Data;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $profileService)
    {
    }

    /**
     * @throws Exception
     */
    public function create(CreateProfileRequest $request): JsonResponse
    {
        $dto = CreateProfileDto::fromRequest($request);

        $existingProfile = Profile::query()->where('user_id', $request->user()->id)->exists();

        if ($existingProfile) {
            throw new Exception('The user already has profile.', 400);
        }

        $profile = $this->profileService->createProfile($dto);

        return $this->response(['data' => $profile],201);
    }

    /**
     * @throws FileCannotBeAdded
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        return $this->response(['data' => $this->profileService->updateProfile(UpdateProfileDto::fromRequest($request))]);
    }

    /**
     * @throws FileIsTooBig
     * @throws FileDoesNotExist|FileCannotBeAdded
     */
    public function uploadPhoto(UploadProfilePhotoRequest $request): JsonResponse
    {
        $dto = UploadProfilePhotoDto::fromRequest($request);

        return $this->response(['data' => $this->profileService->uploadPhoto($dto)]);
    }

    /**
     * @throws Exception
     */
    public function deletePhoto(DeletePhotoRequest $request): JsonResponse
    {
        $this->profileService->deletePhotoById($request->getPhotoId());

        return $this->response(['message' => 'Photo deleted successfully']);
    }

    public function searchProfiles(SearchProfilesRequest $request): AnonymousResourceCollection
    {
        $dto = SearchProfilesDto::fromRequest($request);

        return $this->profileService->searchProfiles($dto);
    }

    public function show(GetProfileRequest $request): JsonResponse
    {
        $profile = $this->profileService->getProfileByUserId($request->getProfileUserId());

        return $this->response([
            'data' => new ProfileResource($profile)
        ]);
    }

    public function setReaction(SetReactionRequest $request): JsonResponse
    {
        $dto = UserProfileActionTypeDto::fromRequest($request);

        $this->profileService->setReaction($dto);

        return $this->response(['success' => true]);
    }

    public function getCountLikedProfiles(): JsonResponse
    {
        return $this->response([
            'count' => $this->profileService->getCountLikedProfiles(auth()->id())
        ]);
    }
}
