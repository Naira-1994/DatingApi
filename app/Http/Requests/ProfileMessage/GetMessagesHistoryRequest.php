<?php

namespace App\Http\Requests\ProfileMessage;

use App\Http\Requests\ListRequest;

class GetMessagesHistoryRequest extends ListRequest
{
    public const DATE = 'date';
    public const ID_TO = 'id_to';

    public function rules(): array
    {
        return [
            self::DATE => [
                'nullable',
                'string',
            ],
            self::ID_TO => [
                'integer',
                'exists:users,id',
            ],
        ];
    }

    public function getDate(): ?string
    {
        return $this->query(self::DATE);
    }

    public function getIdTo(): ?int
    {
        return $this->query(self::ID_TO);
    }
}
