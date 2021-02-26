<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function MarkAsRead(Request $request){
        $request->validate([
            'uuid' => 'required'
        ]);

        $Notification = Auth::user()->Notifications->find($request->uuid);
        if($Notification){
            $Notification->markAsRead();
            return response()->json([
                'status' => true
            ]);
        }
    }
}
