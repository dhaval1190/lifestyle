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

class UserController extends Controller
{
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
        //SEOMeta::setTitle('Dashboard - Edit Profile - ' . (empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name));
        SEOMeta::setTitle(__('seo.backend.user.profile.edit-profile', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();

        $printable_categories = new Category();
        $printable_categories = $printable_categories->getPrintableCategoriesNoDash();

        $all_countries = Country::orderBy('country_name')->get();
        $all_states = $login_user->country ? $login_user->country()->first()->states()->orderBy('state_name')->get() : null;
        $all_cities = $login_user->state ? $login_user->state()->first()->cities()->orderBy('city_name')->get() : null;
        $media_detail = MediaDetail::where('user_id', $login_user->id)->orderBy('media_type')->get();
        $video_media_array = MediaDetail::where('user_id', $login_user->id)->where('media_type', 'video')->get();
        $podcast_media_array = MediaDetail::where('user_id', $login_user->id)->where('media_type', 'podcast')->get();
        $ebook_media_array = MediaDetail::where('user_id', $login_user->id)->where('media_type', 'ebook')->get();

        return response()->view('backend.user.profile.edit',
            compact('login_user', 'printable_categories', 'all_countries', 'all_states', 'all_cities', 'media_detail', 'video_media_array', 'podcast_media_array', 'ebook_media_array'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function updateProfile(Request $request)
    {
        $input = $request->all();

        $rules = [];
        $rulesMessage = [];

        $rules['name']                      = ['required', 'string', 'max:255'];
        $rules['email']                     = ['required', 'string', 'email', 'max:255'];
        $rules['phone']                     = ['required','string','max:20'];
        $rules['gender']                    = ['nullable','string','in:'.implode(",",array_keys(\App\User::GENDER_TYPES)).'','max:20'];
        // $rules['user_prefer_language']   = ['nullable', 'max:5'];
        // $rules['user_prefer_country_id'] = ['nullable', 'numeric'];
        $rules['user_about']             = ['nullable'];

        if(isset($input['is_coach']) && !empty($input['is_coach'])) {
            $rules['is_coach']              = ['required','in:'.Role::COACH_ROLE_ID];
            $rules['category_ids']          = ['required'];
            $rules['company_name']          = ['nullable','string','max:100'];

            $rules['preferred_pronouns']    = ['required','string','in:'.implode(",",array_keys(\App\User::PREFERRED_PRONOUNS)).'','max:100'];

            $rules['hourly_rate_type']      = ['required','string','max:50'];
            $rules['working_type']          = ['required','string','max:50'];
            $rules['experience_year']       = ['required','string','max:50'];

            $rules['website']               = ['nullable','string','url','max:100'];
            $rules['instagram']             = ['nullable','string','url','max:100'];
            $rules['linkedin']              = ['nullable','string','url','max:100'];
            $rules['facebook']              = ['nullable','string','url','max:100'];
            $rules['youtube']               = ['nullable','string','url','max:100'];

            $rules['address']               = ['required','string','max:160'];
            $rules['country_id']            = ['required'];
            $rules['state_id']              = ['required'];
            $rules['city_id']               = ['required'];
            $rules['post_code']             = ['required','string','max:10'];
            $rules['user_image']            = ['nullable'];

            $rulesMessage['is_coach.required']  = 'Invalid Coach';
            $rulesMessage['is_coach.in']        = 'Invalid Coach!';
        }

        $request->validate($rules, $rulesMessage);

        $name = $request->name;
        $email = $request->email;
        $user_about = $request->user_about;
        // $user_prefer_language = empty($request->user_prefer_language) ? null : $request->user_prefer_language;
        // $user_prefer_country_id = empty($request->user_prefer_country_id) ? null : $request->user_prefer_country_id;

        $login_user = Auth::user();

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
            $login_user->youtube              = isset($input['youtube']) ? $input['youtube'] : null;

            $login_user->address              = isset($input['address']) ? $input['address'] : null;
            $login_user->city_id              = isset($input['city_id']) ? $input['city_id'] : null;
            $login_user->post_code            = isset($input['post_code']) ? $input['post_code'] : null;
            $login_user->state_id             = isset($input['state_id']) ? $input['state_id'] : null;
            $login_user->country_id           = isset($input['country_id']) ? $input['country_id'] : null;

            $login_user->user_about = $user_about;
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
                $ebook = $input['media_image'];
                $ebook_cover = $input['media_cover'];
                if(!Storage::disk('public')->exists('media_files')){
                    Storage::disk('public')->makeDirectory('media_files');
                }
                if(!empty($ebook)) {
                    $ebook_file_name = $request->file('media_image')->getClientOriginalName();
                    $request->media_image->storeAs('public/media_files', $ebook_file_name);
                }
                if(!empty($ebook_cover)) {
                    $ebook_cover_file_name = $request->file('media_cover')->getClientOriginalName();
                    $request->media_cover->storeAs('public/media_files', $ebook_cover_file_name);
                }

                MediaDetail::updateOrCreate([
                    'user_id' => $login_user->id,
                    'media_name' => $request->media_name,
                    'media_type' => $request->media_type,
                    'media_cover' => $ebook_cover_file_name,
                    'media_image' => $ebook_file_name
                ],[
                    'user_id' => $login_user->id,
                    'media_type' => $request->media_type,
                    'media_image' => $ebook_file_name
                ]);
            }

            if(isset($input['podcast_image']) && !empty($input['podcast_image']) && $input['podcast_type']=='podcast'){
                $podcast = $input['podcast_image'];
                $podcast_cover = $input['podcast_cover'];
                if(!Storage::disk('public')->exists('media_files')){
                    Storage::disk('public')->makeDirectory('media_files');
                }
                if(!empty($podcast)) {
                    $podcast_file_name = $request->file('podcast_image')->getClientOriginalName();
                    $request->podcast_image->storeAs('public/media_files', $podcast_file_name);
                }
                if(!empty($podcast_cover)) {
                    $podcast_cover_file_name = $request->file('podcast_cover')->getClientOriginalName();
                    $request->podcast_cover->storeAs('public/media_files', $podcast_cover_file_name);
                }

                MediaDetail::updateOrCreate([
                    'user_id' => $login_user->id,
                    'media_name' => $request->podcast_name,
                    'media_type' => $request->podcast_type,
                    'media_cover' => $podcast_cover_file_name,
                    'media_image' => $podcast_file_name
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
            'new_password' => 'required|string|confirmed|min:8',
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
