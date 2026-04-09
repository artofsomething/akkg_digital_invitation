<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rsvp extends Model
{
    use HasUuids;

    protected $fillable = [
        'invitation_id',
        'guest_name',
        'guest_phone',
        'attendance',
        'total_person',
        'message',
    ];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
    public function getAttendanceLabelAttribute(): string
    {
        return match($this->attendance) {
            'yes'   => '✅ Attending',
            'no'    => '❌ Not Attending',
            'maybe' => '🤔 Maybe',
            default => '-',
        };
    }
}