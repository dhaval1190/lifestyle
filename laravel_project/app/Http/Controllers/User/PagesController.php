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
use App\ProfileVisit;
use App\MediaDetailsVisits;
use App\ItemVisit;
use App\UserNotification;


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
        $profile_visit = ProfileVisit::where('user_id', $login_user->id)->get();
        
        $currentMonthlyVisits = $profile_visit->whereBetween('created_at', [
            today()->startOfMonth()->startOfDay()->toDateTimeString(),
            today()->endOfMonth()->endOfDay()->toDateTimeString(),
        ]);

        $TodayVisits = $profile_visit->whereBetween('created_at', [
            today()->startOfDay()->toDateTimeString(),
            today()->endOfDay()->toDateTimeString(),
        ]); 

        $All_visit_count = $profile_visit->count();
        $visit_count = $currentMonthlyVisits->count();
        $Today_Visits_count = $TodayVisits->count();
        // print_r($visit_count);exit;

        $media_deatils_visit = MediaDetailsVisits::where('user_id', $login_user->id)->where('media_type','video')->groupBy('media_detail_id')->get();
        $podcast_deatils_visit = MediaDetailsVisits::where('user_id', $login_user->id)->where('media_type','podcast')->groupBy('media_detail_id')->get();
        $ebook_deatils_visit = MediaDetailsVisits::where('user_id', $login_user->id)->where('media_type','ebook')->groupBy('media_detail_id')->get();
        $youtube_deatils_visit = MediaDetailsVisits::where('user_id', $login_user->id)->where('media_type','youtube')->groupBy('user_id')->get();
        $PodcastImage= array();
        $media= array();
        $Articledetail= array();
        $Youtube= array();
        $Ebooks= array();

        if(isset($ebook_deatils_visit) && !empty($ebook_deatils_visit)){
            foreach($ebook_deatils_visit as $ebookdetail){
                $Ebooks[$ebookdetail->media_detail_id]['daily'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
                ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$ebookdetail->media_detail_id)->whereBetween('media_details_visits.created_at', [
                    today()->startOfDay()->toDateTimeString(),
                    today()->endOfDay()->toDateTimeString(),
                ])->first();
                $Ebooks[$ebookdetail->media_detail_id]['monthly'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
                  ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$ebookdetail->media_detail_id)->whereBetween('media_details_visits.created_at', [
                    today()->startOfMonth()->startOfDay()->toDateTimeString(),
                    today()->endOfMonth()->endOfDay()->toDateTimeString(),
                    ])->first();
                }
            }
        
        if(isset($youtube_deatils_visit) && !empty($youtube_deatils_visit)){
            foreach($youtube_deatils_visit as $youtubedetail){
            //print_r($youtube_deatils_visit);exit;
            $Youtube[$youtubedetail->user_id]['daily'] = MediaDetailsVisits::join('users', 'users.id', '=', 'media_details_visits.user_id')
            ->select('media_details_visits.*','media_details_visits.user_id',DB::raw('COUNT(media_details_visits.user_id) as totalcount'))->where('media_details_visits.user_id',$youtubedetail->user_id)->where('media_details_visits.media_type','youtube')->whereBetween('media_details_visits.created_at', [
                today()->startOfDay()->toDateTimeString(),
                today()->endOfDay()->toDateTimeString(),
            ])->first();
            $Youtube[$youtubedetail->user_id]['monthly'] = MediaDetailsVisits::join('users', 'users.id', '=', 'media_details_visits.user_id')
                ->select('media_details_visits.*','media_details_visits.user_id',DB::raw('COUNT(media_details_visits.user_id) as totalcount'))->where('media_details_visits.user_id',$youtubedetail->user_id)->where('media_details_visits.media_type','youtube')->whereBetween('media_details_visits.created_at', [
                today()->startOfMonth()->startOfDay()->toDateTimeString(),
                today()->endOfMonth()->endOfDay()->toDateTimeString(),
                ])->first();
            }
        }
        
            $YoutubecurrentMonthlyVisits = $youtube_deatils_visit->whereBetween('created_at', [
                today()->startOfMonth()->startOfDay()->toDateTimeString(),
                today()->endOfMonth()->endOfDay()->toDateTimeString(),
            ]);
            
            $TodayYoutubeVisits = $youtube_deatils_visit->whereBetween('created_at', [
                today()->startOfDay()->toDateTimeString(),
                today()->endOfDay()->toDateTimeString(),
            ]); 
            $Youtubevisit_count = $YoutubecurrentMonthlyVisits->count();
            $Today_YoutubeVisits_count = $TodayYoutubeVisits->count();

        if(isset($media_deatils_visit) && !empty($media_deatils_visit)){
        foreach($media_deatils_visit as $mediadetail){
            $media[$mediadetail->media_detail_id]['daily'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
            ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$mediadetail->media_detail_id)->whereBetween('media_details_visits.created_at', [
                today()->startOfDay()->toDateTimeString(),
                today()->endOfDay()->toDateTimeString(),
            ])->first();
            $media[$mediadetail->media_detail_id]['monthly'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
              ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$mediadetail->media_detail_id)->whereBetween('media_details_visits.created_at', [
                today()->startOfMonth()->startOfDay()->toDateTimeString(),
                today()->endOfMonth()->endOfDay()->toDateTimeString(),
                ])->first();
            }
        }
        $MediacurrentMonthlyVisits = $media_deatils_visit->whereBetween('created_at', [
            today()->startOfMonth()->startOfDay()->toDateTimeString(),
            today()->endOfMonth()->endOfDay()->toDateTimeString(),
        ]);
        
        $TodayMediaVisits = $media_deatils_visit->whereBetween('created_at', [
            today()->startOfDay()->toDateTimeString(),
            today()->endOfDay()->toDateTimeString(),
        ]); 
        $Mediavisit_count = $MediacurrentMonthlyVisits->count();
        $Today_MedaidetailsVisits_count = $TodayMediaVisits->count();        
        
        if(isset($podcast_deatils_visit) && !empty($podcast_deatils_visit)){
        foreach($podcast_deatils_visit as $detail){
            $PodcastImage[$detail->media_detail_id]['daily'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
            ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$detail->media_detail_id)->whereBetween('media_details_visits.created_at', [
                today()->startOfDay()->toDateTimeString(),
                today()->endOfDay()->toDateTimeString(),
            ])->first();
            $PodcastImage[$detail->media_detail_id]['monthly'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
              ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$detail->media_detail_id)->whereBetween('media_details_visits.created_at', [
                today()->startOfMonth()->startOfDay()->toDateTimeString(),
                today()->endOfMonth()->endOfDay()->toDateTimeString(),
                ])->first();
            }
        }
        $PodcastcurrentMonthlyVisits = $podcast_deatils_visit->whereBetween('created_at', [
            today()->startOfMonth()->startOfDay()->toDateTimeString(),
            today()->endOfMonth()->endOfDay()->toDateTimeString(),
        ])->unique('media_details_visits.media_detail_id');

        $PodcastTodayVisits = $podcast_deatils_visit->whereBetween('created_at', [
            today()->startOfDay()->toDateTimeString(),
            today()->endOfDay()->toDateTimeString(),
        ]);
        $item = Item::where('item_status', Item::ITEM_PUBLISHED)->first();

        $MonthlyPodcastvisit_Count = $PodcastcurrentMonthlyVisits->count();
        $Today_Podcastvisits_Count = $PodcastTodayVisits->count();   

        $Ariclevisit_deatils_visit = ItemVisit::join('items', 'items.id', '=', 'items_visits.item_id')
        ->select('items.*','items_visits.item_id',DB::raw('COUNT(items_visits.item_id) as totalcount'))->where('user_id', $login_user->id)->groupBy('item_id')->get();
       
        if(isset($Ariclevisit_deatils_visit) && !empty($Ariclevisit_deatils_visit)){
            foreach($Ariclevisit_deatils_visit as $articledetail){
                $Articledetail[$articledetail->item_id]['daily'] = ItemVisit::join('items', 'items.id', '=', 'items_visits.item_id')
                ->select('items.*','items_visits.item_id',DB::raw('COUNT(items_visits.item_id) as totalcount'))->where('items_visits.item_id',$articledetail->item_id)->whereBetween('items_visits.created_at', [
                    today()->startOfDay()->toDateTimeString(),
                    today()->endOfDay()->toDateTimeString(),
                ])->first();
                $Articledetail[$articledetail->item_id]['monthly'] = ItemVisit::join('items', 'items.id', '=', 'items_visits.item_id')
                ->select('items.*','items_visits.item_id',DB::raw('COUNT(items_visits.item_id) as totalcount'))->where('items_visits.item_id',$articledetail->item_id)->whereBetween('items_visits.created_at', [
                    today()->startOfMonth()->startOfDay()->toDateTimeString(),
                    today()->endOfMonth()->endOfDay()->toDateTimeString(),
                    ])->first();
                }
            }
        $visits = ItemVisit::where('item_id', $item->id)->get();                    
                    $ArticlecurrentMonthlyVisits = $visits->whereBetween('created_at', [
                        today()->startOfMonth()->startOfDay()->toDateTimeString(),
                        today()->endOfMonth()->endOfDay()->toDateTimeString(),
                    ]);
                    $ArticlecurrentTodayVisits = $visits->whereBetween('created_at', [
                        today()->startOfDay()->toDateTimeString(),
                        today()->endOfDay()->toDateTimeString(),
                    ]);
        $MonthlyAriclevisit_count = $ArticlecurrentMonthlyVisits->count();
        $TodayAriclevisit_count = $ArticlecurrentTodayVisits->count();

        $notifications = UserNotification::where('user_id',$login_user->id)->get();      
       
        
        // $media_detail_id = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
        // ->select('media_details_visits.*')->groupBy('media_detail_id')->get();
        return response()->view('backend.user.index',
            compact('login_user','pending_item_count', 'item_count', 'message_count', 'comment_count', 'progress_data', 'data_points',
            'recent_threads', 'recent_comments', 'paid_subscription_days_left','plan_name','visit_count','Today_Visits_count','Mediavisit_count','TodayMediaVisits','Today_MedaidetailsVisits_count','PodcastTodayVisits','TodayVisits','Today_Podcastvisits_Count','PodcastcurrentMonthlyVisits','MonthlyPodcastvisit_Count','PodcastImage','media','MonthlyAriclevisit_count','TodayAriclevisit_count','Articledetail','Youtube','notifications','All_visit_count','Ebooks'));
    }

    public function readNotification(Request $request){
        return response()->json(['data'=>$request->all()]);
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
