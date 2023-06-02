<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Plan;
use App\Setting;
use App\SettingBankTransfer;
use App\Subscription;
use Artesaos\SEOTools\Facades\SEOMeta;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Theme;
use App\User;
use App\Customization;
use Laravel\Cashier\Cashier;

class PlanController extends Controller
{
    public function index(){

        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.user.subscription.edit-subscription', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // $all_plans = Plan::where('id', '!=', $subscription->plan_id)
        //     ->where('plan_type', Plan::PLAN_TYPE_PAID)
        //     ->where('plan_status', Plan::PLAN_ENABLED)
        //     ->get();

        $all_plans = Plan::where('plan_type', Plan::PLAN_TYPE_PAID)
            ->where('plan_status', Plan::PLAN_ENABLED)
            ->get();

        
        $authUser_subs = Subscription::where('user_id',auth()->user()->id)->orderBy('id','DESC')->first();
        $active_plan = Plan::where('id', '=', $authUser_subs->plan_id)->where('plan_type', Plan::PLAN_TYPE_PAID)
        ->where('plan_status', Plan::PLAN_ENABLED)
        ->first();


        $subscription_status = '';
        if(isset($authUser_subs->stripe_id)){
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET')); 
            $subscriptionData = \Stripe\Subscription::retrieve($authUser_subs->stripe_id);
            // dd($subscriptionData->status);          
                $subscription_status = $subscriptionData->status;            
        }
        

        // get payment gateway enable status
        $setting_site_paypal_enable = $settings->setting_site_paypal_enable;
        $setting_site_razorpay_enable = $settings->setting_site_razorpay_enable;
        $setting_site_stripe_enable = $settings->setting_site_stripe_enable;
        $setting_site_payumoney_enable = $settings->setting_site_payumoney_enable;

        $all_setting_bank_transfers = SettingBankTransfer::where('setting_bank_transfer_status', Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE)->get();
        $setting_site_bank_transfer_enable = $all_setting_bank_transfers->count() > 0 ? true : false;

        return response()->view('backend.user.subscription.plan_list',
            compact('all_plans', 'setting_site_paypal_enable', 'setting_site_razorpay_enable',
                    'setting_site_stripe_enable', 'all_setting_bank_transfers', 'setting_site_bank_transfer_enable',
                    'setting_site_payumoney_enable','active_plan','subscription_status'));

    }

    public function show(Request $request,$plan_slug)
    {
        
        $authUser_subs = Subscription::where('user_id',auth()->user()->id)->orderBy('id','DESC')->first();
        if(isset($authUser_subs->stripe_id)){
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET')); 
            $subscriptionData = \Stripe\Subscription::retrieve($authUser_subs->stripe_id);
            if($subscriptionData->status == 'canceled' || $subscriptionData->status == 'active'){
                return back();
            }            
        }

        $plan = Plan::where('slug',$plan_slug)->first();     
        $intent = auth()->user()->createSetupIntent();

        return view("backend.user.subscription.subscription", compact("plan",'intent'));
    }

    public function upgradePlan(Request $request,$plan_slug)
    {
        $plan = Plan::where('slug',$plan_slug)->first();
        if($plan){
            $intent = auth()->user()->createSetupIntent();
            return view("backend.user.subscription.upgrade_subscription", compact("plan",'intent'));

        }
        return redirect()->back();
    }
}
