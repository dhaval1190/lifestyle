<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use App\Rules\Base64Image;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index(Request $request)
    {

        $all_events = new Event;

        if($request->search_query == 'upcoming'){
            $all_events = $all_events->where('event_start_date','>=',date('Y-m-d'))
                                ->where(DB::raw("CONCAT(`event_start_date`,' ',`event_end_hour`)"),'>=', date('Y-m-d H:i'))
                                ->orderBy('event_start_date','DESC');
        }
        if($request->search_query == 'past'){
            $all_events = $all_events->where('event_start_date','<=',date('Y-m-d'))
                                ->where('event_end_date','<=',date('Y-m-d'))
                                ->where(DB::raw("CONCAT(`event_start_date`,' ',`event_end_hour`)"),'<=', date('Y-m-d H:i'))
                                ->orderBy('event_start_date','DESC');
        }
        if(isset($request->keyword_search)){
            $all_events = $all_events->where('event_name','LIKE','%'.$request->keyword_search.'%')->orderBy('event_start_date','DESC');
        }
        if($request->search_query == 'published'){
            $all_events = $all_events->where('status',1)->orderBy('event_start_date','DESC');
        }
        if($request->search_query == 'draft'){
            $all_events = $all_events->where('status',0)->orderBy('event_start_date','DESC');
        }
        $event_count = $all_events->count();
        $all_events = $all_events->paginate(5);

        if(!empty($request->search_query)){
            // dd("dksjdjkls");
            $all_events->appends(['search_query'=> $request->search_query]);
        }

        return view('backend.admin.event.index',compact('all_events','event_count'));
    }


    public function create()
    {
        return view('backend.admin.event.create');
    }

    public function store(Request $request)
    {

        // dd($request->all());
        
        $request->validate([
            'event_name' => 'required|max:100|regex:/^[a-zA-Z0-9\s]+$/',
            'event_start_date' => 'required',
            'event_start_hour' => 'required',
            // 'event_start_min' => 'required|numeric',
            'event_description' => 'required|string|max:500',
            // 'event_end_date' => 'required',
            'event_end_hour' => 'required',
            // 'event_end_min' => 'required|numeric',
            'event_social_url' => 'required|url|max:255',
            'draft_publish' => 'required',
            'event_image' => ['nullable',new Base64Image]
        ],[
            'event_start_date.required' => 'Start date is required',
            'event_start_hour.required' => 'Start hour is required',
            // 'event_start_min.required' => 'Start minute is required',
            'event_description.required' => 'Description is required',
            'event_end_date.required' => 'End date is required',
            'event_end_hour.required' => 'End hour is required',
            // 'event_end_min.required' => 'End min is required',
            'event_social_url.required' => 'Social URL is required',
            'draft_publish.required' => 'Please check draft or publish'
        ]);

        $event_start_hour = $request->event_start_hour;
        // $event_start_min = $request->event_start_min;
        $event_end_hour = $request->event_end_hour;
        // $event_end_min = $request->event_end_min;

        $eventObj = new Event;

        $eventObj->event_name = $request->event_name;
        $eventObj->event_start_date = $request->event_start_date;
        $eventObj->event_start_hour = $request->event_start_hour;
        // $eventObj->event_start_min = $request->event_start_min;
        $eventObj->event_end_date = $request->event_start_date;
        $eventObj->event_end_hour = $request->event_end_hour;
        // $eventObj->event_end_min = $request->event_end_min;
        $eventObj->event_description = isset($request->event_description) ? $request->event_description : null;
        $eventObj->event_social_url = $request->event_social_url;
        $eventObj->status = $request->draft_publish;

        
        if(!empty($request->event_image))
        {
            $currentDate = Carbon::now()->toDateString();

            $event_image_file_name = 'event_'.$currentDate.'_'.uniqid().'.jpg';

            // create event storage folder if not exist
            if(!Storage::disk('public')->exists('event')){
                Storage::disk('public')->makeDirectory('event');
            }

            $event_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$request->event_image)))->stream('jpg', 100);
            Storage::disk('public')->put('event/'.$event_image_file_name, $event_image_obj);

            $eventObj->event_image = $event_image_file_name;
        }
        $eventObj->save();

        return redirect()->route('admin.events.index')->with('success','Event created successfully');
    }

    public function edit(Request $request,$id)
    {
        $event = Event::find($id);
        return view('backend.admin.event.edit',compact('event'));
    }

    public function update(Request $request,$id)
    {
        // dd($request->all());
        $request->validate([
            'event_name' => 'required|max:100|regex:/^[a-zA-Z0-9\s]+$/',
            'event_start_date' => 'required',
            'event_start_hour' => 'required',
            // 'event_start_min' => 'required|numeric',
            'event_description' => 'required|string|max:500',
            // 'event_end_date' => 'required',
            'event_end_hour' => 'required',
            // 'event_end_min' => 'required|numeric',
            'event_social_url' => 'required|url|max:255',
            'draft_publish' => 'required',
            'event_image' => ['nullable',new Base64Image]
        ],[
            'event_start_date.required' => 'Start date is required',
            'event_start_hour.required' => 'Start hour is required',
            // 'event_start_min.required' => 'Start minute is required',
            'event_description.required' => 'Description is required',
            'event_end_date.required' => 'End date is required',
            'event_end_hour.required' => 'End hour is required',
            // 'event_end_min.required' => 'End min is required',
            'event_social_url.required' => 'Social URL is required',
            'draft_publish.required' => 'Please check draft or publish'
        ]);

        $event_start_hour = $request->event_start_hour;
        // $event_start_min = $request->event_start_min;
        $event_end_hour = $request->event_end_hour;
        // $event_end_min = $request->event_end_min;

        $eventObj = Event::find($id);
        // dd($id);

        $eventObj->event_name = $request->event_name;
        $eventObj->event_start_date = $request->event_start_date;
        $eventObj->event_start_hour = $request->event_start_hour;
        // $eventObj->event_start_min = $request->event_start_min;
        $eventObj->event_end_date = $request->event_start_date;
        $eventObj->event_end_hour = $request->event_end_hour;
        // $eventObj->event_end_min = $request->event_end_min;
        $eventObj->event_description = isset($request->event_description) ? $request->event_description : null;
        $eventObj->event_social_url = $request->event_social_url;
        $eventObj->status = $request->draft_publish;

        if(!empty($request->event_image))
        {
            $currentDate = Carbon::now()->toDateString();

            $event_image_file_name = 'event_'.$currentDate.'_'.uniqid().'.jpg';

            // create event storage folder if not exist
            if(!Storage::disk('public')->exists('event')){
                Storage::disk('public')->makeDirectory('event');
            }

            $event_image_obj = Image::make(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$request->event_image)))->stream('jpg', 100);
            Storage::disk('public')->put('event/'.$event_image_file_name, $event_image_obj);

            Storage::disk('public')->delete('event/'.$eventObj->event_image);

            $eventObj->event_image = $event_image_file_name;
        }
        $eventObj->save();

        return redirect()->route('admin.events.index')->with('success','Event updated successfully');
    }

    public function destroy(Request $request)
    {
        $id = $request->event_id;
        if($id){
            $eventObj = Event::where('id',$id)->delete();
            return redirect()->route('admin.events.index')->with('success','Event deleted successfully');

        }

        abort(404);
    }

    public function deleteEventImage(Request $request, $id)
    {

        if($id){
            $eventObj = Event::find($id);
            Storage::disk('public')->delete('event/'.$eventObj->event_image);
            $eventObj->where('id',$id)->update(['event_image'=>null]);
            return response()->json(['status'=>'success','msg'=>'Event image deleted successfully']);

        }else{
            return response()->json(['status'=>'error','msg'=>'Something went wrong']);
        }
    }
}