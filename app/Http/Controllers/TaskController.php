<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $task = Task::all();
        return view('task.index', ['task' => $task]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $newTask = Task::create($data);

        return redirect()->route('task.index')->with('success', "Task succesfully added");
    }

    public function edit(Task $task, Request $request)
    {
        return view('task.edit', ['task' => $task]);
    }
    public function update(Task $task, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required|string|in:pending,completed'
        ]);

        $task->update($data);

        return redirect()->route('task.index', ['task' => $task])->with('success', "Data berhasil Diperbarui");
    }
    public function delete(Task $task)
    {
        $task->delete();
        return redirect()->route('task.index')->with('success', 'Data berhasil dihapus');
    }
}
