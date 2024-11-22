<?php

namespace App\Services\DTO\Profile\ProfileMessage;

use App\Http\Requests\ProfileMessage\SendProfileMessageRequest;
use Spatie\LaravelData\Data;

class SendProfileMessageDto extends Data
{
    public int $idFrom;

    public int $idTo;

    public string $messageText;

    public static function fromRequest(SendProfileMessageRequest $request): self
    {
        return self::from([
           'idFrom' => $request->getIdFrom(),
           'idTo' => $request->getIdTo(),
           'messageText' => $request->getMessageText(),
        ]);
    }
}
