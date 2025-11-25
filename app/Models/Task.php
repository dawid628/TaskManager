<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['name', 'priority', 'project_id'];

    /**
     * Get the project that owns the task
     *
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Scope a query to only include tasks for a specific project
     *
     * @param Builder $query
     * @param int|null $projectId
     * @return Builder
     */
    public function scopeForProject(Builder $query, ?int $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }
}
