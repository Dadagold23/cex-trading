<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'user_id',
        'currency_id',
        'amount',
        'method',
        'sender_name',
        'sender_account',
        'proof_file',
        'gateway_reference',
        'transaction_hash',
        'from_wallet_address',
        'to_wallet_address',
        'network',
        'status',
        'reviewed_by',
        'reviewed_at',
        'confirmed_at',
        'rejection_reason',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'confirmed_at' => 'datetime',
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
    public function statusLogs()
    {
        return $this->hasMany(DepositStatusLog::class)->latest();
    }


    public function getExplorerUrlAttribute(): ?string
    {
        if (!$this->transaction_hash) {
            return null;
        }

        $network = strtoupper((string) $this->network);
        $currency = strtoupper((string) optional($this->currency)->code);

        return match (true) {
            $currency === 'BTC' || str_contains($network, 'BITCOIN')
                => 'https://www.blockchain.com/explorer/transactions/btc/' . $this->transaction_hash,

            $currency === 'ETH' || str_contains($network, 'ERC20') || str_contains($network, 'ETH')
                => 'https://etherscan.io/tx/' . $this->transaction_hash,

            $currency === 'USDT' && str_contains($network, 'TRC20')
                => 'https://tronscan.org/#/transaction/' . $this->transaction_hash,

            $currency === 'USDT' && str_contains($network, 'ERC20')
                => 'https://etherscan.io/tx/' . $this->transaction_hash,

            default => null,
        };
    }
}
