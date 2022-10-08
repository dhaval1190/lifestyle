<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Role;
use App\Subscription;
use App\User;
use App\Category;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.user.users', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $request_query_array = $request->query();

        $user_email_verified = $request->user_email_verified == User::USER_EMAIL_NOT_VERIFIED ? User::USER_EMAIL_NOT_VERIFIED : User::USER_EMAIL_VERIFIED;
        $user_suspended = $request->user_suspended == User::USER_SUSPENDED ? User::USER_SUSPENDED : User::USER_NOT_SUSPENDED;
        $order_by = empty($request->order_by) ? User::ORDER_BY_USER_NEWEST : $request->order_by;
        $count_per_page = empty($request->count_per_page) ? User::COUNT_PER_PAGE_10 : $request->count_per_page;

        $all_users_query = User::query();
        $all_users_query->whereIn('role_id', [Role::USER_ROLE_ID, Role::COACH_ROLE_ID]);

        // email verification query
        if($user_email_verified == User::USER_EMAIL_VERIFIED)
        {
            $all_users_query->where('email_verified_at', '!=', null);
        }
        else
        {
            $all_users_query->where('email_verified_at',null);
        }

        // account status query
        if($user_suspended == User::USER_SUSPENDED)
        {
            $all_users_query->where('user_suspended',User::USER_SUSPENDED);
        }
        else
        {
            $all_users_query->where('user_suspended',User::USER_NOT_SUSPENDED);
        }

        // order by
        if($order_by == User::ORDER_BY_USER_NEWEST)
        {
            $all_users_query->orderBy('created_at', 'DESC');
        }
        elseif($order_by == User::ORDER_BY_USER_OLDEST)
        {
            $all_users_query->orderBy('created_at', 'ASC');
        }
        elseif($order_by == User::ORDER_BY_USER_NAME_A_Z)
        {
            $all_users_query->orderBy('name', 'ASC');
        }
        elseif($order_by == User::ORDER_BY_USER_NAME_Z_A)
        {
            $all_users_query->orderBy('name', 'DESC');
        }
        elseif($order_by == User::ORDER_BY_USER_EMAIL_A_Z)
        {
            $all_users_query->orderBy('email', 'ASC');
        }
        elseif($order_by == User::ORDER_BY_USER_EMAIL_Z_A)
        {
            $all_users_query->orderBy('email', 'DESC');
        }

        $all_users_count = $all_users_query->count();
        $all_users = $all_users_query->paginate($count_per_page);

        // show all users except self (admin)
        //$all_users = User::where('role_id', Role::USER_ROLE_ID)->orderBy('created_at', 'DESC')->get();

        return response()->view('backend.admin.user.index',
            compact('all_users', 'all_users_count', 'user_email_verified', 'user_suspended',
                'order_by', 'count_per_page', 'request_query_array'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.user.create-user', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $all_countries = Country::orderBy('country_name')->get();

        $printable_categories = new Category();
        $printable_categories = $printable_categories->getPrintableCategoriesNoDash();

        return response()->view('backend.admin.user.create',
            compact('all_countries','printable_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $rules = [];
        $rulesMessage = [];

        $rules['name']                      = ['required', 'string', 'max:255'];
        $rules['email']                     = ['required', 'string', 'email', 'max:255', 'unique:users'];
        $rules['phone']                     = ['required','string','max:20'];
        $rules['gender']                    = ['required','string','in:male,female','max:20'];
        $rules['password']                  = ['required', 'string', 'min:8', 'confirmed'];
        // $rules['user_prefer_language']   = ['nullable', 'max:5'];
        // $rules['user_prefer_country_id'] = ['nullable', 'numeric'];
        $rules['user_about']             = ['nullable'];

        if(isset($input['is_coach']) && !empty($input['is_coach'])) {
            $rules['is_coach']              = ['required','in:'.Role::COACH_ROLE_ID];
            $rules['category_ids']          = ['required'];
            $rules['company_name']          = ['nullable','string','max:100'];
            $rules['preferred_pronouns']    = ['required','string','in:she_her,he_his','max:100'];
            $rules['instagram']             = ['nullable','string','url','max:100'];
            $rules['linkedin']              = ['nullable','string','url','max:100'];
            $rules['facebook']              = ['nullable','string','url','max:100'];
            $rules['youtube']               = ['nullable','string','url','max:100'];
            $rules['experience_year']       = ['required','string','max:50'];
            $rules['website']               = ['nullable','string','url','max:100'];
            $rules['address']               = ['required','string','max:160'];
            $rules['country_id']            = ['required'];
            $rules['state_id']              = ['required'];
            $rules['city_id']               = ['required'];
            $rules['post_code']             = ['required','string','max:10'];
            $rules['user_image']            = ['nullable'];

            $rulesMessage['is_coach.required']  = 'Invalid Coach Registraion!';
            $rulesMessage['is_coach.in']        = 'Invalid Coach Registraion!';
        }

        $request->validate($rules, $rulesMessage);

        $name = $request->name;
        $email = $request->email;
        $email_verified_at = date("Y-m-d H:i:s");
        $password = Hash::make($request->password);
        $role_id = (isset($input['is_coach']) && $input['is_coach'] == Role::COACH_ROLE_ID) ? Role::COACH_ROLE_ID : Role::USER_ROLE_ID;

        $user_about = $request->user_about;
        $user_prefer_language = empty($request->user_prefer_language) ? 'en' : $request->user_prefer_language;
        $user_prefer_country_id = empty($request->user_prefer_country_id) ? $request->country_id : $request->user_prefer_country_id;

        // validate country_id
        $validate_error = array();
        if(!empty($user_prefer_country_id))
        {
            $country_exist = Country::find($user_prefer_country_id);
            if(!$country_exist)
            {
                $validate_error['user_prefer_country_id'] = __('prefer_country.alert.country-not-found');
            }
        }
        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }

        $user_image = $request->user_image;
        $user_image_name = null;
        if(!empty($user_image)){
            $currentDate = Carbon::now()->toDateString();
            $user_image_name = 'user-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.jpg';
            if(!Storage::disk('public')->exists('user')){
                Storage::disk('public')->makeDirectory('user');
            }
            $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream('jpg', 70);
            Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
        }

        $user = new User();
        $user->name                     = $name;
        $user->email                    = $email;
        $user->email_verified_at        = $email_verified_at;
        $user->password                 = $password;
        $user->role_id                  = $role_id;

        $user->gender                   = isset($input['gender']) ? $input['gender'] : null;
        $user->phone                    = isset($input['phone']) ? $input['phone'] : null;
        $user->hourly_rate              = isset($input['hourly_rate']) ? $input['hourly_rate'] : null;
        $user->experience_year          = isset($input['experience_year']) ? $input['experience_year'] : null;
        $user->availability             = isset($input['availability']) ? $input['availability'] : null;
        $user->company_name             = isset($input['company_name']) ? $input['company_name'] : null;
        $user->instagram                = isset($input['instagram']) ? $input['instagram'] : null;
        $user->linkedin                 = isset($input['linkedin']) ? $input['linkedin'] : null;
        $user->facebook                 = isset($input['facebook']) ? $input['facebook'] : null;
        $user->youtube                  = isset($input['youtube']) ? $input['youtube'] : null;
        $user->website                  = isset($input['website']) ? $input['website'] : null;
        $user->preferred_pronouns       = isset($input['preferred_pronouns']) ? $input['preferred_pronouns'] : null;
        $user->address                  = isset($input['address']) ? $input['address'] : null;
        $user->city_id                  = isset($input['city_id']) ? $input['city_id'] : null;
        $user->post_code                = isset($input['post_code']) ? $input['post_code'] : null;
        $user->state_id                 = isset($input['state_id']) ? $input['state_id'] : null;
        $user->country_id               = isset($input['country_id']) ? $input['country_id'] : null;

        $user->user_about               = $user_about;
        $user->user_image               = $user_image_name;
        $user->user_prefer_language     = $user_prefer_language;
        $user->user_prefer_country_id   = $user_prefer_country_id;
        $user->save();

        if(isset($input['category_ids']) && !empty($input['category_ids'])) {
            $user->categories()->attach($input['category_ids']);
        }

        // when create a new user, we also need to create a free subscription record
        $free_plan = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->first();
        $free_subscription = new Subscription(array(
            'user_id' => $user->id,
            'plan_id' => $free_plan->id,
            'subscription_start_date' => Carbon::now()->toDateString(),
            // 'subscription_max_featured_listing' => is_null($free_plan->plan_max_featured_listing) ? null : $free_plan->plan_max_featured_listing,
            // 'subscription_max_free_listing' => is_null($free_plan->plan_max_free_listing) ? null : $free_plan->plan_max_free_listing,
        ));
        $new_free_subscription = $user->subscription()->save($free_subscription);

        \Session::flash('flash_message', __('alert.user-created'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.user.edit-user', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        /**
         * Get current user's social accounts if any
         */
        $social_accounts = $user->socialiteAccounts()->get();

        $printable_categories = new Category();
        $printable_categories = $printable_categories->getPrintableCategoriesNoDash();

        $all_countries = Country::orderBy('country_name')->get();
        $all_states = $user->country ? $user->country()->first()->states()->orderBy('state_name')->get() : null;
        $all_cities = $user->state ? $user->state()->first()->cities()->orderBy('city_name')->get() : null;

        return response()->view('backend.admin.user.edit',
            compact('user', 'social_accounts', 'printable_categories', 'all_countries', 'all_states', 'all_cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Request $request, User $user)
    {
        $input = $request->all();

        $rules = [];
        $rulesMessage = [];

        $rules['name']                      = ['required', 'string', 'max:255'];
        $rules['email']                     = ['required', 'string', 'email', 'max:255'];
        $rules['phone']                     = ['required','string','max:20'];
        $rules['gender']                    = ['required','string','in:male,female','max:20'];
        // $rules['user_prefer_language']   = ['nullable', 'max:5'];
        // $rules['user_prefer_country_id'] = ['nullable', 'numeric'];
        $rules['user_about']             = ['nullable'];

        if(isset($input['is_coach']) && !empty($input['is_coach'])) {
            $rules['is_coach']              = ['required','in:'.Role::COACH_ROLE_ID];
            $rules['category_ids']          = ['required'];
            $rules['company_name']          = ['nullable','string','max:100'];
            $rules['preferred_pronouns']    = ['required','string','in:both,she_her,he_his','max:100'];

            $rules['website']               = ['nullable','string','url','max:100'];
            $rules['instagram']             = ['nullable','string','url','max:100'];
            $rules['linkedin']              = ['nullable','string','url','max:100'];
            $rules['facebook']              = ['nullable','string','url','max:100'];
            $rules['youtube']               = ['nullable','string','url','max:100'];

            $rules['hourly_rate_type']      = ['required','string','max:50'];
            $rules['working_type']          = ['required','string','max:50'];
            $rules['experience_year']       = ['required','string','max:50'];

            $rules['address']               = ['required','string','max:160'];
            $rules['country_id']            = ['required'];
            $rules['state_id']              = ['required'];
            $rules['city_id']               = ['required'];
            $rules['post_code']             = ['required','string','max:10'];
            $rules['user_image']            = ['nullable'];

            $rulesMessage['is_coach.required']  = 'Invalid Coach Registraion!';
            $rulesMessage['is_coach.in']        = 'Invalid Coach Registraion!';
        }

        $request->validate($rules, $rulesMessage);

        $name = $request->name;
        $email = $request->email;
        $user_about = $request->user_about;
        // $user_prefer_language = empty($request->user_prefer_language) ? null : $request->user_prefer_language;
        // $user_prefer_country_id = empty($request->user_prefer_country_id) ? null : $request->user_prefer_country_id;

        $validate_error = array();
        $user_email_exist = User::where('email', $email)->where('id', '!=', $user->id)->count();
        if($user_email_exist > 0) {
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
        }

        $user_image = $request->user_image;
        $user_image_name = $user->user_image;
        if(!empty($user_image)) {
            $currentDate = Carbon::now()->toDateString();
            $user_image_name = 'user-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.jpg';
            if(!Storage::disk('public')->exists('user')){
                Storage::disk('public')->makeDirectory('user');
            }
            if(Storage::disk('public')->exists('user/' . $user->user_image)){
                Storage::disk('public')->delete('user/' . $user->user_image);
            }
            $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream('jpg', 70);
            Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
        }

        $user->name                 = $name;
        $user->email                = $email;

        $user->gender               = isset($input['gender']) ? $input['gender'] : null;
        $user->phone                = isset($input['phone']) ? $input['phone'] : null;
        $user->hourly_rate          = isset($input['hourly_rate']) ? $input['hourly_rate'] : null;
        $user->hourly_rate_type     = isset($input['hourly_rate_type']) ? $input['hourly_rate_type'] : null;
        $user->working_type         = isset($input['working_type']) ? $input['working_type'] : null;
        $user->experience_year      = isset($input['experience_year']) ? $input['experience_year'] : null;
        $user->availability         = isset($input['availability']) ? $input['availability'] : null;
        $user->company_name         = isset($input['company_name']) ? $input['company_name'] : null;
        $user->instagram            = isset($input['instagram']) ? $input['instagram'] : null;
        $user->linkedin             = isset($input['linkedin']) ? $input['linkedin'] : null;
        $user->facebook             = isset($input['facebook']) ? $input['facebook'] : null;
        $user->youtube              = isset($input['youtube']) ? $input['youtube'] : null;
        $user->website              = isset($input['website']) ? $input['website'] : null;
        $user->preferred_pronouns   = isset($input['preferred_pronouns']) ? $input['preferred_pronouns'] : null;
        $user->address              = isset($input['address']) ? $input['address'] : null;
        $user->city_id              = isset($input['city_id']) ? $input['city_id'] : null;
        $user->post_code            = isset($input['post_code']) ? $input['post_code'] : null;
        $user->state_id             = isset($input['state_id']) ? $input['state_id'] : null;
        $user->country_id           = isset($input['country_id']) ? $input['country_id'] : null;

        $user->user_about = $user_about;
        // $user->user_prefer_language = $user_prefer_language;
        // $user->user_prefer_country_id = $user_prefer_country_id;
        $user->user_image = $user_image_name;
        $user->save();

        if(isset($input['category_ids']) && !empty($input['category_ids'])) {
            $user->categories()->sync($input['category_ids']);
        }

        \Session::flash('flash_message', __('alert.user-updated'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(User $user)
    {
        // Cannot delete admin account
        if(!$user->isAdmin())
        {
            $user->deleteUser();

            \Session::flash('flash_message', __('alert.user-deleted'));
            \Session::flash('flash_type', 'success');
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * @param User $user
     * @return Response
     */
    public function editUserPassword(User $user)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.user.change-user-password', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.user.password.edit', compact('user'));
    }

    public function updateUserPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|string|confirmed|min:8',
        ]);

        // change password
        $user->password = Hash::make($request->new_password);
        $user->save();

        \Session::flash('flash_message', __('alert.user-password-changed'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function editProfile(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.user.edit-profile', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $user_admin = User::getAdmin();

        // $printable_categories = new Category();
        // $printable_categories = $printable_categories->getPrintableCategoriesNoDash();

        // $all_countries = Country::orderBy('country_name')->get();
        // $all_states = $user_admin->country ? $user_admin->country()->first()->states()->orderBy('state_name')->get() : null;
        // $all_cities = $user_admin->state ? $user_admin->state()->first()->cities()->orderBy('city_name')->get() : null;

        return response()->view('backend.admin.user.profile.edit',
            compact('user_admin'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            // 'user_prefer_language' => 'nullable|max:5',
            // 'user_prefer_country_id' => 'nullable|numeric',
            // 'category_ids'          => 'nullable',
            // 'company_name'          => 'nullable|string|max:100',
            'phone'                 => 'required|string|max:20',
            // 'preferred_pronouns'    => 'nullable|string|in:she_her,he_his|max:100',
            // 'gender'                => 'required|string|in:male,female|max:20',
            // 'instagram'             => 'nullable|string|url|max:100',
            // 'linkedin'              => 'nullable|string|url|max:100',
            // 'facebook'              => 'nullable|string|url|max:100',
            // 'youtube'               => 'nullable|string|url|max:100',
            // 'experience_year'       => 'nullable|string|max:50',
            // 'website'               => 'nullable|string|url|max:100',
            // 'address'               => 'required|string|max:160',
            // 'country_id'            => 'required',
            // 'state_id'              => 'required',
            // 'city_id'               => 'required',
            // 'post_code'             => 'required|string|max:10',
            'user_image'            => 'nullable',
            'user_about'            => 'nullable'
        ]);

        $input = $request->all();

        $name = $request->name;
        $email = $request->email;
        $user_about = $request->user_about;
        // $user_prefer_language = empty($request->user_prefer_language) ? null : $request->user_prefer_language;
        // $user_prefer_country_id = empty($request->user_prefer_country_id) ? null : $request->user_prefer_country_id;

        $user_admin = User::getAdmin();

        $validate_error = array();
        $email_exist = User::where('email', $email)
            ->where('id', '!=', $user_admin->id)
            ->count();
        if($email_exist > 0)
        {
            $validate_error['email'] = __('prefer_country.error.user-email-exist');
        }

        if(!empty($user_prefer_country_id))
        {
            $country_exist = Country::find($user_prefer_country_id);
            if(!$country_exist)
            {
                $validate_error['user_prefer_country_id'] = __('prefer_country.alert.country-not-found');
            }
        }

        if(count($validate_error) > 0)
        {
            throw ValidationException::withMessages($validate_error);
        }
        else
        {
            $user_image = $request->user_image;
            $user_image_name = $user_admin->user_image;
            if(!empty($user_image)) {
                $currentDate = Carbon::now()->toDateString();
                $user_image_name = 'admin-' . str_slug($name).'-'.$currentDate.'-'.uniqid().'.jpg';
                if(!Storage::disk('public')->exists('user')){
                    Storage::disk('public')->makeDirectory('user');
                }
                if(Storage::disk('public')->exists('user/' . $user_admin->user_image)){
                    Storage::disk('public')->delete('user/' . $user_admin->user_image);
                }
                $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream('jpg', 70);
                Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
            }

            $user_admin->name = $name;
            $user_admin->email = $email;

            // $user_admin->gender               = isset($input['gender']) ? $input['gender'] : null;
            $user_admin->phone                = isset($input['phone']) ? $input['phone'] : null;
            // $user_admin->hourly_rate          = isset($input['hourly_rate']) ? $input['hourly_rate'] : null;
            // $user_admin->experience_year      = isset($input['experience_year']) ? $input['experience_year'] : null;
            // $user_admin->availability         = isset($input['availability']) ? $input['availability'] : null;
            // $user_admin->company_name         = isset($input['company_name']) ? $input['company_name'] : null;
            // $user_admin->instagram            = isset($input['instagram']) ? $input['instagram'] : null;
            // $user_admin->linkedin             = isset($input['linkedin']) ? $input['linkedin'] : null;
            // $user_admin->facebook             = isset($input['facebook']) ? $input['facebook'] : null;
            // $user_admin->youtube              = isset($input['youtube']) ? $input['youtube'] : null;
            // $user_admin->website              = isset($input['website']) ? $input['website'] : null;
            // $user_admin->preferred_pronouns   = isset($input['preferred_pronouns']) ? $input['preferred_pronouns'] : null;
            // $user_admin->address              = isset($input['address']) ? $input['address'] : null;
            // $user_admin->city_id              = isset($input['city_id']) ? $input['city_id'] : null;
            // $user_admin->post_code            = isset($input['post_code']) ? $input['post_code'] : null;
            // $user_admin->state_id             = isset($input['state_id']) ? $input['state_id'] : null;
            // $user_admin->country_id           = isset($input['country_id']) ? $input['country_id'] : null;

            $user_admin->user_about = $user_about;
            $user_admin->user_image = $user_image_name;
            // $user_admin->user_prefer_language = $user_prefer_language;
            // $user_admin->user_prefer_country_id = $user_prefer_country_id;
            $user_admin->save();

            // if(isset($input['category_ids']) && !empty($input['category_ids'])) {
            //     $user_admin->categories()->sync($input['category_ids']);
            // }

            \Session::flash('flash_message', __('alert.user-profile-updated'));
            \Session::flash('flash_type', 'success');

            return redirect()->route('admin.users.profile.edit');
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
        SEOMeta::setTitle(__('seo.backend.admin.user.change-profile-password', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        return response()->view('backend.admin.user.profile.password.edit');
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
            'new_password' => 'required|string|confirmed|min:8',
        ]);

        $user_admin = User::getAdmin();

        // check current password
        $current_password = $request->password;
        if(!Hash::check($current_password, $user_admin->password))
        {
            throw ValidationException::withMessages(['password' => 'Current password wrong.']);
        }

        // change password
        $user_admin->password = Hash::make($request->new_password);
        $user_admin->save();

        \Session::flash('flash_message', __('alert.user-profile-password-changed'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.profile.edit');
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function suspendUser(User $user)
    {
        $user->user_suspended = User::USER_SUSPENDED;
        $user->save();

        \Session::flash('flash_message', __('alert.user-suspended'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * @param User $user
     * @return RedirectResponse
     */
    public function unsuspendUser(User $user)
    {
        $user->user_suspended = User::USER_NOT_SUSPENDED;
        $user->save();

        \Session::flash('flash_message', __('alert.user-unlocked'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.edit', $user);
    }

    public function bulkVerifyUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|array',
        ]);

        $user_ids = $request->user_id;

        foreach($user_ids as $user_ids_key => $user_id)
        {
            $user = User::find($user_id);

            if($user)
            {
                $user->verifyEmail();
            }
        }

        \Session::flash('flash_message', __('admin_users_table.alert.selected-verified'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.index', $request->query());
    }

    public function bulkSuspendUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|array',
        ]);

        $user_ids = $request->user_id;

        foreach($user_ids as $user_ids_key => $user_id)
        {
            $user = User::find($user_id);

            if($user)
            {
                $user->suspendAccount();
            }
        }

        \Session::flash('flash_message', __('admin_users_table.alert.selected-suspended'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.index', $request->query());
    }

    public function bulkUnsuspendUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|array',
        ]);

        $user_ids = $request->user_id;

        foreach($user_ids as $user_ids_key => $user_id)
        {
            $user = User::find($user_id);

            if($user)
            {
                $user->unsuspendAccount();
            }
        }

        \Session::flash('flash_message', __('admin_users_table.alert.selected-unlocked'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.index', $request->query());
    }

    public function bulkDeleteUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|array',
        ]);

        $user_ids = $request->user_id;

        foreach($user_ids as $user_ids_key => $user_id)
        {
            $user = User::find($user_id);

            if($user)
            {
                $user->deleteUser();
            }
        }

        \Session::flash('flash_message', __('admin_users_table.alert.selected-deleted'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.users.index', $request->query());
    }
}
