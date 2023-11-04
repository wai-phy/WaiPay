<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //login page
    public function loginPage(){
        return view('login');
    }

    //register page
    public function registerPage(){
        return view('register');
    }

    //dashboard
    public function dashboard(Request $request){
        
        $user = Auth::user();
        $user->ip = $request->ip();
        $user->user_agent = $request->server('HTTP_USER_AGENT');
        $user->login_at = date('Y-m-d H:i:s');
        $user->update();

        if(Auth::user()->role == 'admin'){
            return redirect()->route('admin#Home');
        }
        
        return view('frontend.user_home');
    }

    

}
