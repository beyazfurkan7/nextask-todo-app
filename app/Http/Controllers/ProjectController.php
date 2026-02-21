<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request) {
        $filter = $request->get('filter', 'all'); 
        $sort = $request->get('sort', 'manual'); 

        $projects = Project::where('user_id', Auth::id())
            ->withCount('tasks') 
            ->withCount(['tasks as completed_tasks_count' => function ($query) {
                $query->where('is_completed', true); 
            }])
            ->with(['tasks' => function ($query) use ($filter, $sort) {
                if ($filter === 'active') $query->where('is_completed', false);
                elseif ($filter === 'completed') $query->where('is_completed', true);

                if ($sort === 'priority') {
                    $query->orderByRaw("CASE WHEN priority = 'high' THEN 1 WHEN priority = 'medium' THEN 2 WHEN priority = 'low' THEN 3 ELSE 4 END ASC");
                } elseif ($sort === 'date') {
                    $query->orderByRaw("due_date IS NULL ASC, due_date ASC");
                } else {
                    $query->orderBy('position', 'asc')->orderBy('id', 'asc');
                }
            }])->get();

        return view('dashboard', compact('projects', 'filter', 'sort'));
    }

    public function store(Request $request) {
        $request->validate(['title' => 'required|string|max:255']);
        Project::create(['user_id' => Auth::id(), 'title' => $request->title]);
        return back();
    }

    public function storeTask(Request $request, Project $project) {
        if($project->user_id !== Auth::id()) abort(403);
        $request->validate([
            'content' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high'
        ]);
        Task::create([
            'project_id' => $project->id, 
            'content' => $request->content,
            'due_date' => $request->due_date,
            'priority' => $request->priority
        ]);
        return back();
    }

    public function updateProject(Request $request, Project $project) {
        if($project->user_id !== Auth::id()) abort(403);
        $request->validate(['title' => 'required|string|max:255']);
        $project->update(['title' => $request->title, 'color' => $request->color]);
        return back();
    }

    public function updateTask(Request $request, Task $task) {
        if($task->project->user_id !== Auth::id()) abort(403);
        $request->validate([
            'content' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high'
        ]);
        $task->update([
            'content' => $request->content,
            'due_date' => $request->due_date,
            'priority' => $request->priority
        ]);
        return back();
    }

    public function destroyProject(Project $project) {
        if($project->user_id !== Auth::id()) abort(403);
        $project->delete();
        return back();
    }

    public function toggleTask(Task $task) {
        if($task->project->user_id !== Auth::id()) abort(403);
        $task->update(['is_completed' => !$task->is_completed]);
        return back();
    }

    public function reorderTasks(Request $request, Project $project) {
        if($project->user_id !== Auth::id()) abort(403);
        if($request->task_ids && is_array($request->task_ids)) {
            foreach($request->task_ids as $index => $taskId) {
                Task::where('id', $taskId)->where('project_id', $project->id)->update(['position' => $index]);
            }
        }
        return response()->json(['success' => true]);
    }

    public function destroyTask(Request $request, Task $task) {
        if($task->project->user_id !== Auth::id()) abort(403);
        $task->delete(); 

        if($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }
        return back();
    }

    public function restoreTask(Request $request, $id) {
        $task = Task::withTrashed()->findOrFail($id);
        if($task->project->user_id !== Auth::id()) abort(403);
        
        $task->restore(); 

        if($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }
        return back();
    }
}




