@extends('layouts.app')
@section('title', 'Tasks')

@section('content')
    <div class="px-4 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-white">Tasks</h1>
            <button id="openCreateTaskModal"
                    class="bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold py-2.5 px-5 rounded-lg transition-all duration-200 shadow-lg shadow-cyan-500/20">
                Create task
            </button>
        </div>

        <div class="mb-6 bg-zinc-800 border border-zinc-700 p-4 rounded-xl shadow-xl">
            <form method="GET" action="{{ route('tasks.index') }}" class="flex items-center gap-4">
                <label for="project_id" class="font-semibold text-zinc-300">Project:</label>
                <select name="project_id" id="project_id"
                        class="bg-zinc-900 border border-zinc-700 text-zinc-100 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"
                        onchange="this.form.submit()">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}"
                            {{ $selectedProjectId == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
                @if($selectedProjectId)
                    <a href="{{ route('tasks.index') }}"
                       class="text-cyan-400 hover:text-cyan-300 font-medium transition-colors">
                        Clear Filter
                    </a>
                @endif
            </form>
        </div>

        @if($tasks->isEmpty())
            <div class="bg-zinc-800 border border-zinc-700 rounded-xl shadow-xl p-12 text-center">
                <p class="text-zinc-400 text-xl">There are no tasks for this project</p>
            </div>
        @else
            <div class="bg-zinc-800 border border-zinc-700 rounded-xl shadow-xl overflow-hidden">
                <ul id="task-list" class="divide-y divide-zinc-700">
                    @foreach($tasks as $task)
                        <li data-id="{{ $task->id }}"
                            data-priority="{{ $task->priority }}"
                            class="task-item p-4 hover:bg-zinc-750 cursor-move flex items-center justify-between group transition-colors">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="flex flex-col items-center">
                                    <svg class="w-6 h-6 text-zinc-500 group-hover:text-cyan-400 transition-colors"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 8h16M4 16h16"></path>
                                    </svg>
                                    <span
                                        class="text-xs text-zinc-400 font-semibold priority-number">#{{ $task->priority }}</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-zinc-100">{{ $task->name }}</h3>
                                    <div class="flex items-center gap-4 mt-1 text-sm text-zinc-400">
                                        @if($task->project)
                                            <span
                                                class="bg-blue-500/20 text-blue-400 border border-blue-500/30 px-2.5 py-1 rounded-lg font-medium">
                                            📁 {{ $task->project->name }}
                                        </span>
                                        @endif
                                        <span>Created: {{ $task->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button data-task-id="{{ $task->id }}"
                                        class="edit-task-btn bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/30 text-amber-400 px-4 py-2 rounded-lg font-medium transition-all">
                                    Edit
                                </button>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                      class="inline delete-task-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="delete-task-btn bg-red-500/20 hover:bg-red-500/30 border border-red-500/30 text-red-400 px-4 py-2 rounded-lg font-medium transition-all">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    @include('tasks.modals.create')
    @include('tasks.modals.edit')

    @push('scripts')
        <script>
            $(document).ready(function () {
                if ($('#task-list').length > 0) {
                    $('#task-list').sortable({
                        items: '.task-item',
                        cursor: 'move',
                        opacity: 0.8,
                        placeholder: 'sortable-placeholder',
                        forcePlaceholderSize: true,
                        tolerance: 'pointer',
                        update: function (event, ui) {
                            const tasks = [];

                            $('#task-list .task-item').each(function (index) {
                                tasks.push({
                                    id: parseInt($(this).data('id')),
                                    priority: index + 1
                                });
                            });

                            $.ajax({
                                url: '{{ route("tasks.reorder") }}',
                                method: 'POST',
                                data: {
                                    tasks: tasks,
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function (response) {
                                    if (response.success) {
                                        $('#task-list .task-item').each(function (index) {
                                            $(this).find('.priority-number').text('#' + (index + 1));
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    console.error('Error updating task order:', xhr);
                                    alert('Failed to update task order. Please refresh the page.');
                                }
                            });
                        }
                    });
                }

                $('#openCreateTaskModal').click(function () {
                    $('#createTaskModal').removeClass('hidden');
                });

                $('.edit-task-btn').click(function () {
                    const taskId = $(this).data('task-id');

                    $.ajax({
                        url: '/tasks/' + taskId,
                        method: 'GET',
                        dataType: 'json',
                        success: function (task) {
                            $('#edit_task_name').val(task.name);
                            $('#edit_task_project_id').val(task.project_id);
                            $('#editTaskForm').attr('action', '/tasks/' + taskId);
                            $('#editTaskModal').removeClass('hidden');
                        },
                        error: function (xhr) {
                            console.error('Error fetching task data:', xhr);
                            alert('Failed to load task data.');
                        }
                    });
                });

                $('.close-modal').click(function () {
                    $('#createTaskModal').addClass('hidden');
                    $('#editTaskModal').addClass('hidden');
                });

                $(window).click(function (event) {
                    if ($(event.target).is('#createTaskModal')) {
                        $('#createTaskModal').addClass('hidden');
                    }
                    if ($(event.target).is('#editTaskModal')) {
                        $('#editTaskModal').addClass('hidden');
                    }
                });

                $('.delete-task-btn').click(function (e) {
                    e.preventDefault();
                    const form = $(this).closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
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
