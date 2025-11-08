<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//TODO
//Task Controller refactoring
//solve PHP db driver package problems!!!!!!

class TaskController extends Controller
{
    public function get_all_tasks(Request $request){
        if(Auth::check()){
            
            $tasks = Task::where('user_id', Auth::id());
            return response()->json([
                'tasks' => $tasks,
                'msg' => 'success'
            ]);
            
        }
        return response()->json([
           'msg' => 'user not logged in',
           'page' => 'login_page'
        ]);
    }
    public function add_task(Request $request){
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'state' => 'required|boolean'
        ]);

        if(Auth::check()){
            
            $task = $request->user()->tasks()->create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'state' => $validated['state']
            ]);
            return response()->json([
                'task' => $task,
                'msg' => 'success'
            ]);
            
        }
        return response()->json([
           'msg' => 'user not logged in',
           'page' => 'login_page'
        ]);
    }
    public function delete_task(Request $request){
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'state' => 'required|boolean'
        ]);
        if(Auth::check()){
            
            $taskToDelete = Task::where('user_id', Auth::id())->where('title', $validated['title']);
            $taskToDelete->delete();
            return response()->json([
                'msg' => 'success'
            ]);
            
        }

    }
    public function update_task(Request $request){
        $validated = $request->validate([
            'curr_title' => 'required|string',
            'title' => 'optional|string',
            'description' => 'optional|string',
            'state' => 'required|boolean'
        ]);
        if (Auth::check()) {
            $task = Task::where('user_id', Auth::id())
                        ->where('title', $validated['curr_title'])
                        ->first();
        
            if ($task) {
                if ($validated['title']) {
                    $task->title = $validated['title'];
                }
                if ($validated['description']) {
                    $task->description = $validated['description'];
                }
                $task->state = $validated['state'];
                $task->save();
            }
        }
    }
}
