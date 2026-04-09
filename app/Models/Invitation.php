<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id', 'template_id', 'title', 'slug',
        'status', 'event_date', 'event_time',
        'event_location', 'event_address',
        'latitude', 'longitude', 'expired_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'expired_at' => 'datetime',
    ];

    // ── Boot: auto slug ──────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title) . '-' . Str::random(6);
            }
        });
    }

    // ── Relationships ────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function sections()
    {
        return $this->hasMany(InvitationSection::class, 'invitation_id')
                    ->orderBy('order');
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'invitation_id')
                    ->orderBy('order');
    }

    public function guestBooks()
    {
        return $this->hasMany(GuestBook::class, 'invitation_id')
                    ->where('is_approved', true)
                    ->latest();
    }

    public function rsvps()
    {
        return $this->hasMany(Rsvp::class, 'invitation_id')
                    ->latest();
    }

    public function views()
    {
        return $this->hasMany(InvitationView::class, 'invitation_id');
    }

    // ── Helpers ──────────────────────────────────────

    /** Get a specific section by type (only visible) */
    public function getSection(string $type): ?InvitationSection
    {
        return $this->sections
            ->where('section_type', $type)
            ->where('is_visible', true)
            ->first();
    }

    /** Check if invitation is expired */
    public function isExpired(): bool
    {
        return $this->expired_at && now()->gt($this->expired_at);
    }

    /** Public URL */
    public function publicUrl(string $guestName = ''): string
    {
        $url = route('invitation.show', $this->slug);
        return $guestName ? $url . '?to=' . urlencode($guestName) : $url;
    }
}
