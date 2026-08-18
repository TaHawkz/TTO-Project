<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patent extends Model
{
    protected $fillable = [
        'disclosure_id', 'title', 'patent_number', 'status', 'jurisdiction',
        'filing_date', 'publication_date', 'grant_date', 'expiry_date',
        'applicant', 'attorney_firm', 'attorney_contact', 'notes', 'managed_by',
    ];

    protected function casts(): array
    {
        return [
            'filing_date'      => 'date',
            'publication_date' => 'date',
            'grant_date'       => 'date',
            'expiry_date'      => 'date',
        ];
    }

    public function disclosure(): BelongsTo
    {
        return $this->belongsTo(Disclosure::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    public function deadlines(): HasMany
    {
        return $this->hasMany(PatentDeadline::class);
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(Agreement::class);
    }

    public function commercialization(): HasOne
    {
        return $this->hasOne(Commercialization::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'       => 'Draft',
            'filed'       => 'Filed',
            'published'   => 'Published',
            'examination' => 'Under Examination',
            'granted'     => 'Granted',
            'expired'     => 'Expired',
            'abandoned'   => 'Abandoned',
            default       => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'granted'     => 'bg-green-100 text-green-800',
            'filed'       => 'bg-blue-100 text-blue-800',
            'examination' => 'bg-yellow-100 text-yellow-800',
            'published'   => 'bg-purple-100 text-purple-800',
            'expired'     => 'bg-gray-100 text-gray-600',
            'abandoned'   => 'bg-red-100 text-red-800',
            default       => 'bg-gray-100 text-gray-700',
        };
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->status === 'granted'
            && $this->expiry_date
            && $this->expiry_date->lte(now()->addDays(90));
    }
}
