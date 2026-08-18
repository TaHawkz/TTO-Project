<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisclosureDocument extends Model
{
    protected $fillable = [
        'disclosure_id', 'uploaded_by', 'filename', 'original_name',
        'mime_type', 'file_size', 'path', 'document_type',
    ];

    public function disclosure(): BelongsTo
    {
        return $this->belongsTo(Disclosure::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('portal.documents.download', $this);
    }
}
