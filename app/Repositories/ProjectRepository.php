<?php

namespace App\Repositories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository
{
    /**
     * Get all projects with task count
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Project::withCount('tasks')->get();
    }

    /**
     * Find a project by ID
     *
     * @param int $id
     * @return Project|null
     */
    public function find(int $id): ?Project
    {
        return Project::find($id);
    }

    /**
     * Create a new project
     *
     * @param array $data
     * @return Project
     */
    public function create(array $data): Project
    {
        return Project::create($data);
    }

    /**
     * Update an existing project
     *
     * @param Project $project
     * @param array $data
     * @return bool
     */
    public function update(Project $project, array $data): bool
    {
        return $project->update($data);
    }

    /**
     * Delete a project
     *
     * @param Project $project
     * @return bool
     */
    public function delete(Project $project): bool
    {
        return $project->delete();
    }
}
