<div id="createTaskModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-6 border border-zinc-700 w-96 shadow-2xl rounded-xl bg-zinc-800">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-zinc-100">Create New Task</h3>
            <button class="close-modal text-zinc-400 hover:text-zinc-100 text-2xl font-bold transition-colors">
                &times;
            </button>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="create_task_name" class="block text-zinc-300 font-semibold mb-2">
                    Task Name *
                </label>
                <input type="text"
                       name="name"
                       id="create_task_name"
                       class="w-full bg-zinc-900 border border-zinc-700 text-zinc-100 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all placeholder-zinc-500"
                       required
                       placeholder="Enter task name">
            </div>

            <div class="mb-6">
                <label for="create_task_project_id" class="block text-zinc-300 font-semibold mb-2">
                    Project (Optional)
                </label>
                <select name="project_id"
                        id="create_task_project_id"
                        class="w-full bg-zinc-900 border border-zinc-700 text-zinc-100 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all">
                    <option value="">No Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" {{ $selectedProjectId == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 shadow-lg shadow-cyan-500/20">
                    Create Task
                </button>
                <button type="button"
                        class="close-modal flex-1 bg-zinc-700 hover:bg-zinc-600 text-zinc-100 font-semibold py-2.5 px-4 rounded-lg transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
