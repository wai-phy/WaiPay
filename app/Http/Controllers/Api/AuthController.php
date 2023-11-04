<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register
    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:6|max:15',
        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->ip = $request->ip();
        $user->user_agent = $request->server('HTTP_USER_AGENT');
        $user->login_at = date('Y-m-d H:i:s');
        $user->save();

        Wallet::firstOrCreate([
            'user_id' => $user->id
        ], [
            'account_number' => UUIDGenerate::accountNumber(),
            'amount' => 0,
        ]);

        $token = $user->createToken('Wai Pay')->accessToken;

        return success('Successfully registered',['token'=>$token]);
    }

    //login
    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $user->ip = $request->ip();
            $user->user_agent = $request->server('HTTP_USER_AGENT');
            $user->login_at = date('Y-m-d H:i:s');
            $user->update();

            Wallet::firstOrCreate([
                'user_id' => $user->id
            ], [
                'account_number' => UUIDGenerate::accountNumber(),
                'amount' => 0,
            ]);
            $token = $user->createToken('Wai Pay')->accessToken;
            return success('Successfully login',['token'=>$token]);

        };

        return fail('The credential does not match',null);
    }

    //logout
    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();
        
        return success('Logout success',200);
    }
}
