<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'user_id',
        'order_type',
        'currency_id',
        'base_currency_id',
        'amount',
        'rate',
        'fee',
        'subtotal',
        'total',
        'status',
        'payment_method',
        'payment_reference',
        'proof_file',
        'notes',
        'approved_by',
        'approved_at',
        'rejected_reason',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'completed_at' => 'datetime',
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

    public function baseCurrency()
    {
        return $this->belongsTo(Currency::class, 'base_currency_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(TradeStatusLog::class);
    }
}
