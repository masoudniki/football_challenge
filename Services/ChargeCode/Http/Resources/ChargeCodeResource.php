<?php

namespace Services\ChargeCode\Http\Resources;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChargeCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "code"=>$this->code,
            "amount"=>$this->amount,
            "usage_limit"=>$this->usage_limit,
            "usage_count"=>$this->usage_count,
            "used_by"=>UserResource::collection($this->whenLoaded("users"))
        ];
    }
}
