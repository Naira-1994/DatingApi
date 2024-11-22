<?php

namespace App\Http\Controllers\V1\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\GetUserPairsRequest;
use App\Services\DTO\Profile\GetUserPairsDto;
use App\Services\Profile\ProfileService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserProfilePairsController extends Controller
{
    public function __construct(protected ProfileService $profileService)
    {

    }
    public function list(GetUserPairsRequest $request): AnonymousResourceCollection
    {
        $dto = GetUserPairsDto::fromRequest($request);

        return $this->profileService->getUserPairs($dto);
    }
}
