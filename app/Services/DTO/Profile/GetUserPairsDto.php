<?php

namespace App\Services\DTO\Profile;

use App\Http\Requests\Profile\GetUserPairsRequest;
use App\Services\DTO\PaginationParamsDto;
use Spatie\LaravelData\Data;

class GetUserPairsDto extends Data
{
    public PaginationParamsDto $pagination;
    public int $userId;

    public static function fromRequest(GetUserPairsRequest $request): self
    {
        return self::from([
            'pagination' => PaginationParamsDto::fromParams($request->getPage(), $request->getPerPage()),
            'userId' => $request->user()->id,
        ]);
    }
}
