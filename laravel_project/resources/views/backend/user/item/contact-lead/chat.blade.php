@extends('backend.user.layouts.app')

@section('styles')
    <style>
        .validation_error {
            color: rgb(255, 00, 0) !important;
            font-weight: 500 !important;
            display: block;
        }

        .flex-set-from-to {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
/* 
        .media-body {
            padding: 10px;
        } */

        .flex-end-set {
            display: flex;
            justify-content: end;
        }

        .media-border2 {
            border: 1px solid rgba(0, 0, 0, .1);
            padding: 10px;

        }

        .main-media-fixed {
            height: 450px ;
            position: sticky;
            top: 200px;
            overflow-y: scroll;
            width: 100%;
            border: 1px solid rgba(0, 0, 0, .1);
        }
        .h-450{
            height: 450px;
            overflow-y:scroll;
           
        }        
        .h-auto1{
            height: 100%;
            overflow: scroll;
           
        }
        .heading-font-highlight {
            font-weight: 800;
            display: flex;
        }
        .first-highlight {
            font-weight: 800;
            color: #F05127;
            text-decoration-color: #F05127;
            text-decoration: underline;
        }
        .sec-highlight{
            color: #0069d9;
            padding-left: 10px;
            text-decoration-color: #0069d9;
            text-decoration: underline;
        }

        .hide-sm-space{
            padding-right: 4px;
        }
        @media (max-width:576px) {
            .btn-w-100{
                width: 100%;
            }
            .flex-set-from-to{
                display: block
            }
            .first-highlight {
             font-size: 14px;
            }
            .sec-highlight{
                font-size: 14px;
        }
        .hide-sm-space{
            display: none;
        }
        }

    </style>
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
@php
    $record_count = $all_chat_messages->count();
@endphp
    <!-- Content Row -->
    @if($record_count > 1)
        <div class="row bg-white pt-4 pl-3 pr-3 pb-4 custom_set">
            <div class="col-12 col-lg-8 m-auto p-md-3">
                {{-- <span class="text-lg text-gray-800">{{ __('backend.message.subject') }}: {{ "ffffff" }}</span>
                <hr/> --}}
                <div class="row mb-2">
                    @php $auth_user_id = auth()->user()->id; @endphp
                    <div class="col-12 main-media-fixed p-0 @if($record_count >= 6) h-450 @elseif($record_count <= 5) h-auto1 @endif">
                            @foreach ($all_chat_messages as $key => $message)
                            <div class="media">
                                <div class="media-body">
                                    @php
                                        
                                        if ($message->message == null) {
                                            continue;
                                        }
                                        $username_ftn1 = getUserCoachName($message->sender_id);
                                        $username_ftn2 = getUserCoachName($message->receiver_id);
                                        $color_ftn1 = getUserCoachColor($message->sender_id);
                                        $color_ftn2 = getUserCoachColor($message->receiver_id);
                                        $contact_coach_id = $message->contact_coach_id;
                                    @endphp
                                <div class="media-border2">
                                <div class="flex-set-from-to">
                                        <div class="heading-font-highlight">
                                        <div class="{{ $color_ftn1 }}">
                                            From: {{ $username_ftn1 ? $username_ftn1->name : '' }}
                                        </div>

                                        <div class="{{ $color_ftn2 }}">
                                            To: {{ $username_ftn2 ? $username_ftn2->name : '' }}
                                        </div>
                                        </div>
                                        <div>
                                            <small class="flex-end-set"><span class="hide-sm-space">Sent</span>
                                                {{ $message->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <p class="mt-1 mb-0">{{ $message->message }}</p>
                                </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @php
                    // echo base64_encode($message->contact_coach_id);
                    // echo "<br>";
                    // echo base64_decode("MTg4");
                @endphp
            </div>
        </div>
    @endif
    @php
        if($record_count == 1)
        {
            foreach ($all_chat_messages as $mg) {
                $contact_coach_id = $mg->contact_coach_id;
            }
        }
    @endphp
   <div class="container p-0">
        <div class="row mb-4">
            <div class="@if($record_count == 1) col-lg-12 @else col-lg-12 @endif  m-auto p-0 p-md-0">
                <form method="POST" action="" name="chatContactFrm" id="chatContactFrm">
                    <input type="hidden" name="co_id" value="{{ $contact_coach_id }}">
                    <div class="form-row mb-3">
                        <div class="col-md-12">
                            <label class="text-black" for="message">{{ __('Send message') }}</label>
                            <textarea rows="6" id="chat_msg" type="text" class="form-control" name="chat_msg"></textarea>
                            <p class="validation_error"></p>
                        </div>
                    </div>

                    <div class="form-row mb-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary py-2 px-4 text-white btn-w-100">
                                {{ __('backend.message.reply') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
   </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            toastr.options.timeOut = 8000;
            @if (Session::has('error'))
                toastr.error("{{ Session::get('error ') }}");
            @elseif (Session::has('success'))
                toastr.success("{{ Session::get('success') }}");
            @endif
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @php
                $curr_url = url()->current();
                $current_url_arr = explode('contact-chat/', $curr_url)[1];
                $exp_current_url_arr = explode('/', $current_url_arr);
                // $uid = $exp_current_url_arr[0];
                // $cid = $exp_current_url_arr[1];
                $uid = base64_decode($exp_current_url_arr[0]);
                $cid = base64_decode($exp_current_url_arr[1]);
            @endphp

            $('#chatContactFrm').submit(function(e) {
                e.preventDefault();
                $('#submitbtn').attr('disabled', false);
                $('.validation_error').html('');
                var formData = new FormData(this);

                jQuery.ajax({
                    type: 'POST',
                    url: '/user/contact-chat-store/' + @json($uid) + '/' +
                        @json($cid),
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,

                    success: function(response) {
                        console.log(response);
                        if (response.status == true) {
                            location.reload();
                        }
                        if (response.status == 'validator_error') {
                            $.each(response.msg, function(key, value) {
                                $(`#${key}`).siblings('p').html(value);

                            });
                        }

                    }
                });
            });


        });
    </script>
@endsection
