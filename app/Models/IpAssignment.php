<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpAssignment extends Model
{
    protected $fillable = [
        'disclosure_id', 'outcome', 'determination_date', 'determined_by', 'notes',
    ];

    protected function casts(): array
    {
        return ['determination_date' => 'date'];
    }

    public function disclosure(): BelongsTo
    {
        return $this->belongsTo(Disclosure::class);
    }

    public function determinedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'determined_by');
    }

    public function getOutcomeLabelAttribute(): string
    {
        return match ($this->outcome) {
            'university'          => 'University',
            'inventor'            => 'Inventor',
            'joint'               => 'Joint (University & Inventor)',
            'sponsored_research'  => 'Sponsored Research',
            default               => ucfirst($this->outcome),
        };
    }
}
