<?php

namespace App\Http\Controllers\Auth;

use App\Customization;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Providers\RouteServiceProvider;
use App\Role;
use App\Setting;
use App\SocialLogin;
use App\Subscription;
use App\Theme;
use App\User;
use App\Category;
use Artesaos\SEOTools\Facades\SEOMeta;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Notifications\ReferrerBonus;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [];
        $rulesMessage = [];

        $settings = app('site_global_settings');
        if($settings->setting_site_recaptcha_sign_up_enable == Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE) {
            config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);
            $rules['g-recaptcha-response'] = ['recaptcha'];
        }

        $rules['name']      = ['required', 'string', 'max:255'];
        $rules['email']     = ['required', 'string', 'email', 'max:255', 'unique:users'];
        $rules['password']  = ['required', 'string', 'min:8', 'confirmed'];

        if(isset($data['is_coach']) && !empty($data['is_coach'])) {
            $rules['is_coach']              = ['required','in:'.Role::COACH_ROLE_ID];
            // $rules['plan_id']               = ['required','exists:plans,id'];
            $rules['category_ids']          = ['required'];
            $rules['company_name']          = ['nullable','string','max:100'];
            $rules['phone']                 = ['required','string','max:20'];
            // $rules['gender']                = ['required','string','in:'.array_keys(\App\User::GENDER_TYPES).'','max:20'];
            $rules['preferred_pronouns']    = ['required','string','in:'.implode(",",array_keys(\App\User::PREFERRED_PRONOUNS)).'','max:100'];

            $rules['website']               = ['nullable','string','url','max:100'];
            $rules['instagram']             = ['nullable','string','max:100'];
            $rules['linkedin']              = ['nullable','string','max:100'];
            $rules['facebook']              = ['nullable','string','max:100'];
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
            $rules['is_terms_read']         = ['required'];

            $rulesMessage['is_coach.required']  = 'Invalid Coach Registration!';
            $rulesMessage['is_coach.in']        = 'Invalid Coach Registration!';
            $rulesMessage['hourly_rate_type.required']  = 'The hourly rate field is required.';
            $rulesMessage['category_ids.required']      = 'The category field is required.';
            $rulesMessage['working_type.required']      = 'The working method field is required.';
            $rulesMessage['country_id.required']        = 'The country field is required.';
            $rulesMessage['state_id.required']          = 'The state field is required.';
            $rulesMessage['city_id.required']           = 'The city field is required.';
            // $rulesMessage['plan_id.required']   = 'Invalid Plan Selected!';
            // $rulesMessage['plan_id.exists']     = 'Invalid Plan Selected!';
        }

       return Validator::make($data, $rules, $rulesMessage);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $referrer = User::find(session()->pull('referrer'));        

        $new_user =  User::create([
            'name'                  => $data['name'],
            'email'                 => $data['email'],
            'password'              => Hash::make($data['password']),
            'role_id'               => (isset($data['is_coach']) && $data['is_coach'] == Role::COACH_ROLE_ID) ? Role::COACH_ROLE_ID : Role::USER_ROLE_ID,
            'user_suspended'        => User::USER_NOT_SUSPENDED,
            'referrer_id'           => $referrer ? $referrer->id : null,
        ]);
        

        if(isset($data['is_coach']) && $data['is_coach'] == Role::COACH_ROLE_ID) {

            $new_user->company_name          = isset($data['company_name']) ? $data['company_name'] : null;
            $new_user->phone                 = isset($data['phone']) ? $data['phone'] : null;

            // $new_user->gender                = isset($data['gender']) ? $data['gender'] : null;
            $new_user->preferred_pronouns    = isset($data['preferred_pronouns']) ? $data['preferred_pronouns'] : null;

            $new_user->hourly_rate           = isset($data['hourly_rate']) ? $data['hourly_rate'] : null;
            $new_user->hourly_rate_type      = isset($data['hourly_rate_type']) ? $data['hourly_rate_type'] : null;
            $new_user->working_type          = isset($data['working_type']) ? $data['working_type'] : null;
            $new_user->experience_year       = isset($data['experience_year']) ? $data['experience_year'] : null;
            $new_user->availability          = isset($data['availability']) ? $data['availability'] : null;

            $new_user->website               = isset($data['website']) ? $data['website'] : null;
            $new_user->instagram             = isset($data['instagram']) ? $data['instagram'] : null;
            $new_user->linkedin              = isset($data['linkedin']) ? $data['linkedin'] : null;
            $new_user->facebook              = isset($data['facebook']) ? $data['facebook'] : null;
            $new_user->youtube               = isset($data['youtube']) ? $data['youtube'] : null;

            $new_user->address               = isset($data['address']) ? $data['address'] : null;
            $new_user->country_id            = isset($data['country_id']) ? $data['country_id'] : null;
            $new_user->state_id              = isset($data['state_id']) ? $data['state_id'] : null;
            $new_user->city_id               = isset($data['city_id']) ? $data['city_id'] : null;
            $new_user->post_code             = isset($data['post_code']) ? $data['post_code'] : null;
            $new_user->is_terms_read         = isset($data['is_terms_read']) && !empty($data['is_terms_read']) ? $data['is_terms_read'] : 0;

            // $user_image = $data['user_image'];
            if(isset($data['user_image']) && !empty($data['user_image'])){
                $currentDate = Carbon::now()->toDateString();
                $user_image_name = 'user-' . str_slug($new_user->name).'-'.$currentDate.'-'.uniqid().'.jpg';
                if(!\Storage::disk('public')->exists('user')){
                    \Storage::disk('public')->makeDirectory('user');
                }
                $new_user_image = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$user_image)))->stream('jpg', 200);
                \Storage::disk('public')->put('user/'.$user_image_name, $new_user_image);
                $new_user->user_image = $user_image_name;
            }

        }

        $new_user->user_prefer_language = 'en';
        $new_user->user_prefer_country_id = $new_user->country_id;
        $new_user->save();

        if(isset($data['category_ids']) && !empty($data['category_ids'])) {
            $new_user->categories()->attach($data['category_ids']);
        }

        // if(isset($data['plan_id']) && !empty($data['plan_id'])) {
        //     $plan = Plan::where('id', $data['plan_id'])->first();
        // } else {
        //     $plan = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->first();
        // }

        $plan = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->first();

        if($plan) {
            $subscription = new Subscription(array(
                'user_id' => $new_user->id,
                'plan_id' => $plan->id,
                'subscription_start_date' => Carbon::now()->toDateString(),
                // 'subscription_max_featured_listing' => is_null($plan->plan_max_featured_listing) ? null : $plan->plan_max_featured_listing,
                // 'subscription_max_free_listing' => is_null($plan->plan_max_free_listing) ? null : $plan->plan_max_free_listing,
            ));
            $new_subscription = $new_user->subscription()->save($subscription);
        }

        return $new_user;
    }

    public function showRegistrationForm(Request $request)
    {
        $settings = app('site_global_settings');

        SEOMeta::setTitle(__('seo.auth.register', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);

        $social_logins = new SocialLogin();
        $social_login_facebook = $social_logins->isFacebookEnabled();
        $social_login_google = $social_logins->isGoogleEnabled();
        $social_login_twitter = $social_logins->isTwitterEnabled();
        $social_login_linkedin = $social_logins->isLinkedInEnabled();
        $social_login_github = $social_logins->isGitHubEnabled();

        $site_innerpage_header_background_type = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_image = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_IMAGE)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_background_youtube_video = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_BACKGROUND_YOUTUBE_VIDEO)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_title_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_TITLE_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $site_innerpage_header_paragraph_font_color = Customization::where('customization_key', Customization::SITE_INNERPAGE_HEADER_PARAGRAPH_FONT_COLOR)
            ->where('theme_id', $settings->setting_site_active_theme_id)->first()->customization_value;

        $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
        $theme_view_path = $theme_view_path->getViewPath();

        if($settings->setting_site_recaptcha_sign_up_enable == Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE)
        {
            config_re_captcha($settings->setting_site_recaptcha_site_key, $settings->setting_site_recaptcha_secret_key);
        }

        if ($request->has('ref')) {
            session(['referrer' => $request->query('ref')]);
        }

        return view($theme_view_path . 'auth.register',
            compact('social_login_facebook', 'social_login_google',
                'social_login_twitter', 'social_login_linkedin', 'social_login_github',
                'site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        if($request->email){
            $sel_user = User::where('email',$request->email)->first();
            if($sel_user){
                return response()->json(['status'=>"email_reg",'msg'=>"Email Id already registered"]);

            }
        }
        
        $validator = Validator::make($request->all(),[
            'category_ids' => 'required',
            'name' => 'required|regex:/^[\pL\s]+$/u|max:30',
            'email' => 'required|regex:/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/',
            // 'phone' => 'required|numeric|digits_between:10,12',
            // 'password' => 'required|confirmed|min:8|regex:/^(?=[^a-z]*[a-z])(?=[^A-Z]*[A-Z])(?=\D*\d)(?=[^!@?]*[!@?]).{10,}$/',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'preferred_pronouns' => 'required',
            'hourly_rate_type' => 'required',
            'working_type' => 'required',
            'experience_year' => 'required',
            'address'=> 'required|max:100',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'post_code' => 'required|numeric|digits_between:1,15'
        ],[
            'category_ids.required' => 'Category is required',
            'name.required' => 'Name is required',
            'email.required'=> 'Email is required',
            // 'phone.required'=> 'Phone is required',
            'password.required'=> 'Password is required',
            'password.min'=> 'Password must at least 8 chars',
            'password.regex'=> 'Password must contains letter,number,special chars',
            'preferred_pronouns.required' => 'Preferred pronouns is required',
            'hourly_rate_type.required' => 'Hourly rate type is required',
            'working_type.required' => 'Working type is required',
            'experience_year.required' => 'Experience year is required',
            'address.required' => 'Address is required',
            'country_id.required' => 'Country is required',
            'state_id.required' => 'State is required',
            'city_id.required' => 'City is required',
            'post_code.required' => 'Post code is required',
            'post_code.numeric' => 'The post code must be a number',
            'post_code.digits_between' => 'The post code must not more than 15 digits'

        ]);

        // return response()->json(['data'=>$request->input()]);
        
        $settings = app('site_global_settings');
        if($settings->settings_site_smtp_enabled == Setting::SITE_SMTP_ENABLED)
        {
            config_smtp(
                $settings->settings_site_smtp_sender_name,
                $settings->settings_site_smtp_sender_email,
                $settings->settings_site_smtp_host,
                $settings->settings_site_smtp_port,
                $settings->settings_site_smtp_encryption,
                $settings->settings_site_smtp_username,
                $settings->settings_site_smtp_password
            );
        }

        if(!empty($settings->setting_site_name)) {
            config(['app.name' => $settings->setting_site_name]);
        }

        //$this->validator($request->all())->validate();
        if($validator->passes()){
            try {
                event(new Registered($user = $this->create($request->all())));
            } catch (\Exception $e) {
                Log::error($e->getMessage() . "\n" . $e->getTraceAsString());
            }

            return response()->json(['status'=>"success",'data'=>$request->input()]);

        }else{
            return response()->json(['status'=>"error",'msg'=>$validator->errors()]);
        }



        if(isset($user)) {
            $this->guard()->login($user);
            return $this->registered($request, $user)
                ?: redirect($this->redirectPath());
        } else {
            return redirect()->route('login');
        }
    }
     /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    public function userSignUp(Request $request)
    {

        if($request->email){
            $sel_user = User::where('email',$request->email)->first();
            if($sel_user){
                return response()->json(['status'=>"email_reg",'msg'=>"Email Id already registered"]);

            }
        }

        $validator = Validator::make($request->all(),[
            
            'name' => 'required|regex:/^[\pL\s]+$/u|max:30',
            // 'email' => 'required|regex:/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/',    
            'email' =>  'required|email',               
            // 'password' => 'required|confirmed|min:8|regex:/^(?=[^a-z]*[a-z])(?=[^A-Z]*[A-Z])(?=\D*\d)(?=[^!@?]*[!@?]).{10,}$/',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            
        ],[            
            'name.required' => 'Name is required',
            'name.regex'         =>  "Invalid name",
            'name.max'           =>  "Name must not more than 30 characters",
            'email.required'=> 'Email is required',            
            'email.regex'         =>  "Invalid email format", 
            'email.email'       => "Invalid email format",           
            'password.required'=> 'Password is required',
            'password.min'=> 'Password must at least 8 chars',
            'password.regex'=> 'Password must contains letter,number,special chars',            

        ]);

        $settings = app('site_global_settings');
        if($settings->settings_site_smtp_enabled == Setting::SITE_SMTP_ENABLED)
        {
            config_smtp(
                $settings->settings_site_smtp_sender_name,
                $settings->settings_site_smtp_sender_email,
                $settings->settings_site_smtp_host,
                $settings->settings_site_smtp_port,
                $settings->settings_site_smtp_encryption,
                $settings->settings_site_smtp_username,
                $settings->settings_site_smtp_password
            );
        }

        if(!empty($settings->setting_site_name)) {
            config(['app.name' => $settings->setting_site_name]);
        }

        if($validator->passes()){
            try {
                event(new Registered($user = $this->create($request->all())));
            } catch (\Exception $e) {
                Log::error($e->getMessage() . "\n" . $e->getTraceAsString());
            }

            return response()->json(['status'=>"success",'data'=>$request->input()]);

        }else{
            return response()->json(['status'=>"error",'msg'=>$validator->errors()]);
        }

        if(isset($user)) {
            $this->guard()->login($user);
            return $this->registered($request, $user)
                ?: redirect($this->redirectPath());
        } else {
            return redirect()->route('login');
        }

        
    }

    public function coachSignUp(Request $request)
    {

        if($request->email){
            $sel_user = User::where('email',$request->email)->first();
            if($sel_user){
                return response()->json(['status'=>"email_reg",'msg'=>"Email Id already registered"]);

            }
        }

        $validator = Validator::make($request->all(),[
            
            'name' => 'required|regex:/^[\pL\s]+$/u|max:30',
            // 'email' => 'required|regex:/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/',    
            'email' =>  'required|email',         
            // 'password' => 'required|confirmed|min:8|regex:/^(?=[^a-z]*[a-z])(?=[^A-Z]*[A-Z])(?=\D*\d)(?=[^!@?]*[!@?]).{10,}$/',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            
        ],[            
            'name.required' => 'Name is required',
            'name.regex'         =>  "Invalid name",
            'name.max'           =>  "Name must not more than 30 characters",
            'email.required'=> 'Email is required',            
            'email.regex'         =>  "Invalid email format", 
            'email.email'       => "Invalid email format",           
            'password.required'=> 'Password is required',
            'password.min'=> 'Password must at least 8 chars',
            'password.regex'=> 'Password must contains letter,number,special chars',            

        ]);

        $settings = app('site_global_settings');
        if($settings->settings_site_smtp_enabled == Setting::SITE_SMTP_ENABLED)
        {
            config_smtp(
                $settings->settings_site_smtp_sender_name,
                $settings->settings_site_smtp_sender_email,
                $settings->settings_site_smtp_host,
                $settings->settings_site_smtp_port,
                $settings->settings_site_smtp_encryption,
                $settings->settings_site_smtp_username,
                $settings->settings_site_smtp_password
            );
        }

        if(!empty($settings->setting_site_name)) {
            config(['app.name' => $settings->setting_site_name]);
        }

        if($validator->passes()){
            try {
                event(new Registered($user = $this->create($request->all())));

            } catch (\Exception $e) {
                Log::error($e->getMessage() . "\n" . $e->getTraceAsString());
            }
            $this->guard()->login($user);
            return $this->registered($request, $user)
                ? response()->json(['status'=>"success",'data'=>$request->input()]) : redirect($this->redirectPath());

            // return response()->json(['status'=>"success",'data'=>$request->input()]);

        }else{
            return response()->json(['status'=>"error",'msg'=>$validator->errors()]);
        }

        // if(isset($user)) {
        //     $this->guard()->login($user);
        //     return $this->registered($request, $user)
        //         ?: redirect($this->redirectPath());
        // } else {
        //     return redirect()->route('login');
        // }

        
    }


    protected function registered(Request $request, $user)
    {
        if ($user->referrer !== null) {
            //Notification::send($user->referrer, new ReferrerBonus($user));
        }

        return redirect($this->redirectPath());
    }
}
