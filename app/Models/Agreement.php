<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agreement extends Model
{
    protected $fillable = [
        'title', 'type', 'disclosure_id', 'patent_id', 'parties',
        'signed_date', 'expiry_date', 'status', 'document_path', 'managed_by',
    ];

    protected function casts(): array
    {
        return [
            'parties'     => 'array',
            'signed_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function disclosure(): BelongsTo
    {
        return $this->belongsTo(Disclosure::class);
    }

    public function patent(): BelongsTo
    {
        return $this->belongsTo(Patent::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'assignment'         => 'Assignment',
            'nda_cda'            => 'NDA / CDA',
            'revenue_sharing'    => 'Revenue Sharing',
            'sponsored_research' => 'Sponsored Research',
            'licensing'          => 'Licensing',
            'other'              => 'Other',
            default              => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'        => 'Draft',
            'under_review' => 'Under Review',
            'signed'       => 'Signed',
            'expired'      => 'Expired',
            'terminated'   => 'Terminated',
            default        => ucfirst($this->status),
        };
    }
}
