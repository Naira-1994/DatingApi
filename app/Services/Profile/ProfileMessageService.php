<?php

namespace App\Services\Profile;

use App\Http\Resources\Profile\ProfileMessage\ProfileMessageResource;
use App\Http\Resources\Profile\ProfileMessage\SendMessageResource;
use App\Models\ProfileMessage;
use App\Repositories\Profile\ProfileMessage\ProfileMessageRepositoryInterface;
use App\Repositories\Profile\ProfileRepositoryInterface;
use App\Services\DTO\Profile\ProfileMessage\GetMessagesHistoryDto;
use App\Services\DTO\Profile\ProfileMessage\GetNewMessagesByDateDto;
use App\Services\DTO\Profile\ProfileMessage\SendProfileMessageDto;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class ProfileMessageService
{
    public function __construct(
        protected readonly ProfileMessageRepositoryInterface $profileMessageRepository,
        protected readonly ProfileRepositoryInterface $profileRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function send(SendProfileMessageDto $dto): SendMessageResource
    {
        try {
            $profileMessage = $this->profileMessageRepository->create(ProfileMessage::createModel($dto));
            $this->profileRepository->setNewLastOnlineTime($dto->idFrom);


            return new SendMessageResource($profileMessage);
        } catch (Throwable $e) {
            throw new Exception('Failed send message to project!');
        }
    }

    public function getNewMessagesByDate(GetNewMessagesByDateDto $dto): AnonymousResourceCollection
    {
        return ProfileMessageResource::collection($this->profileMessageRepository->getNewMessagesByDate($dto));
    }

    public function getMessagesHistory(GetMessagesHistoryDto $dto): AnonymousResourceCollection
    {
        return ProfileMessageResource::collection($this->profileMessageRepository->getMessagesHistory($dto));
    }
}
