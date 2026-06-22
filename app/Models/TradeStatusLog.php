<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'trade_order_id',
        'status',
        'note',
        'created_by',
    ];

    public function tradeOrder()
    {
        return $this->belongsTo(TradeOrder::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
