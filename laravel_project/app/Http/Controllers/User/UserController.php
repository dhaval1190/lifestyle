<?php

namespace App\Http\Controllers\User;

use App\Country;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Category;
use App\MediaDetail;
use App\EmailTemplate;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
use App\Subscription;
use App\Item;
use App\Rules\Base64Image;
use App\MediaDetailsVisits;
use Illuminate\Validation\Rules\Password;



class UserController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function editProfile(Request $request)
    {
        $settings = app('site_global_settings');
        $site_prefer_country_id = app('site_prefer_country_id');
        

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - Edit Profile - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.profile.edit-profile', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();
        $user_detail = User::where('id', $login_user->id)->first();

        $printable_categories = new Category();
        $printable_categories = $printable_categories->getPrintableCategoriesNoDash();

        $all_countries = Country::orderBy('country_name')->get();
        $all_states = $login_user->country ? $login_user->country()->first()->states()->orderBy('state_name')->get() : null;
        $all_cities = $login_user->state ? $login_user->state()->first()->cities()->orderBy('city_name')->get() : null;
        $media_detail = MediaDetail::where('user_id', $login_user->id)->orderBy('media_type')->get();
        $video_media_array = MediaDetail::where('user_id', $login_user->id)->where('media_type', 'video')->get();
        $podcast_media_array = MediaDetail::where('user_id', $login_user->id)->where('media_type', 'podcast')->get();
        $ebook_media_array = MediaDetail::where('user_id', $login_user->id)->where('media_type', 'ebook')->get();

        $subscription_obj = new Subscription();

        $active_user_ids = $subscription_obj->getActiveUserIds();
        $categories = Category::withCount(['allItems' => function ($query) use ($active_user_ids, $site_prefer_country_id) {
            $query->whereIn('items.user_id', $active_user_ids)
            ->where('items.item_status', Item::ITEM_PUBLISHED);
            // ->where(function ($query) use ($site_prefer_country_id) {
            //     $query->where('items.country_id', $site_prefer_country_id)
            //     ->orWhereNull('items.country_id');
            // });
        }])
        ->where('category_parent_id', null)
        ->orderBy('all_items_count', 'desc')->get();

        /**
         * Do listing query
         * 1. get paid listings and free listings.
         * 2. decide how many paid and free listings per page and total pages.
         * 3. decide the pagination to paid or free listings
         * 4. run query and render
         */

        // paid listing
        $filter_categories = empty($request->filter_categories) ? array() : $request->filter_categories;

        $category_obj = new Category();
        $item_ids = $category_obj->getItemIdsByCategoryIds($filter_categories);

        // state & city
        $filter_state = empty($request->filter_state) ? null : $request->filter_state;
        $filter_city = empty($request->filter_city) ? null : $request->filter_city;

        // free listing
        $free_items_query = Item::query();

        // get free users id array
        //$free_user_ids = $subscription_obj->getFreeUserIds();
        //$free_user_ids = $subscription_obj->getActiveUserIds();
        $free_user_ids = $active_user_ids;

        if(count($item_ids) > 0)
        {
            $free_items_query->whereIn('items.id', $item_ids);
        }

        $free_items_query->where("items.item_status", Item::ITEM_PUBLISHED)
            // ->where(function ($query) use ($site_prefer_country_id) {
            //     $query->where('items.country_id', $site_prefer_country_id)
            //         ->orWhereNull('items.country_id');
            // })
            // ->where('items.item_featured', Item::ITEM_NOT_FEATURED)
            // ->where('items.item_featured_by_admin', Item::ITEM_NOT_FEATURED_BY_ADMIN)
            ->where('items.user_id',$login_user->id);

        // filter free listings state
        if(!empty($filter_state))
        {
            $free_items_query->where('items.state_id', $filter_state);
        }

        // filter free listings city
        if(!empty($filter_city))
        {
            $free_items_query->where('items.city_id', $filter_city);
        }

        /**
         * Start filter gender type, working type, price range
         */
        $filter_gender_type = empty($request->filter_gender_type) ? '' : $request->filter_gender_type;
        $filter_preferred_pronouns = empty($request->filter_preferred_pronouns) ? '' : $request->filter_preferred_pronouns;
        $filter_working_type = empty($request->filter_working_type) ? '' : $request->filter_working_type;
        $filter_hourly_rate = empty($request->filter_hourly_rate) ? '' : $request->filter_hourly_rate;
        if($filter_gender_type || $filter_working_type || $filter_hourly_rate || $filter_preferred_pronouns) {
            $free_items_query->leftJoin('users', function($join) {
                $join->on('users.id', '=', 'items.user_id');
            });
            if($filter_gender_type) {
                $free_items_query->where('users.gender', $filter_gender_type);
            }
            if($filter_preferred_pronouns) {
                $free_items_query->where('users.preferred_pronouns', $filter_preferred_pronouns);
            }
            if($filter_working_type) {
                $free_items_query->where('users.working_type', $filter_working_type);
            }
            if($filter_hourly_rate) {
                $free_items_query->where('users.hourly_rate_type', $filter_hourly_rate);
            }
        }
        /**
         * End filter gender type, working type, price range
        */

        /**
         * Start filter sort by for free listing
         */
        $filter_sort_by = empty($request->filter_sort_by) ? Item::ITEMS_SORT_BY_NEWEST_CREATED : $request->filter_sort_by;
        if($filter_sort_by == Item::ITEMS_SORT_BY_NEWEST_CREATED)
        {
            $free_items_query->orderBy('items.created_at', 'DESC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_OLDEST_CREATED)
        {
            $free_items_query->orderBy('items.created_at', 'ASC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_HIGHEST_RATING)
        {
            $free_items_query->orderBy('items.item_average_rating', 'DESC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_LOWEST_RATING)
        {
            $free_items_query->orderBy('items.item_average_rating', 'ASC');
        }
        elseif($filter_sort_by == Item::ITEMS_SORT_BY_NEARBY_FIRST)
        {
            $free_items_query->selectRaw('*, ( 6367 * acos( cos( radians( ? ) ) * cos( radians( item_lat ) ) * cos( radians( item_lng ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( item_lat ) ) ) ) AS distance', [$this->getLatitude(), $this->getLongitude(), $this->getLatitude()])
                ->where('items.item_type', Item::ITEM_TYPE_REGULAR)
                ->orderBy('distance', 'ASC');
        }
        /**
         * End filter sort by for free listing
         */

        $free_items_query->distinct('items.id')
            ->with('state')
            ->with('city')
            ->with('user');

        $total_free_items = $free_items_query->count();

        $querystringArray = [
            'filter_categories' => $filter_categories,
            'filter_sort_by' => $filter_sort_by,
            'filter_state' => $filter_state,
            'filter_city' => $filter_city,
            'filter_gender_type' => $filter_gender_type,
            'filter_preferred_pronouns' => $filter_preferred_pronouns,
            'filter_working_type' => $filter_working_type,
            'filter_hourly_rate' => $filter_hourly_rate,
        ];

       
           
            $free_items = $free_items_query->paginate(4);

           
            
      
        
        $all_printable_categories = $category_obj->getPrintableCategoriesNoDash();

        // $all_states = Country::find($site_prefer_country_id)
        //     ->states()
        //     ->orderBy('state_name')
        //     ->get();

        // $all_cities = collect([]);
        // if(!empty($filter_state))
        // {
        //     $state = State::find($filter_state);
        //     $all_cities = $state->cities()->orderBy('city_name')->get();
        // }
        $user_obj = new User();
        $progress_data = $user_obj->profileProgressData($request,$login_user);
        $data_points = array(
            array("y"=> ($progress_data['percentage']), "name"=> "Completed", "color"=> "#4D72DE"),
            array("y"=> (100- $progress_data['percentage']), "name"=> "Remaining", "color"=> "#D3D3D3")
        );    
        $total_results = $total_free_items;

        return response()->view('backend.user.profile.edit',
            compact('user_detail','free_items','total_results','login_user', 'printable_categories', 'all_countries', 'all_states', 'all_cities', 'media_detail', 'video_media_array', 'podcast_media_array', 'ebook_media_array','progress_data','data_points'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateProfile(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $login_user = Auth::user();
        if(empty($youtube_url['youtube'])){
           
           MediaDetail::where('user_id',$login_user->id)->where('media_type','youtube')->update(['is_status'=> 1]);
            session()->forget("viewed_user_youtube.{$login_user->id}");
        }
        if($request->youtube){
            if(stripos($request->youtube,'youtube') == false && stripos($request->youtube,'youtu.be') == false){
                return back()->with('youtube_error','Please enter youtube URL only');
            }
        }
        if(isset($input['youtube'])){
            if (strpos($input['youtube'], "?v=") !== false) {            
                $youtube_url_id = explode("?v=",$input['youtube'])[1];
                $embed_url = "https://www.youtube.com/embed/".$youtube_url_id;
                
            }elseif(strpos($input['youtube'], "youtu.be") !== false){
                $youtube_url_id = explode(".be/",$input['youtube'])[1];
                $embed_url = "https://www.youtube.com/embed/".$youtube_url_id;

            }
            /*elseif(strpos($input['youtube'], "channel") !== false || strpos($input['youtube'], "@") !== false){
                return back()->with('youtube_error','Please enter youtube video url Only');
                
            }*/
            else{
                $embed_url = $input['youtube'];
            }
            if(isset($input['youtube'])){
                $youtube_url = User::where('id', $login_user->id)->select('youtube')->first();
                if(empty($youtube_url['youtube']) && $youtube_url['youtube'] != $input['youtube']){
                $update = MediaDetail::where('user_id',$login_user->id)->where('media_type','youtube')->update(['is_status'=> 1]);
                session()->forget("viewed_user_youtube.{$login_user->id}");
                }
            }
            if(isset($embed_url)){
            MediaDetail::Create([
                'user_id' => $login_user->id,
                'media_name' => 'youtube',
                'media_type' => 'youtube',
                'media_url' => $embed_url,
                
            ]);
        }
    
        }
        
        $rules = [];
        $rulesMessage = [];

        $rules['name']                      = ['required', 'regex:/^[\pL\s]+$/u', 'max:30'];
        // $rules['email']                     = ['required', 'string', 'email', 'max:255'];
        // $rules['email']                     = ['required', 'regex:/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/','email','max:255'];
        $rules['email']                     = ['required', 'email','max:255'];
        // $rules['phone']                     = ['required','string','max:20'];
        $rules['phone']                     = ['nullable','regex:/^([0-9\s\-\+\(\)]*)$/','min:10','max:20'];
        $rules['gender']                    = ['nullable','string','in:'.implode(",",array_keys(\App\User::GENDER_TYPES)).'','max:20'];
        // $rules['user_prefer_language']   = ['nullable', 'max:5'];
        // $rules['user_prefer_country_id'] = ['nullable', 'numeric'];
        $rules['user_about']                = ['nullable'];
        $rules['certifications']            = ['nullable'];
        $rules['awards']                    = ['nullable'];
        // $rules['podcast_image']             = ['nullable', 'file', 'mimes:mp3', 'max:30720'];
        $rules['podcast_image']             = ['nullable','string','url','max:500'];
        $rules['podcast_cover']             = ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
        $rules['media_image']               = ['nullable', 'file', 'mimes:pdf', 'max:30720'];
        $rules['media_cover']               = ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
        $rules['media_cover']               = ['required_with:media_image'];
        // $rules['podcast_cover']             = ['required_with:podcast_image'];
        $rules['podcast_web_type']             = ['required_with:podcast_image'];
        // $rules['post_code']             =   ['required','numeric','digits_between:1,15'];
        $rules['company_name']          = ['nullable','string','max:100'];
        $rules['user_image']            = ['nullable',new Base64Image];
        $rules['user_cover_image']            = ['nullable',new Base64Image];
        $rulesMessage['media_image.mimes']  = 'Ebook must be a file of type: pdf.';
        $rulesMessage['media_image.max']    = 'Ebook may not be greater than 30720 kilobytes.';
        // $rulesMessage['podcast_image.mimes']= 'Podcast Audio must be a file of type: mp3';
        // $rulesMessage['podcast_image.max']  = 'Podcast MP3/MP4 Audio may not be greater than 30720 kilobytes.';
        $rulesMessage['media_cover.mimes']  = 'Ebook Cover must be a file of type: jpg, jpeg, png.';
        $rulesMessage['media_cover.max']    = 'Ebook Cover may not be greater than 5120 kilobytes.';
        // $rulesMessage['podcast_cover.mimes']= 'Podcast Cover must be a file of type: jpg, jpeg, png.';
        // $rulesMessage['podcast_cover.max']  = 'Podcast Cover may not be greater than 5120 kilobytes.';
        $rulesMessage['media_cover.required_with'] = 'Ebook Cover field is required when Ebook PDF is present.';
        // $rulesMessage['podcast_cover.required_with'] = 'Podcast Cover field is required when Podcast MP3 is present.';
        // $rulesMessage['phone.required'] = 'Phone is required';
        // $rulesMessage['phone.digits_between'] = 'The phone must between 10 and 20 digits';
        


        if(isset($input['is_coach']) && !empty($input['is_coach'])) {
            $rules['is_coach']              = ['required','in:'.Role::COACH_ROLE_ID];
            $rules['phone']                     = ['required','regex:/^([0-9\s\-\+\(\)]*)$/','min:10','max:20'];
            $rules['category_ids']          = ['required'];
            $rules['company_name']          = ['nullable','string','max:100'];

            $rules['preferred_pronouns']    = ['required','string','in:'.implode(",",array_keys(\App\User::PREFERRED_PRONOUNS)).'','max:100'];

            $rules['hourly_rate_type']      = ['required','string','max:50'];
            $rules['working_type']          = ['required','string','max:50'];
            $rules['experience_year']       = ['required','string','max:50'];

            $rules['website']               = ['nullable','string','url','max:100'];
            $rules['instagram']             = ['nullable','string','max:100'];
            $rules['linkedin']              = ['nullable','string','url','max:100'];
            $rules['facebook']              = ['nullable','string','url','max:100'];
            $rules['youtube']               = ['nullable','string','url','max:100'];

            $rules['address']               = ['required','string','max:160'];
            $rules['country_id']            = ['required'];
            $rules['state_id']              = ['required'];
            $rules['city_id']               = ['required'];
            $rules['post_code']             = ['required','regex:/^[a-zA-Z0-9]+([- ]?[a-zA-Z0-9]+)*$/','max:10'];
            // $rules['user_image']            = ['nullable'];
            $rules['user_image']            = ['nullable',new Base64Image];
            $rules['user_cover_image']            = ['nullable',new Base64Image];
            // $rules['podcast_cover']             = ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
            // $rules['podcast_cover']             = ['required_with:podcast_image'];
            $rules['podcast_web_type']             = ['required_with:podcast_image'];

            $rulesMessage['podcast_image.url']  = 'Please enter valid URL';
            $rulesMessage['is_coach.required']  = 'Invalid Coach';
            $rulesMessage['is_coach.in']        = 'Invalid Coach!';
            $rulesMessage['phone.required'] = 'Phone is required';
            $rulesMessage['phone.digits_between'] = 'The phone must between 10 and 20 digits';
            // $rulesMessage['podcast_cover.mimes']= 'Podcast Cover must be a file of type: jpg, jpeg, png.';
            // $rulesMessage['podcast_cover.max']  = 'Podcast Cover may not be greater than 5120 kilobytes.';
            // $rulesMessage['podcast_cover.required_with'] = 'Podcast Cover field is required when Podcast MP3 is present.';
        }

        $instagram_username = $request->instagram;
        if($instagram_username){
            if(stripos($instagram_username,'@') !== false){   
                $instagram_username = explode('@',$request->instagram)[1];
            }
            if(stripos($instagram_username,'.com') !== false || stripos($instagram_username,'http') !== false || stripos($instagram_username,'https') !== false || stripos($instagram_username,'www.') !== false || stripos($instagram_username,'//') !== false){   
                return back()->with('instagram_error','Please enter valid instagram user name Only');
            }
        }         
        if($request->facebook){
            if(stripos($request->facebook,'facebook') == false){
                return back()->with('facebook_error','Please enter facebook URL only');
            }
        }   
        if($request->linkedin){
            if(stripos($request->linkedin,'linkedin') == false){
                return back()->with('linkedin_error','Please enter linkedinssss URL only');
            }
        }
        // if($request->youtube){
        //     if(stripos($request->youtube,'youtube') == false && stripos($request->youtube,'youtu.be') == false){
        //         return back()->with('youtube_error','Please enter youtube URL only');
        //     }
        // }

        $request->validate($rules, $rulesMessage);

        $name = $request->name;
        $email = $request->email;
        $user_about = $request->user_about;
        $youtube_intro_title = $request->youtube_intro_title;
        $youtube_intro_description = $request->youtube_intro_description;
        $certifications = $request->certifications;
        $awards = $request->awards;
        // $user_prefer_language = empty($request->user_prefer_language) ? null : $request->user_prefer_language;
        // $user_prefer_country_id = empty($request->user_prefer_country_id) ? null : $request->user_prefer_country_id;

        $login_user = Auth::user();
        if(isset($input['youtube'])){
            $youtube_url = User::where('id', $login_user->id)->select('youtube')->first();
            if($youtube_url['youtube'] != $input['youtube']){
            $update = MediaDetailsVisits::where('user_id',$login_user->id)->where('media_type','youtube')->update(['is_status'=> 1]);
            session()->forget("viewed_user_youtube.{$login_user->id}");
            }
        }

        $validate_error = array();
        $email_exist = User::where('email', $email)->where('id', '!=', $login_user->id)->count();
        if($email_exist > 0) {
            $validate_error['email'] = __('prefer_country.error.user-email-exist');
        }

        if(!empty($user_prefer_country_id)) {
            $country_exist = Country::find($user_prefer_country_id);
            if(!$country_exist) {
                $validate_error['user_prefer_country_id'] = __('prefer_country.alert.country-not-found');
            }
        }

        if(count($validate_error) > 0) {
            throw ValidationException::withMessages($validate_error);
        } else {
            $user_image = $request->user_image;
            $user_image_name = $login_user->user_image;
            if(!empty($user_image)) {
                $currentDate = Carbon::now()->toDateString();
                $user_image_name = 'user-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.jpg';
                if(!Storage::disk('public')->exists('user')){
                    Storage::disk('public')->makeDirectory('user');
                }
                if(Storage::disk('public')->exists('user/' . $login_user->user_image)){
                    Storage::disk('public')->delete('user/' . $login_user->user_image);
                }
                $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream('jpg', 70);
                Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
            }

            $user_cover_image = $request->user_cover_image;
            $user_cover_image_name = $login_user->user_cover_image;
            if(!empty($user_cover_image)) {
                $currentDate = Carbon::now()->toDateString();
                $user_cover_image_name = 'user-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.jpg';
                if(!Storage::disk('public')->exists('user')){
                    Storage::disk('public')->makeDirectory('user');
                }
                if(Storage::disk('public')->exists('user/' . $login_user->user_cover_image)){
                    Storage::disk('public')->delete('user/' . $login_user->user_cover_image);
                }
                $new_user_cover_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_cover_image)))->stream('jpg', 70);
                Storage::disk('public')->put('user/'.$user_cover_image_name, $new_user_cover_image);
            }

            $login_user->name = $name;
            $login_user->email = $email;

            $login_user->company_name         = isset($input['company_name']) ? $input['company_name'] : null;
            $login_user->phone                = isset($input['phone']) ? $input['phone'] : null;

            $login_user->gender               = isset($input['gender']) ? $input['gender'] : null;
            $login_user->preferred_pronouns   = isset($input['preferred_pronouns']) ? $input['preferred_pronouns'] : null;

            $login_user->hourly_rate          = isset($input['hourly_rate']) ? $input['hourly_rate'] : null;
            $login_user->hourly_rate_type     = isset($input['hourly_rate_type']) ? $input['hourly_rate_type'] : null;
            $login_user->working_type         = isset($input['working_type']) ? $input['working_type'] : null;
            $login_user->experience_year      = isset($input['experience_year']) ? $input['experience_year'] : null;
            $login_user->availability         = isset($input['availability']) ? $input['availability'] : null;

            $login_user->website              = isset($input['website']) ? $input['website'] : null;
            // $login_user->instagram            = isset($input['instagram']) ? $input['instagram'] : null;
            $login_user->instagram            = isset($input['instagram']) ? $instagram_username : null;
            $login_user->linkedin             = isset($input['linkedin']) ? $input['linkedin'] : null;
            $login_user->facebook             = isset($input['facebook']) ? $input['facebook'] : null;
            // $login_user->youtube              = isset($input['youtube']) ? $input['youtube'] : null;
            $login_user->youtube              = isset($embed_url) ? $embed_url : null;

            $login_user->address              = isset($input['address']) ? $input['address'] : null;
            $login_user->city_id              = isset($input['city_id']) ? $input['city_id'] : null;
            $login_user->post_code            = isset($input['post_code']) ? $input['post_code'] : null;
            $login_user->state_id             = isset($input['state_id']) ? $input['state_id'] : null;
            $login_user->country_id           = isset($input['country_id']) ? $input['country_id'] : null;

            $login_user->user_about = $user_about;
            $login_user->awards = $awards;
            $login_user->certifications = $certifications;
            $login_user->youtube_intro_title = $youtube_intro_title;
            $login_user->youtube_intro_description = $youtube_intro_description;
            $login_user->user_image = $user_image_name;
            $login_user->user_cover_image = $user_cover_image_name;
            // $login_user->user_prefer_language = $user_prefer_language;
            // $login_user->user_prefer_country_id = $user_prefer_country_id;
            $login_user->save();

            if(isset($input['category_ids']) && !empty($input['category_ids'])) {
                $login_user->categories()->sync($input['category_ids']);
            }

            if(isset($input['media_details']) && !empty($input['media_details'])){
                foreach($input['media_details'] as $media_detail) {
                    $media = explode("||",$media_detail);
                    MediaDetail::updateOrCreate([
                        'user_id' => $login_user->id,
                        'media_type' => $media[0],
                        'media_url' => $media[1]
                    ],[
                        'media_type' => $media[0],
                        'media_url' => $media[1]
                    ]);
                }
            }

            if(isset($input['media_image']) && !empty($input['media_image']) && $input['media_type']=='ebook'){
                if(!Storage::disk('public')->exists('media_files')){
                    Storage::disk('public')->makeDirectory('media_files');
                }
                if(!empty($input['media_image'])) {
                    $ebook_file_name = $request->file('media_image')->getClientOriginalName();
                    $request->media_image->storeAs('public/media_files', $ebook_file_name);
                }
                if(!empty($input['media_cover'])) {
                    // $ebook_cover_file_name = $request->file('media_cover')->getClientOriginalName();
                    // $request->media_cover->storeAs('public/media_files', $ebook_cover_file_name);

                    $ebook_cover_file_name = $request->file('media_cover')->getClientOriginalName();
                    $destinationPath = public_path('/storage/media_files');
                    $img = Image::make($request->file('media_cover')->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$ebook_cover_file_name);
                }

                MediaDetail::updateOrCreate([
                    'user_id' => $login_user->id,
                    'media_name' => $request->media_name,
                    'media_type' => $request->media_type,
                    'media_cover' => !empty($ebook_cover_file_name) ? $ebook_cover_file_name : null,
                    'media_image' => !empty($ebook_file_name) ? $ebook_file_name : null,
                ],[
                    'user_id' => $login_user->id,
                    'media_type' => $request->media_type,
                    'media_image' => $ebook_file_name
                ]);
            }

            // if(isset($input['podcast_image']) && !empty($input['podcast_image']) && $input['podcast_type']=='podcast'){
            //     if(!Storage::disk('public')->exists('media_files')){
            //         Storage::disk('public')->makeDirectory('media_files');
            //     }
            //     if(!empty($input['podcast_image'])) {
            //         $podcast_file_name = $request->file('podcast_image')->getClientOriginalName();
            //         $request->podcast_image->storeAs('public/media_files', $podcast_file_name);
            //     }
            //     if(!empty($input['podcast_cover'])) {
            //         $podcast_cover_file_name = $request->file('podcast_cover')->getClientOriginalName();
                    
            //         // $request->podcast_cover->storeAs('public/media_files', $podcast_cover_file_name);
            //         // $podcast_cover_file_name = $request->file('podcast_cover')->getClientOriginalName();                    
                
            //         $destinationPath = public_path('/storage/media_files');
            //         $img = Image::make($request->file('podcast_cover')->getRealPath());
            //         $img->resize(200, 200, function ($constraint) {
            //             $constraint->aspectRatio();
            //         })->save($destinationPath.'/'.$podcast_cover_file_name);

            //     }

            //     MediaDetail::updateOrCreate([
            //         'user_id' => $login_user->id,
            //         'media_name' => $request->podcast_name,
            //         'media_type' => $request->podcast_type,
            //         'media_cover' => !empty($podcast_cover_file_name) ? $podcast_cover_file_name : null,
            //         'media_image' => !empty($podcast_file_name) ? $podcast_file_name : null,
            //     ],[
            //         'user_id' => $login_user->id,
            //         'media_type' => $request->podcast_type,
            //         'media_image' => $podcast_file_name
            //     ]);
            // }

            if(isset($input['podcast_image']) && !empty($input['podcast_image']) && $input['podcast_type']=='podcast'){
                // if(!Storage::disk('public')->exists('media_files')){
                //     Storage::disk('public')->makeDirectory('media_files');
                // }
                if($request->podcast_web_type == 'apple_podcast'){
                    if(stripos($request->podcast_image,'apple') == false){
                        return back()->with('podcast_error','Podcast type and URL not matching');
                    }
                    if(stripos($request->podcast_image,'apple') !== false && stripos($request->podcast_image,'embed') !== false){
                        return back()->with('podcast_error','Please enter valid URL');
                    }else{
                        $podcast_url = $request->podcast_image;
                        $exp_podcast_url = explode("podcasts.apple.com",$podcast_url); 
                        $exp_exp_podcast_url = explode('/id',$exp_podcast_url[1]);
                        $exp_exp_exp_podcast_url = explode('?i=',$exp_exp_podcast_url[1]);
                        $playlist_id = $exp_exp_exp_podcast_url[0];
                        $playlist_episode_id = $exp_exp_exp_podcast_url[1];
                        // echo $playlist_id;
                        // echo $playlist_episode_id;
                        // exit;                       
                        $embed_podcast_url = $exp_podcast_url[0]."embed.podcasts.apple.com".$exp_podcast_url[1];  
                        
                        $ch = curl_init("https://itunes.apple.com/lookup?id=".$playlist_id."&country=US&media=podcast&entity=podcastEpisode&limit=100");
                        $options = array(
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
                        );
                        curl_setopt_array( $ch, $options );

                        $results = curl_exec($ch);
                        $results_data = json_decode($results,true);
                        
                        curl_close($ch);                        
                        foreach($results_data['results'] as $result){
                            // print_r($result);
                            if($result['trackId'] == $playlist_episode_id){
                                $podcast_track_name = $result['trackName'];                                
                                $podcast_duration_millisec =  $result['trackTimeMillis'];
                                $seconds = floor($podcast_duration_millisec / 1000);
                                $podcast_duration = gmdate("i:s", $seconds);
                                $podcast_cover_file_name =  $result['artworkUrl160'];         
            
                            }
                        }
                    }
                }
                if($request->podcast_web_type == 'stitcher_podcast'){
                    if(stripos($request->podcast_image,'stitcher') !== false && stripos($request->podcast_image,'embed') !== false){
                        $embed_podcast_url = $request->podcast_image;
                    }elseif(stripos($request->podcast_image,'iframe') !== false){
                        return back()->with('podcast_error','Please enter proper URL');
                    }else{
                        return back()->with('podcast_error','Please enter proper URL');
                    }
                    
                }
                if($request->podcast_web_type == 'google_podcast'){
                    if(stripos($request->podcast_image,'redcircle') == false){
                        return back()->with('podcast_error','Podcast type and URL not matching');
                    }else{
                        $embed_podcast_url = $request->podcast_image;
                        $podcast_ep_id = explode('ep/',$embed_podcast_url)[1];
                        
                        $html = $this->file_get_contents_curl($embed_podcast_url);

                        $doc = new \DOMDocument();
                        @$doc->loadHTML($html);
                        $nodes = $doc->getElementsByTagName('title');
                        //get and display what you need:
                        $title = $nodes->item(0)->nodeValue;
                        $metas = $doc->getElementsByTagName('meta');

                        $podcast_cover_file_name = '';
                        for ($i = 0; $i < $metas->length; $i++)
                        {
                            $meta = $metas->item($i);
                            if($meta->getAttribute('name') == 'twitter:image')
                                $podcast_cover_file_name = $meta->getAttribute('content');
                            if($meta->getAttribute('property') == 'og:url')
                                $podcast_web_url = $meta->getAttribute('content');
                        }
                        $exp_podcast_web_url = explode('/',$podcast_web_url);
                        $podcast_url_key = $exp_podcast_web_url[4];
                        // echo "https://feeds.redcircle.com/".$podcast_url_key;exit;

                        $xml = simplexml_load_file("https://feeds.redcircle.com/".$podcast_url_key);
                        // print_r($xml);exit;
                        $json = json_encode($xml);
                        $podcast_array = json_decode($json,TRUE);
                        // print_r($podcast_array);exit;                            

                        $podcast_track_name = '';
                        $podcast_duration = null;
                        foreach($podcast_array['channel']['item'] as $result){
                            if(str_contains($result['enclosure']['@attributes']['url'],$podcast_ep_id)){
                                $podcast_track_name =  $result['title'];
                                break;
                            }
                        }


                    }
                }
                if($request->podcast_web_type == 'spotify_podcast'){
                    if(stripos($request->podcast_image,'spotify') == false){
                        return back()->with('podcast_error','Podcast type and URL not matching');
                    }
                    if(stripos($request->podcast_image,'iframe') !== false){
                        return back()->with('podcast_error','Please enter proper URL');
                    // }elseif(stripos($request->podcast_image,'spotify') !== false && stripos($request->podcast_image,'episode') !== false){
                    }elseif(stripos($request->podcast_image,'spotify') !== false){
                        $podcast_url = $request->podcast_image;
                        $exp_podcast_url = explode("open.spotify.com",$podcast_url);
                        $exp_podcast_url_2 = explode("?",$exp_podcast_url[1])[0];
                        $exp2_exp_podcast_url_2 = explode("/",$exp_podcast_url_2);
                        $spotify_epi_id = $exp2_exp_podcast_url_2[2];
                        $embed_podcast_url = $exp_podcast_url[0]."open.spotify.com/embed".$exp_podcast_url_2."/?utm_source=generator";  
                        // dd($embed_podcast_url);
                        
                        //call API to get podcast details like name,image,duration
                        $headers  = ['Authorization: Basic '.base64_encode('1b82aa6d520c43dba5c52d8874ce2f5d:3bb76a5092ec4691bf208260ea612350')];
                        $url      = 'https://accounts.spotify.com/api/token';
                        $options  = [CURLOPT_URL            => $url,
                                    CURLOPT_RETURNTRANSFER => TRUE,
                                    CURLOPT_POST           => TRUE,
                                    CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
                                    CURLOPT_HTTPHEADER     => $headers];
                        $credentials = $this->callSpotifyApi($options); 
                        // dd($credentials);
                        $token = $credentials->access_token;



                        $headers  = ['Content-Type: application/json','Authorization: Bearer '.$token];
                        // $url      = 'https://api.spotify.com/v1/playlists/37i9dQZF1DXdMbUSbTOEeW';
                        $url      = 'https://api.spotify.com/v1/episodes/'.$spotify_epi_id.'?market=US';
                        $options  = [CURLOPT_URL            => $url,
                                    CURLOPT_RETURNTRANSFER => TRUE,
                                    CURLOPT_FOLLOWLOCATION => TRUE,
                                    CURLOPT_ENCODING       => '',
                                    CURLOPT_MAXREDIRS      => 10,
                                    CURLOPT_TIMEOUT        => 30,
                                    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST  => 'GET',
                                    CURLOPT_HTTPHEADER     => $headers];
                        $spotify_podcast_details = $this->callSpotifyApi($options); 
                        // print_r($spotify_podcast_details);exit;
                        if(isset($spotify_podcast_details->name)){
                            $podcast_track_name = $spotify_podcast_details->name;
                            $podcast_cover_file_name = $spotify_podcast_details->images[1]->url;
                            $spotify_podcast_duration_millisec = $spotify_podcast_details->duration_ms;
    
                            $seconds = floor($spotify_podcast_duration_millisec / 1000);
                            $podcast_duration = gmdate("i:s", $seconds);
                        }else{
                            return back()->with('podcast_error','Non existing episode id');
                        }

                        // dd($podcast_duration);
                    }
                }
                if(!empty($input['podcast_image'])) {
                    // $podcast_file_name = $request->file('podcast_image')->getClientOriginalName();
                    // $request->podcast_image->storeAs('public/media_files', $podcast_file_name);
                    // $podcast_file_name = $request->podcast_image;
                    $podcast_file_name = $embed_podcast_url;
                }
                if(!empty($input['podcast_cover'])) {
                    $podcast_cover_file_name = $request->file('podcast_cover')->getClientOriginalName();
                    
                    // $request->podcast_cover->storeAs('public/media_files', $podcast_cover_file_name);
                    // $podcast_cover_file_name = $request->file('podcast_cover')->getClientOriginalName();                    
                
                    $destinationPath = public_path('/storage/media_files');
                    $img = Image::make($request->file('podcast_cover')->getRealPath());
                    $img->resize(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$podcast_cover_file_name);

                }

                // dd($podcast_duration);
                MediaDetail::updateOrCreate([
                    'user_id' => $login_user->id,
                    // 'media_name' => $request->podcast_name,
                    'media_name' => $podcast_track_name,
                    'media_type' => $request->podcast_type,
                    'media_cover' => !empty($podcast_cover_file_name) ? $podcast_cover_file_name : null,
                    'media_image' => !empty($podcast_file_name) ? $podcast_file_name : null,
                    'podcast_web_type' => !empty($request->podcast_web_type) ? $request->podcast_web_type : null,
                    'media_duration' => $podcast_duration
                ],[
                    'user_id' => $login_user->id,
                    'media_type' => $request->podcast_type,
                    'media_image' => $podcast_file_name
                ]);
            }

            \Session::flash('flash_message', __('alert.user-profile-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('user.profile.edit');
        }
    }

    public function addPodcastDetails(Request $request)
    {
        // return response()->json(['status'=>'successsss','msg'=>$request->all()]);
        $input = $request->all();

        $login_user = Auth::user();

        if(isset($input['podcast_image']) && !empty($input['podcast_image']) && $input['podcast_type']=='podcast'){
            if($request->podcast_web_type == 'spotify_podcast'){
                if(stripos($request->podcast_image,'spotify') == false){
                    return response()->json(['status'=>'error','msg'=>'Podcast type and URL not matching']);
                }
                if(stripos($request->podcast_image,'iframe') !== false){
                    return response()->json(['status'=>'error','msg'=>'Please enter proper URL']);
    
                // }elseif(stripos($request->podcast_image,'spotify') !== false && stripos($request->podcast_image,'episode') !== false){
                }elseif(stripos($request->podcast_image,'spotify') !== false){
                    $podcast_url = $request->podcast_image;
                    $exp_podcast_url = explode("open.spotify.com",$podcast_url);
                    $exp_podcast_url_2 = explode("?",$exp_podcast_url[1])[0];
                    $exp2_exp_podcast_url_2 = explode("/",$exp_podcast_url_2);
                    $spotify_epi_id = $exp2_exp_podcast_url_2[2];
                    $embed_podcast_url = $exp_podcast_url[0]."open.spotify.com/embed".$exp_podcast_url_2."/?utm_source=generator";  
                    // dd($embed_podcast_url);
                    
                    //call API to get podcast details like name,image,duration
                    $headers  = ['Authorization: Basic '.base64_encode('1b82aa6d520c43dba5c52d8874ce2f5d:3bb76a5092ec4691bf208260ea612350')];
                    $url      = 'https://accounts.spotify.com/api/token';
                    $options  = [CURLOPT_URL            => $url,
                                CURLOPT_RETURNTRANSFER => TRUE,
                                CURLOPT_POST           => TRUE,
                                CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
                                CURLOPT_HTTPHEADER     => $headers];
                    $credentials = $this->callSpotifyApi($options); 
                    // dd($credentials);
                    $token = $credentials->access_token;



                    $headers  = ['Content-Type: application/json','Authorization: Bearer '.$token];
                    // $url      = 'https://api.spotify.com/v1/playlists/37i9dQZF1DXdMbUSbTOEeW';
                    $url      = 'https://api.spotify.com/v1/episodes/'.$spotify_epi_id.'?market=US';
                    $options  = [CURLOPT_URL            => $url,
                                CURLOPT_RETURNTRANSFER => TRUE,
                                CURLOPT_FOLLOWLOCATION => TRUE,
                                CURLOPT_ENCODING       => '',
                                CURLOPT_MAXREDIRS      => 10,
                                CURLOPT_TIMEOUT        => 30,
                                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST  => 'GET',
                                CURLOPT_HTTPHEADER     => $headers];
                    $spotify_podcast_details = $this->callSpotifyApi($options); 
                    // print_r($spotify_podcast_details);exit;
                    if(isset($spotify_podcast_details->name)){
                        $podcast_track_name = $spotify_podcast_details->name;
                        $podcast_cover_file_name = $spotify_podcast_details->images[1]->url;
                        $spotify_podcast_duration_millisec = $spotify_podcast_details->duration_ms;

                        $seconds = floor($spotify_podcast_duration_millisec / 1000);
                        $podcast_duration = gmdate("i:s", $seconds);
                    }else{
                        return response()->json(['status'=>'error','msg'=>'Non existing episode id']);
                    }

                    // dd($podcast_duration);
                }
            }

            if($request->podcast_web_type == 'apple_podcast'){
                if(stripos($request->podcast_image,'apple') == false){
                    return response()->json(['status'=>'error','msg'=>'Podcast type and URL not matching']);
                }
                if(stripos($request->podcast_image,'apple') !== false && stripos($request->podcast_image,'embed') !== false){
                    return response()->json(['status'=>'error','msg'=>'Please enter valid URL']);
                }else{
                    $podcast_url = $request->podcast_image;
                    $exp_podcast_url = explode("podcasts.apple.com",$podcast_url); 
                    $exp_exp_podcast_url = explode('/id',$exp_podcast_url[1]);
                    $exp_exp_exp_podcast_url = explode('?i=',$exp_exp_podcast_url[1]);
                    $playlist_id = $exp_exp_exp_podcast_url[0];
                    $playlist_episode_id = $exp_exp_exp_podcast_url[1];
                    // echo $playlist_id;
                    // echo $playlist_episode_id;
                    // exit;                       
                    $embed_podcast_url = $exp_podcast_url[0]."embed.podcasts.apple.com".$exp_podcast_url[1];  
                    
                    $ch = curl_init("https://itunes.apple.com/lookup?id=".$playlist_id."&country=US&media=podcast&entity=podcastEpisode&limit=100");
                    $options = array(
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
                    );
                    curl_setopt_array( $ch, $options );

                    $results = curl_exec($ch);
                    $results_data = json_decode($results,true);
                    
                    curl_close($ch);                        
                    foreach($results_data['results'] as $result){
                        // print_r($result);
                        if($result['trackId'] == $playlist_episode_id){
                            $podcast_track_name = $result['trackName'];                                
                            $podcast_duration_millisec =  $result['trackTimeMillis'];
                            $seconds = floor($podcast_duration_millisec / 1000);
                            $podcast_duration = gmdate("i:s", $seconds);
                            $podcast_cover_file_name =  $result['artworkUrl160'];         
        
                        }
                    }
                }
            }
            if($request->podcast_web_type == 'google_podcast'){
                if(stripos($request->podcast_image,'redcircle') == false){
                    return response()->json(['status'=>'error','msg'=>'Podcast type and URL not matching']);
                }else{
                    $embed_podcast_url = $request->podcast_image;
                    $podcast_ep_id = explode('ep/',$embed_podcast_url)[1];
                    
                    $html = $this->file_get_contents_curl($embed_podcast_url);

                    $doc = new \DOMDocument();
                    @$doc->loadHTML($html);
                    $nodes = $doc->getElementsByTagName('title');
                    //get and display what you need:
                    $title = $nodes->item(0)->nodeValue;
                    $metas = $doc->getElementsByTagName('meta');

                    $podcast_cover_file_name = '';
                    for ($i = 0; $i < $metas->length; $i++)
                    {
                        $meta = $metas->item($i);
                        if($meta->getAttribute('name') == 'twitter:image')
                            $podcast_cover_file_name = $meta->getAttribute('content');
                        if($meta->getAttribute('property') == 'og:url')
                            $podcast_web_url = $meta->getAttribute('content');
                    }
                    $exp_podcast_web_url = explode('/',$podcast_web_url);
                    $podcast_url_key = $exp_podcast_web_url[4];
                    // echo "https://feeds.redcircle.com/".$podcast_url_key;exit;

                    $xml = simplexml_load_file("https://feeds.redcircle.com/".$podcast_url_key);
                    // print_r($xml);exit;
                    $json = json_encode($xml);
                    $podcast_array = json_decode($json,TRUE);
                    // print_r($podcast_array);exit;                            

                    $podcast_track_name = '';
                    $podcast_duration = null;
                    foreach($podcast_array['channel']['item'] as $result){
                        if(str_contains($result['enclosure']['@attributes']['url'],$podcast_ep_id)){
                            $podcast_track_name =  $result['title'];
                            break;
                        }
                    }


                }
            }
            if($request->podcast_web_type == 'stitcher_podcast'){
                if(stripos($request->podcast_image,'stitcher') !== false && stripos($request->podcast_image,'embed') !== false){
                    $embed_podcast_url = $request->podcast_image;
                }elseif(stripos($request->podcast_image,'iframe') !== false){
                    return response()->json(['status'=>'error','msg'=>'Iframe URL is not allowed']);
                }else{
                    return response()->json(['status'=>'error','msg'=>'Please enter embed URL']);
                }
                
            }
            
            if(!empty($input['podcast_image'])) {
                // $podcast_file_name = $request->file('podcast_image')->getClientOriginalName();
                // $request->podcast_image->storeAs('public/media_files', $podcast_file_name);
                // $podcast_file_name = $request->podcast_image;
                $podcast_file_name = $embed_podcast_url;
            }
            if(!empty($input['podcast_cover'])) {
                $podcast_cover_file_name = $request->file('podcast_cover')->getClientOriginalName();
                
                // $request->podcast_cover->storeAs('public/media_files', $podcast_cover_file_name);
                // $podcast_cover_file_name = $request->file('podcast_cover')->getClientOriginalName();                    
            
                $destinationPath = public_path('/storage/media_files');
                $img = Image::make($request->file('podcast_cover')->getRealPath());
                $img->resize(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$podcast_cover_file_name);

            }

            $insert_podcast = MediaDetail::updateOrCreate([
                'user_id' => $login_user->id,
                // 'media_name' => $request->podcast_name,
                'media_name' => $podcast_track_name,
                'media_type' => $request->podcast_type,
                'media_cover' => !empty($podcast_cover_file_name) ? $podcast_cover_file_name : null,
                'media_image' => !empty($podcast_file_name) ? $podcast_file_name : null,
                'podcast_web_type' => !empty($request->podcast_web_type) ? $request->podcast_web_type : null,
                'media_duration' => $podcast_duration
            ],[
                'user_id' => $login_user->id,
                'media_type' => $request->podcast_type,
                'media_image' => $podcast_file_name
            ]);

            if($insert_podcast){
                return response()->json(['status'=>'success','msg'=>$podcast_track_name]);
            }else{
                return response()->json(['status'=>'error','msg'=>'Something went wrong!']);
            }
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function editProfilePassword(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        //SEOMeta::setTitle('Dashboard - Change Password - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.profile.change-profile-password', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.user.profile.password.edit');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateProfilePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        $login_user = Auth::user();

        // check current password
        $current_password = $request->password;
        if(!Hash::check($current_password, $login_user->password))
        {
            throw ValidationException::withMessages(['password' => 'Current password wrong.']);
        }

        // change password
        $login_user->password = Hash::make($request->new_password);
        $login_user->save();

        \Session::flash('flash_message', __('alert.user-profile-password-changed'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('user.profile.edit');
    }    

    public function showEmailtemplate($param)
    {
        // dd($param);
        $user_id = Auth::user()->id;
        $template_data = EmailTemplate::where('user_id',$user_id)->where('is_contact_or_profile',$param)->first();
        // dd($template_data);

        if($param == 'profile'){
            return view('backend.user.profile_email_template',compact('template_data'));
        }elseif($param == 'coach'){
            return view('backend.user.coach_email_template',compact('template_data'));

        }
    }

    public function updateEmailtemplate(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'subject' => 'required|max:100',
            'message' => 'required|max:1000',
        ]);
        if($request->message == '<p><br></p>'){
            return back()->with('message_error','Message field is required');
        }

        $user_id = Auth::user()->id;
        $emailObj = new EmailTemplate;

        $template_data = EmailTemplate::where('user_id',$user_id)->where('is_contact_or_profile',$request->is_contact_profile)->first();
        // dd($template_data);
        
        if($template_data){
            EmailTemplate::where('id',$template_data->id )->where('user_id',$user_id )->update(['email_template'=>$request->message,'subject'=>$request->subject]);
        }else{
            $emailObj->user_id = $user_id;
            $emailObj->email_template = $request->message;
            $emailObj->subject = $request->subject;
            $emailObj->is_contact_or_profile = $request->is_contact_profile;
            $emailObj->save();
        }
        
        \Session::flash('flash_message', __('Template updated successfully'));
        \Session::flash('flash_type', 'success');
        
        // dd("222");
        return redirect()->route('user.email.template',$request->is_contact_profile);
    }

    public function deleteTemplate(Request $request)
    {

        // dd($request->all());
        if(isset($request->id)){
            $user_id = Auth::user()->id;
            EmailTemplate::where('id',$request->id )->where('user_id',$user_id)->delete();

            \Session::flash('flash_message', __('Template deleted successfully'));
            \Session::flash('flash_type', 'success');
            return redirect()->route('user.email.template',$request->is_contact_profile);
        }

        return back();
    }

    function callSpotifyApi($options) 
    {
        $curl  = curl_init();
        curl_setopt_array($curl, $options); 
        $json  = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        if ($error) {
            return ['error'   => TRUE,
                    'message' => $error];
        }
        $data  = json_decode($json);
        if (is_null($data)) {
            return ['error'   => TRUE,
                    'message' => json_last_error_msg()];
        }
        return $data; 
    }

    function file_get_contents_curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }
}
