<?php

namespace App\Http\Controllers;

use App\Models\Tasks;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->json()->all();
        $validator = validator($data, [
            'title' => 'required|max:255',
            'due_date' => 'required|date',
            'priority' => 'required|in:1,2,3',
            'completed' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => null, 'error_message' => $validator->errors()])->setStatusCode(400);
        }
        $userId = $request->input('user_id');
        $data['user_id'] = $userId;
        $task = new Tasks($data);
        $task->save();
        return response()->json(['data' => $task, 'error_message' => null])->setStatusCode(201);
    }

    public function getList(Request $request)
    {
        $page = $request->query('page', 1);
        $pageSize = $request->query('pageSize', 5);
        $userId = $request->input('user_id');
        $tasks = Tasks::where('user_id', $userId)->paginate(perPage: $pageSize, page: $page);

        $taskArray = $tasks->toArray();
        return response()->json(
            [
                'data' => $taskArray['data'],
                'current_page' => $taskArray['current_page'],
                'last_page' => $taskArray['last_page'],
                'per_page' => $taskArray['per_page'],
                'total_data' => $taskArray['total']
            ]
        )->setStatusCode(200);
    }

    public function getDueDateTasks(Request $request)
    {
        $userId = $request->input('user_id');
        $totalTasks = Tasks::where('user_id', $userId)
            ->whereDate('due_date', now()->toDateString())
            ->orderBy('due_date', 'asc')
            ->count();

        return response()->json(['total_data' => $totalTasks, 'error_message' => null])->setStatusCode(200);
    }

    public function updateCompletedStatus(Request $request, int $taskId)
    {
        $userId = $request->input('user_id');
        $task = Tasks::where(['id' => $taskId, 'user_id' => $userId])->first();
        if (!$task) {
            return response()->json(['data' => null, 'error_message' => 'Task not found'], 404);
        }

        $task->completed = !$task->completed;
        $task->save();
        return response()->json(['data' => $task, 'error_message' => null])->setStatusCode(200);
    }
}
