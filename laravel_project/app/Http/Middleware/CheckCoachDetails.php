<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckCoachDetails
{
    
    public function handle(Request $request, Closure $next)
    {

        $login_user = Auth::user();
        // dd($login_user->post_code);
        if(isset($login_user->post_code)){
            return $next($request);
        }else{            
            return redirect()->route('user.profile.edit');
        }
    }
}