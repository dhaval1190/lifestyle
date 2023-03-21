<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravelista\Comments\Commenter;
use Cmgmyr\Messenger\Traits\Messagable;
use DateTime;
use DateInterval;
use App\Mail\Notification;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, Commenter, Messagable;

    const USER_NOT_SUSPENDED = 0;
    const USER_SUSPENDED = 1;

    const ORDER_BY_USER_NEWEST = 1;
    const ORDER_BY_USER_OLDEST = 2;
    const ORDER_BY_USER_NAME_A_Z = 3;
    const ORDER_BY_USER_NAME_Z_A = 4;
    const ORDER_BY_USER_EMAIL_A_Z = 5;
    const ORDER_BY_USER_EMAIL_Z_A = 6;

    const USER_EMAIL_VERIFIED = 2;
    const USER_EMAIL_NOT_VERIFIED = 1;

    const COUNT_PER_PAGE_10 = 10;
    const COUNT_PER_PAGE_25 = 25;
    const COUNT_PER_PAGE_50 = 50;
    const COUNT_PER_PAGE_100 = 100;
    const COUNT_PER_PAGE_250 = 250;
    const COUNT_PER_PAGE_500 = 500;
    const COUNT_PER_PAGE_1000 = 1000;

    const GENDER_TYPES = [
        'male'    => 'Male',
        'female'  => 'Female',
        'other'   => 'Other',
    ];

    const PREFERRED_PRONOUNS = [
        'all'                   => 'All',
        'he_him'                => 'He / Him',
        'she_her'               => 'She / Her',
        'them_they'             => 'Them / They',
        'zhe_zhem'              => 'Zhe / Zhem',
        'prefer_not_to_answer'  => 'Prefer not to answer',
    ];

    const HOURLY_RATES = [
        '$'       => "$ (Less than 125)",
        '$$'      => "$$ (125-225)",
        '$$$'     => "$$$ (225-325)",
        '$$$$'    => "$$$$ (More than 325)"
    ];

    const WORKING_TYPES = [
        "person-to-person"  => "Person-to-Person",
        "remotely"          => "Remotely",
        "hybrid"            => "Hybrid (Person-to-Person / Remotely)",
    ];

    const EXPERIENCE_YEARS = [
        "0-2"       => "0-2",
        "3-5"       => "3-5",
        "5-10"      => "5-10",
        "10-plus"   => "More than 10",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'referrer_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'role_id',
        'gender',
        'phone',
        'category_id',
        'hourly_rate',
        'hourly_rate_type',
        'working_type',
        'experience_year',
        'availability',
        'company_name',
        'instagram',
        'linkedin',
        'facebook',
        'youtube',
        'website',
        'preferred_pronouns',
        'address',
        'city_id',
        'post_code',
        'state_id',
        'country_id',
        'user_image',
        'user_about',
        'user_suspended',
        'user_prefer_language',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['referral_link'];

    /**
     * Get the user's referral link.
     *
     * @return string
     */
    public function getReferralLinkAttribute()
    {
        return $this->referral_link = route('register', ['ref' => $this->id]);
    }

    /**
     * A user has a referrer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    /**
     * A user has many referrals.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referrer_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function hasRole()
    {
        return $this->role->name;
    }

    public function isAdmin()
    {
        return $this->role_id == Role::ADMIN_ROLE_ID;
    }

    public function isCoach()
    {
        return $this->role_id == Role::COACH_ROLE_ID;
    }

    public function isUser()
    {
        return $this->role_id == Role::USER_ROLE_ID;
    }

    public function isEditor()
    {
        return $this->role_id == Role::EDITOR_ROLE_ID;
    }

    public function hasSuspended()
    {
        return $this->user_suspended == User::USER_SUSPENDED;
    }

    public function hasActive()
    {
        return $this->user_suspended == User::USER_NOT_SUSPENDED;
    }

    /**
     * Get the product attributes for the user.
     */
    public function attributes()
    {
        return $this->hasMany('App\Attribute');
    }

    /**
     * Get the products for the user.
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * Get the items for the user.
     */
    public function category()
    {
        return $this->belongsTo('App\Category','category_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'user_categories', 'user_id', 'category_id');
    }

    /**
     * Get the item state that owns the item.
     */
    public function state()
    {
        return $this->belongsTo('App\State', 'state_id');
    }

    /**
     * Get the item city that owns the item.
     */
    public function city()
    {
        return $this->belongsTo('App\City', 'city_id');
    }

    /**
     * Get the item country that owns the item.
     */
    public function country()
    {
        return $this->belongsTo('App\Country', 'country_id');
    }

    /**
     * Get the items for the user.
     */
    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function socialiteAccounts()
    {
        return $this->hasMany('App\SocialiteAccount');
    }

    /**
     * Get the claims of this user
     * @return HasMany
     */
    public function claims()
    {
        return $this->hasMany('App\ItemClaim');
    }
    public function views()
    {
        return $this->hasMany(ProfileView::class);
    }

    /**
     * Get the visits relationship.
     *
     * @return HasMany
     */
    public function visits()
    {
        return $this->hasMany(ProfileVisit::class);
    }


    /**
     * Get the items saved by this user
     */
    public function savedItems()
    {
        return $this->belongsToMany('App\Item')->withTimestamps();
    }

    public function hasSavedItem(int $item_id)
    {
        return DB::table('item_user')
            ->where('item_id', $item_id)
            ->where('user_id', $this->id)
            ->get()
            ->count() > 0 ? true : false;
    }

    public function subscription()
    {
        return $this->hasOne('App\Subscription');
    }

    public function hasPaidSubscription()
    {
        $today = new DateTime('now');
        // $today = $today->format("Y-m-d");
        $today = $today->add(new DateInterval('P14D'))->format("Y-m-d");

        $subscription_exist = Subscription::where('user_id', $this->id)
            ->where('subscription_end_date', '>=', $today)->count();

        return $subscription_exist > 0 ? true : false;
    }

    public function subscriptionDaysLeft()
    {
        if($this->hasPaidSubscription())
        {
            $subscription = $this->subscription()->first();

            $subscription_end_date = new DateTime($subscription->subscription_end_date);
            $today = new DateTime('now');

            $days_left = $subscription_end_date->diff($today);

            return intval($days_left->days);
        }
        else
        {
            return 0;
        }
    }

    public function canFeatureItem()
    {
        if($this->isAdmin())
        {
            return true;
        }
        elseif($this->hasPaidSubscription())
        {
            $plan_max_featured_listing = $this->subscription->plan->plan_max_featured_listing;

            if(is_null($plan_max_featured_listing))
            {
                return true;
            }
            else
            {
                $total_featured_items = $this->items()
                    ->where('item_featured', Item::ITEM_FEATURED)
                    ->count();

                return $plan_max_featured_listing - $total_featured_items >= 1 ? true : false;
            }
        }
        else
        {
            // get the free plan
            $free_plan = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->first();

            $plan_max_featured_listing = $free_plan->plan_max_featured_listing;

            if(is_null($plan_max_featured_listing))
            {
                return true;
            }
            else
            {
                $total_featured_items = $this->items()
                    ->where('item_featured', Item::ITEM_FEATURED)
                    ->count();

                return $plan_max_featured_listing - $total_featured_items >= 1 ? true : false;
            }
        }
    }

    public function canFreeItem()
    {
        if($this->isAdmin())
        {
            return true;
        }
        elseif($this->hasPaidSubscription())
        {
            $plan_max_free_listing = $this->subscription->plan->plan_max_free_listing;

            if(is_null($plan_max_free_listing))
            {
                return true;
            }
            else
            {
                $total_free_items = $this->items()
                    // ->where('item_featured', Item::ITEM_NOT_FEATURED)
                    ->count();

                return $plan_max_free_listing - $total_free_items >= 1 ? true : false;
            }
        }
        else
        {
            // get the free plan
            $free_plan = Plan::where('plan_type', Plan::PLAN_TYPE_FREE)->first();

            $plan_max_free_listing = $free_plan->plan_max_free_listing;

            if(is_null($plan_max_free_listing))
            {
                return true;
            }
            else
            {
                $total_free_items = $this->items()
                    // ->where('item_featured', Item::ITEM_NOT_FEATURED)
                    ->count();

                return $plan_max_free_listing - $total_free_items >= 1 ? true : false;
            }
        }
    }

    public static function getAdmin()
    {
        return User::where('role_id', Role::ADMIN_ROLE_ID)->first();
    }

    public function getLocale()
    {
        return $this->user_prefer_language;
    }

    public function verifyEmail()
    {
        if(empty($this->email_verified_at))
        {
            $this->email_verified_at = date("Y-m-d H:i:s");
            $this->save();
        }
    }

    public function suspendAccount()
    {
        if($this->user_suspended == User::USER_NOT_SUSPENDED)
        {
            $this->user_suspended = User::USER_SUSPENDED;
            $this->save();
        }
    }

    public function unsuspendAccount()
    {
        if($this->user_suspended == User::USER_SUSPENDED)
        {
            $this->user_suspended = User::USER_NOT_SUSPENDED;
            $this->save();
        }
    }

    public function deleteProfileImage()
    {
        if(!empty($this->user_image))
        {
            if(Storage::disk('public')->exists('user/' . $this->user_image)){
                Storage::disk('public')->delete('user/' . $this->user_image);
            }

            $this->user_image = null;

            $this->save();
        }
    }

    public function deleteUser()
    {
        // #1 - delete all items of this user.
        $items = $this->items()->get();
        foreach($items as $key => $item)
        {
            $item->deleteItem();
        }

        // #2 - delete all user's messages
        $participants = DB::table('participants')
            ->where('user_id', $this->id)
            ->get();
        foreach($participants as $key => $participant)
        {
            DB::table('participants')
                ->where('thread_id', $participant->thread_id)
                ->delete();
            DB::table('messages')
                ->where('thread_id', $participant->thread_id)
                ->delete();
            DB::table('threads')
                ->where('id', $participant->thread_id)
                ->delete();
            ThreadItem::where('thread_id', $participant->thread_id)->delete();
        }

        // #3 - delete user's review image galleries
        $reviews = DB::table('reviews')
            ->where('author_id', $this->id)
            ->get();

        foreach($reviews as $key => $a_review)
        {
            $review_image_galleries = DB::table('review_image_galleries')
                ->where('review_id', $a_review->id)
                ->get();

            foreach($review_image_galleries as $key_1 => $review_image_gallery)
            {
                if(Storage::disk('public')->exists('item/review/' . $review_image_gallery->review_image_gallery_name)){
                    Storage::disk('public')->delete('item/review/' . $review_image_gallery->review_image_gallery_name);
                }
                if(Storage::disk('public')->exists('item/review/' . $review_image_gallery->review_image_gallery_thumb_name)){
                    Storage::disk('public')->delete('item/review/' . $review_image_gallery->review_image_gallery_thumb_name);
                }

                DB::table('review_image_galleries')
                    ->where('id', $review_image_gallery->id)
                    ->delete();
            }
        }

        // #4 - delete user's reviews
        DB::table('reviews')
            ->where('author_id', $this->id)
            ->delete();

        // #5 - delete user's comments
        DB::table('comments')
            ->where('commenter_id', $this->id)
            ->delete();

        // #6 - delete saved items records
        DB::table('item_user')
            ->where('user_id', $this->id)
            ->delete();

        // #7 - delete socialite accounts records
        $socialite_accounts = $this->socialiteAccounts()->get();
        foreach($socialite_accounts as $key => $socialite_account)
        {
            $socialite_account->delete();
        }

        // #8 - delete all user invoices
        $user_subscription = $this->subscription()->first();

        if($user_subscription)
        {
            $invoices = $user_subscription->invoices()->get();

            foreach($invoices as $key => $invoice)
            {
                $invoice->delete();
            }
        }

        // #9 - delete subscription record
        $subscriptions = $this->subscription()->get();
        foreach($subscriptions as $key => $subscription)
        {
            $subscription->delete();
        }

        // #10 - delete user profile image
        $this->deleteProfileImage();


        // #11 - delete user business claims
        $item_claims = $this->claims()->get();
        foreach($item_claims as $item_claims_key => $item_claim)
        {
            $item_claim->deleteItemClaim();
        }

        // #12 - delete user products
        $products = $this->products()->get();
        foreach($products as $products_key => $product)
        {
            $product->deleteProduct();
        }

        // #13 - delete user product attributes
        $attributes = $this->attributes()->get();
        foreach($attributes as $attributes_key => $attribute)
        {
            $attribute->deleteAttribute();
        }

        // #14 - delete the user
        $this->delete();
    }

    public function profileProgressData(Request $request,$user)
    {
        $data               = array();
        $remaining_detail   = array();
        $data['id']         = $user['id'];
        $data['name']       = $user['name'];
        $data['email']      = $user['email'];
        $data['profile']    = '';
        $data['percentage'] = 0;

        if($user->categories()->count() > 0 && !empty($user['user_image']) && !empty($user['name']) && !empty($user['company_name']) && !empty($user['phone']) && !empty($user['email']) && !empty($user['hourly_rate_type']) && !empty($user['preferred_pronouns']) && !empty($user['working_type']) && !empty($user['state_id']) && !empty($user['post_code']) && !empty($user['country_id']) && !empty($user['city_id']) && !empty($user['address']) && !empty($user['awards']) && !empty($user['certifications']) && !empty($user['experience_year'])){
            $data['basic_profile']  = true;
            $data['profile']        = 'Basic';
            $data['percentage']     = 20;
            if(!empty($user['website']) && ($user['instagram'] || $user['facebook'] || $user['linkedin'] || $user['youtube']) && ($user['instagram'] || $user['facebook'] || $user['linkedin'] || $user['youtube'])){
                $data['social_profile'] = true;
                $data['profile']        = 'Social';
                $data['percentage']     = 30;
                $article_count          = Item::where('user_id', $user->id)->count();
                $video_media_count      = MediaDetail::where('user_id', $user->id)->where('media_type', 'video')->count();
                $podcast_media_count    = MediaDetail::where('user_id', $user->id)->where('media_type', 'podcast')->count();
                $ebook_media_count      = MediaDetail::where('user_id', $user->id)->where('media_type', 'ebook')->count();
                $blog_count             = \Canvas\Post::where('user_id', $user->id)->count();
                $referral_count         = count($user->referrals);
                if($article_count >= 10){
                    $data['bronze_profile'] = true;
                    $data['profile']        = 'Bronze';
                    $data['percentage']     = 50;
                    if($article_count >= 20 && $video_media_count >= 1 && $podcast_media_count >= 1 && $ebook_media_count >= 1 && $blog_count >= 1){
                        $data['silver_profile'] = true;
                        $data['profile']        = 'Silver';
                        $data['percentage']     = 60;
                        $review_count = DB::table('reviews')->where('author_id', $user->id)->count();
                        if($review_count >= 3){
                            $data['gold_profile']   = true;
                            $data['profile']        = 'Gold';
                            $data['percentage']     = 70;
                            if($article_count >= 30 && $review_count >= 7 && $referral_count >= 1){
                                $data['platinum_profile']   = true;
                                $data['profile']            = 'Platinum';
                                $data['percentage']         = 90;
                                if($referral_count >= 5){
                                    $data['rhodiumm_profile']   = true;
                                    $data['profile']            = 'Rhodium';
                                    $data['percentage']         = 100;
                                }else{
                                    $data['rhodiumm_profile'] = false;
                                    $remaining_detail['referral_count'] = (5 - $referral_count).' Client Referrals';
                                }
                            }else{
                                $data['platinum_profile'] = false;
                                $remaining_detail['article_count'] = (30 - $review_count).' Pieces of content';
                                $remaining_detail['review_count'] = (3 - $review_count).' Client Reviews';
                                if(empty($user->referrals)) $remaining_detail['referral_count'] = $referral_count.' Client Referrals';
                            }
                        }else{
                            $data['gold_profile'] = false;
                            $remaining_detail['review_count'] = (3 - $review_count).' Client Reviews';
                        }
                    }else{
                        $data['silver_profile'] = false;
                        $remaining_detail['article_count'] = (20 - $article_count).' Pieces of content';
                        if(empty($video_media_count)) $remaining_detail['video_media_count'] = '1 Youtube Video Detail';
                        if(empty($podcast_media_count)) $remaining_detail['podcast_media_count'] = '1 Podcast Detail';
                        if(empty($ebook_media_count)) $remaining_detail['ebook_media_count'] = '1 Ebook Detail';
                        if(empty($blog_count)) $remaining_detail['blog_count'] = '1 Blog Detail';
                    }
                }else{
                    $data['bronze_profile'] = false;
                    $remaining_detail['article_count'] = (10 - $article_count).' Pieces of content';
                }
            }else{
                $data['Social_profile'] = false;
                if(empty($user['website'])) $remaining_detail['website'] = 'Website Detail';
                if(empty($user['instagram'])) $remaining_detail['instagram'] = 'IG Handle Detail';
                if(empty($user['facebook'])) $remaining_detail['facebook'] = 'Facebook Detail';
                if(empty($user['linkedin'])) $remaining_detail['linkedin'] = 'LinkedIn Detail';
                if(empty($user['youtube'])) $remaining_detail['youtube'] = 'Youtube Detail';
            }
        }else{
            $data['basic_profile'] = false;
            if(empty($user['user_image'])) $remaining_detail['user_image'] = 'Upload Image';
            if(empty($user['name'])) $remaining_detail['name'] = 'Name';
            if(empty($user['company_name'])) $remaining_detail['company_name'] = 'Company Name';
            if(empty($user['phone'])) $remaining_detail['phone'] = 'Phone';
            if(empty($user['email'])) $remaining_detail['email'] = 'E-Mail Address';
            if(empty($user['hourly_rate_type'])) $remaining_detail['hourly_rate_type'] = 'Hourly Rate';
            if(empty($user['preferred_pronouns'])) $remaining_detail['preferred_pronouns'] = 'Preferred Pronouns';
            if(empty($user['working_type'])) $remaining_detail['working_type'] = 'Working Method';
            if(empty($user['state_id'])) $remaining_detail['state_id'] = 'State';
            if(empty($user['post_code'])) $remaining_detail['post_code'] = 'Post Code';
            if(empty($user['country_id'])) $remaining_detail['country_id'] = 'Country';
            if(empty($user['city_id'])) $remaining_detail['city_id'] = 'City';
            if(empty($user['address'])) $remaining_detail['address'] = 'Address';
            if(empty($user['awards'])) $remaining_detail['awards'] = 'Awards';
            if(empty($user['certifications'])) $remaining_detail['certifications'] = 'Certifications';
            if(empty($user['experience_year'])) $remaining_detail['experience_year'] = 'Experience Year';
        }
        $data['remaining_detail'] = $remaining_detail;
        return $data;
    }
    
    public function emailReminderForProfileCompletion(Request $request)
    {
        $settings = app('site_global_settings');

        if(Auth::check())
        {
            $login_user = Auth::user();
            $email_to = $login_user->email;
            $email_from_name = $login_user->name;
            $email_subject = __('Profile Completion Reminder');

            $progress_data = $this->profileProgressData($request,$login_user);
            $html_content = array();
            if(isset($progress_data['remaining_detail']) && !empty($progress_data['remaining_detail'])){
                foreach($progress_data['remaining_detail'] as $detail){
                    $html_content[] .= ' - '.$detail;
                }
            }

            $email_notify_message = [
                __('You have reached :level level.', ['level' => $progress_data['profile']]),
                __(':percentage % completed out of 100.', ['percentage' => $progress_data['percentage']]),
                __('For achieve your next level you have to do following things.')
            ];

            /**
             * Start initial SMTP settings
             */
            if($settings->settings_site_smtp_enabled == Setting::SITE_SMTP_ENABLED)
            {
                // config SMTP
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
            /**
             * End initial SMTP settings
             */

            if(!empty($settings->setting_site_name))
            {
                // set up APP_NAME
                config([
                    'app.name' => $settings->setting_site_name,
                ]);
            }

            try
            {
                Mail::to($email_to)->send(
                    new Notification(
                        $email_subject,
                        $email_from_name,
                        null,
                        $email_notify_message,
                        null,
                        'success',
                        null,
                        $html_content
                    )
                );
            }
            catch (\Exception $e)
            {
                Log::error($e->getMessage() . "\n" . $e->getTraceAsString());
            }
        }      

    }
}
