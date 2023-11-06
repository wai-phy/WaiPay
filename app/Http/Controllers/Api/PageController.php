<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;
use App\Notifications\GeneralNotification;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Notification;
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

    //verifyAccount
    public function verifyAccount(Request $request){
        if($request->phone){
            $auth_user = Auth::user();
            if($auth_user->phone != $request->phone){
                $user = User::where('phone',$request->phone)->first();

                if($user){
                    return success('success',['name' => $user->name, 'phone' => $user->phone]);
                }
            }
        }
        return fail('Invalid data', null);
    }

    //transferConfirm
    public function transferConfirm(Request $request){
        $request->validate(
            [
                'to_phone' => 'required',
                'amount' => 'required|integer',
                'hash_value' => 'required'
            ],
            [
                'to_phone.required' => 'To (phone number) field is required..!',
                'hash_value.required' => 'The given data is invalid'
            
            ]
        );

        $auth_user = Auth::user();
        $from_account = $auth_user;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        $hash_value = $request->hash_value;


        $str = $to_phone.$amount.$description;
        $hash_value2 = hash_hmac('sha256', $str, 'waipay123!$#');
        if($hash_value !== $hash_value2){

            return fail('The given data is invalid.', null);

        }
        
        if($amount < 1000 ){
            return fail('The amount must at least 1000 MMK.', null);
        }

        if($from_account->phone == $to_phone){
            return fail('To Account is Invalid.', null);
        }

        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return fail('To Account is Invalid.', null);
        }

        if(!$from_account->wallet || !$to_account->wallet){
            return fail('Something went wrong. The given data is invalid.', null);
        }

        if($from_account->wallet->amount < $amount){
            return fail('You do not have sufficient amount to transfer !!', null);
        }

        
        return success('Success',[
            'from_name' => $from_account->name,
            'from_phone' => $from_account->phone,

            'to_name' => $to_account->name,
            'to_phone' => $to_account->phone,

            'amount' => $amount,
            'description' => $description,
            'hash_value' => $hash_value

        ]);
    }

    // transfer complete
    public function transferComplete(Request $request){
        $request->validate(
            [
                'to_phone' => 'required',
                'amount' => 'required|integer',
                'hash_value' => 'required'
            ],
            [
                'to_phone.required' => 'To (phone number) field is required..!',
                'hash_value.required' => 'The given data is invalid'
            
            ]
        );

        if(!$request->password){
            return fail('Please fill your password.', null);
            
        }

        $auth_user = Auth::user();
        if(!Hash::check($request->password, $auth_user->password)){
            return fail('The password is incorrect!', null);
        }
         
        $auth_user = Auth::user();
        $from_account = $auth_user;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        $hash_value = $request->hash_value;


        $str = $to_phone.$amount.$description;
        $hash_value2 = hash_hmac('sha256', $str, 'waipay123!$#');
        if($hash_value !== $hash_value2){
            return fail('The given data is invalid.', null);

        }
        
        if($amount < 1000 ){
            return fail('The amount must at least 1000 MMK.',null);
        }

        if($from_account->phone == $to_phone){
            return fail('To Account is Invalid.', null);
        }
        
        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return fail('To Account is Invalid.', null);
        }

        if(!$from_account->wallet || !$to_account->wallet){
            return fail('Something went wrong. The given data is invalid.', null);
        }

        if($from_account->wallet->amount < $amount){
            return fail('You do not have sufficient amount to transfer !!', null);
        }

        DB::beginTransaction();

        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount',$amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount',$amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();
            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->description = $description ;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no ;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_account->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            //from account notification
            $title = 'E-Money Transferred!';
            $message = 'Your wallet transfered ' . number_format($amount) . 'MMK to ' . $to_account->name . '(' . $to_account->phone . ')';
            $sourceable_id = $from_account->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $from_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction',
                'parameter' => $from_account_transaction->trx_id
            ];

            Notification::send([$from_account], new GeneralNotification($title,$message,$sourceable_id,$sourceable_type,$web_link, $deep_link ));

            //from account notification
            $title = 'E-Money Recieved!';
            $message = 'Your wallet recieved ' . number_format($amount) . 'MMK from ' . $from_account->name . '(' . $from_account->phone . ')';
            $sourceable_id = $to_account->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction',
                'parameter' => $to_account_transaction->trx_id
            ];

            Notification::send([$to_account], new GeneralNotification($title,$message,$sourceable_id,$sourceable_type,$web_link, $deep_link));


            DB::commit();
            return success('Successfully transferred',['transaction_id' =>$from_account_transaction->trx_id]);

        } catch (\Exception $e) {

            DB::rollBack();
            return fail('Something wrong',null);
        }

    }

    //scan and pay transfer
    public function scanAndPayTransfer(Request $request){
        $from_account = Auth::user();
        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return fail('QR Scan is Invalid.',null);
        }

        return success('Successful',[
            'from_name'=>$from_account->name,
            'from_phone'=>$from_account->phone,

            'to_name'=>$to_account->name,
            'to_phone'=>$to_account->phone,


        ]);
    }

     //scan and pay transfer confirm
     public function scanAndPayTransferConfirm(Request $request)
     {  
         $request->validate(
             [
                 'to_phone' => 'required',
                 'amount' => 'required|integer'
             ],
             ['to_phone.required' => 'To (phone number) field is required..!']
         );
 
         $auth_user = Auth::user();
         $from_account = $auth_user;
         $to_phone = $request->to_phone;
         $amount = $request->amount;
         $description = $request->description;
         $hash_value = $request->hash_value;
 
 
         $str = $to_phone.$amount.$description;
         $hash_value2 = hash_hmac('sha256', $str, 'waipay123!$#');
         if($hash_value !== $hash_value2){
            return fail('The given data is invalid.',null);
 
         }
         
         if($amount < 1000 ){
            return fail('The amount must at least 1000 MMK.',null);
         }
 
         if($from_account->phone == $to_phone){
            return fail('To Account is Invalid.',null);
         }
 
         $to_account = User::where('phone',$request->to_phone)->first();
         if(!$to_account){
            return fail('To Account is Invalid.',null);
         }
 
         if(!$from_account->wallet || !$to_account->wallet){
            return fail('Something went wrong. The given data is invalid.',null);
         }
 
         if($from_account->wallet->amount < $amount){
            return fail('You do not have sufficient amount to transfer !!',null);
         }
 
         
         return success('Success',[
            'from_name' => $from_account->name,
            'from_phone' => $from_account->phone,

            'to_name' => $to_account->name,
            'to_phone' => $to_account->phone,

            'amount' => $amount,
            'description' => $description,
            'hash_value' => $hash_value

        ]);
     }

      // scanAndPayTransferComplete complete
    public function scanAndPayTransferComplete(Request $request){
        $request->validate(
            [
                'to_phone' => 'required',
                'amount' => 'required|integer',
                'hash_value' => 'required'
            ],
            [
                'to_phone.required' => 'To (phone number) field is required..!',
                'hash_value.required' => 'The given data is invalid'
            
            ]
        );

        if(!$request->password){
            return fail('Please fill your password.', null);
            
        }

        $auth_user = Auth::user();
        if(!Hash::check($request->password, $auth_user->password)){
            return fail('The password is incorrect!', null);
        }
         
        $auth_user = Auth::user();
        $from_account = $auth_user;
        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        $hash_value = $request->hash_value;


        $str = $to_phone.$amount.$description;
        $hash_value2 = hash_hmac('sha256', $str, 'waipay123!$#');
        if($hash_value !== $hash_value2){
            return fail('The given data is invalid.', null);

        }
        
        if($amount < 1000 ){
            return fail('The amount must at least 1000 MMK.',null);
        }

        if($from_account->phone == $to_phone){
            return fail('To Account is Invalid.', null);
        }
        
        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return fail('To Account is Invalid.', null);
        }

        if(!$from_account->wallet || !$to_account->wallet){
            return fail('Something went wrong. The given data is invalid.', null);
        }

        if($from_account->wallet->amount < $amount){
            return fail('You do not have sufficient amount to transfer !!', null);
        }

        DB::beginTransaction();

        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount',$amount);
            $from_account_wallet->update();

            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount',$amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();
            $from_account_transaction = new Transaction();
            $from_account_transaction->ref_no = $ref_no;
            $from_account_transaction->trx_id = UUIDGenerate::trxId();
            $from_account_transaction->user_id = $from_account->id;
            $from_account_transaction->type = 2;
            $from_account_transaction->amount = $amount;
            $from_account_transaction->source_id = $to_account->id;
            $from_account_transaction->description = $description ;
            $from_account_transaction->save();

            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no ;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $amount;
            $to_account_transaction->source_id = $from_account->id;
            $to_account_transaction->description = $description;
            $to_account_transaction->save();

            //from account notification
            $title = 'E-Money Transferred!';
            $message = 'Your wallet transfered ' . number_format($amount) . 'MMK to ' . $to_account->name . '(' . $to_account->phone . ')';
            $sourceable_id = $from_account->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $from_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction',
                'parameter' => $from_account_transaction->trx_id
            ];

            Notification::send([$from_account], new GeneralNotification($title,$message,$sourceable_id,$sourceable_type,$web_link, $deep_link ));

            //from account notification
            $title = 'E-Money Recieved!';
            $message = 'Your wallet recieved ' . number_format($amount) . 'MMK from ' . $from_account->name . '(' . $from_account->phone . ')';
            $sourceable_id = $to_account->id;
            $sourceable_type = Transaction::class;
            $web_link = url('/transaction/' . $to_account_transaction->trx_id);
            $deep_link = [
                'target' => 'transaction',
                'parameter' => $to_account_transaction->trx_id
            ];

            Notification::send([$to_account], new GeneralNotification($title,$message,$sourceable_id,$sourceable_type,$web_link, $deep_link));


            DB::commit();
            return success('Successfully transferred',['transaction_id' =>$from_account_transaction->trx_id]);

        } catch (\Exception $e) {

            DB::rollBack();
            return fail('Something wrong',null);
        }

    }
}
