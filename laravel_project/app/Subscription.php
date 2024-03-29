<?php

namespace App;

use DateTime;
use DateInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subscription extends Model
{
    const PAY_METHOD_PAYPAL = 'PayPal';
    const PAY_METHOD_RAZORPAY = 'Razorpay';
    const PAY_METHOD_STRIPE = 'Stripe';
    const PAY_METHOD_BANK_TRANSFER = 'Bank Transfer';
    const PAY_METHOD_PAYUMONEY = 'PayUMoney';

    const PAID_SUBSCRIPTION_LEFT_DAYS = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'plan_id', 'subscription_start_date', 'subscription_end_date', 'subscription_max_featured_listing',
        'subscription_paypal_profile_id', 'subscription_razorpay_plan_id', 'subscription_razorpay_subscription_id',
        'subscription_pay_method', 'subscription_stripe_customer_id', 'subscription_stripe_subscription_id',
        'subscription_stripe_future_plan_id',
    ];

    /**
     * Get the plan that owns the subscription.
     */
    public function plan()
    {
        return $this->belongsTo('App\Plan');
    }

    /**
     * Get the user that owns the subscription.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }

    /**
     * @param int $plan_id
     * @param int $subscription_id
     * @param int $user_id
     * @return bool
     */
    public function planSubscriptionValidation($plan_id, $subscription_id, $user_id)
    {
        $validation = true;
        $plan_id_exist = Plan::where('id', $plan_id)
            ->where('plan_type', Plan::PLAN_TYPE_PAID)
            ->where('plan_status', Plan::PLAN_ENABLED)
            ->count();
        if($plan_id_exist == 0)
        {
            $validation =  false;
        }
        $subscription_id_exist = Subscription::where('id', $subscription_id)
            ->where('user_id', $user_id)
            ->count();
        if($subscription_id_exist == 0)
        {
            $validation =  false;
        }

        return $validation;
    }

    public function getPaidUserIds()
    {
        $today = new DateTime('now');
        // $today = $today->format("Y-m-d");
        $today = $today->add(new DateInterval('P14D'))->format("Y-m-d");

        if(Auth::check()){           
            if(Auth::user()->role_flag == 1){
                $role_flag = array(0,1);
            }else{                    
                $role_flag = array(0);
            }
        }else{                    
            $role_flag = array(0);
        }

        // get paid users id array
        $paid_user_ids = array();

        $paid_user_ids = Subscription::join('users as u', 'subscriptions.user_id', '=', 'u.id')
            ->whereIn('u.role_flag',$role_flag)
            ->where('u.email_verified_at', '!=', null)
            ->where('u.user_suspended', User::USER_NOT_SUSPENDED)
            ->where('subscriptions.subscription_end_date', '!=', null)
            ->where('subscriptions.subscription_end_date','>=', $today)
            ->pluck('u.id')
            ->toArray();

        return $paid_user_ids;
    }

    public function getFreeUserIds()
    {
        $today = new DateTime('now');
        // $today = $today->format("Y-m-d");
        $today = $today->add(new DateInterval('P14D'))->format("Y-m-d");

        $free_user_ids = array();

        $free_user_ids = Subscription::join('users as u', 'subscriptions.user_id', '=', 'u.id')
            ->where('u.email_verified_at', '!=', null)
            ->where('u.user_suspended', User::USER_NOT_SUSPENDED)
            ->where('subscriptions.subscription_end_date', null)
            ->orWhere(function($query) use ($today) {
                $query->where('subscriptions.subscription_end_date', '!=', null)
                    ->where('subscriptions.subscription_end_date','<=', $today);
            })
            ->pluck('u.id')
            ->toArray();

        return $free_user_ids;
    }

    public function getActiveUserIds()
    {
        $active_user_ids = array();

        if(Auth::check()){           
            if(Auth::user()->role_flag == 1){
                $role_flag = array(0,1);
            }else{                    
                $role_flag = array(0);
            }
        }else{                    
            $role_flag = array(0);
        }

        $active_user_ids = Subscription::join('users as u', 'subscriptions.user_id', '=', 'u.id')
            ->whereIn('u.role_flag',$role_flag)
            ->where('u.email_verified_at', '!=', null)
            ->where('u.user_suspended', User::USER_NOT_SUSPENDED)
            ->pluck('u.id')
            ->toArray();

        return $active_user_ids;
    }

    public function getActiveCoachUserIds()
    {
        $active_user_ids = array();

        $active_user_ids = Subscription::join('users as u', 'subscriptions.user_id', '=', 'u.id')
            ->where('u.email_verified_at', '!=', null)
            ->where('u.role_id', Role::COACH_ROLE_ID)
            ->where('u.user_suspended', User::USER_NOT_SUSPENDED)
            ->pluck('u.id')
            ->toArray();

        return $active_user_ids;
    }
}
