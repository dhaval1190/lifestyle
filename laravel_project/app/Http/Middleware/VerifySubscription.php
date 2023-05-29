<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Artesaos\SEOTools\Facades\SEOMeta;
use App\Theme;
use App\Customization;
use App\Setting;
use App\Plan;
use App\Subscription;
use App\SettingBankTransfer;

class VerifySubscription
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        // $subscription = $user->subscription()->first();
        // if($subscription && ($subscription->plan->plan_type == \App\Plan::PLAN_TYPE_PAID) && $subscription->invoices->count() == 0) {
        //     return redirect()->route('user.subscription.verify', $subscription->id);
        // } else {
        //     return $next($request);
        // }
        $subscription = $user->subscription()->orderBy('id','DESC')->first();
        // dd($subscription);

        if(isset($subscription->stripe_id)){
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET')); 
            $subscriptionData = \Stripe\Subscription::retrieve($subscription->stripe_id);
            // dd(date('Y-m-d H:i:s',$subscriptionData->current_period_end));
            $current_period_end =  date('Y-m-d H:i:s', $subscriptionData->current_period_end);
            if($subscriptionData->status == 'canceled'){
                $update_ends_at = Subscription::where('stripe_id',$subscription->stripe_id)->where('user_id',$user->id)->update(['ends_at'=>$current_period_end,'stripe_status'=>$subscriptionData->status]);
            }

            $new_subs_end_date = date('Y-m-d H:i:s',$subscriptionData->current_period_end);
            $new_subs_end_date_val = explode(" ",$new_subs_end_date)[0];
            $subscription->subscription_end_date = $new_subs_end_date_val;
            $subscription->save();
        }

        $today = date('Y-m-d');
        $plan_details = Plan::where('id',$subscription->plan_id)->first();

        if($subscription && ($plan_details->plan_type == \App\Plan::PLAN_TYPE_PAID) && $subscription->subscription_end_date < $today) {
            if(str_contains(url()->current(),"user/plans")){
                return $next($request);
            }
            return redirect()->route('user.subscription.verify', $subscription->id);
        } else {
            return $next($request);
        }
    }
}
