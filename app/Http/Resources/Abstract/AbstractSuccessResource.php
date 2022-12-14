<?php

namespace App\Http\Resources\Abstract;

use Awesome\Foundation\Traits\Resources\Resourceable;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractSuccessResource extends JsonResource
{
    use Resourceable;

    public function toArray($request = null): array
    {
        return [
            'success' => $this->bool($this->resource),
        ];
    }
}
