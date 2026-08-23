<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'album_id',
        'google_drive_id',
        'name',
        'slug',
        'mime_type',
        'type',
        'thumbnail_url',
        'drive_url',
        'file_size',
        'captured_at',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'captured_at' => 'datetime',
        'file_size' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($media) {
            if (empty($media->slug)) {
                $media->slug = Str::slug(pathinfo($media->name, PATHINFO_FILENAME));
            }
        });
    }

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if (!$bytes) return '0 B';

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = floor(log($bytes, 1024));

        return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
    }
}
