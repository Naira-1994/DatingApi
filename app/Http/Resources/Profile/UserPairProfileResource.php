<?php

namespace App\Http\Resources\Profile;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPairProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
          'user_id' => $this->resource['user_id'],
          'pair_user_id' => $this->resource['pair_user_id'],
          'last_message' => $this->resource['last_message'] ?? null,
          'last_message_date' => $this->resource['last_message_date'] ? Carbon::createFromFormat('Y-m-d H:i:s' , $this->resource['last_message_date'])->timestamp : null,
        ];
    }
}
