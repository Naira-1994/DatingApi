<?php

namespace App\Repositories\Profile\ProfileMessage;

use App\Models\ProfileMessage;
use App\Services\DTO\Profile\ProfileMessage\GetNewMessagesByDateDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProfileMessageRepositoryInterface
{
    public function create(array $data): ProfileMessage;

    public function getNewMessagesByDate(GetNewMessagesByDateDto $dto): LengthAwarePaginator;
}
