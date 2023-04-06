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
        // dd($login_user->phone);
        // if(isset($login_user->category_id) && $login_user->isCoach()){
            if($login_user->isCoach()){
                if(($login_user->categories()->count() > 0) && isset($login_user->hourly_rate_type) && isset($login_user->experience_year) && isset($login_user->preferred_pronouns)){
                    return $next($request);

                }else{
                    return redirect()->route('user.profile.edit');
                }
            }else{            
                return $next($request);
        }
    }
}
