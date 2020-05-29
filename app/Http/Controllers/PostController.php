<?php

namespace App\Http\Controllers;

use App\Events\LikePost;
use App\Events\PostCreate;
use App\Events\PostOwner;
use App\Post;
use App\PostLike;
use Auth;
use Illuminate\Http\Request;
use Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('id', 'DESC')
            ->with(['user', 'likes', 'comments'])->get();
        return response()->json($posts, 200);
    }

    public function myPosts()
    {
        $user = Auth::guard('api')->user();
        $posts = Post::orderBy('id', 'desc')
            ->with(['user', 'likes', 'comments'])
            ->where('user_id', $user->id)->get();
        return response()->json($posts, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string|min:3',
            'image' => 'image|mimes:png,jpg,jpeg',
        ]);
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        if ($request->has('image')) {
            $data['image'] = '/uploads/' . Storage::disk('uploads')->put('post', $request->image);
        }
        $posts = Post::create($data);
        $post = Post::with(['user', 'likes', 'comments'])->find($posts->id);
        broadcast(new PostCreate($post));
        broadcast(new PostOwner($post));
        return response()->json(['message' => 'Post Published successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
    public function like($id)
    {
        $userID = auth()->id();
        $post = Post::findOrFail($id);
        $like = PostLike::where(['post_id' => $id, 'user_id' => $userID])->first();
        if ($like) {
            $like->delete();
            $post = Post::with(['user', 'likes', 'comments'])->find($id);
            broadcast(new LikePost($post));
            return response()->json(['message' => 'post unliked'], 200);
        }
        $like = new PostLike();
        $like->user_id = $userID;
        $like->post_id = $id;
        $like->save();
        $post = Post::with(['user', 'likes', 'comments'])->find($id);
        broadcast(new LikePost($post));
        return response()->json(['message' => 'post liked'], 200);
    }
}
