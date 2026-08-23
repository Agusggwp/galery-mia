<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MemberInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'name',
        'description',
        'is_active',
        'expires_at',
        'max_submissions',
        'submission_count',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'max_submissions' => 'integer',
        'submission_count' => 'integer',
    ];

    public static function generateUniqueToken(int $length = 16): string
    {
        do {
            $token = Str::random($length);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'invitation_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return true;
        }

        if ($this->max_submissions && $this->submission_count >= $this->max_submissions) {
            return true;
        }

        return false;
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    public function getJoinUrlAttribute(): string
    {
        return route('member.join', $this->token);
    }
}
