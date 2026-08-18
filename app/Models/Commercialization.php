<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commercialization extends Model
{
    protected $fillable = [
        'patent_id', 'disclosure_id', 'title', 'type', 'status',
        'partner_name', 'partner_contact', 'partner_email',
        'description', 'notes', 'managed_by',
    ];

    public function patent(): BelongsTo
    {
        return $this->belongsTo(Patent::class);
    }

    public function disclosure(): BelongsTo
    {
        return $this->belongsTo(Disclosure::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'licensing'         => 'Licensing',
            'startup'           => 'Startup / Spin-out',
            'joint_development' => 'Joint Development',
            'direct_sale'       => 'Direct Sale',
            default             => ucfirst(str_replace('_', ' ', $this->type)),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'evaluation'          => 'Evaluation',
            'industry_engagement' => 'Industry Engagement',
            'negotiation'         => 'Negotiation',
            'agreement_executed'  => 'Agreement Executed',
            'active'              => 'Active',
            'closed'              => 'Closed',
            default               => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
