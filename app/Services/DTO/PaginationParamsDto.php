<?php

namespace App\Services\DTO;

use Spatie\LaravelData\Data;

class PaginationParamsDto extends Data
{
    public int $page;

    public int $perPage;

    public static function fromParams(int $page, int $perPage): self
    {
        return self::from([
            'page' => $page,
            'perPage' => $perPage,
        ]);
    }
}
