@extends('frontend.layouts.app')
@section('about_active', 'active')

<style>
    .validation_error {
        color: rgb(255, 00, 0) !important;
        font-weight: 500 !important;
        display: block;
}
</style>

@section('content')

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ asset('frontend/images/placeholder/header-inner.webp') }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: {{ $site_innerpage_header_background_color }};">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image) }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: #333333;">
    @endif

        @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            <div data-youtube="{{ $site_innerpage_header_background_youtube_video }}"></div>
        @endif

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">

                <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">


                    <div class="row justify-content-center mt-5">
                        <div class="col-md-8 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('Chat Details') }}</h1>
                            {{-- <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ __('frontend.about.description') }}</p> --}}
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12">
                    @foreach($all_chat_messages as $msg)
                        @php
                        if($msg->message == null) continue;
                           $username_ftn1 =  getUserCoachName($msg->sender_id);
                           $username_ftn2 =  getUserCoachName($msg->receiver_id);
                        @endphp
                        From: {{ $username_ftn1 ? $username_ftn1->name : '' }}
                        <br>
                        To: {{ $username_ftn2 ? $username_ftn2->name : '' }}
                        <p>{{ $msg->message }}</p>
                        <hr />
                    @endforeach
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12">
                    <form action="" method="POST" name="chatContactFrm" id="chatContactFrm" class="contact-coach-form">
                        <div class="form-section">
                            <div class="col-md-12">
                                <div class="row">
                                    <label for="Question 1"
                                        class="text-black">{{ __('Enter message') }}<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="chat_msg" rows="3" name="chat_msg" required></textarea>
                                    <p class="validation_error"></p>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 form-navigation">
                                <button type="submit" id="submitbtn"
                                    class="btn btn-primary py-2 px-4 mt-2 text-white rounded">
                                    {{ __('Send') }}
                                </button>
                                <span class="please_wait">Please Wait..</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
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
                $current_url_arr = explode("contact-chat/",$curr_url)[1];
                $exp_current_url_arr = explode('/',$current_url_arr);
                //$uid = $exp_current_url_arr[0];
                //$cid = $exp_current_url_arr[1];
                $uid = base64_decode($exp_current_url_arr[0]);
                $cid = base64_decode($exp_current_url_arr[1]);
            @endphp

            $('#chatContactFrm').submit(function(e){
                e.preventDefault();
                $('#submitbtn').attr('disabled',false);
                $('.validation_error').html('');
                var formData = new FormData(this);

                jQuery.ajax({
                    type: 'POST',
                    url: '/contact-chat-store/'+@json($uid)+'/'+@json($cid),
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
                        if(response.status == 'validator_error')
                        {
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
