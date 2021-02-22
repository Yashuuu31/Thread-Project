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

        $comment = new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $request->post_id;;
        $comment->comment = $request->comment;
        $comment->save();

        $user = User::find($comment->user_id);
        $CommentTemp = "<div class='callout callout-info'>
        <h6 class='font-weight-bold'>{$user->name}</h6>
        <div class='row'>
            <p class='col'>{$comment->comment}</p>
            <button data-post='{$comment->post_id}' data-comment='{$comment->id}' type='button' class='btn DestroyComment'>
                <i class='fas fa-trash'></i>
            </button>
        </div>
    </div>";

        return response()->json([
            'status' => true,
            'comment' => $CommentTemp,
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
