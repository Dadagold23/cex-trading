<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'amount' => (float) $this->amount,
            'method' => $this->method,
            'status' => $this->status,
            'currency' => [
                'id' => $this->currency?->id,
                'code' => $this->currency?->code,
                'name' => $this->currency?->name,
            ],
            'created_at' => $this->created_at?->toDateTimeString(),
            'reviewed_at' => $this->reviewed_at?->toDateTimeString(),
        ];
    }
}
