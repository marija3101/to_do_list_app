<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskCollection;
use Illuminate\Support\Facades\Validator;


class TaskController extends Controller
{
    public function index() 
    {
        $tasks = Task::all();
        return new TaskCollection($tasks);
    }

    public function show(Task $task) 
    {
        
        return new TaskResource($task);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title'=> 'required|string|max:100',
            'description'=> 'required|string|max:255',
            'completed'=> 'required',
            'user_id'=> 'required'
        ]);

        if($validator->fails())
        return response()->json($validator->errors());

        $task = Task::create([
            'title'=>$request->title,
            'description'=>$request->description,
            'completed'=>$request->completed,
            'user_id'=>$request->user_id
        ]);
        return response()->json(["Task is created!!!", new TaskResource($task)]);
    }
    
    public function update(Request $request, Task $task) 
    {
        $validator = Validator::make($request->all(),[
            'title'=> 'required|string|max:100',
            'description'=> 'required|string|max:255',
            'completed'=> 'required',
            'user_id'=> 'required'
        ]);

        if($validator->fails())
        return response()->json($validator->errors());

        $task->title = $request->title;
        $task->description=$request->description;
        $task->completed=$request->completed;

        $task->save();
        return response()->json(["Task is updated!!!", new TaskResource($task)]);
    }

    public function destroy(Task $task) 
    {
        $task->delete();
        return response()->json('Task is deleted');
    }

    public function complete($task_id)
    {
        $task = Task::find($task_id);
        if(is_null($task)) 
        return response()->json('Data not found', 404);

        $task->completed = true;
        $task->save();

        return response()->json('Task is completed!');
    }

    public function incomplete($task_id)
    {
        $task = Task::find($task_id);
        if(is_null($task)) 
        return response()->json('Data not found', 404);

        $task->completed = false;
        $task->save();

        return response()->json('Task is incompleted!');
    }
}
