<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {

        return response()->json(Task::latest()->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'completed' => ['sometimes', 'boolean'],
        ]);

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'completed' => ['sometimes', 'boolean'],
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ]);
    }

    // Testing pull request CI
}
