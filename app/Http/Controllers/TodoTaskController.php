<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

class TodoTaskController extends Controller
{
    public function index()
    {
        $todos = Todo::orderBy('id', 'desc')->get();
        return view('todo.index', compact('todos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task' => 'required|unique:todo,task',
        ]);

        Todo::create(['task' => $request->task]);

        return response()->json(['message' => 'Todo Task added successfully']);
    }

    public function update(Request $request, Todo $todo)
    {
        $todo->update(['is_completed' => !$todo->is_completed]);
        return response()->json(['message' => 'Todo Task updated successfully']);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
