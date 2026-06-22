<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'buy_rate' => (float) $this->buy_rate,
            'sell_rate' => (float) $this->sell_rate,
            'buy_fee' => (float) $this->buy_fee,
            'sell_fee' => (float) $this->sell_fee,
            'min_amount' => (float) $this->min_amount,
            'max_amount' => $this->max_amount !== null ? (float) $this->max_amount : null,
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
        ];
    }
}
