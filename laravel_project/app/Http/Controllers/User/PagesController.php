<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Item;
use App\User;
use App\Setting;
use App\Plan;
use App\MediaDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Artesaos\SEOTools\Facades\SEOMeta;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Laravelista\Comments\Comment;

class PagesController extends Controller
{
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();

        $paid_subscription_days_left = $login_user->subscriptionDaysLeft();

        $subscription_details = $login_user->subscription()->where('user_id', $login_user->id)->first();
        $plan_details = Plan::where('id',$subscription_details->plan_id)->first();
        $plan_name = $plan_details->plan_name;

        $pending_item_count = $login_user->items()->where('item_status', Item::ITEM_SUBMITTED)->count();
        $item_count = $login_user->items()->count();
        $message_count = Message::where('user_id', $login_user->id)->count();
        $comment_count = Comment::where('commenter_id', $login_user->id)->count();

        $recent_threads = Thread::forUser($login_user->id)->latest('updated_at')->take(3)->get();
        $recent_comments = Comment::where('commenter_id', $login_user->id)->orderBy('created_at', 'DESC')->take(5)->get();

        $user_obj = new User();
        $progress_data = $user_obj->profileProgressData($request,$login_user);
        $data_points = array(
            array("y"=> ($progress_data['percentage']), "name"=> "Completed", "color"=> "#4D72DE"),
            array("y"=> (100- $progress_data['percentage']), "name"=> "Remaining", "color"=> "#D3D3D3")
        );

        return response()->view('backend.user.index',
            compact('login_user','pending_item_count', 'item_count', 'message_count', 'comment_count', 'progress_data', 'data_points',
            'recent_threads', 'recent_comments', 'paid_subscription_days_left','plan_name'));
    }

    public function profileProgressData(Request $request,$user_id){
        //$user_data = User::find($user_id);
        $user_obj = new User();
        $all_coaches = User::where('role_id', 2)->where('user_suspended', User::USER_NOT_SUSPENDED)->get();
        foreach($all_coaches as $user){
            $user_data = User::find($user->id);
            $progress_data[] = $user_obj->profileProgressData($request,$user_data);
        }

        echo "<pre>";
        print_r($progress_data);exit;
        
    }
}
