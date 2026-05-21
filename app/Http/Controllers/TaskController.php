<?php

namespace App\Http\Controllers;

use App\Models\Tasks;

use Illuminate\Http\Request;
use Laravel\Prompts\Task;
use Psy\Readline\Hoa\Console;

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
}
