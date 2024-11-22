<?php

namespace App\Services\DTO\Profile\ProfileMessage;

use App\Http\Requests\ProfileMessage\GetMessagesHistoryRequest;
use App\Services\DTO\PaginationParamsDto;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class GetMessagesHistoryDto extends Data
{
    public ?string $date;
    public int $userId;
    public int $id_to;
    public PaginationParamsDto $pagination;

    public static function fromRequest(GetMessagesHistoryRequest $request): self
    {
        $date = $request->getDate();
        return self::from([
            'date' => $date,
            'userId' => $request->user()->id,
            'id_to' => $request->getIdTo(),
            'pagination' => PaginationParamsDto::fromParams($request->getPage(), $request->getPerPage()),
        ]);
    }
}
