<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public const PER_PAGE = 'per_page';

    public const PAGE = 'page';


    public function getPage(): int
    {
        return $this->query(self::PAGE) ?? 1;
    }

    public function getPerPage(): int
    {
        return $this->query(self::PER_PAGE) ?? 15;
    }
}
