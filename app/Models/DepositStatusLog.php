<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposit_id',
        'status',
        'note',
        'created_by',
        'source',
    ];

    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
