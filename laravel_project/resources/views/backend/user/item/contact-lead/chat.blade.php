@extends('backend.user.layouts.app')

@section('styles')
@endsection

@section('content')
    <div class="row justify-content-between">
        <div class="col-md-9 col-8">
            <h1 class="h3 mb-2 font-set-sm text-gray-800">{{ __('Chat Details') }}</h1>
            {{-- <p class="mb-4">{{ __('backend.message.show-message-desc-user') }}</p> --}}
        </div>
        <div class="col-4 col-md-3 text-right">
            <a href="{{ route('user.contact-leads.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4 custom_set">
        <div class="col-12 p-0">
            {{-- <span class="text-lg text-gray-800">{{ __('backend.message.subject') }}: {{ "ffffff" }}</span>
            <hr/> --}}
            @foreach ($all_chat_messages as $key => $message)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="media">
                            <div class="media-body">
                                {{-- <span class="text-gray-800">{{ __('backend.message.from') }}:</span> <span>{{ $message->user->name }}</span><br/>
                            <span class="text-gray-800">{{ __('backend.message.to') }}:</span> <span> {{ $thread->participantsString($message->user->id) }}</span><br/> --}}
                            @php
                            if($message->message == null) continue;
                            $username_ftn1 =  getUserCoachName($message->sender_id);
                            $username_ftn2 =  getUserCoachName($message->receiver_id);
                            @endphp
                            From: {{ $username_ftn1 ? $username_ftn1->name : '' }}
                            <br>
                            To: {{ $username_ftn2 ? $username_ftn2->name : '' }}
                                <p class="mt-3 mb-3">{{ $message->message }}</p>
                                <small>{{ __('backend.message.posted') }}
                                    {{ $message->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
            @endforeach
@php
// echo base64_encode($message->contact_coach_id);
// echo "<br>";
// echo base64_decode("MTg4");
@endphp
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <form method="POST" action="" name="chatContactFrm" id="chatContactFrm">
                <input type="hidden" name="co_id" value="{{ $message->contact_coach_id }}">
                <div class="form-row mb-3">
                    <div class="col-md-12">
                        <label class="text-black" for="message">{{ __('backend.message.reply-message') }}</label>
                        <textarea rows="6" id="chat_msg" type="text" class="form-control" name="chat_msg"></textarea>
                        @error('message')
                        <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-row mb-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary py-2 px-4 text-white">
                            {{ __('backend.message.reply') }}
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @php
            $curr_url = url()->current();
            $current_url_arr = explode("contact-chat/",$curr_url)[1];
            $exp_current_url_arr = explode('/',$current_url_arr);
            // $uid = $exp_current_url_arr[0];
            // $cid = $exp_current_url_arr[1];
            $uid = base64_decode($exp_current_url_arr[0]);
            $cid = base64_decode($exp_current_url_arr[1]);
        @endphp

        $('#chatContactFrm').submit(function(e){
            e.preventDefault();
            $('#submitbtn').attr('disabled',false);
            console.log("ddddddddd")
            var formData = new FormData(this);

            jQuery.ajax({
                type: 'POST',
                url: '/user/contact-chat-store/'+@json($uid)+'/'+@json($cid),
                data: formData,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,

                success: function(response) {
                    console.log(response);
                    if(response.status == true){
                        location.reload();
                    }
                    
                }
            });
        });

        
    });
</script>
@endsection
