<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

use App\Helper\EventMsg;
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
        //
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
