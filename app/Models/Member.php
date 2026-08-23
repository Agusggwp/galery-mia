<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nickname',
        'slug',
        'student_number',
        'class_name',
        'major',
        'generation',
        'photo',
        'bio',
        'instagram',
        'whatsapp',
        'is_instagram_public',
        'is_whatsapp_public',
        'privacy_agreed',
        'is_visible',
        'status',
        'invitation_id',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'is_instagram_public' => 'boolean',
        'is_whatsapp_public' => 'boolean',
        'privacy_agreed' => 'boolean',
        'is_visible' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(MemberInvitation::class, 'invitation_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
                return $this->photo;
            }
            return asset('storage/' . $this->photo);
        }

        // SVG Avatar placeholder with initials
        $initials = strtoupper(substr($this->nickname ?: $this->name, 0, 2));
        return 'data:image/svg+xml;utf8,' . rawurlencode('
            <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300" fill="#111827">
                <rect width="300" height="300" fill="#1e1b4b"/>
                <circle cx="150" cy="150" r="140" fill="#8b5cf6" stroke="#000000" stroke-width="8"/>
                <text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" fill="#ffffff" font-family="sans-serif" font-size="90" font-weight="900">' . $initials . '</text>
            </svg>
        ');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
