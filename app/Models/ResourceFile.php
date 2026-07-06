<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
        'youtube_url',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    public function getUrlAttribute(): string
    {
        if ($this->youtube_url) {
            return $this->youtube_url;
        }
        return asset('storage/' . $this->path);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
