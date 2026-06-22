<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'amount' => (float) $this->amount,
            'fee' => (float) $this->fee,
            'net_amount' => (float) $this->net_amount,
            'method' => $this->method,
            'destination_type' => $this->destination_type,
            'destination_details' => $this->destination_details,
            'status' => $this->status,
            'currency' => [
                'id' => $this->currency?->id,
                'code' => $this->currency?->code,
                'name' => $this->currency?->name,
            ],
            'created_at' => $this->created_at?->toDateTimeString(),
            'processed_at' => $this->processed_at?->toDateTimeString(),
        ];
    }
}
