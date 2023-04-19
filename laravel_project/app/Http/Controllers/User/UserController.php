<?php

namespace App\Http\Controllers\User;

use App\Country;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Category;
use App\MediaDetail;
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

        $total_results = $total_free_items;

        return response()->view('backend.user.profile.edit',
            compact('user_detail','free_items','all_cities','total_results','login_user', 'printable_categories', 'all_countries', 'all_states', 'all_cities', 'media_detail', 'video_media_array', 'podcast_media_array', 'ebook_media_array'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateProfile(Request $request)
    {
        $input = $request->all();

        if(isset($input['youtube'])){
            if (strpos($input['youtube'], "?v=") !== false) {            
                $youtube_url_id = explode("?v=",$input['youtube'])[1];
                $embed_url = "https://www.youtube.com/embed/".$youtube_url_id;
                
            }elseif(strpos($input['youtube'], "youtu.be") !== false){
                $youtube_url_id = explode(".be/",$input['youtube'])[1];
                $embed_url = "https://www.youtube.com/embed/".$youtube_url_id;
            }else{

                $embed_url = $input['youtube'];
            }
        }
        
        $rules = [];
        $rulesMessage = [];

        $rules['name']                      = ['required', 'regex:/^[\pL\s]+$/u', 'max:30'];
        // $rules['email']                     = ['required', 'string', 'email', 'max:255'];
        $rules['email']                     = ['required', 'regex:/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/','email','max:255'];
        // $rules['phone']                     = ['required','string','max:20'];
        $rules['phone']                     = ['nullable','numeric','digits_between:10,20'];
        $rules['gender']                    = ['nullable','string','in:'.implode(",",array_keys(\App\User::GENDER_TYPES)).'','max:20'];
        // $rules['user_prefer_language']   = ['nullable', 'max:5'];
        // $rules['user_prefer_country_id'] = ['nullable', 'numeric'];
        $rules['user_about']                = ['nullable'];
        $rules['certifications']            = ['nullable'];
        $rules['awards']                    = ['nullable'];
        $rules['podcast_image']             = ['nullable', 'file', 'mimes:mp3', 'max:30720'];
        $rules['podcast_cover']             = ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
        $rules['media_image']               = ['nullable', 'file', 'mimes:pdf', 'max:30720'];
        $rules['media_cover']               = ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
        $rules['media_cover']               = ['required_with:media_image'];
        $rules['podcast_cover']             = ['required_with:podcast_image'];
        // $rules['post_code']             =   ['required','numeric','digits_between:1,15'];
        $rules['company_name']          = ['nullable','string','max:100'];
        $rules['user_image']            = ['nullable',new Base64Image];
        $rules['user_cover_image']            = ['nullable',new Base64Image];
        $rulesMessage['media_image.mimes']  = 'Ebook must be a file of type: pdf.';
        $rulesMessage['media_image.max']    = 'Ebook may not be greater than 30720 kilobytes.';
        $rulesMessage['podcast_image.mimes']= 'Podcast Audio must be a file of type: mp3';
        $rulesMessage['podcast_image.max']  = 'Podcast MP3/MP4 Audio may not be greater than 30720 kilobytes.';
        $rulesMessage['media_cover.mimes']  = 'Ebook Cover must be a file of type: jpg, jpeg, png.';
        $rulesMessage['media_cover.max']    = 'Ebook Cover may not be greater than 5120 kilobytes.';
        $rulesMessage['podcast_cover.mimes']= 'Podcast Cover must be a file of type: jpg, jpeg, png.';
        $rulesMessage['podcast_cover.max']  = 'Podcast Cover may not be greater than 5120 kilobytes.';
        $rulesMessage['media_cover.required_with'] = 'Ebook Cover field is required when Ebook PDF is present.';
        $rulesMessage['podcast_cover.required_with'] = 'Podcast Cover field is required when Podcast MP3 is present.';
        // $rulesMessage['phone.required'] = 'Phone is required';
        // $rulesMessage['phone.digits_between'] = 'The phone must between 10 and 20 digits';
        


        if(isset($input['is_coach']) && !empty($input['is_coach'])) {
            $rules['is_coach']              = ['required','in:'.Role::COACH_ROLE_ID];
            $rules['phone']                     = ['required','numeric','digits_between:10,20'];
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
            $rules['post_code']             = ['required','numeric','digits_between:1,15'];
            // $rules['user_image']            = ['nullable'];
            $rules['user_image']            = ['nullable',new Base64Image];
            $rules['user_cover_image']            = ['nullable',new Base64Image];
            $rules['podcast_cover']             = ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
            $rules['podcast_cover']             = ['required_with:podcast_image'];

            $rulesMessage['is_coach.required']  = 'Invalid Coach';
            $rulesMessage['is_coach.in']        = 'Invalid Coach!';
            $rulesMessage['phone.required'] = 'Phone is required';
            $rulesMessage['phone.digits_between'] = 'The phone must between 10 and 20 digits';
            $rulesMessage['podcast_cover.mimes']= 'Podcast Cover must be a file of type: jpg, jpeg, png.';
            $rulesMessage['podcast_cover.max']  = 'Podcast Cover may not be greater than 5120 kilobytes.';
            $rulesMessage['podcast_cover.required_with'] = 'Podcast Cover field is required when Podcast MP3 is present.';
        }

        if($request->instagram){
            if(stripos($request->instagram,'.com') !== false || stripos($request->instagram,'http') !== false || stripos($request->instagram,'https') !== false || stripos($request->instagram,'www.') !== false || stripos($request->instagram,'//') !== false){   
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
        if($request->youtube){
            if(stripos($request->youtube,'youtube') == false && stripos($request->youtube,'youtu.be') == false){
                return back()->with('youtube_error','Please enter youtube URL only');
            }
        }

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
            $login_user->instagram            = isset($input['instagram']) ? $input['instagram'] : null;
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

            if(isset($input['podcast_image']) && !empty($input['podcast_image']) && $input['podcast_type']=='podcast'){
                if(!Storage::disk('public')->exists('media_files')){
                    Storage::disk('public')->makeDirectory('media_files');
                }
                if(!empty($input['podcast_image'])) {
                    $podcast_file_name = $request->file('podcast_image')->getClientOriginalName();
                    $request->podcast_image->storeAs('public/media_files', $podcast_file_name);
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

                MediaDetail::updateOrCreate([
                    'user_id' => $login_user->id,
                    'media_name' => $request->podcast_name,
                    'media_type' => $request->podcast_type,
                    'media_cover' => !empty($podcast_cover_file_name) ? $podcast_cover_file_name : null,
                    'media_image' => !empty($podcast_file_name) ? $podcast_file_name : null,
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
}
