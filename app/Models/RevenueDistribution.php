<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevenueDistribution extends Model
{
    protected $fillable = [
        'revenue_record_id', 'recipient_type', 'recipient_name',
        'recipient_user_id', 'percentage', 'amount', 'payment_status', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'percentage' => 'decimal:2',
            'amount'     => 'decimal:2',
            'paid_at'    => 'date',
        ];
    }

    public function revenueRecord(): BelongsTo
    {
        return $this->belongsTo(RevenueRecord::class);
    }

    public function recipientUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }
}
