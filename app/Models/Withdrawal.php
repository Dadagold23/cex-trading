<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'user_id',
        'currency_id',
        'amount',
        'fee',
        'net_amount',
        'method',
        'destination_type',
        'destination_details',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'destination_details' => 'array',
            'reviewed_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
