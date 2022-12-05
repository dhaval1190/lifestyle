<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Item;
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

        $progress_data = $this->profileProgressData($request);
        $data_points = array(
            array("y"=> ($progress_data['percentage']), "name"=> "Completed", "color"=> "#4D72DE"),
            array("y"=> (100- $progress_data['percentage']), "name"=> "Remaining", "color"=> "#D3D3D3")
        );

        return response()->view('backend.user.index',
            compact('login_user','pending_item_count', 'item_count', 'message_count', 'comment_count', 'progress_data', 'data_points',
            'recent_threads', 'recent_comments', 'paid_subscription_days_left','plan_name'));
    }

    public function profileProgressData(Request $request)
    {
        $login_user         = Auth::user();
        $data               = array();
        $data['profile']    = '';
        $data['percentage'] = 0;

        if($login_user->categories()->count() > 0 && !empty($login_user['user_image']) && !empty($login_user['name']) && !empty($login_user['company_name']) && !empty($login_user['phone']) && !empty($login_user['email']) && !empty($login_user['hourly_rate_type']) && !empty($login_user['preferred_pronouns']) && !empty($login_user['working_type']) && !empty($login_user['state_id']) && !empty($login_user['post_code']) && !empty($login_user['country_id']) && !empty($login_user['city_id']) && !empty($login_user['address']) && !empty($login_user['awards']) && !empty($login_user['certifications']) && !empty($login_user['experience_year'])){
            $data['basic_profile']  = true;
            $data['profile']        = 'Basic';
            $data['percentage']     = 20;
            if(!empty($login_user['website']) && ($login_user['instagram'] || $login_user['facebook'] || $login_user['linkedin'] || $login_user['youtube']) && ($login_user['instagram'] || $login_user['facebook'] || $login_user['linkedin'] || $login_user['youtube'])){
                $data['social_profile'] = true;
                $data['profile']        = 'Social';
                $data['percentage']     = 30;
                $article_count          = Item::where('user_id', $login_user->id)->count();
                $video_media_count      = MediaDetail::where('user_id', $login_user->id)->where('media_type', 'video')->count();
                $podcast_media_count    = MediaDetail::where('user_id', $login_user->id)->where('media_type', 'podcast')->count();
                $ebook_media_count      = MediaDetail::where('user_id', $login_user->id)->where('media_type', 'ebook')->count();
                $blog_count             = \Canvas\Post::where('user_id', $login_user->id)->count();
                if($article_count >= 10){
                    $data['bronze_profile'] = true;
                    $data['profile']        = 'Bronze';
                    $data['percentage']     = 50;
                    if($article_count >= 20 && $video_media_count >= 1 && $podcast_media_count >= 1 && $ebook_media_count >= 1 && $blog_count >= 1){
                        $data['silver_profile'] = true;
                        $data['profile']        = 'Silver';
                        $data['percentage']     = 60;
                        $review_count = DB::table('reviews')->where('author_id', $login_user->id)->count();
                        if($review_count >= 3){
                            $data['gold_profile']   = true;
                            $data['profile']        = 'Gold';
                            $data['percentage']     = 70;
                            if($article_count >= 30 && $review_count >= 7){
                                $data['platinum_profile']   = true;
                                $data['profile']            = 'Platinum';
                                $data['percentage']         = 90;
                            }else{
                                $data['platinum_profile'] = false;
                            }
                        }else{
                            $data['gold_profile'] = false;
                        }
                    }else{
                        $data['silver_profile'] = false;
                    }
                }else{
                    $data['bronze_profile'] = false;
                }
            }else{
                $data['Social_profile'] = false;
            }
        }else{
            $data['basic_profile'] = false;
        }
        return $data;
    }
}
