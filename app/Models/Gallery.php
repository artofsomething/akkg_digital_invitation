<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    use HasUuids;

    protected $fillable = [
        'invitation_id',
        'image_path',
        'caption',
        'order',
    ];

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    // Get full image URL
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}