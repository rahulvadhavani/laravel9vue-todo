<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $todos = Todo::select('id','title','status','created_at')->orderby('id','desc')->get();
            return response()->json(['status' => true, 'message' => 'Success.', 'data' => $todos]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!!', 'original_message' => $th->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TodoRequest $request)
    {
        try {
            $data = $request->validated();
            $id = $request->id ?? 0;
            $todo = Todo::updateOrCreate(['id' => $id], $data);
            $status = $id > 0 ? 'Updated' : 'Added';
            return response()->json(['status' => true, 'message' => "Task {$status} successfully."]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!!', 'original_message' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $todo = Todo::where('id', $id)->first();
            if ($todo === null) {
                return response()->json(['status' => false, 'message' => 'Not found']);
            }
            return response()->json(['status' => true, 'message' => 'Success', 'data' => $todo]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!!', 'original_message' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $todo = Todo::where('id', $id)->first();
            if ($todo === null) {
                return response()->json(['status' => false, 'message' => 'Not found']);
            }
            $todo->delete();
            return response()->json(['status' => true, 'message' => 'Task deleted successfully']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!!', 'original_message' => $th->getMessage()]);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $todo = Todo::where('id', $request->id??0)->first();
            if ($todo === null) {
                return response()->json(['status' => false, 'message' => 'Not found']);
            }
            $todo->update(['status' => !$todo->status]);
            return response()->json(['status' => true, 'message' => 'Task status updated successfully']);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!!', 'original_message' => $th->getMessage()]);
        }
    }
}
