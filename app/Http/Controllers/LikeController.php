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
            'user_id' => 'required|numeric',
        ]);

        $isPost = Like::where('post_id', $request->post_id)->first();
        $userIds = [];
        if ($isPost) {
            $userIds = explode(',', $isPost->user_ids);
            // if (!in_array(Auth::user()->id, $userIds)) {
            //     array_push($userIds, Auth::user()->id);
            // }
            if ($request->is_liked == 1) {
                array_push($userIds, Auth::user()->id);
            } else {
                if (($key = array_search(Auth::user()->id, $userIds)) !== false) {
                    unset($userIds[$key]);
                }
            }
            $users = implode(',', $userIds);
            $isPost->user_ids = $users;
            $isPost->save();
        }

        return response()->json([
            'status' => true
        ]);
    }
}
