<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ChatMessage;
use App\ContactLead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserContactChatController extends Controller
{
    public function chatIndex($cid,$uid,$con_id)
    {
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
            return view('backend.user.item.contact-lead.chat',compact('all_chat_messages'));

        }else{
            abort(404);
        }

    }

    public function chatStore(Request $request,$uid,$cid)
    {
        $validator = Validator::make($request->all(),[
            'chat_msg' => 'required|max:200'
        ],[
            'chat_msg.required' => 'Message field is required',
            'chat_msg.max' => 'Message field must not more than 200 characters'
        ]);

        if($validator->passes()){
            if(isset($cid,$uid)){
                // $lead_data = ContactLead::where('receiver_id',$cid)->orderBy('id','DESC')->first();
    
                $auth_user = Auth::user();
                if($auth_user->id == $uid){
                    $uid = $cid;
                }else{
                    $uid = $uid;
                }
                // return response()->json(['status'=>'dddddd','msg'=>$uid]);
    
                ChatMessage::create([
                    'sender_id' => $auth_user->id,
                    'receiver_id' => $uid,
                    'message' => $request->chat_msg,
                    'contact_coach_id' => $request->co_id
                ]);
                $request->session()->flash('success','Message sent');
                return response()->json(['status'=>true,'msg'=>'Msg sent successfully']);
            }else{
                return response()->json(['status'=>false,'msg'=>'User not found']);
            }
        }else{
            return response()->json(['status'=>'validator_error','msg'=>$validator->errors()]);
        }

    }
}
