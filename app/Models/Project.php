<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Project extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = [
        'name',
        'slug',
        'summary',
        'description',
    ];

    protected $fillable = [
        'owner_id',
        'uuid',
        'name',
        'slug',
        'summary',
        'description',
        'status',
        'is_featured',
        'starts_at',
        'ends_at',
        'completed_at',
        'budget',
        'progress',
        'meta',
        'settings',
    ];

    protected $casts = [
        'status' => ProjectStatus::class,
        'is_featured' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'completed_at' => 'datetime',
        'budget' => 'decimal:2',
        'meta' => 'array',
        'settings' => 'array',
        'name' => 'array',
        'slug' => 'array',
        'summary' => 'array',
        'description' => 'array',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Helper method to get slug for current locale
    public function getLocalizedSlugAttribute(): string
    {
        return $this->getTranslation('slug', app()->getLocale());
    }

    // Helper method to get translated status
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label(app()->getLocale());
    }
}
