<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\PseudoTypes\True_;

class CommentController extends Controller
{
    public function StoreComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required|numeric',
            'comment' => 'required',
        ]);
        // dd($request->master_id);
        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->post_id;
        $comment->master_comment = $request->master_id;
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'status' => true,
            'comment' => $comment
        ]);
    }

    public function DestroyComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required|numeric',
            'comment_id' => 'required|numeric',
        ]);

        $comment = Comment::find($request->comment_id);
        if (Auth::user()->id == $comment->user_id || $comment->post->user_id == Auth::user()->id){
            $comment->delete();
            return response()->json([
                'status' => true
            ]);
        }
    }
}
