<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Wallet;
use Illuminate\Http\Request;
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

        $to_phone = $request->to_phone;
        $amount = $request->amount;
        $description = $request->description;
        return view('frontend.transfer_confirm', compact('auth_user', 'to_phone', 'amount', 'description'));
    }
}
