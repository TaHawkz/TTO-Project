<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Startup extends Model
{
    protected $fillable = [
        'name', 'founders', 'faculty_advisor', 'technology_used',
        'incorporation_date', 'funding_status', 'funding_amount',
        'website', 'description', 'industry_sector', 'is_published',
    ];

    protected $casts = [
        'founders' => 'array',
        'incorporation_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->latest();
    }
}
