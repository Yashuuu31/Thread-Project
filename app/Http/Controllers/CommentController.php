<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\PseudoTypes\True_;

class CommentController extends Controller
{
    public function StoreComment(Request $request){
        $request->validate([
            'post_id' => 'required|numeric',
            'comment' => 'required',
        ]);

        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->post_id;;
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'status' => true
        ]);
    }
}
