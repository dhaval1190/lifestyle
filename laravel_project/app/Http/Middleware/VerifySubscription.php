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
        $subscription = $user->subscription()->first();
        if($subscription && ($subscription->plan->plan_type == \App\Plan::PLAN_TYPE_PAID) && $subscription->invoices->count() == 0) {
            return redirect()->route('user.subscription.verify', $subscription->id);
        } else {
            return $next($request);
        }
    }
}
