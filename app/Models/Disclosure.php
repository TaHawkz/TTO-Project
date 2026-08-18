<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Disclosure extends Model
{
    protected $fillable = [
        'disclosure_id', 'title', 'abstract', 'description', 'technical_field',
        'problem_solved', 'novel_features', 'potential_applications', 'industry_sector',
        'existing_alternatives', 'funding_source', 'sponsor_info', 'project_reference',
        'status', 'submitted_by', 'assigned_to', 'reviewer_notes', 'rejection_reason', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function inventors(): HasMany
    {
        return $this->hasMany(DisclosureInventor::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(DisclosureDocument::class);
    }

    public function patent(): HasOne
    {
        return $this->hasOne(Patent::class);
    }

    public function assignment(): HasOne
    {
        return $this->hasOne(IpAssignment::class);
    }

    public function commercialization(): HasOne
    {
        return $this->hasOne(Commercialization::class);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        if ($user->canReviewDisclosures()) {
            return $query;
        }
        return $query->where('submitted_by', $user->id);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'                  => 'Draft',
            'submitted'              => 'Submitted',
            'under_review'           => 'Under Review',
            'ownership_determined'   => 'Ownership Determined',
            'patentability_assessed' => 'Patentability Assessed',
            'committee_review'       => 'Committee Review',
            'approved'               => 'Approved',
            'rejected'               => 'Rejected',
            'patent_filing'          => 'Patent Filing',
            'commercializing'        => 'Commercializing',
            default                  => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft'                  => 'bg-gray-100 text-gray-700',
            'submitted'              => 'bg-blue-100 text-blue-800',
            'under_review'           => 'bg-yellow-100 text-yellow-800',
            'ownership_determined'   => 'bg-purple-100 text-purple-800',
            'patentability_assessed' => 'bg-indigo-100 text-indigo-800',
            'committee_review'       => 'bg-orange-100 text-orange-800',
            'approved'               => 'bg-green-100 text-green-800',
            'rejected'               => 'bg-red-100 text-red-800',
            'patent_filing'          => 'bg-teal-100 text-teal-800',
            'commercializing'        => 'bg-emerald-100 text-emerald-800',
            default                  => 'bg-gray-100 text-gray-700',
        };
    }

    public static function generateDisclosureId(): string
    {
        $year = now()->year;
        $prefix = "DISC-{$year}-";

        $last = static::where('disclosure_id', 'like', "{$prefix}%")
            ->orderByDesc('disclosure_id')
            ->value('disclosure_id');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
