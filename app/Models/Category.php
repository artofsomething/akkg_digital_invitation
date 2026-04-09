<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;               // ✅ Add this
use Spatie\Sluggable\SlugOptions;           // ✅ Add this
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasUuids;
    use HasSlug;                            // ✅ Add this

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'is_active',
    ];

    // ✅ Define slug options
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')     // Generate from 'name' field
            ->saveSlugsTo('slug')           // Save to 'slug' field
            ->doNotGenerateSlugsOnUpdate(); // Don't change slug on update
    }

    // ✅ Route model binding using slug
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }
}