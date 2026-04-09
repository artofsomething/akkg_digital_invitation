<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invitation extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'template_id',
        'title',
        'slug',
        'status',
        'event_date',
        'event_time',
        'event_location',
        'event_address',
        'latitude',
        'longitude',
        'expired_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'expired_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(InvitationSection::class)->orderBy('order');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class)->orderBy('order');
    }

    public function guestBooks(): HasMany
    {
        return $this->hasMany(GuestBook::class)->latest();
    }

    public function rsvps(): HasMany
    {
        return $this->hasMany(Rsvp::class)->latest();
    }

    public function views(): HasMany
    {
        return $this->hasMany(InvitationView::class);
    }

    // Accessors
    public function getUrlAttribute(): string
    {
        return route('invitation.show', $this->slug);
    }

    public function getTotalViewsAttribute(): int
    {
        return $this->views()->count();
    }
}