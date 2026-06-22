<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TradeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'order_type' => $this->order_type,
            'amount' => (float) $this->amount,
            'rate' => (float) $this->rate,
            'fee' => (float) $this->fee,
            'subtotal' => (float) $this->subtotal,
            'total' => (float) $this->total,
            'status' => $this->status,
            'currency' => [
                'id' => $this->currency?->id,
                'code' => $this->currency?->code,
                'name' => $this->currency?->name,
            ],
            'base_currency' => [
                'id' => $this->baseCurrency?->id,
                'code' => $this->baseCurrency?->code,
                'name' => $this->baseCurrency?->name,
            ],
            'created_at' => $this->created_at?->toDateTimeString(),
            'approved_at' => $this->approved_at?->toDateTimeString(),
            'completed_at' => $this->completed_at?->toDateTimeString(),
        ];
    }
}
