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
        $comment->post_id = $request->post_id;
        $comment->master_comment = isset($request->master_id) ? $request->master_id : 0;
        $comment->comment = $request->comment;
        $comment->save();

     
        $CommentTemp = '';
        if($request->master_id == 0){
                $CommentTemp = "<div class='callout callout-info'>
                <h6 class='font-weight-bold'>".Auth::user()->name."</h6>
                <div class='row'>
                    <p class='col'>{$comment->comment}</p>
                    <button type='button'  class='btn ReplyComment'>
                                            <i class='fas fa-reply text-success'></i>
                                        </button>
                    <button data-post='{$comment->post_id}' data-comment='{$comment->id}' type='button' class='btn DestroyComment'>
                        <i class='fas fa-trash'></i>
                    </button>
                </div>
            </div>";
        }else{
            $master = Comment::find($request->master_id);
            $CommentTemp = "<div class='callout callout-info' data-master='{$comment->master_comment}'>
            <h6 class='font-weight-bold'>{$master->user->name}</h6>
            <div class='row'>
                <p class='col'>{$master->comment}</p>
                
                <button type='button' data-post='{$comment->post_id}' data-comment='{$comment->master_comment}'  class='btn ReplyComment'>
                                        <i class='fas fa-reply text-success'></i>
                                    </button>
                <button data-post='{$comment->post_id}' data-comment='{$comment->id}' type='button' class='btn DestroyComment'>
                    <i class='fas fa-trash'></i>
                </button>
            </div>
            
            <div class='callout callout-danger col-md-8 offset-md-1'>
            <h6 class='font-weight-bold'>{$comment->user->name}</h6>
            <div class='row'>
                <p class='col'>{$comment->comment}</p>

                <button type='button' data-post='{$comment->post_id}'
                    data-comment='{$comment->id}' class='btn DestroyComment'>
                    <i class='fas fa-trash'></i>
                </button>

            </div>
        </div>  
        </div>";
        }

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
