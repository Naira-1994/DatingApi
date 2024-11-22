<?php

namespace App\Http\Resources\Profile;

use App\Http\Resources\Media\MediaResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        $geo_position = json_decode($this->resource->geo_position, true);

        return [
            'user_id' => $this->resource->user_id,
            'name' => $this->resource->name,
            'birthday' => (int)$this->resource->birthday,
            'gender' => $this->resource->gender,
            'description' => $this->resource->description,
            'geo_position' => [
                'latitude' => $geo_position['latitude'],
                'longitude' => $geo_position['longitude'],
            ],
            'photos' => MediaResource::collection($this->resource->media),
            'interests' => json_decode($this->resource->interests, true),
            'type' => $this->resource->type,
            'last_online' => (int)$this->resource->last_online,
            'created_at' => Carbon::createFromFormat('Y-m-d H:i:s' , $this->resource->created_at_timestamp)->timestamp,
            'updated_at' => Carbon::createFromFormat('Y-m-d H:i:s' , $this->resource->updated_at_timestamp)->timestamp,
        ];
    }
}
