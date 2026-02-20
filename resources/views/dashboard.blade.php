<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 tracking-tight leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <div x-data="{ toastOpen: false, toastMessage: '', toastType: 'success' }" 
         @form-submitted.window="toastMessage = $event.detail; toastType = 'success'; toastOpen = true; setTimeout(() => toastOpen = false, 3000)">
        
        <template x-teleport="body">
            <div x-show="toastOpen" 
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed bottom-6 right-6 z-[99999] max-w-sm w-full bg-slate-800 text-white shadow-2xl rounded-xl pointer-events-auto flex items-center p-4 gap-3"
                 style="display: none;">
                <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium" x-text="toastMessage"></p>
            </div>
        </template>

        <div class="py-12 bg-slate-50 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Projects</h1>
                        <p class="text-sm text-slate-500 mt-1">Manage your day and stay productive.</p>
                    </div>

                    <form action="{{ route('projects.store') }}" method="POST" 
                          @submit="$dispatch('form-submitted', 'Creating project...')"
                          class="w-full md:w-auto flex gap-3">
                        @csrf
                        <input type="text" name="title" placeholder="New project name..." 
                               class="w-full md:w-64 border-slate-200 bg-white focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-sm transition-all duration-200" required>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-5 rounded-xl shadow-md shadow-indigo-200 transition-all duration-200 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none flex-shrink-0">
                            Create
                        </button>
                    </form>
                </div>

                @if(!$projects->isEmpty())
                <div class="mb-8 flex flex-col sm:flex-row gap-4 items-center justify-between bg-white p-3 rounded-2xl shadow-sm border border-slate-200">
                    
                    <div class="flex gap-1 bg-slate-100/50 p-1 rounded-xl w-full sm:w-auto">
                        <a href="{{ route('dashboard', ['filter' => 'all', 'sort' => request('sort', 'manual')]) }}" 
                           class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('filter', 'all') === 'all' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }}">
                           All
                        </a>
                        <a href="{{ route('dashboard', ['filter' => 'active', 'sort' => request('sort', 'manual')]) }}" 
                           class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('filter') === 'active' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }}">
                           Active
                        </a>
                        <a href="{{ route('dashboard', ['filter' => 'completed', 'sort' => request('sort', 'manual')]) }}" 
                           class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('filter') === 'completed' ? 'bg-white text-indigo-700 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }}">
                           Completed
                        </a>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto px-2">
                        <span class="text-sm font-semibold text-slate-400">Sort by:</span>
                        <div class="flex gap-1 bg-slate-100/50 p-1 rounded-xl w-full sm:w-auto">
                            <a href="{{ route('dashboard', ['filter' => request('filter', 'all'), 'sort' => 'manual']) }}" 
                               class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('sort', 'manual') === 'manual' ? 'bg-white text-slate-800 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }}">
                               Manual
                            </a>
                            <a href="{{ route('dashboard', ['filter' => request('filter', 'all'), 'sort' => 'date']) }}" 
                               class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('sort') === 'date' ? 'bg-white text-slate-800 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }}">
                               Date
                            </a>
                            <a href="{{ route('dashboard', ['filter' => request('filter', 'all'), 'sort' => 'priority']) }}" 
                               class="flex-1 text-center sm:flex-none px-4 py-1.5 rounded-lg text-sm font-medium transition-colors {{ request('sort') === 'priority' ? 'bg-white text-slate-800 shadow-sm border border-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50' }}">
                               Priority
                            </a>
                        </div>
                    </div>

                </div>
                @endif

                @if($projects->isEmpty())
                    <div class="bg-white rounded-3xl border border-slate-200 border-dashed p-12 text-center flex flex-col items-center justify-center mt-10">
                        <div class="bg-indigo-50 p-4 rounded-full mb-4">
                            <svg class="w-10 h-10 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">No projects yet</h3>
                        <p class="text-slate-500 mt-1 max-w-sm">Get started by creating your first project using the form above.</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @foreach($projects as $project)
                        
                        <div class="rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col transition-colors duration-300 relative" 
                             :class="selectedColor"
                             x-data="{ editProject: false, taskModalOpen: false, deleteProjectModalOpen: false, deletingProject: false, selectedColor: '{{ $project->color ?? 'bg-white' }}' }"
                             x-show="!deletingProject"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95">
                            
                            <div class="p-5 border-b border-slate-100/50 bg-white/40 backdrop-blur-sm flex justify-between items-center group">
                                <div x-show="!editProject" class="flex justify-between items-center w-full">
                                    <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                                        {{ $project->title }}
                                    </h3>
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <button @click="editProject = true" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-white/60 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </button>
                                        <button @click="deleteProjectModalOpen = true" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-white/60 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </div>
                                </div>

                                <form x-show="editProject" action="{{ route('projects.update', $project->id) }}" method="POST" class="flex flex-col gap-3 w-full" style="display: none;" @submit="$dispatch('form-submitted', 'Updating project...')">
                                    @csrf @method('PUT')
                                    <div class="flex gap-2 items-center w-full">
                                        <input type="text" name="title" value="{{ $project->title }}" 
                                               class="flex-1 border-slate-200 bg-white/80 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 text-sm px-3 py-1.5" required>
                                        <button type="submit" class="text-xs bg-slate-800 text-white px-3 py-1.5 rounded-lg hover:bg-slate-700 transition">Save</button>
                                        <button type="button" @click="editProject = false" class="text-xs text-slate-500 hover:text-slate-800 px-2 transition">Cancel</button>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Color:</span>
                                        <input type="hidden" name="color" x-model="selectedColor">
                                        <div class="flex gap-1.5">
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
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                    },
                                                    body: JSON.stringify({ task_ids: taskIds })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if(data.success) {
                                                        $dispatch('form-submitted', 'Task order saved.');
                                                    }
                                                });
                                            }
                                        });
                                    ">
                                    
                                    @forelse($project->tasks as $task)
                                        <li class="w-full bg-white/50 rounded-xl" data-id="{{ $task->id }}" x-data="{ editTask: false, deletingTask: false }" x-show="!deletingTask"
                                            x-transition:leave="transition ease-in duration-300"
                                            x-transition:leave-start="opacity-100 transform translate-x-0"
                                            x-transition:leave-end="opacity-0 transform translate-x-4">
                                            
                                            <div x-show="!editTask" class="flex flex-col sm:flex-row sm:justify-between sm:items-center group p-3 rounded-xl border border-transparent hover:border-slate-200 hover:bg-white transition-all duration-200 gap-2 shadow-sm">
                                                
                                                <div class="flex items-start gap-2">
                                                    <div class="drag-handle cursor-grab mt-1 text-slate-300 hover:text-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z" />
                                                        </svg>
                                                    </div>

                                                    <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" class="m-0 flex items-center shrink-0 mt-0.5" @submit="$dispatch('form-submitted', 'Task status updated.')">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="w-5 h-5 flex-shrink-0 rounded border-2 flex items-center justify-center cursor-pointer transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 
                                                            {{ $task->is_completed ? 'bg-green-500 border-green-500 text-white' : 'bg-white border-slate-300 text-transparent hover:border-green-500 hover:text-green-500' }}">
                                                            @if($task->is_completed)
                                                                <svg class="w-3.5 h-3.5 block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                            @else
                                                                <svg class="w-3.5 h-3.5 hidden group-hover:block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                            @endif
                                                        </button>
                                                    </form>

                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-medium transition-colors duration-200 {{ $task->is_completed ? 'text-slate-400 line-through' : 'text-slate-700' }}">
                                                            {{ $task->content }}
                                                        </span>
                                                        
                                                        <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                                            @if($task->priority === 'high')
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700 uppercase tracking-wider">High</span>
                                                            @elseif($task->priority === 'medium')
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 uppercase tracking-wider">Medium</span>
                                                            @else
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">Low</span>
                                                            @endif

                                                            @if($task->due_date)
                                                                @php
                                                                    $isOverdue = $task->due_date->isBefore(today()) && !$task->is_completed;
                                                                    $isToday = $task->due_date->isToday() && !$task->is_completed;
                                                                @endphp
                                                                <span class="inline-flex items-center gap-1 text-[11px] font-medium 
                                                                    {{ $task->is_completed ? 'text-slate-400' : ($isOverdue ? 'text-red-600 font-bold' : ($isToday ? 'text-amber-600 font-bold' : 'text-slate-500')) }}">
                                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                                    {{ $isToday ? 'Today' : $task->due_date->format('M j') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center self-end sm:self-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 ml-8 sm:ml-0">
                                                    <button @click="editTask = true" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-md transition-colors">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </button>
                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="m-0 flex items-center"
                                                          @submit.prevent="deletingTask = true; $dispatch('form-submitted', 'Task deleted.'); setTimeout(() => $el.submit(), 300)">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-md transition-colors">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <form x-show="editTask" action="{{ route('tasks.update', $task->id) }}" method="POST" class="w-full mt-2 p-3 bg-white rounded-xl border border-slate-200 shadow-sm" style="display: none;" @submit="$dispatch('form-submitted', 'Task updated.')">
                                                @csrf @method('PUT')
                                                
                                                <input type="text" name="content" value="{{ $task->content }}" placeholder="Task description..."
                                                       class="w-full border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 text-sm py-1.5 mb-3" required>
                                                
                                                <div class="flex flex-col sm:flex-row gap-3 items-end sm:items-center">
                                                    <div class="flex gap-2 w-full sm:w-auto flex-1">
                                                        <input type="date" name="due_date" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" 
                                                               class="w-1/2 sm:w-auto border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1.5 text-slate-600">
                                                        <select name="priority" class="w-1/2 sm:w-auto border-slate-300 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1.5 text-slate-600">
                                                            <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low Priority</option>
                                                            <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium Priority</option>
                                                            <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High Priority</option>
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="flex gap-2 w-full sm:w-auto justify-end">
                                                        <button type="button" @click="editTask = false" class="text-xs text-slate-500 hover:text-slate-800 px-3 py-1.5 transition">Cancel</button>
                                                        <button type="submit" class="text-xs bg-green-500 text-white px-4 py-1.5 rounded-lg hover:bg-green-600 font-medium transition shadow-sm">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </li>
                                    @empty
                                        <div class="py-6 flex flex-col items-center justify-center text-center">
                                            <div class="bg-white/40 p-3 rounded-full mb-2">
                                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                            </div>
                                            <p class="text-xs text-slate-500 font-medium">No tasks here yet.</p>
                                        </div>
                                    @endforelse
                                </ul>
                            </div>

                            <div class="p-4 border-t border-slate-100/50 bg-white/40 backdrop-blur-sm">
                                <button @click="taskModalOpen = true" class="w-full py-2.5 border border-dashed border-slate-300 rounded-xl text-sm font-medium text-slate-500 hover:text-indigo-600 hover:border-indigo-300 hover:bg-white/60 transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    Add New Task
                                </button>
                            </div>

                            <template x-teleport="body">
                                <div x-show="taskModalOpen" style="display: none;" class="relative z-[99999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div x-show="taskModalOpen" 
                                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

                                    <div class="fixed inset-0 z-10 overflow-y-auto">
                                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                            <div x-show="taskModalOpen" @click.away="taskModalOpen = false"
                                                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                                                
                                                <form action="{{ route('tasks.store', $project->id) }}" method="POST" @submit="$dispatch('form-submitted', 'Saving task...')">
                                                    @csrf
                                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                                            </div>
                                                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                                                <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">New Task for "{{ $project->title }}"</h3>
                                                                <p class="text-sm text-slate-500 mb-4">Add a detailed task to keep track of your progress.</p>
                                                                
                                                                <div class="mb-4">
                                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Task Description</label>
                                                                    <input type="text" name="content" placeholder="What needs to be done?" 
                                                                           class="w-full border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 shadow-sm bg-slate-50 focus:bg-white transition-colors" required autofocus>
                                                                </div>

                                                                <div class="flex gap-4 mb-2">
                                                                    <div class="w-1/2">
                                                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Due Date</label>
                                                                        <input type="date" name="due_date" 
                                                                               class="w-full border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5 shadow-sm bg-slate-50 focus:bg-white text-slate-600 transition-colors">
                                                                    </div>
                                                                    <div class="w-1/2">
                                                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Priority</label>
                                                                        <select name="priority" class="w-full border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5 shadow-sm bg-slate-50 focus:bg-white text-slate-600 transition-colors">
                                                                            <option value="low">Low</option>
                                                                            <option value="medium" selected>Medium</option>
                                                                            <option value="high">High</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200">
                                                        <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition">Save Task</button>
                                                        <button type="button" @click="taskModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-teleport="body">
                                <div x-show="deleteProjectModalOpen" style="display: none;" class="relative z-[99999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div x-show="deleteProjectModalOpen" 
                                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

                                    <div class="fixed inset-0 z-10 overflow-y-auto">
                                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                            <div x-show="deleteProjectModalOpen" @click.away="deleteProjectModalOpen = false"
                                                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-red-100">
                                                
                                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" 
                                                      @submit.prevent="deleteProjectModalOpen = false; deletingProject = true; $dispatch('form-submitted', 'Deleting project...'); setTimeout(() => $el.submit(), 300)">
                                                    @csrf @method('DELETE')
                                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                                </svg>
                                                            </div>
                                                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                                                <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title">Delete Project</h3>
                                                                <div class="mt-2">
                                                                    <p class="text-sm text-slate-500">Are you sure you want to delete the project <strong class="text-slate-700">"{{ $project->title }}"</strong>? All of its tasks will be permanently removed. This action cannot be undone.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-slate-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-200">
                                                        <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition">Delete Project</button>
                                                        <button type="button" @click="deleteProjectModalOpen = false" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition">Cancel</button>
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



