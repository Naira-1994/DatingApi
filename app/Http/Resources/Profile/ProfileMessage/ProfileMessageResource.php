<?php

namespace App\Http\Resources\Profile\ProfileMessage;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
class ProfileMessageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id_from' => $this->resource->id_from,
            'id_to' => $this->resource->id_to,
            'message_text' => $this->resource->message_text,
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s' , $this->resource->created_at_timestamp)->timestamp
        ];
    }
}
