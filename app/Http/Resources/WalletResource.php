<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'currency' => [
                'id' => $this->currency?->id,
                'code' => $this->currency?->code,
                'name' => $this->currency?->name,
                'symbol' => $this->currency?->symbol,
            ],
            'available_balance' => (float) $this->available_balance,
            'held_balance' => (float) $this->held_balance,
            'total_balance' => (float) $this->available_balance + (float) $this->held_balance,
        ];
    }
}
