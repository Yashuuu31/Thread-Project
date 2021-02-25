<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function PostLike(Request $request)
    {
        $request->validate([
            'post_id' => 'required|numeric',
        ]);

        $isPost = Like::where('user_id', Auth::user()->id)->where('post_id', $request->post_id)->first();

        if($isPost){
            if($request->is_liked == 1){
                $isPost->is_liked = 1;
            }else{
                $isPost->is_liked = 0;
            }
            $isPost->save();
        }else{
            $like = new Like;
            $like->post_id = $request->post_id;
            $like->user_id = Auth::user()->id;
            $like->is_liked = 1;
            $like->save();  
        }
        
        $allLikes = Like::where('post_id', $request->post_id)->where('is_liked', '!=', '0')->count();

        return response()->json([
            'status' => true,
            'allLikes' => $allLikes
        ]);
    }
}
