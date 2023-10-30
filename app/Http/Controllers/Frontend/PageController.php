<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;



class PageController extends Controller
{
    // app home
    public function home()
    {
        $auth_user = Auth::user();

        $title = 'Hello';
        $message = 'How are you?';
        $sourceable_id = 1;
        $sourceable_type = User::class;
        $web_link = url('profile');

        Notification::send([$auth_user], new GeneralNotification($title,$message,$sourceable_id,$sourceable_type,$web_link));


        return view('frontend.user_home', compact('auth_user'));
    }

    //profile
    public function profile()
    {
        $user = Auth::user();
        return view('frontend.profile', compact('user'));
    }

    //update password
    public function updatePassword()
    {
        return view('frontend.password_update');
    }

    //store update password
    public function updatePasswordStore(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required|min:6|max:20',
            'newPassword' => 'required|min:6|max:20',
        ]);
        $old_password = $request->oldPassword;
        $new_password = $request->newPassword;
        $user = Auth::user();

        if (Hash::check($old_password, $user->password)) {
            $user->password = Hash::make($new_password);
            $user->update();
            return redirect()->route('profile')->with('update', 'Updated Successfully.');
        }

        return back()->withErrors(['oldPassword' => 'The Old Password Is Not Correct'])->withInput();
    }

    //wallet page
    public function wallet()
    {
        $auth_user = Auth::user();
        return view('frontend.wallet', compact('auth_user'));
    }

    //transfer
    public function transfer()
    {
        $auth_user = Auth::user();
        return view('frontend.transfer', compact('auth_user'));
    }

    //transfer confirm
    public function transferConfirm(Request $request)
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

            return back()->withErrors(['fail'=>'The given data is invalid.'])->withInput();

        }
        
        if($amount < 1000 ){
            return back()->withErrors(['amount'=>'The amount must at least 1000 MMK.'])->withInput();
        }

        if($from_account->phone == $to_phone){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }

        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }

        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['fail'=>'Something went wrong. The given data is invalid.']);
        }

        if($from_account->wallet->amount < $amount){
            return back()->withErrors(['amount'=>'You do not have sufficient amount to transfer !!']);
        }

        
        return view('frontend.transfer_confirm', compact('from_account', 'amount', 'description','to_account','hash_value'));
    }

    // transfer complete
    public function transferComplete(Request $request){
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

            return back()->withErrors(['fail'=>'The given data is invalid.'])->withInput();

        }
        
        if($amount < 1000 ){
            return back()->withErrors(['amount'=>'The amount must at least 1000 MMK.'])->withInput();
        }

        if($from_account->phone == $to_phone){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }
        
        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }

        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['fail'=>'Something went wrong. The given data is invalid.']);
        }

        if($from_account->wallet->amount < $amount){
            return back()->withErrors(['amount'=>'You do not have sufficient amount to transfer !!']);
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

            DB::commit();
            return redirect()->route('transaction_detail',$from_account_transaction->trx_id)->with(['transfer_success'=>'Successfully transfered']);

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors(['fail'=>'Something wrong' . $e])->withInput();
        }

    }

    //transaction history
    public function transaction(Request $request){
        $auth_user = Auth::user();
        $transactions = Transaction::with('user','source')->orderBy('created_at', 'desc')->where('user_id',$auth_user->id);

        if($request->type){
            $transactions = $transactions->where('type',$request->type);
        }

        if($request->date){
            $transactions = $transactions->whereDate('created_at',$request->date);
        }

        $transactions = $transactions->paginate(5);
        return view('frontend.transaction',compact('transactions'));
    }

    //transaction detail
    public function transactionDetail($trx_id){
        $auth_user = Auth::user();
        $transaction = Transaction::with('user','source')->where('user_id',$auth_user->id)->where('trx_id',$trx_id)->first();
        return view('frontend.transaction_detail',compact('transaction'));
    }

    //verify account
    public function verifyAccount(Request $request){
        $auth_user = Auth::user();

        if($auth_user->phone != $request->phone){
            $user = User::where('phone',$request->phone)->first();
            if($user){
                return response()->json([
                    'status'=>'success',
                    'message'=>'success',
                    'data' => $user
                ]);
            }
        }

        return response()->json([
            'status'=>'fail',
            'message'=>'Invalid Data',
        ]);
    }

    //password check
    public function passwordCheck(Request $request){
        if(!$request->password){
            return response()->json([
                'status'=>'fail',
                'message'=>'Please fill your password.',
            ]);
        }

        $auth_user = Auth::user();
        if(Hash::check($request->password, $auth_user->password)){
            return response()->json([
                'status'=>'success',
                'message'=>'The password is correct',
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'The password is incorrect!',
            ]);
        }
    }

    //transfer data hash
    public function transferHash(Request $request){

        $str = $request->to_phone.$request->amount.$request->description;
        $hash_value = hash_hmac('sha256', $str, 'waipay123!$#');

        return response()->json([
            'status'=>'success',
            'data'=> $hash_value,
        ]);

    }

    //recieve qr 
    public function recieveQR(){
        $auth_user = Auth::user();
        return view('frontend.recieve_qr',compact('auth_user'));
    }

    //scan and pay
    public function scanAndPay(){
        return view('frontend.scan_and_pay');
    }

    //scan and pay transfer
    public function scanAndPayTransfer(Request $request){
        $from_account = Auth::user();
        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return back()->withErrors(['fail'=>'QR Scan is Invalid.'])->withInput();
        }

        return view('frontend.scan_and_pay_transfer', compact('from_account','to_account'));
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

            return back()->withErrors(['fail'=>'The given data is invalid.'])->withInput();

        }
        
        if($amount < 1000 ){
            return back()->withErrors(['amount'=>'The amount must at least 1000 MMK.'])->withInput();
        }

        if($from_account->phone == $to_phone){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }

        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }

        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['fail'=>'Something went wrong. The given data is invalid.']);
        }

        if($from_account->wallet->amount < $amount){
            return back()->withErrors(['amount'=>'You do not have sufficient amount to transfer !!']);
        }

        
        return view('frontend.scan_and_pay_transfer_confirm', compact('from_account', 'amount', 'description','to_account','hash_value'));

        }

    //scan and pay transfer complete
    public function scanAndPayTransferComplete(Request $request){
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

            return back()->withErrors(['fail'=>'The given data is invalid.'])->withInput();

        }
        
        if($amount < 1000 ){
            return back()->withErrors(['amount'=>'The amount must at least 1000 MMK.'])->withInput();
        }

        if($from_account->phone == $to_phone){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }
        
        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }

        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['fail'=>'Something went wrong. The given data is invalid.']);
        }

        if($from_account->wallet->amount < $amount){
            return back()->withErrors(['amount'=>'You do not have sufficient amount to transfer !!']);
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

            DB::commit();
            return redirect()->route('transaction_detail',$from_account_transaction->trx_id)->with(['transfer_success'=>'Successfully transfered']);

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors(['fail'=>'Something wrong' . $e])->withInput();
        }

    }

    }