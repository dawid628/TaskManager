<?php

namespace App\Services;

use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    /**
     * Initialize project service with repository
     */
    public function __construct(
        private readonly ProjectRepository $projectRepository
    ) {}

    /**
     * Get all projects
     *
     * @return Collection
     */
    public function getAllProjects(): Collection
    {
        return $this->projectRepository->all();
    }

    /**
     * Get a single project by ID
     *
     * @param int $id
     * @return Project|null
     */
    public function getProject(int $id): ?Project
    {
        return $this->projectRepository->find($id);
    }

    /**
     * Create a new project
     *
     * @param array $data
     * @return Project
     */
    public function createProject(array $data): Project
    {
        return $this->projectRepository->create($data);
    }

    /**
     * Update an existing project
     *
     * @param Project $project
     * @param array $data
     * @return bool
     */
    public function updateProject(Project $project, array $data): bool
    {
        return $this->projectRepository->update($project, $data);
    }

    /**
     * Delete a project
     *
     * @param Project $project
     * @return bool
     */
    public function deleteProject(Project $project): bool
    {
        return $this->projectRepository->delete($project);
    }
}
