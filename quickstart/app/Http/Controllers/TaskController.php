<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cache;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Task;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    protected $tasks=null;
    /**
     * Create a new controller instance
     * @return void
     * */
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');
        $this->tasks= $tasks;
    }
    public function index(Request $request)
    {
        return view('tasks',['tasks'=>$this->tasks->forUser($request->user())]);    
    }
    public function store(Request $request)
    {
        $this->validate($request, ['name'=>'required|max:255']);
        //Create the task
        $request->user()->tasks()->create(['name'=>$request->name]);
        return redirect('/tasks');
    }
    public function destory(Request $request,Task $task)
    {
        $this->authorize('destory',$task);
        $task->delete();
        //redirect()->route('profile',['id'=>123]);
        return redirect('/tasks');
    }
}
