<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Initialize controller with services
     */
    public function __construct(
        private readonly TaskService $taskService,
        private readonly ProjectService $projectService
    ) {}

    /**
     * Display a listing of tasks
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $projects = $this->projectService->getAllProjects();
        $selectedProjectId = $request->get('project_id');
        $tasks = $this->taskService->getAllTasks($selectedProjectId);

        return view('tasks.index', compact('tasks', 'projects', 'selectedProjectId'));
    }

    /**
     * Show the form for creating a new task
     *
     * @return View
     */
    public function create(): View
    {
        $projects = $this->projectService->getAllProjects();

        return view('tasks.create', compact('projects'));
    }

    /**
     * Get task data as JSON for editing
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json([
            'id' => $task->id,
            'name' => $task->name,
            'project_id' => $task->project_id,
        ]);
    }

    /**
     * Store a newly created task in storage
     *
     * @param StoreTaskRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->taskService->createTask($request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Show the form for editing the specified task
     *
     * @param Task $task
     * @return View
     */
    public function edit(Task $task): View
    {
        $projects = $this->projectService->getAllProjects();

        return view('tasks.edit', compact('task', 'projects'));
    }

    /**
     * Update the specified task in storage
     *
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->taskService->updateTask($task, $request->validated());

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified task from storage
     *
     * @param Task $task
     * @return RedirectResponse
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->taskService->deleteTask($task);

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Reorder tasks via drag and drop
     *
     * @return JsonResponse
     */
    public function reorder(): JsonResponse
    {
        $this->taskService->reorderTasks();

        return response()->json(['success' => true]);
    }
}
