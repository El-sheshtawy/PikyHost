<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class Task extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = [
        'title',
        'description',
    ];

    protected $fillable = [
        'project_id',
        'uuid',
        'title',
        'description',
        'status',
        'priority',
        'progress',
        'starts_at',
        'due_at',
        'completed_at',
        'meta',
        'order_column',
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'starts_at' => 'datetime',
        'due_at' => 'datetime',
        'completed_at' => 'datetime',
        'meta' => 'array',
        'title' => 'array',
        'description' => 'array',
    ];

    protected $appends = ['status_label', 'priority_label'];

// uuid
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    // Helper method to get translated status
    public function getStatusLabelAttribute(): string
    {
        return $this->status->label(app()->getLocale());
    }

    // Helper method to get translated priority
    public function getPriorityLabelAttribute(): string
    {
        return $this->priority->label(app()->getLocale());
    }

    public function getAssigneesAttribute(): string
    {
        return $this->users->pluck('name')->join(', ') ?: 'No assignees';
    }
}
