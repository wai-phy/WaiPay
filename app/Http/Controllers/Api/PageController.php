<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\NotificationDetailResource;

class PageController extends Controller
{
    //testing 
    public function profile(){
        $user = Auth::user();

        $data = new ProfileResource($user);
        return success('success',$data);
    }

    //transaction
    public function transaction(Request $request){
        $user = Auth::user();

        $transactions = Transaction::with('user','source')->orderBy('created_at','desc')->where('user_id',$user->id);
        
        if($request->type){
            $transactions = $transactions->where('type',$request->type);
        }

        if($request->date){
            $transactions = $transactions->whereDate('created_at',$request->date);
        }
        
        $transactions = $transactions->paginate(5);

        $data = TransactionResource::collection($transactions)->additional(['result' => 1, 'message' => 'Success']);

        return $data;
    }

    //transactionDetail
    public function transactionDetail($trx_id){
        $auth_user = Auth::user();

        $transaction = Transaction::where('user_id',$auth_user->id)->where('trx_id',$trx_id)->firstOrFail();
        $data = new TransactionDetailResource($transaction);

        return success('Successful',$data);

    }

    //notification
    public function notification(){
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(5);

        return NotificationResource::collection($notifications)->additional(['result'=>1 , 'message' => 'Successful']);
    }

    //notification detail
    public function notificationDetail($id){
        $user = Auth::user();
        $notification = $user->notifications()->where('id',$id)->firstOrFail();
        $notification->markAsRead();

        $data = new NotificationDetailResource($notification);

        return success('success', $data);
    }
}
