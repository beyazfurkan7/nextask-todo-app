<x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <div x-data="{ 
            toastOpen: false, 
            toastMessage: '', 
            deletedTaskId: null,
            timeout: null,
            
            // YENİ: Dark Mode State & Toggle Function
            isDark: document.documentElement.classList.contains('dark'),
            toggleTheme() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                }
            },

            restoreTask() {
                if(!this.deletedTaskId) return;
                fetch('/tasks/' + this.deletedTaskId + '/restore', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        $dispatch('task-restored', { taskId: this.deletedTaskId });
                        this.toastOpen = false;
                        this.deletedTaskId = null;
                        setTimeout(() => { $dispatch('form-submitted', 'Task restored successfully!'); }, 300);
                        setTimeout(() => { window.location.reload(); }, 1000);
                    }
                });
            }
         }" 
         @form-submitted.window="
            clearTimeout(timeout);
            toastMessage = $event.detail; 
            deletedTaskId = null;
            toastOpen = true; 
            timeout = setTimeout(() => toastOpen = false, 3000);
         "
         @task-deleted.window="
            clearTimeout(timeout);
            toastMessage = 'Task moved to trash.';
            deletedTaskId = $event.detail.taskId;
            toastOpen = true;
            timeout = setTimeout(() => { toastOpen = false; deletedTaskId = null; }, 4000);
         ">
        
        <template x-teleport="body">
            <div x-show="toastOpen" 
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed bottom-6 right-6 z-[99999] max-w-sm w-full bg-slate-800 dark:bg-slate-700 text-white shadow-2xl rounded-xl pointer-events-auto flex items-center p-4 gap-3 border border-slate-700 dark:border-slate-600"
                 style="display: none;">
                <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-sm font-medium flex-1" x-text="toastMessage"></p>
                <button x-show="deletedTaskId" @click="restoreTask()" class="text-xs font-bold text-indigo-300 hover:text-white transition-colors uppercase tracking-wider px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 focus:ring-2 focus:ring-indigo-400 focus:outline-none">Undo</button>
            </div>
        </template>

        <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-300">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div>
                        <div class="flex items-center gap-4">
                            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight transition-colors duration-300">Projects</h1>
                            
                            <button @click="toggleTheme()" class="p-2 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors focus:outline-none">
                                <svg x-show="isDark" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                <svg x-show="!isDark" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                            </button>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 transition-colors duration-300">Manage your day and stay productive.</p>
                    </div>

                    <form action="{{ route('projects.store') }}" method="POST" @submit="$dispatch('form-submitted', 'Creating project...')" class="w-full md:w-auto flex gap-3">
                        @csrf
                        <input type="text" name="title" placeholder="New project name..." class="w-full md:w-64 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm transition-all duration-300" required>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-5 rounded-xl shadow-md shadow-indigo-200 dark:shadow-none transition-all duration-200 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none flex-shrink-0">Create</button>
                    </form>
                </div>

                @if(!$projects->isEmpty())
                <div class="mb-8 flex flex-col sm:flex-row gap-4 items-center justify-between bg-white dark:bg-slate-800 p-3 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 transition-colors duration-300">
                    <div class="flex gap-1 bg-slate-100/50 dark:bg-slate-900/50 p-1 rounded-xl w-full sm:w-auto">
                        <a href="{{ route('dashboard', ['filter' => 'all', 'sort' => request('sort', 'manual')]) }}" class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('filter', 'all') === 'all' ? 'bg-white dark:bg-slate-700 text-indigo-700 dark:text-indigo-400 shadow-sm border border-slate-200/50 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-700/50' }}">All</a>
                        <a href="{{ route('dashboard', ['filter' => 'active', 'sort' => request('sort', 'manual')]) }}" class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('filter') === 'active' ? 'bg-white dark:bg-slate-700 text-indigo-700 dark:text-indigo-400 shadow-sm border border-slate-200/50 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-700/50' }}">Active</a>
                        <a href="{{ route('dashboard', ['filter' => 'completed', 'sort' => request('sort', 'manual')]) }}" class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('filter') === 'completed' ? 'bg-white dark:bg-slate-700 text-indigo-700 dark:text-indigo-400 shadow-sm border border-slate-200/50 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-700/50' }}">Completed</a>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto px-2">
                        <span class="text-sm font-semibold text-slate-400 dark:text-slate-500">Sort by:</span>
                        <div class="flex gap-1 bg-slate-100/50 dark:bg-slate-900/50 p-1 rounded-xl w-full sm:w-auto">
                            <a href="{{ route('dashboard', ['filter' => request('filter', 'all'), 'sort' => 'manual']) }}" class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('sort', 'manual') === 'manual' ? 'bg-white dark:bg-slate-700 text-slate-800 dark:text-white shadow-sm border border-slate-200/50 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-700/50' }}">Manual</a>
                            <a href="{{ route('dashboard', ['filter' => request('filter', 'all'), 'sort' => 'date']) }}" class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('sort') === 'date' ? 'bg-white dark:bg-slate-700 text-slate-800 dark:text-white shadow-sm border border-slate-200/50 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-700/50' }}">Date</a>
                            <a href="{{ route('dashboard', ['filter' => request('filter', 'all'), 'sort' => 'priority']) }}" class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('sort') === 'priority' ? 'bg-white dark:bg-slate-700 text-slate-800 dark:text-white shadow-sm border border-slate-200/50 dark:border-slate-600' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-200/50 dark:hover:bg-slate-700/50' }}">Priority</a>
                        </div>
                    </div>
                </div>
                @endif

                @if($projects->isEmpty())
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 border-dashed p-12 text-center flex flex-col items-center justify-center mt-10 transition-colors duration-300">
                        <div class="bg-indigo-50 dark:bg-indigo-900/30 p-4 rounded-full mb-4"><svg class="w-10 h-10 text-indigo-500 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg></div>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">No projects yet</h3>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($projects as $project)
                        <div class="rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col transition-colors duration-300 relative" 
                             :class="isDark ? 'bg-slate-800' : selectedColor"
                             x-data="{ editProject: false, taskModalOpen: false, deleteProjectModalOpen: false, deletingProject: false, selectedColor: '{{ $project->color ?? 'bg-white' }}' }"
                             x-show="!deletingProject"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95">
                            
                            <div class="p-5 border-b border-slate-100/50 dark:border-slate-700/50 bg-white/40 dark:bg-slate-800/40 backdrop-blur-sm flex flex-col relative group transition-colors duration-300">
                                <div x-show="!editProject" class="flex justify-between items-start w-full">
                                    <div class="flex flex-col">
                                        <h3 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">{{ $project->title }}</h3>
                                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-1">
                                            {{ $project->completed_tasks_count }} / {{ $project->tasks_count }} tasks completed
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <button @click="editProject = true" class="p-1.5 text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-white/60 dark:hover:bg-slate-700/50 rounded-lg transition-colors" title="Edit"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg></button>
                                        <button @click="deleteProjectModalOpen = true" class="p-1.5 text-slate-400 dark:text-slate-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-white/60 dark:hover:bg-slate-700/50 rounded-lg transition-colors" title="Delete"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </div>
                                </div>
                                
                                <div x-show="!editProject" class="mt-4 w-full">
                                    @php $percentage = $project->tasks_count > 0 ? round(($project->completed_tasks_count / $project->tasks_count) * 100) : 0; @endphp
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-2 bg-slate-200/70 dark:bg-slate-700/70 rounded-full overflow-hidden">
                                            <div class="h-full {{ $percentage == 100 ? 'bg-green-500 dark:bg-green-400' : 'bg-indigo-500 dark:bg-indigo-400' }} transition-all duration-700 ease-out" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-xs font-extrabold {{ $percentage == 100 ? 'text-green-600 dark:text-green-400' : 'text-indigo-600 dark:text-indigo-400' }} w-8 text-right">{{ $percentage }}%</span>
                                    </div>
                                </div>

                                <form x-show="editProject" action="{{ route('projects.update', $project->id) }}" method="POST" class="flex flex-col gap-3 w-full" style="display: none;">
                                    @csrf @method('PUT')
                                    <div class="flex gap-2 items-center w-full">
                                        <input type="text" name="title" value="{{ $project->title }}" class="flex-1 border-slate-200 dark:border-slate-600 bg-white/80 dark:bg-slate-700 dark:text-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-1.5" required>
                                        <button type="submit" class="text-xs bg-slate-800 dark:bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-slate-700 dark:hover:bg-indigo-500 transition">Save</button>
                                        <button type="button" @click="editProject = false" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 px-2 transition">Cancel</button>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Color:</span>
                                        <input type="hidden" name="color" x-model="selectedColor">
                                        <div class="flex gap-1.5" :class="isDark ? 'opacity-50 pointer-events-none' : ''">
                                            <button type="button" @click="selectedColor = 'bg-white'" :class="selectedColor === 'bg-white' ? 'ring-2 ring-offset-1 ring-slate-400 scale-110' : ''" class="w-5 h-5 rounded-full bg-white border border-slate-300 shadow-sm transition-all hover:scale-110"></button>
                                            <button type="button" @click="selectedColor = 'bg-red-50'" :class="selectedColor === 'bg-red-50' ? 'ring-2 ring-offset-1 ring-red-400 scale-110' : ''" class="w-5 h-5 rounded-full bg-red-50 border border-red-200 shadow-sm transition-all hover:scale-110"></button>
                                            <button type="button" @click="selectedColor = 'bg-orange-50'" :class="selectedColor === 'bg-orange-50' ? 'ring-2 ring-offset-1 ring-orange-400 scale-110' : ''" class="w-5 h-5 rounded-full bg-orange-50 border border-orange-200 shadow-sm transition-all hover:scale-110"></button>
                                            <button type="button" @click="selectedColor = 'bg-yellow-50'" :class="selectedColor === 'bg-yellow-50' ? 'ring-2 ring-offset-1 ring-yellow-400 scale-110' : ''" class="w-5 h-5 rounded-full bg-yellow-50 border border-yellow-200 shadow-sm transition-all hover:scale-110"></button>
                                            <button type="button" @click="selectedColor = 'bg-green-50'" :class="selectedColor === 'bg-green-50' ? 'ring-2 ring-offset-1 ring-green-400 scale-110' : ''" class="w-5 h-5 rounded-full bg-green-50 border border-green-200 shadow-sm transition-all hover:scale-110"></button>
                                            <button type="button" @click="selectedColor = 'bg-blue-50'" :class="selectedColor === 'bg-blue-50' ? 'ring-2 ring-offset-1 ring-blue-400 scale-110' : ''" class="w-5 h-5 rounded-full bg-blue-50 border border-blue-200 shadow-sm transition-all hover:scale-110"></button>
                                            <button type="button" @click="selectedColor = 'bg-purple-50'" :class="selectedColor === 'bg-purple-50' ? 'ring-2 ring-offset-1 ring-purple-400 scale-110' : ''" class="w-5 h-5 rounded-full bg-purple-50 border border-purple-200 shadow-sm transition-all hover:scale-110"></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="p-4 flex-1">
                                <ul class="space-y-2"
                                    x-init="
                                        Sortable.create($el, {
                                            animation: 150,
                                            handle: '.drag-handle',
                                            ghostClass: 'opacity-40',
                                            onEnd: function(evt) {
                                                let itemEls = Array.from($el.children);
                                                let taskIds = itemEls.map(el => el.getAttribute('data-id')).filter(id => id);
                                                fetch('{{ route('tasks.reorder', $project->id) }}', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                    body: JSON.stringify({ task_ids: taskIds })
                                                }).then(res => res.json()).then(data => { if(data.success) $dispatch('form-submitted', 'Task order saved.'); });
                                            }
                                        });
                                    ">
                                    @forelse($project->tasks as $task)
                                        <li class="w-full bg-white/50 dark:bg-slate-700/30 rounded-xl" data-id="{{ $task->id }}" 
                                            x-data="{ 
                                                editTask: false, 
                                                deletingTask: false,
                                                deleteTask() {
                                                    this.deletingTask = true;
                                                    fetch('{{ route('tasks.destroy', $task->id) }}', {
                                                        method: 'POST',
                                                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
                                                        body: JSON.stringify({ _method: 'DELETE' })
                                                    }).then(res => res.json()).then(data => {
                                                        if(data.success) {
                                                            $dispatch('task-deleted', { taskId: {{ $task->id }} });
                                                            setTimeout(() => { window.location.reload(); }, 1500);
                                                        }
                                                    });
                                                }
                                            }" 
                                            x-show="!deletingTask"
                                            @task-restored.window="if ($event.detail.taskId === {{ $task->id }}) deletingTask = false"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 transform scale-95"
                                            x-transition:enter-end="opacity-100 transform scale-100"
                                            x-transition:leave="transition ease-in duration-300"
                                            x-transition:leave-start="opacity-100 transform scale-100 translate-x-0"
                                            x-transition:leave-end="opacity-0 transform scale-95 translate-x-4">
                                            
                                            <div x-show="!editTask" class="flex flex-col sm:flex-row sm:justify-between sm:items-center group p-3 rounded-xl border border-transparent hover:border-slate-200 dark:hover:border-slate-600 hover:bg-white dark:hover:bg-slate-700/80 transition-all duration-200 gap-2 shadow-sm">
                                                <div class="flex items-start gap-2">
                                                    <div class="drag-handle cursor-grab mt-1 text-slate-300 dark:text-slate-500 hover:text-indigo-500 dark:hover:text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z" /></svg>
                                                    </div>
                                                    <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="m-0 flex items-center shrink-0 mt-0.5">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="w-5 h-5 flex-shrink-0 rounded border-2 flex items-center justify-center cursor-pointer transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 {{ $task->is_completed ? 'bg-green-500 border-green-500 text-white dark:border-green-400 dark:bg-green-500' : 'bg-white dark:bg-slate-800 border-slate-300 dark:border-slate-500 text-transparent hover:border-green-500 dark:hover:border-green-400 hover:text-green-500 dark:hover:text-green-400' }}">
                                                            @if($task->is_completed)<svg class="w-3.5 h-3.5 block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                            @else<svg class="w-3.5 h-3.5 hidden group-hover:block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>@endif
                                                        </button>
                                                    </form>
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-medium transition-colors duration-200 {{ $task->is_completed ? 'text-slate-400 dark:text-slate-500 line-through' : 'text-slate-700 dark:text-slate-200' }}">{{ $task->content }}</span>
                                                        <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                                            @if($task->priority === 'high')<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-400 uppercase tracking-wider">High</span>
                                                            @elseif($task->priority === 'medium')<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 uppercase tracking-wider">Medium</span>
                                                            @else<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-600/50 text-slate-600 dark:text-slate-400 uppercase tracking-wider">Low</span>@endif

                                                            @if($task->due_date)
                                                                @php
                                                                    $isOverdue = $task->due_date->isBefore(today()) && !$task->is_completed;
                                                                    $isToday = $task->due_date->isToday() && !$task->is_completed;
                                                                @endphp
                                                                <span class="inline-flex items-center gap-1 text-[11px] font-medium {{ $task->is_completed ? 'text-slate-400 dark:text-slate-500' : ($isOverdue ? 'text-red-600 dark:text-red-400 font-bold' : ($isToday ? 'text-amber-600 dark:text-amber-400 font-bold' : 'text-slate-500 dark:text-slate-400')) }}">
                                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                                    {{ $isToday ? 'Today' : $task->due_date->format('M j') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center self-end sm:self-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 ml-8 sm:ml-0">
                                                    <button @click="editTask = true" class="p-1.5 text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-600 rounded-md transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></button>
                                                    <form @submit.prevent="deleteTask()" class="m-0 flex items-center">
                                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md transition-colors"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                                    </form>
                                                </div>
                                            </div>

                                            <form x-show="editTask" action="{{ route('tasks.update', $task->id) }}" method="POST" class="w-full mt-2 p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm" style="display: none;">
                                                @csrf @method('PUT')
                                                <input type="text" name="content" value="{{ $task->content }}" class="w-full border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5 mb-3" required>
                                                <div class="flex flex-col sm:flex-row gap-3 items-end sm:items-center">
                                                    <div class="flex gap-2 w-full sm:w-auto flex-1">
                                                        <input type="date" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" class="w-1/2 sm:w-auto border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1.5">
                                                        <select name="priority" class="w-1/2 sm:w-auto border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 dark:text-white rounded-lg focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1.5">
                                                            <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low Priority</option>
                                                            <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium Priority</option>
                                                            <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High Priority</option>
                                                        </select>
                                                    </div>
                                                    <div class="flex gap-2 w-full sm:w-auto justify-end">
                                                        <button type="button" @click="editTask = false" class="text-xs text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 px-3 py-1.5 transition">Cancel</button>
                                                        <button type="submit" class="text-xs bg-green-500 text-white px-4 py-1.5 rounded-lg hover:bg-green-600 font-medium transition shadow-sm">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </li>
                                    @empty
                                        <div class="py-6 flex flex-col items-center justify-center text-center">
                                            <div class="bg-white/40 dark:bg-slate-800/40 p-3 rounded-full mb-2"><svg class="w-5 h-5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg></div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">No tasks here yet.</p>
                                        </div>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="p-4 border-t border-slate-100/50 dark:border-slate-700/50 bg-white/40 dark:bg-slate-800/40 backdrop-blur-sm transition-colors duration-300">
                                <button @click="taskModalOpen = true" class="w-full py-2.5 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-300 dark:hover:border-indigo-500 hover:bg-white/60 dark:hover:bg-slate-700/50 transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg> Add New Task
                                </button>
                            </div>

                            <template x-teleport="body">
                                <div x-show="taskModalOpen" style="display: none;" class="relative z-[99999]">
                                    <div x-show="taskModalOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm transition-opacity"></div>
                                    <div class="fixed inset-0 z-10 overflow-y-auto">
                                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                            <div x-show="taskModalOpen" @click.away="taskModalOpen = false" class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-slate-700">
                                                <form action="{{ route('tasks.store', $project->id) }}" method="POST">
                                                    @csrf
                                                    <div class="bg-white dark:bg-slate-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4 transition-colors duration-300">
                                                        <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white" id="modal-title">New Task</h3>
                                                        <div class="mt-4"><input type="text" name="content" placeholder="What needs to be done?" class="w-full border-slate-200 dark:border-slate-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 shadow-sm bg-slate-50 dark:bg-slate-700 dark:text-white" required autofocus></div>
                                                        <div class="flex gap-4 mb-2 mt-4">
                                                            <div class="w-1/2"><label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Due Date</label><input type="date" name="due_date" class="w-full border-slate-200 dark:border-slate-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5 shadow-sm bg-slate-50 dark:bg-slate-700 dark:text-white text-slate-600"></div>
                                                            <div class="w-1/2"><label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Priority</label><select name="priority" class="w-full border-slate-200 dark:border-slate-600 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5 shadow-sm bg-slate-50 dark:bg-slate-700 dark:text-white text-slate-600"><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option></select></div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-slate-50 dark:bg-slate-900/50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 dark:border-slate-700 transition-colors duration-300">
                                                        <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition">Save Task</button>
                                                        <button type="button" @click="taskModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 sm:mt-0 sm:w-auto transition">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-teleport="body">
                                <div x-show="deleteProjectModalOpen" style="display: none;" class="relative z-[99999]">
                                    <div x-show="deleteProjectModalOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-slate-900/80 backdrop-blur-sm transition-opacity"></div>
                                    <div class="fixed inset-0 z-10 overflow-y-auto">
                                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                            <div x-show="deleteProjectModalOpen" @click.away="deleteProjectModalOpen = false" class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-red-100 dark:border-red-900/50">
                                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <div class="bg-white dark:bg-slate-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4 transition-colors duration-300">
                                                        <h3 class="text-lg font-bold leading-6 text-slate-900 dark:text-white">Delete Project</h3>
                                                        <div class="mt-2"><p class="text-sm text-slate-500 dark:text-slate-400">Are you sure you want to delete the project?</p></div>
                                                    </div>
                                                    <div class="bg-slate-50 dark:bg-slate-900/50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200 dark:border-slate-700 transition-colors duration-300">
                                                        <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition">Delete Project</button>
                                                        <button type="button" @click="deleteProjectModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-slate-800 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 sm:mt-0 sm:w-auto transition">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>








