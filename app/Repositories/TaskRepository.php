<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    /**
     * Get all tasks optionally filtered by project
     *
     * @param int|null $projectId
     * @return Collection
     */
    public function all(?int $projectId = null): Collection
    {
        return Task::with('project')
            ->when(!is_null($projectId), function ($query) use ($projectId) {
                return $query->forProject($projectId);
            })
            ->orderBy('priority')
            ->get();
    }

    /**
     * Create a new task
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    /**
     * Update an existing task
     *
     * @param Task $task
     * @param array $data
     * @return bool
     */
    public function update(Task $task, array $data): bool
    {
        return $task->update($data);
    }

    /**
     * Delete a task
     *
     * @param Task $task
     * @return bool
     */
    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    /**
     * Get the next priority number for a project
     *
     * @return int
     */
    public function getNextPriority(): int
    {
        $maxPriority = Task::max('priority') ?? 0;

        return $maxPriority + 1;
    }

    /**
     * Update task priority
     *
     * @param int $taskId
     * @param int $priority
     * @return bool
     */
    public function updatePriority(int $taskId, int $priority): bool
    {
        return Task::where('id', $taskId)->update(['priority' => $priority]);
    }

    /**
     * Get tasks by project ordered by priority
     *
     * @param int|null $projectId
     * @return Collection
     */
    public function getTasksByProject(?int $projectId = null): Collection
    {
        return Task::when($projectId, function ($query) use ($projectId) {
            return $query->where('project_id', $projectId);
        })
            ->orderBy('priority', 'asc')
            ->get();
    }
}
