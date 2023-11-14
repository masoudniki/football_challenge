<?php

namespace Services\Wallet\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "amount"=>$this->amount,
            "type"=>$this->type->name,
            "created_at"=>$this->created_at,
            "information"=>TransactionInformationResource::collection($this->whenLoaded("information"))
        ];
    }
}
