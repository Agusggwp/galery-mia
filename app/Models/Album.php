<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'google_drive_id',
        'name',
        'slug',
        'description',
        'cover_media_id',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->name);
            }
        });
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    public function visibleMedia(): HasMany
    {
        return $this->hasMany(Media::class)->where('is_visible', true);
    }

    public function coverMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'cover_media_id');
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->coverMedia && $this->coverMedia->thumbnail_url) {
            return $this->coverMedia->thumbnail_url;
        }

        $firstMedia = $this->visibleMedia()->first();
        if ($firstMedia && $firstMedia->thumbnail_url) {
            return $firstMedia->thumbnail_url;
        }

        return 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80';
    }
}
