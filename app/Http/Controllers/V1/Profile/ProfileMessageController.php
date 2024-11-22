<?php

namespace App\Http\Controllers\V1\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileMessage\GetMessagesHistoryRequest;
use App\Http\Requests\ProfileMessage\GetNewMessagesByDateRequest;
use App\Http\Requests\ProfileMessage\SendProfileMessageRequest;
use App\Http\Resources\Profile\ProfileMessage\ProfileMessageResource;
use App\Http\Resources\Profile\ProfileMessage\SendMessageResource;
use App\Services\DTO\Profile\ProfileMessage\GetMessagesHistoryDto;
use App\Services\DTO\Profile\ProfileMessage\GetNewMessagesByDateDto;
use App\Services\DTO\Profile\ProfileMessage\SendProfileMessageDto;
use App\Services\Profile\ProfileMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProfileMessageController extends Controller
{
    public function __construct(
        protected readonly ProfileMessageService $profileMessageService
    ) {
    }

    /**
     * @throws \Exception
     */
    public function send(SendProfileMessageRequest $request): SendMessageResource
    {
        $dto = SendProfileMessageDto::fromRequest($request);

        return $this->profileMessageService->send($dto);
    }

    public function getNewMessagesByDate(GetNewMessagesByDateRequest $request): AnonymousResourceCollection
    {
        $dto = GetNewMessagesByDateDto::fromRequest($request);

        return $this->profileMessageService->getNewMessagesByDate($dto);
    }

    public function getMessagesHistory(GetMessagesHistoryRequest $request): AnonymousResourceCollection
    {
        $dto = GetMessagesHistoryDto::fromRequest($request);

        return $this->profileMessageService->getMessagesHistory($dto);
    }
}
