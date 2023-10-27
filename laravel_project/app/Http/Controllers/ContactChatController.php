<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\URL;
use App\Setting;
use App\Customization;
use App\Theme;
use App\TempUser;
use App\ChatMessage;
use App\ContactLead;


class ContactChatController extends Controller
{
    public function index($uid,$cid,$con_id)
    {

        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('Chat', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        if($settings->setting_page_about_enable == Setting::ABOUT_PAGE_ENABLED)
        {

            /**
             * Start inner page header customization
             */
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
            /**
             * End inner page header customization
             */

            /**
             * Start initial blade view file path
             */
            $theme_view_path = Theme::find($settings->setting_site_active_theme_id);
            $theme_view_path = $theme_view_path->getViewPath();
            /**
             * End initial blade view file path
             */
            
            $cid = base64_decode($cid);
            $uid = base64_decode($uid);
            $con_id = base64_decode($con_id);

            $all_chat_messages = ChatMessage::where(function ($query) use ($cid,$uid,$con_id) {
                                    $query->where('sender_id',$cid)
                                    ->where('receiver_id',$uid)
                                    ->where('contact_coach_id',$con_id);
                                })
                                ->orWhere(function ($query) use ($cid,$uid,$con_id) {
                                    $query->where('sender_id',$uid)
                                    ->where('receiver_id',$cid)
                                    ->where('contact_coach_id',$con_id);
                                })
                                ->orderBy('created_at','asc')
                                ->get();


            if($all_chat_messages->isNotEmpty()){
                return response()->view($theme_view_path . 'contact_chat',
                    compact('site_innerpage_header_background_type', 'site_innerpage_header_background_color',
                            'site_innerpage_header_background_image', 'site_innerpage_header_background_youtube_video',
                            'site_innerpage_header_title_font_color', 'site_innerpage_header_paragraph_font_color','all_chat_messages'));

            }else{
                abort(404);
            }
        }
        else
        {
            return redirect()->route('page.home');
        }
    }

    public function store(Request $request,$uid,$cid)
    {

        if(isset($cid,$uid)){
            $lead_data = ContactLead::where('sender_id',$uid)->orderBy('id','DESC')->first();
            // return response()->json(['status'=>'dddddd','msg'=>$lead_data->id]);

            ChatMessage::create([
                'sender_id' => $uid,
                'receiver_id' => $cid,
                'message' => $request->chat_msg,
                'contact_coach_id' => $lead_data->id
            ]);
            return response()->json(['status'=>true,'msg'=>'Msg sent successfully']);
        }else{
            return response()->json(['status'=>false,'msg'=>'User not found']);
        }

    }
}
