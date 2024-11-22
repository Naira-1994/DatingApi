<?php

namespace App\Repositories\Profile\ProfileMessage;

use App\Models\Profile;
use App\Models\ProfileMessage;
use App\Services\DTO\Profile\ProfileMessage\GetMessagesHistoryDto;
use App\Services\DTO\Profile\ProfileMessage\GetNewMessagesByDateDto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ProfileMessageRepository implements ProfileMessageRepositoryInterface
{
    public function create(array $data): ProfileMessage
    {/**
         * @var ProfileMessage
         */
        return ProfileMessage::query()->create($data);
    }

    public function getNewMessagesByDate(GetNewMessagesByDateDto $dto): LengthAwarePaginator
    {
        return ProfileMessage::query()
            ->where(function($query) use ($dto) {
                $query->where('id_from', auth()->user()->id)
                    ->orWhere('id_to', $dto->userId);
            })
            ->when($dto->date, function (Builder $query) use ($dto) {
               $query->where('created_at_timestamp','>=', $dto->date);
            })
            ->paginate(
                  $dto->pagination->perPage,
                  ['*'],
                  'page',
                  $dto->pagination->page
              );
    }

    public function getMessagesHistory(GetMessagesHistoryDto $dto): LengthAwarePaginator
    {
        return ProfileMessage::query()
            ->where(function ($query) use ($dto) {
                $query->where(function ($subQuery) use ($dto) {
                    $subQuery->where('id_from', $dto->userId)
                        ->where('id_to', $dto->id_to);
                })->orWhere(function ($subQuery) use ($dto) {
                    $subQuery->where('id_from', $dto->id_to)
                        ->where('id_to', $dto->userId);
                });
            })
            ->when($dto->date, function (Builder $query) use ($dto) {
                if ($dto->date) {
                    $query->where('created_at_timestamp', '>=', $dto->date);
                }
            })
            ->paginate(
                $dto->pagination->perPage,
                ['*'],
                'page',
                $dto->pagination->page
            );


    }
}
