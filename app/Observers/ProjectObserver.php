<?php

namespace App\Observers;

use App\Services\TaskService;

class ProjectObserver
{
    public function __construct(
        private readonly TaskService $taskService
    ) {}

    /**
     * Handle the Project "deleted" event.
     *
     * @return void
     */
    public function deleted(): void
    {
        $this->taskService->reorderTasks();
    }
}
