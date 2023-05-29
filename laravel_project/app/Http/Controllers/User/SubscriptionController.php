<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Plan;
use App\Setting;
use App\SettingBankTransfer;
use App\Subscription;
use Artesaos\SEOTools\Facades\SEOMeta;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Theme;
use App\User;
use App\Customization;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Subscription as CashierSubscription;

class SubscriptionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $auth_user = Auth::user();
        // $aa = $auth_user->paymentMethods();
        // if ($auth_user->subscribed('default')) {
        //     echo "ksdkljlskd";
        // }

        // dd($aa);
        // dd($auth_user->subscribed('yearly-premium'));
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - Subscriptions - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.subscription.subscriptions', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // show subscription information for current user
        $login_user = Auth::user();
        // $subscription = $login_user->subscription()->orderBy('id','desc')->first();
        $subscription = Subscription::first();
        $user = User::where('id',auth()->user()->id)->orderBY('id','DESC')->first();
        
        // dd($subscription);
        // dd($subscription->plan->plan_name);

        //$invoices = $subscription->invoices()->orderBy('created_at', 'DESC')->get();
        // $invoices = $subscription->invoices()->latest('created_at')->get();

        // $paid_subscription_days_left = $login_user->subscriptionDaysLeft();

        // return response()->view('backend.user.subscription.index',
        //     compact('subscription', 'invoices', 'paid_subscription_days_left'));

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        // dd($stripe);
        if($user->stripe_id) {
            $invoices = $stripe->invoices->all(['customer'=>$user->stripe_id, 'limit'=>100, 'status'=>'paid']);
        } else {
            $invoices = array();
        }
        
        $current_subscription = $login_user->subscriptions()->first();
        $plan_details = Plan::where('id',$current_subscription->plan_id)->first();
        // dd($current_subscription);

            return response()->view('backend.user.subscription.index',compact('subscription','invoices','current_subscription','plan_details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Subscription $subscription)
    {
        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
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
        

        // get payment gateway enable status
        $setting_site_paypal_enable = $settings->setting_site_paypal_enable;
        $setting_site_razorpay_enable = $settings->setting_site_razorpay_enable;
        $setting_site_stripe_enable = $settings->setting_site_stripe_enable;
        $setting_site_payumoney_enable = $settings->setting_site_payumoney_enable;

        $all_setting_bank_transfers = SettingBankTransfer::where('setting_bank_transfer_status', Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE)->get();
        $setting_site_bank_transfer_enable = $all_setting_bank_transfers->count() > 0 ? true : false;

        return response()->view('backend.user.subscription.edit',
            compact('subscription', 'all_plans', 'setting_site_paypal_enable', 'setting_site_razorpay_enable',
                    'setting_site_stripe_enable', 'all_setting_bank_transfers', 'setting_site_bank_transfer_enable',
                    'setting_site_payumoney_enable','active_plan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Subscription $subscription
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, Subscription $subscription)
    {
        $plan_id = $request->plan_id;

        // validate plan_id
        if(empty($plan_id))
        {
            \Session::flash('flash_message', __('alert.subscription-choose-plan'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.edit', $subscription->id);
        }

        // validate plan_id exist
        $plan_id_exist = Plan::where('id', $plan_id)
            ->where('plan_status', Plan::PLAN_ENABLED)
            ->whereIn('plan_type', [Plan::PLAN_TYPE_FREE, Plan::PLAN_TYPE_PAID])
            ->count();

        if($plan_id_exist == 0)
        {
            \Session::flash('flash_message', __('alert.plan-not-exist'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.subscriptions.edit', $subscription->id);
        }

        // TODO
        // start PayPal payment gateway process

        // update plan_id to the subscription record
        $subscription->plan_id = $plan_id;
        // update subscription_end_date
        $select_plan = Plan::find($plan_id);

        $today = new DateTime('now');
        if(!empty($subscription->subscription_end_date))
        {
            $today = new DateTime($subscription->subscription_end_date);
        }

        if($select_plan->plan_period == Plan::PLAN_MONTHLY)
        {
            $today->modify("+1 month");
            $subscription->subscription_end_date = $today->format("Y-m-d");
        }
        if($select_plan->plan_period == Plan::PLAN_QUARTERLY)
        {
            $today->modify("+3 month");
            $subscription->subscription_end_date = $today->format("Y-m-d");
        }
        if($select_plan->plan_period == Plan::PLAN_YEARLY)
        {
            $today->modify("+12 month");
            $subscription->subscription_end_date = $today->format("Y-m-d");
        }
        $subscription->save();

        \Session::flash('flash_message', __('alert.plan-switched'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.subscriptions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscription  $subscription
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Subscription $subscription)
    {
        return redirect()->route('user.subscriptions.index');
    }

    public function verifySubscription(Subscription $subscription)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.auth.verify-subscription', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        // $all_plans = Plan::where('id', $subscription->plan_id)->get();
        $all_plans = Plan::where('plan_type', Plan::PLAN_TYPE_PAID)
            ->where('plan_status', Plan::PLAN_ENABLED)
            ->get();

        // get payment gateway enable status
        $setting_site_paypal_enable = $settings->setting_site_paypal_enable;
        $setting_site_razorpay_enable = $settings->setting_site_razorpay_enable;
        $setting_site_stripe_enable = $settings->setting_site_stripe_enable;
        $setting_site_payumoney_enable = $settings->setting_site_payumoney_enable;

        $all_setting_bank_transfers = SettingBankTransfer::where('setting_bank_transfer_status', Setting::SITE_PAYMENT_BANK_TRANSFER_ENABLE)->get();
        $setting_site_bank_transfer_enable = $all_setting_bank_transfers->count() > 0 ? true : false;

        return response()->view('backend.user.subscription.verify',
            compact('subscription', 'all_plans', 'setting_site_paypal_enable', 'setting_site_razorpay_enable',
                    'setting_site_stripe_enable', 'all_setting_bank_transfers', 'setting_site_bank_transfer_enable',
                    'setting_site_payumoney_enable'));
    }

    public function freeSubscriptionActivate(Subscription $subscription)
    {
        $subscription->plan_id = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->first()->id;
        // $subscription->subscription_pay_method = null;
        // $subscription->subscription_stripe_customer_id = null;
        // $subscription->subscription_stripe_subscription_id = null;
        // $subscription->subscription_stripe_future_plan_id = null;
        $subscription->save();

        \Session::flash('flash_message', __('alert.free-subscription-activate'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.subscriptions.index');
    }

    public function subscriptionPay(Request $request){

        $plan = Plan::find($request->plan);

        $prevAuthSubscriptionData = Subscription::where('user_id',auth()->user()->id)->orderBy('id','DESC')->first();
        $subscription = $request->user()->newSubscription($request->plan, $plan->stripe_plan)->create($request->token);

        if($subscription){
            $stripe_plan = $subscription->stripe_price;
            $stripe_plan_created_at = $subscription->created_at;
            $subscription_id = $subscription->id;
    
            $subscriptionObj = Subscription::where('id',$subscription_id)->where('user_id',auth()->user()->id)->first();
    
            $planObj = Plan::where('stripe_plan',$stripe_plan)->first();
            $plan_period = $planObj->plan_period;
            $plan_id = $planObj->id;
    
            if($plan_period == Plan::PLAN_MONTHLY){
                $subscriptionObj->subscription_end_date = date('Y-m-d', strtotime($prevAuthSubscriptionData->subscription_end_date . ' +1 month'));
                
            }
            if($plan_period == Plan::PLAN_YEARLY){
                $subscriptionObj->subscription_end_date = date('Y-m-d', strtotime($prevAuthSubscriptionData->subscription_end_date . ' +12 month'));
            }
            
            $subscriptionObj->subscription_start_date = date('Y-m-d');
            $subscriptionObj->plan_id = $plan_id;
            $subscriptionObj->save();
    
            return redirect()->route('user.subscriptions.index')->with('success','Subscription purchase successfully!');

        }else{
            return redirect()->route('user.subscriptions.index')->with('error','Something went wrong.Please contact to Administrator!');
        }
        
    }

    public function upgradeSubscriptionPay(Request $request)
    {

        $auth_user = Auth::user();
        $plan = Plan::find($request->plan);
                
        if($plan)                
        {
            $current_subs = Subscription::where('user_id',$auth_user->id)->orderBy('id','DESC')->first();                
            $stripe = \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));                    
            $subscription = \Stripe\Subscription::retrieve($current_subs->stripe_id);
            // Change Plan                
            $charge = \Stripe\Subscription::update($current_subs->stripe_id, [                
                    'cancel_at_period_end' => false,                
                    'proration_behavior' => 'create_prorations',                
                    // 'billing_cycle_anchor' => 'unchanged',                
                    'items' => [                
                            [                
                            'id' => $subscription->items->data[0]->id,                
                            'price' => $plan->stripe_plan,                
                            ],                
                        ],                
                    ]);
    
            if($charge)
            {    
                // $plan_period = $plan->plan_period;
                //$plan_id = $plan->id;
        
                //     if($plan_period == Plan::PLAN_MONTHLY){
                //         $current_subs->subscription_end_date = date('Y-m-d', strtotime($current_subs->subscription_end_date . ' +1 month'));
                                
                //     }
                //     if($plan_period == Plan::PLAN_YEARLY){
                //         $current_subs->subscription_end_date = date('Y-m-d', strtotime($current_subs->subscription_end_date . ' +12 month'));
                //     } 
                
                $new_subscription = $auth_user->subscription()->orderBy('id','DESC')->first();
                if(isset($new_subscription->stripe_id)){
                    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET')); 
                    $subscriptionData = \Stripe\Subscription::retrieve($new_subscription->stripe_id);
                    $new_subscription->subscription_start_date = date('Y-m-d');
                    $new_subscription->plan_id = $plan->id;
                    $new_subs_end_date = date('Y-m-d H:i:s',$subscriptionData->current_period_end);
                    $new_subs_end_date_val = explode(" ",$new_subs_end_date)[0];
                    $new_subscription->subscription_end_date = $new_subs_end_date_val;
                    $new_subscription->save();
                    return redirect()->route('user.subscriptions.index')->with('success','Subscription purchase successfully!');
                }
            }else{
                        return redirect()->route('user.subscriptions.index')->with('error','Something went wrong.Please contact to Administrator!');
                }
        }else{
                return redirect()->route('user.subscriptions.index')->with('error','Something went wrong.Please contact to Administrator!');
            }                
        
    }
}
