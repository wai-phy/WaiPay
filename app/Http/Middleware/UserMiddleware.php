<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if(Auth::user()->role == 'admin'){
            
            return back();
        }

        Wallet::firstOrCreate([
            'user_id' => $user->id
        ], [
            'account_number' => UUIDGenerate::accountNumber(),
            'amount' => 0,
        ]);
        
        return $next($request);
    }
}
