<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = [
            "title.required" => " Title field name is required!",
            "description.required" => " Description field name is required!",
        ];
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ], $message);
        if ($validator->fails()) {
            return Response::json(["msg" => $validator->errors()]);
        } else {
            $post = Post::create([
                "title" => $request->title,
                "description" => $request->description,
            ]);
            return Response::json([$post, "msg" => "Create sucess!!"]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);
        return Response::json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);
        $post->update(
            [
                "title" => $request->title,
                "description" => $request->description,
            ]
        );


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Post::find($id)->delete();
    }
}
