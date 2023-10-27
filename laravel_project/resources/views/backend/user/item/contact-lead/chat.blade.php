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

        .media-body {
            padding: 10px;
        }

        .flex-end-set {
            display: flex;
            justify-content: end;
        }

        .media-border2 {
            border: 1px solid rgba(0, 0, 0, .1);

        }

        .main-media-fixed {
            height: 650px;
            position: sticky;
            overflow-y: scroll;
            top: 200px;
            width: 100%;
        }

        .heading-font-highlight {
            font-weight: 800;
            color: #F05127;
            text-decoration: underline;
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

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4 custom_set">
        <div class="col-12 col-lg-8 m-auto p-0  main-media-fixed">
            {{-- <span class="text-lg text-gray-800">{{ __('backend.message.subject') }}: {{ "ffffff" }}</span>
            <hr/> --}}
            @foreach ($all_chat_messages as $key => $message)
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="media">
                            <div class="media-body media-border2">
                                  @php
                                    if ($message->message == null) {
                                        continue;
                                    }
                                    $username_ftn1 = getUserCoachName($message->sender_id);
                                    $username_ftn2 = getUserCoachName($message->receiver_id);
                                @endphp
                                <div class="flex-set-from-to">
                                    <div class="heading-font-highlight">
                                        From: {{ $username_ftn1 ? $username_ftn1->name : '' }}

                                        To: {{ $username_ftn2 ? $username_ftn2->name : '' }}
                                    </div>
                                    <div>
                                        <small class="flex-end-set">{{ __('backend.message.posted') }}
                                            {{ $message->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <p class="mt-1 mb-0">{{ $message->message }}</p>

                            </div>
                        </div>
                    </div>
                </div>
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
                        <p class="validation_error"></p>
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
        $(document).ready(function() {
            toastr.options.timeOut = 10000;
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
