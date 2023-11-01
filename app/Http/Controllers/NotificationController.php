<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //notification
    public function notification(){

        $user = Auth::user();
        $notifications = $user->notifications()->paginate(5);
        return view('frontend.notification',compact('notifications'));
    }

    //notification detail show
    public function show($id){
        $user = Auth::user();
        $notification = $user->notifications()->where('id',$id)->first();
        $notification->markAsRead();

        return view('frontend.notification_details',compact('notification'));
    }

}
