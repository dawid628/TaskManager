@extends('layouts.app')
@section('title', 'Projects')

@section('content')
    <div class="px-4 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Projects</h1>
            <button id="openCreateProjectModal"
                    class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold py-2.5 px-5 rounded-lg transition-all duration-200 shadow-lg shadow-cyan-500/20">
                Create project
            </button>
        </div>

        @if($projects->isEmpty())
            <div class="bg-zinc-800 border border-zinc-700 rounded-xl shadow-xl p-12 text-center">
                <p class="text-zinc-400 text-xl">There are no projects yet</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <div
                        class="bg-zinc-800 border border-zinc-700 rounded-xl hover:border-zinc-600 transition-all p-6 shadow-xl hover:shadow-cyan-500/10">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-zinc-100">{{ $project->name }}</h3>
                            <span
                                class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 text-xs font-semibold px-2.5 py-1 rounded-lg">
                            {{ $project->tasks_count }} {{ Str::plural('task', $project->tasks_count) }}
                        </span>
                        </div>

                        <p class="text-zinc-400 text-sm mb-4">
                            Created: {{ $project->created_at->format('M d, Y') }}
                        </p>

                        <div class="flex gap-2">
                            <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}"
                               class="flex-1 bg-emerald-500/20 hover:bg-emerald-500/30 border border-emerald-500/30 text-emerald-400 text-center px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                View Tasks
                            </a>
                            <button data-project-id="{{ $project->id }}"
                                    class="edit-project-btn bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/30 text-amber-400 px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                Edit
                            </button>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST"
                                  class="inline delete-project-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        class="delete-project-btn bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @include('projects.modals.create')
    @include('projects.modals.edit')

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#openCreateProjectModal').click(function () {
                    $('#createProjectModal').removeClass('hidden');
                });

                $('.edit-project-btn').click(function () {
                    const projectId = $(this).data('project-id');

                    $.ajax({
                        url: '/projects/' + projectId,
                        method: 'GET',
                        dataType: 'json',
                        success: function (project) {
                            $('#edit_project_name').val(project.name);
                            $('#editProjectForm').attr('action', '/projects/' + projectId);
                            $('#editProjectModal').removeClass('hidden');
                        },
                        error: function (xhr) {
                            console.error('Error fetching project data:', xhr);
                            alert('Failed to load project data.');
                        }
                    });
                });

                $('.close-modal').click(function () {
                    $('#createProjectModal').addClass('hidden');
                    $('#editProjectModal').addClass('hidden');
                });

                $(window).click(function (event) {
                    if ($(event.target).is('#createProjectModal')) {
                        $('#createProjectModal').addClass('hidden');
                    }
                    if ($(event.target).is('#editProjectModal')) {
                        $('#editProjectModal').addClass('hidden');
                    }
                });

                $('.delete-project-btn').click(function (e) {
                    e.preventDefault();
                    const form = $(this).closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This will delete all tasks in this project!",
                        icon: 'warning',
                        background: '#18181b',
                        color: '#e4e4e7',
                        showCancelButton: true,
                        confirmButtonColor: '#06b6d4',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Delete',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
