<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technology extends Model
{
    protected $fillable = [
        'title', 'description', 'industry_sector', 'development_stage',
        'benefits', 'licensing_available', 'contact_email', 'is_published', 'sort_order',
    ];

    protected $casts = [
        'benefits' => 'array',
        'licensing_available' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function getStageLabelAttribute(): string
    {
        return match ($this->development_stage) {
            'early_stage' => 'Early Stage',
            'filed'       => 'Patent Filed',
            'granted'     => 'Patent Granted',
            'licensed'    => 'Licensed',
            default       => ucfirst($this->development_stage),
        };
    }

    public function getStageBadgeClassAttribute(): string
    {
        return match ($this->development_stage) {
            'filed'   => 'filed',
            'granted' => 'granted',
            'licensed'=> 'licensed',
            default   => '',
        };
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('sort_order');
    }
}
