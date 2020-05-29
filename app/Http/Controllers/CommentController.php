<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentAdded;
use App\Post;
use Illuminate\Http\Request;
use Storage;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'image' => 'image|mimes:png,jpg,jpeg',
            'comment' => 'required|string|min:3',
        ]);
        $data = [];
        $data['user_id'] = auth()->user()->id;
        if ($request->has('image')) {
            $data['image'] = '/uploads/' . Storage::disk('uploads')->put('comments', $request->image);
        }
        $data['text'] = $request->comment;
        $data['post_id'] = $id;
        $comment = Comment::create($data);
        $post = Post::with(['user', 'likes', 'comments'])->find($id);
        broadcast(new CommentAdded($post));

        return response()->json(['message' => 'Comment added successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
