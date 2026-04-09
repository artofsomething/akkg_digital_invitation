<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasUuids;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'thumbnail',
        'preview_url',
        'color_scheme',
        'font_family',
        'is_active',
        'is_premium',
        'used_count',
    ];

    protected $casts = [
        'color_scheme' => 'array',
        'is_active'    => 'boolean',
        'is_premium'   => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }
}