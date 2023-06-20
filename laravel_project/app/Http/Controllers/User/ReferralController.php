<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use App\User;

class ReferralController extends Controller
{
    public function index()
    {

        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('role_permission.item-leads.seo.index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

         $all_referrals = Auth::user()->referrals()->orderBy('created_at','DESC')->paginate(10);
        //  $all_referrals = $all_referrals->paginate(10);
        //  dd($all_referrals->count());
        return response()->view('backend.user.item.referral.index',compact('all_referrals'));
    }
}
