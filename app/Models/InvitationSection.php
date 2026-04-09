<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvitationSection extends Model
{
    use HasUuids;

    protected $fillable = [
        'invitation_id',
        'section_type',
        'content',
        'is_visible',
        'order',
    ];

    protected $casts = [
        'content'    => 'array',
        'is_visible' => 'boolean',
    ];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }
}
