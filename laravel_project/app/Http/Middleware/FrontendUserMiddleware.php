<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Role;
use App\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class FrontendUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check() && (Auth::user()->isUser() || Auth::user()->isCoach()))
        {
            // check if user account suspended
            if(Auth::user()->hasActive())
            {

                // dd("1111");
                return $next($request);
            }
            else
            {

                Auth::logout();
                $settings = app('site_global_settings');
                /**
                 * Start SEO
                 */
                SEOMeta::setTitle(__('seo.backend.user.profile.suspended', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
                SEOMeta::setDescription('');
                SEOMeta::setCanonical(URL::current());
                SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
                /**
                 * End SEO
                 */
                // return response()->view('backend.user.suspend');
                return redirect()->route('login')->with('email_not_verified','Your email ID is suspended.Please contact to Admin');
            }


        }else{
            return $next($request);
        }
    }
}
