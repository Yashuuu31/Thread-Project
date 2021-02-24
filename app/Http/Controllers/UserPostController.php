<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

use App\Helper\EventMsg;
use App\Models\Comment;
use App\Models\FavoritePost;
use App\Models\Like;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserPostController extends Controller
{
    public $moduleName = "User Posts";
    public $view = "pages/user_posts";
    public $route = "user_posts";

    public function index()
    {
        $moduleName = $this->moduleName;
        $UserPosts = Post::all();
        
        return view("$this->view/index", compact('moduleName', 'UserPosts'));
    }


    public function create()
    {
        //
    }

    public function store(PostRequest $request)
    {
        $request->validated();

        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->title = $request->title;
        $post->des = $request->des;
        $post->save();

        EventMsg::SuccessMsg("Post Uploded Successfully.");
        return redirect()->route("$this->route.index");
    }

    public function show($id)
    {
        $post = Post::find($id);
        $comments = Comment::where('post_id', $id)->get();
        $likes = Like::where('post_id', $post->id)
                ->where('user_id', Auth::user()->id)
                ->first();
        $favs = FavoritePost::where('post_id', $post->id)
                ->where('user_id', Auth::user()->id)
                ->first();
        $allLikes = Like::where('post_id', $post->id)
                ->where('is_liked', '1')
                ->get();
        return view("{$this->view}/detail", compact('post', 'comments', 'likes', 'favs', 'allLikes'));
    }

    public function edit($id)
    {
        $post = Post::find($id);

        if (Auth::user()->id == $post->user_id) {
            return response()->json([
                'status' => true,
                'data' => $post
            ]);
        }
    }

    public function update(PostRequest $request, $id)
    {
        $request->validated();

        $post = Post::find($id);
        if (Auth::user()->id == $post->user_id) {
            $post->title = $request->title;
            $post->des = $request->des;
            $post->save();

            EventMsg::SuccessMsg("Post Updated Successfully.");
            return redirect()->route("$this->route.index");
        }
    }


    public function destroy($id)
    {
        $post = Post::find($id);
        if (Auth::user()->id == $post->user_id) {
            EventMsg::SuccessMsg("Post Deleted Successfully.");
            $post->delete();
            return response()->json([
                'status' => true,
            ]);
        }
    }
}
