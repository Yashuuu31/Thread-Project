<?php

namespace App\Http\Controllers;

use App\Models\FavoritePost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostFavController extends Controller
{
    public function PostFav(Request $request)
    {
        $request->validate([
            'post_id' => 'required|numeric',
        ]);

        $isPost = FavoritePost::where('user_id', Auth::user()->id)->where('post_id', $request->post_id)->first();

        if($isPost){
            if($request->is_fav == 1){
                $isPost->is_fav = 1;
            }else{
                $isPost->is_fav = 0;
            }
            $isPost->save();
        }else{
            $favPost = new FavoritePost;
            $favPost->post_id = $request->post_id;
            $favPost->user_id = Auth::user()->id;
            $favPost->is_fav = 1;
            $favPost->save();  
        }
        
        
        return response()->json([
            'status' => true
        ]);
    }
}
