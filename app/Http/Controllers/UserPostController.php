<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

use App\Helper\EventMsg;
use Illuminate\Support\Facades\Auth;

class UserPostController extends Controller
{
    public $moduleName = "User Posts";
    public $view = "pages/user_posts";
    public $route = "user_posts";

    public function index()
    {     
        $moduleName = $this->moduleName;
        $UserPosts = Post::where('user_id', Auth::user()->id)->get();
        return view("$this->view/index",compact('moduleName','UserPosts'));
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // $request->validated();

        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->title = $request->title;
        $post->des = $request->des;
        $post->save();

        EventMsg::SuccessMsg("Post Uploded Successfully.");
        return redirect()->route("$this->route.index");

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
