<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    /**
     * Initialize controller with service
     */
    public function __construct(
        private readonly ProjectService $projectService
    ) {}

    /**
     * Display a listing of projects
     *
     * @return View
     */
    public function index(): View
    {
        $projects = $this->projectService->getAllProjects();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project
     *
     * @return View
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created project in storage
     *
     * @param StoreProjectRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $this->projectService->createProject($request->validated());

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully!');
    }

    /**
     * Get project data as JSON for editing
     *
     * @param Project $project
     * @return JsonResponse
     */
    public function show(Project $project): JsonResponse
    {
        return response()->json([
            'id' => $project->id,
            'name' => $project->name,
        ]);
    }

    /**
     * Show the form for editing the specified project
     *
     * @param Project $project
     * @return View
     */
    public function edit(Project $project): View
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified project in storage
     *
     * @param UpdateProjectRequest $request
     * @param Project $project
     * @return RedirectResponse
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->projectService->updateProject($project, $request->validated());

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified project from storage
     *
     * @param Project $project
     * @return RedirectResponse
     */
    public function destroy(Project $project): RedirectResponse
    {
        $this->projectService->deleteProject($project);

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
