<?php

namespace App\Services\DTO\Profile\ProfileMessage;

use App\Http\Requests\ProfileMessage\GetNewMessagesByDateRequest;
use App\Services\DTO\PaginationParamsDto;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class GetNewMessagesByDateDto extends Data
{
    public ?string $date;

    public int $userId;

    public PaginationParamsDto $pagination;

    public static function fromRequest(GetNewMessagesByDateRequest $request): self
    {
        return self::from([
            'date' => $request->getDate(),
            'userId' => $request->user()->id,
            'pagination' => PaginationParamsDto::fromParams($request->getPage(), $request->getPerPage()),
        ]);
    }
}
