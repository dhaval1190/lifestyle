<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use App\ContactLead;
use App\User;

class ContactLeadController extends Controller
{
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('role_permission.item-leads.seo.index', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();
        // dd($login_user);
        // $all_contact_leads = ContactLead::where('receiver_id',$login_user->id)
        //                                 ->orWhere('sender_id',$login_user->id)
        //                                 ->orderBy('id','DESC')->paginate(10);

        $all_contact_leads = ContactLead::query();

        if($request->filter_lead == '' || $request->filter_lead == 'all'){
            $all_contact_leads = $all_contact_leads->where('receiver_id',$login_user->id)->orWhere('sender_id',$login_user->id);
        }elseif($request->filter_lead == 'deleted'){
            $all_contact_leads = $all_contact_leads->where('receiver_id',$login_user->id)->where('status',1)->orWhere('sender_id',$login_user->id);
        }
        
        $all_contact_leads = $all_contact_leads->orderBy('id','DESC')->paginate(10);

        

        return response()->view('backend.user.item.contact-lead.index', compact('all_contact_leads'));
    }

    public function edit(Request $request,$id)
    {

        // dd($id);
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('role_permission.item-leads.seo.edit', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $login_user = Auth::user();

        $contact_lead = ContactLead::where('id',$id)->where('receiver_id',$login_user->id)
                                    ->orWhere('sender_id',$login_user->id)
                                    ->first(); 
        if(isset($contact_lead)){
            $user_role = User::where('id',$contact_lead->sender_id)->select('role_id')->first();
            // $contact_lead = ContactLead::where('id',$id)->first();
                if($contact_lead){
                    return response()->view('backend.user.item.contact-lead.edit',
                        compact('contact_lead','user_role','login_user'));
                }else{
                    return redirect()->route('user.contact-leads.index');
                }            
        }else{
            return redirect()->route('page.home');
        }
    }

    public function destroy(Request $request,$id)
    {
        $leadID = $request->leadID;
        if($leadID != ''){
            if($request->action_type == 'delete'){
                $delete_lead = ContactLead::where('id',$leadID)->update(['status'=>1]); 
                $request->session()->flash('success','Lead deleted successfully');               
            }else{
                $delete_lead = ContactLead::where('id',$leadID)->update(['status'=>0]);
                $request->session()->flash('success','Lead restored successfully');
            }
            if($delete_lead){
                
                return redirect()->route('user.contact-leads.index');
            }
        }
        
        $request->session()->flash('error','Something wrong');
        return redirect()->route('user.contact-leads.index');
    }
}
