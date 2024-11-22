<?php

namespace App\Http\Requests\ProfileMessage;
use App\Http\Requests\ListRequest;

class GetNewMessagesByDateRequest extends ListRequest
{
    public const DATE = 'date';

    public function rules(): array
    {
        return [
            self::DATE => [
                'string',
            ]
        ];
    }

    public function getDate(): ?string
    {
        return $this->query(self::DATE);
    }
}
