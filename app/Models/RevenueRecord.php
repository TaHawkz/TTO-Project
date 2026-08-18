<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RevenueRecord extends Model
{
    protected $fillable = [
        'source_type', 'agreement_id', 'disclosure_id', 'patent_id',
        'gross_amount', 'deductions', 'net_amount',
        'received_date', 'currency', 'notes', 'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'received_date' => 'date',
            'gross_amount'  => 'decimal:2',
            'deductions'    => 'decimal:2',
            'net_amount'    => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $record) {
            $record->net_amount = $record->gross_amount - $record->deductions;
        });
    }

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class);
    }

    public function disclosure(): BelongsTo
    {
        return $this->belongsTo(Disclosure::class);
    }

    public function patent(): BelongsTo
    {
        return $this->belongsTo(Patent::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(RevenueDistribution::class);
    }
}
