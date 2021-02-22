<?php

namespace App\Http\Controllers;

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
        $isUser = User::find(Auth::user()->id);
        $postIds = [];
        if ($isUser) {
            $postIds = explode(',', $isUser->post_saved);
            if ($request->is_saved == 0) {
                array_push($postIds, $request->post_id);
            } else {
                if (($key = array_search($request->post_id, $postIds)) !== false) {
                    unset($postIds[$key]);
                }
            }
            $isUser->post_saved = implode(',', $postIds);
            $isUser->save();
        }
        
        return response()->json([
            'status' => true
        ]);
    }
}
