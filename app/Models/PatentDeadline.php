<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatentDeadline extends Model
{
    protected $fillable = [
        'patent_id', 'deadline_type', 'due_date',
        'is_completed', 'completed_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date'     => 'date',
            'is_completed' => 'boolean',
            'completed_at' => 'date',
        ];
    }

    public function patent(): BelongsTo
    {
        return $this->belongsTo(Patent::class);
    }

    public function getIsOverdueAttribute(): bool
    {
        return !$this->is_completed && $this->due_date && $this->due_date->lt(now());
    }
}
