<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PageController extends Controller
{
    // app home
    public function home()
    {
        $auth_user = Auth::user();
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
        $auth_user = Auth::user();
        $request->validate(
            [
                'to_phone' => 'required',
                'amount' => 'required|integer'
            ],
            ['to_phone.required' => 'To (phone number) field is required..!']
        );
        if($request->amount < 1000 ){
            return back()->withErrors(['amount'=>'The amount must at least 1000 MMK.'])->withInput();
        }

        if($auth_user->phone == $request->to_phone){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }

        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }
        $from_account = $auth_user;
        $amount = $request->amount;
        $description = $request->description;
        return view('frontend.transfer_confirm', compact('from_account', 'amount', 'description','to_account'));
    }

    // transfer complete
    public function transferComplete(Request $request){
        $auth_user = Auth::user();
        $request->validate(
            [
                'to_phone' => 'required',
                'amount' => 'required|integer'
            ],
            ['to_phone.required' => 'To (phone number) field is required..!']
        );
        if($request->amount < 1000 ){
            return back()->withErrors(['amount'=>'The amount must at least 1000 MMK.'])->withInput();
        }
        if($auth_user->phone == $request->to_phone){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }

        $to_account = User::where('phone',$request->to_phone)->first();
        if(!$to_account){
            return back()->withErrors(['to_phone'=>'To Account is Invalid.'])->withInput();
        }
        $from_account = $auth_user;
        $amount = $request->amount;
        $description = $request->description;

        if(!$from_account->wallet || !$to_account->wallet){
            return back()->withErrors(['fail'=>'Something went wrong. The given data is invalid']);
        }

        DB::beginTransaction();

        try {
            $from_account_wallet = $from_account->wallet;
            $from_account_wallet->decrement('amount',$amount);
            $from_account_wallet->update();
    
            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount',$amount);
            $to_account_wallet->update();
            DB::commit();
            return redirect('/')->with(['transfer_success'=>'Successfully transfered']);

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors(['fail'=>'Something wrong' . $e])->withInput();
        }
       
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
}
