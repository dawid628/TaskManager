<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    /**
     * Initialize task service with repository
     */
    public function __construct(
        private readonly TaskRepository $taskRepository
    ) {}

    /**
     * Get all tasks optionally filtered by project
     *
     * @param int|null $projectId
     * @return Collection
     */
    public function getAllTasks(?int $projectId = null): Collection
    {
        return $this->taskRepository->all($projectId);
    }

    /**
     * Create a new task with auto-assigned priority
     *
     * @param array $data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        $data['priority'] = $this->taskRepository->getNextPriority();

        return $this->taskRepository->create($data);
    }

    /**
     * Update an existing task
     *
     * @param Task $task
     * @param array $data
     * @return bool
     */
    public function updateTask(Task $task, array $data): bool
    {
        return $this->taskRepository->update($task, $data);
    }

    /**
     * Delete a task and reorder remaining tasks
     *
     * @param Task $task
     * @return bool
     */
    public function deleteTask(Task $task): bool
    {
        $projectId = $task->project_id;
        $deleted = $this->taskRepository->delete($task);

        if ($deleted) {
            $this->reorderTaskPriorities($projectId);
        }

        return $deleted;
    }

    /**
     * Reorder tasks based on new priority values
     *
     * @param array $tasks
     * @return bool
     */
    public function reorderTasks(array $tasks): bool
    {
        foreach ($tasks as $taskData) {
            $this->taskRepository->updatePriority(
                $taskData['id'],
                $taskData['priority']
            );
        }

        return true;
    }

    /**
     * Reorder all task priorities sequentially after deletion
     *
     * @param int|null $projectId
     * @return void
     */
    private function reorderTaskPriorities(?int $projectId): void
    {
        $tasks = $this->taskRepository->getTasksByProject($projectId);

        foreach ($tasks as $index => $task) {
            $newPriority = $index + 1;

            if ($task->priority !== $newPriority) {
                $this->taskRepository->updatePriority($task->id, $newPriority);
            }
        }
    }
}
