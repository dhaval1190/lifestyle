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
use App\ProfileReviews;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;


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
        // $message_count = Message::where('user_id', $login_user->id)->count();
        // $message_count = Message::where('user_id', $login_user->id)->distinct()->count('thread_id');
        $message_count = Thread::forUser($login_user->id)->latest('updated_at')->count();
        // dd($message_count);
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
        $youtube_deatils_visit = MediaDetailsVisits::where('user_id', $login_user->id)->where('media_type','youtube')->where('is_status',0)->groupBy('user_id')->get();
        
        $all_media_deatils_visit = MediaDetail::where('user_id', $login_user->id)->where('media_type','video')->groupBy('id')->get();
        $all_podcast_deatils_visit = MediaDetail::where('user_id', $login_user->id)->where('media_type','podcast')->groupBy('id')->get();
        $all_Ebook_Details = MediaDetail::where('user_id', $login_user->id)->where('media_type','ebook')->groupBy('id')->get();
        $all_youtube_deatils_visit = MediaDetail::where('user_id', $login_user->id)->where('media_type','youtube')->where('is_status',0)->groupBy('id')->get();

        $PodcastImage= array();
        $media= array();
        $Articledetail= array();
        $Youtube= array();
        $Ebooks= array();

        $AllEbooks = array();  
        $AllMedia = array();       
        $AllYoutube = array();       
        $AllPodcast = array();       


        if(isset($ebook_deatils_visit) && !empty($ebook_deatils_visit)){
            foreach($ebook_deatils_visit as $ebookdetail){
                $Ebooks[$ebookdetail->media_detail_id]['daily'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
                ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$ebookdetail->media_detail_id)->whereBetween('media_details_visits.created_at', [
                    today()->startOfDay()->toDateTimeString(),
                    today()->endOfDay()->toDateTimeString(),
                ])->first();
                $Ebooks[$ebookdetail->media_detail_id]['monthly'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
                  ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$ebookdetail->media_detail_id)
                //   ->whereBetween('media_details_visits.created_at', [
                //     today()->startOfMonth()->startOfDay()->toDateTimeString(),
                //     today()->endOfMonth()->endOfDay()->toDateTimeString(),
                //     ])
                    ->first();
                }
            }
            
        if(isset($all_Ebook_Details) && !empty($all_Ebook_Details)){
            foreach($all_Ebook_Details as $Ebookdetail){
                $AllEbooks[$Ebookdetail->id]['daily'] = MediaDetail::Leftjoin('media_details_visits', 'media_details_visits.media_detail_id', '=', 'media_details.id')
                ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details.id',$Ebookdetail->id)
                ->whereBetween('media_details_visits.created_at', [
                    today()->startOfDay()->toDateTimeString(),
                    today()->endOfDay()->toDateTimeString(),
                ])
                ->first();
                $AllEbooks[$Ebookdetail->id]['monthly'] = MediaDetail::Leftjoin('media_details_visits', 'media_details_visits.media_detail_id', '=', 'media_details.id')
                    ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details.id',$Ebookdetail->id)
                //    ->whereBetween('media_details.created_at', [
                //     today()->startOfMonth()->startOfDay()->toDateTimeString(),
                //     today()->endOfMonth()->endOfDay()->toDateTimeString(),
                //     ])
                    ->first();
                }
            }
            if(isset($all_youtube_deatils_visit) && !empty($all_youtube_deatils_visit)){
                foreach($all_youtube_deatils_visit as $allyoutubedetail){                       
                $query = MediaDetailsVisits::where('media_detail_id',$allyoutubedetail->id)->where('user_id',$allyoutubedetail->user_id)->where('is_status',0)->first();
                if(isset($query) && !empty($query)){

                $AllYoutube[$allyoutubedetail->id]['daily'] = MediaDetail::Leftjoin('media_details_visits', 'media_details_visits.media_detail_id', '=', 'media_details.id')
                ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details.id',$allyoutubedetail->id)
                ->whereBetween('media_details_visits.created_at', [
                    today()->startOfDay()->toDateTimeString(),
                    today()->endOfDay()->toDateTimeString(),
                ])
                ->first();
                $AllYoutube[$allyoutubedetail->id]['monthly'] = MediaDetail::Leftjoin('media_details_visits', 'media_details_visits.media_detail_id', '=', 'media_details.id')
                ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details.id',$allyoutubedetail->id)
                //    ->whereBetween('media_details.created_at', [
                //     today()->startOfMonth()->startOfDay()->toDateTimeString(),
                //     today()->endOfMonth()->endOfDay()->toDateTimeString(),
                //     ])
                    ->first();
                }else{
                $AllYoutube[$allyoutubedetail->id]['monthly']['media_url']= $allyoutubedetail->media_url;
                $AllYoutube[$allyoutubedetail->id]['daily']['media_url']= $allyoutubedetail->media_url;
                $AllYoutube[$allyoutubedetail->id]['monthly']['totalcount']=0;
                $AllYoutube[$allyoutubedetail->id]['daily']['totalcount']=0;


                }
                }
            }
            //print_r($AllYoutube);exit;
        
            if(isset($youtube_deatils_visit) && !empty($youtube_deatils_visit)){
                foreach($youtube_deatils_visit as $youtubedetail){
                //print_r($youtube_deatils_visit);exit;
                $Youtube[$youtubedetail->user_id]['daily'] = MediaDetailsVisits::join('users', 'users.id', '=', 'media_details_visits.user_id')
                ->select('media_details_visits.*','media_details_visits.user_id',DB::raw('COUNT(media_details_visits.user_id) as totalcount'))->where('media_details_visits.user_id',$youtubedetail->user_id)->where('media_details_visits.media_type','youtube')->where('is_status',0)->whereBetween('media_details_visits.created_at', [
                    today()->startOfDay()->toDateTimeString(),
                    today()->endOfDay()->toDateTimeString(),
                ])->first();
                $Youtube[$youtubedetail->user_id]['monthly'] = MediaDetailsVisits::join('users', 'users.id', '=', 'media_details_visits.user_id')
                    ->select('media_details_visits.*','media_details_visits.user_id',DB::raw('COUNT(media_details_visits.user_id) as totalcount'))->where('media_details_visits.user_id',$youtubedetail->user_id)->where('media_details_visits.media_type','youtube')->where('is_status',0)
                    // ->whereBetween('media_details_visits.created_at', [
                    // today()->startOfMonth()->startOfDay()->toDateTimeString(),
                    // today()->endOfMonth()->endOfDay()->toDateTimeString(),
                    // ])
                    ->first();
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


            if(isset($all_media_deatils_visit) && !empty($all_media_deatils_visit)){
                foreach($all_media_deatils_visit as $allmediadetail){

                    $AllMedia[$allmediadetail->id]['daily'] = MediaDetail::Leftjoin('media_details_visits', 'media_details_visits.media_detail_id', '=', 'media_details.id')
                    ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details.id',$allmediadetail->id)
                    ->whereBetween('media_details_visits.created_at', [
                        today()->startOfDay()->toDateTimeString(),
                        today()->endOfDay()->toDateTimeString(),
                    ])
                    ->first();
                    $AllMedia[$allmediadetail->id]['monthly'] = MediaDetail::Leftjoin('media_details_visits', 'media_details_visits.media_detail_id', '=', 'media_details.id')
                    ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details.id',$allmediadetail->id)
                    // ->whereBetween('media_details.created_at', [
                    //     today()->startOfDay()->toDateTimeString(),
                    //     today()->endOfDay()->toDateTimeString(),
                    // ])
                    ->first();
                    }
                }
               //print_r($AllMedia[$allmediadetail->id]['daily']);exit;
            if(isset($media_deatils_visit) && !empty($media_deatils_visit)){
            foreach($media_deatils_visit as $mediadetail){
                $media[$mediadetail->media_detail_id]['daily'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
                ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$mediadetail->media_detail_id)->whereBetween('media_details_visits.created_at', [
                    today()->startOfDay()->toDateTimeString(),
                    today()->endOfDay()->toDateTimeString(),
                ])->first();
                $media[$mediadetail->media_detail_id]['monthly'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
                ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$mediadetail->media_detail_id)
                //   ->whereBetween('media_details_visits.created_at', [
                //     today()->startOfMonth()->startOfDay()->toDateTimeString(),
                //     today()->endOfMonth()->endOfDay()->toDateTimeString(),
                //     ])
                    ->first();
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
            
            if(isset($all_podcast_deatils_visit) && !empty($all_podcast_deatils_visit)){
                foreach($all_podcast_deatils_visit as $alldetail){
                    $AllPodcast[$alldetail->id]['daily'] = MediaDetail::Leftjoin('media_details_visits', 'media_details_visits.media_detail_id', '=', 'media_details.id')
                    ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details.id',$alldetail->id)
                    ->whereBetween('media_details_visits.created_at', [
                        today()->startOfDay()->toDateTimeString(),
                        today()->endOfDay()->toDateTimeString(),
                    ])
                    ->first();
                    $AllPodcast[$alldetail->id]['monthly'] = MediaDetail::Leftjoin('media_details_visits', 'media_details_visits.media_detail_id', '=', 'media_details.id')
                    ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details.id',$alldetail->id)
                    // ->whereBetween('media_details.created_at', [
                    //     today()->startOfDay()->toDateTimeString(),
                    //     today()->endOfDay()->toDateTimeString(),
                    // ])
                    ->first();
                    }
                }
            
            if(isset($podcast_deatils_visit) && !empty($podcast_deatils_visit)){
            foreach($podcast_deatils_visit as $detail){
                $PodcastImage[$detail->media_detail_id]['daily'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
                ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$detail->media_detail_id)->whereBetween('media_details_visits.created_at', [
                    today()->startOfDay()->toDateTimeString(),
                    today()->endOfDay()->toDateTimeString(),
                ])->first();
                $PodcastImage[$detail->media_detail_id]['monthly'] = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
                ->select('media_details.*','media_details_visits.media_detail_id',DB::raw('COUNT(media_details_visits.media_detail_id) as totalcount'))->where('media_details_visits.media_detail_id',$detail->media_detail_id)
                //   ->whereBetween('media_details_visits.created_at', [
                //     today()->startOfMonth()->startOfDay()->toDateTimeString(),
                //     today()->endOfMonth()->endOfDay()->toDateTimeString(),
                //     ])
                    ->first();
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

            // $Ariclevisit_deatils_visit = ItemVisit::join('items', 'items.id', '=', 'items_visits.item_id')
            // ->select('items.*','items_visits.item_id',DB::raw('COUNT(items_visits.item_id) as totalcount'))->where('user_id', $login_user->id)->groupBy('item_id')->get();
        
            // if(isset($Ariclevisit_deatils_visit) && !empty($Ariclevisit_deatils_visit)){
            //     foreach($Ariclevisit_deatils_visit as $articledetail){
            //         $Articledetail[$articledetail->item_id]['daily'] = ItemVisit::join('items', 'items.id', '=', 'items_visits.item_id')
            //         ->select('items.*','items_visits.item_id',DB::raw('COUNT(items_visits.item_id) as totalcount'))->where('items_visits.item_id',$articledetail->item_id)->whereBetween('items_visits.created_at', [
            //             today()->startOfDay()->toDateTimeString(),
            //             today()->endOfDay()->toDateTimeString(),
            //         ])->first();
            //         $Articledetail[$articledetail->item_id]['monthly'] = ItemVisit::join('items', 'items.id', '=', 'items_visits.item_id')
            //         ->select('items.*','items_visits.item_id',DB::raw('COUNT(items_visits.item_id) as totalcount'))->where('items_visits.item_id',$articledetail->item_id)
            //         // ->whereBetween('items_visits.created_at', [
            //         //     today()->startOfMonth()->startOfDay()->toDateTimeString(),
            //         //     today()->endOfMonth()->endOfDay()->toDateTimeString(),
            //         //     ])
            //             ->first();
            //         }
            //     }

            $Ariclevisit_deatils_visit = Item::where('user_id', $login_user->id)->groupBy('id')->get();

            // $Ariclevisit_deatils_visit = Item::join('items_visits', 'items_visits.item_id', '=', 'items.id')
            // ->select('items.*','items_visits.item_id',DB::raw('COUNT(items_visits.item_id) as totalcount'))->where('user_id', $login_user->id)->groupBy('id')->get();
            if(isset($Ariclevisit_deatils_visit) && !empty($Ariclevisit_deatils_visit)){
                foreach($Ariclevisit_deatils_visit as $article_detail){

                    $Articledetail[$article_detail->id]['daily'] = Item::Leftjoin('items_visits', 'items_visits.item_id', '=', 'items.id')
                    ->select('items.*','items_visits.item_id',DB::raw('COUNT(items_visits.item_id) as totalcount'))->where('items.id',$article_detail->id)->whereBetween('items_visits.created_at', [
                        today()->startOfDay()->toDateTimeString(),
                        today()->endOfDay()->toDateTimeString(),
                    ])->first();
                    

                    $Articledetail[$article_detail->id]['monthly'] = Item::Leftjoin('items_visits', 'items_visits.item_id', '=', 'items.id')
                    ->select('items.*','items_visits.item_id',DB::raw('COUNT(items_visits.item_id) as totalcount'))->where('items.id',$article_detail->id)
                    // ->whereBetween('items_visits.created_at', [
                    //     today()->startOfMonth()->startOfDay()->toDateTimeString(),
                    //     today()->endOfMonth()->endOfDay()->toDateTimeString(),
                    //     ])
                        ->first();
                    }
                }
                // print_r($Articledetail);exit;

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
            $notifications = UserNotification::join('users', 'users.id', '=', 'notification.visitor_id')->select('notification.*','name','user_image')->where('user_id',$login_user->id)->where('is_read',0)->get();     
        // $media_detail_id = MediaDetailsVisits::join('media_details', 'media_details.id', '=', 'media_details_visits.media_detail_id')
        // ->select('media_details_visits.*')->groupBy('media_detail_id')->get();
        return response()->view('backend.user.index',
            compact('login_user','AllEbooks','AllMedia','AllPodcast','AllYoutube','pending_item_count', 'item_count', 'message_count', 'comment_count', 'progress_data', 'data_points',
            'recent_threads', 'recent_comments', 'paid_subscription_days_left','plan_name','visit_count','Today_Visits_count','Mediavisit_count','TodayMediaVisits','Today_MedaidetailsVisits_count','PodcastTodayVisits','TodayVisits','Today_Podcastvisits_Count','PodcastcurrentMonthlyVisits','MonthlyPodcastvisit_Count','PodcastImage','media','MonthlyAriclevisit_count','TodayAriclevisit_count','Articledetail','Youtube','notifications','All_visit_count','Ebooks'));
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
    public function profileReviewsStore(Request $request)
    {
        $login_user = Auth::user();         
        $settings = app('site_global_settings');    
        // $profile = ProfileReviews::where('reviewrateable_id',$request->id)
        //     //->where('country_id', $site_prefer_country_id)
        //     ->where('author_id', '!=',Auth::user()->id)
        //     ->first();     
           $review_count = new Item();

           if($review_count)
           {
               if($review_count->profilereviewedByUser(Auth::user()->id,$request->id))
               {
                   \Session::flash('flash_message', __('review.alert.cannot-post-more-one-review'));
                   \Session::flash('flash_type', 'danger');
   
                   return redirect()->route('page.profile',encrypt($request->id));
               }
               else
               {
                $validator = Validator::make($request->all(),[
                    'rating' => 'required|numeric|max:5',   
                    'body' => 'required|max:65535',
                   
                ],[
                    'rating.required' => 'Rating field is required',
                    'body.required' => 'Description is required',
                   
                ]);
                if($validator->passes()){
                
                $rating_body = $request->body;
                $overall_rating = $request->rating;
                $customer_service_rating = $request->customer_service_rating;   
                $profile_user_id = $request->id;      
                $recommend = $request->recommend == 1 ? Item::ITEM_REVIEW_RECOMMEND_YES : Item::ITEM_REVIEW_RECOMMEND_NO;
                $approved = $login_user->isAdmin() ? true : false;

                $new_item = new ProfileReviews([
                    'rating' => $overall_rating,
                    'body' => $rating_body,
                    'customer_service_rating' => $customer_service_rating,                    
                    'recommend' => $recommend,
                    'approved' => $approved, // This is optional and defaults to false
                    'author_id' => $login_user->id,
                    'reviewrateable_id'=>$profile_user_id
                ], $login_user);
                $new_item->save();
                return response()->json(['status'=>"success",'msg'=>'Review Add SuccessFull']);
                
                // $review_count = new Item();
                // $review_count->syncProfileverageRating($profile_user_id);

                \Session::flash('flash_message', __('review.alert.review-posted-success'));
                \Session::flash('flash_type', 'success');
            }else{
                return response()->json(['status'=>"error",'msg'=>$validator->errors()]);
                }
                return redirect()->route('page.profile',encrypt($request->id));
            }
        }
    }
    public function profileReviewsIndex(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('review.seo.manage-reviews', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $reviews_type = $request->reviews_type;

        if(empty($reviews_type) || $reviews_type == 'all')
        {
            $reviews = DB::table('profile_reviews')
                ->where('author_id', \Illuminate\Support\Facades\Auth::user()->id)
                ->orderBy('updated_at', 'desc')
                ->get();
                //print_r($reviews);exit;
        }
        else
        {
            if($reviews_type == 'pending')
            {
                $reviews = DB::table('profile_reviews')
                    ->where('author_id', \Illuminate\Support\Facades\Auth::user()->id)
                    ->where('approved', Item::ITEM_REVIEW_PENDING)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }

            if($reviews_type == 'approved')
            {
                $reviews = DB::table('profile_reviews')
                    ->where('author_id', \Illuminate\Support\Facades\Auth::user()->id)
                    ->where('approved', Item::ITEM_REVIEW_APPROVED)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }
        }

        return response()->view('backend.user.profile_review.profile_reviews',
            compact('reviews_type', 'reviews'));
    }
    public function profileReviewsEdit(Request $request)
    {
        $settings = app('site_global_settings');
        //$site_prefer_country_id = app('site_prefer_country_id');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('review.seo.edit-a-review', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */
          $profile_Reviews = ProfileReviews::where('id',$request->id)->get();
                return response()->view('backend.user.profile_review.profile_reviews_edit',compact('profile_Reviews'));  
      
    }
    public function profileReviewsUpdate(Request $request)
    {
        $login_user = Auth::user();         
        $settings = app('site_global_settings');
      
            $validator = Validator::make($request->all(),[
                'rating' => 'required|numeric|max:5',   
                'body' => 'required|max:65535',
               
            ],[
                'rating.required' => 'Rating field is required',
                'body.required' => 'Description is required',
               
            ]);
            if($validator->passes()){

            $data = ProfileReviews::find($request->id);
            
            $data->body = $request->body;
            $data->rating = $request->rating;           
            $data->recommend = $request->recommend == 1 ? Item::ITEM_REVIEW_RECOMMEND_YES : Item::ITEM_REVIEW_RECOMMEND_NO;
            
            $res = $data->save();
            return response()->json(['status'=>"success",'msg'=>'Review Update SuccessFull']);
            $review_count = new Item();
            $review_count->syncProfileverageRating($data->reviewrateable_id);

           
            \Session::flash('flash_message', __('review.alert.review-updated-success'));
            \Session::flash('flash_type', 'success');
        }else{
            return response()->json(['status'=>"error",'msg'=>$validator->errors()]);
            }

            return redirect()->route('user.page.reviews.index');
            
    }
    public function profileReviewsDestroy(Request $request)
    {
        $Delete_review = ProfileReviews::find($request->id);
        ProfileReviews::where('id', $Delete_review->id)->delete();
        $Delete_review->delete();

        $review_count = new Item();
        $review_count->syncProfileverageRating($Delete_review->reviewrateable_id);

        \Session::flash('flash_message', __('review.alert.review-deleted-success'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.page.reviews.index');  
    }
}
