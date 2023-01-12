<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;

class CreateUserImageResource extends JsonResource
{
    public function toArray($request = null): array
    {
        if (is_null($this->resource)) {
            return [];
        }

        return [
            'id' => $this->resource->id,
            'path' => $this->resource->getCloudPath()
        ];
    }
}
