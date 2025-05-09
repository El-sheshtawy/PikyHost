<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Project extends Model implements HasMedia
{
    use InteractsWithMedia;

//    public $translatable = [
//        'name',
//        'slug',
//        'summary',
//        'description',
//    ];

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('feature_project_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('second_feature_image')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('project_documents')
            ->acceptsMimeTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]);

        $this->addMediaCollection('project_gallery')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'video/mp4']);
    }

    public function getFeatureProjectImageUrl(): ?string
    {
        return $this->getFirstMediaUrl('feature_project_image') ?: null;
    }
    public function getSecondFeatureImageUrl(): ?string
    {
        return $this->getFirstMediaUrl('second_feature_image') ?: null;
    }

    public function getProjectDocumentsUrls(): array
    {
        return $this->getMedia('project_documents')
            ->map(fn($media) => $media->getUrl())
            ->toArray();
    }

    public function getProjectGalleryUrls(string $conversion = null): array
    {
        return $this->getMedia('project_gallery')
            ->map(fn($media) => $conversion && $media->hasGeneratedConversion($conversion)
                ? $media->getUrl($conversion)
                : $media->getUrl())
            ->toArray();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
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

    public function getFileTypeName($mimeType)
    {
        $types = [
            'application/pdf' => 'PDF Document',
            'image/jpeg' => 'JPEG Image',
            'image/png' => 'PNG Image',
            'image/gif' => 'GIF Image',
            'image/webp' => 'WebP Image',
            'application/vnd.ms-excel' => 'Excel Spreadsheet',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Excel Spreadsheet',
            'application/msword' => 'Word Document',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Word Document',
            'text/plain' => 'Text File',
            'text/csv' => 'CSV File',
        ];

        return $types[$mimeType] ?? 'File';
    }
}
