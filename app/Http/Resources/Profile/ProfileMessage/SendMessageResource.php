<?php

namespace App\Http\Resources\Profile\ProfileMessage;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SendMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'timestamp' => Carbon::createFromFormat('Y-m-d H:i:s' , $this->resource->created_at_timestamp)->timestamp,
            'message' => $this->resource->message_text,
        ];
    }
}
