@extends('frontend.layouts.app')
@section('register_active', 'active')


@section('styles')
    <!-- Start Google reCAPTCHA version 2 -->
    @if($site_global_settings->setting_site_recaptcha_sign_up_enable == \App\Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE)
        {!! htmlScriptTagJsApi(['lang' => empty($site_global_settings->setting_site_language) ? 'en' : $site_global_settings->setting_site_language]) !!}
    @endif
    <!-- End Google reCAPTCHA version 2 -->
@endsection

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
        <style>
            .error_color{
            color: red;
        }
        </style>

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">

                <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">


                    <div class="row justify-content-center mt-5">
                        <div class="col-md-10 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('auth.register') }}</h1>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section bg-light">
        <div class="container">

            @include('frontend.partials.alert')

            <div class="row justify-content-center">
                <div class="col-md-7 mb-5">
                    <div class="alert alert-success alert-dismissible fade show" id="register_success_error_div" role="alert" style="display:none;margin:4px;">
                        <strong id="register_success"></strong>
                    </div>

                    <form method="POST" class="p-5 bg-white" name="signUpForm" id="signUpForm">
                        @csrf
                        <div class="row mb-4">
                            <div class="col">
                              <h3>Register as a user</h3>
                            </div>
                          </div>

                        <div class="form-group row">

                            <div class="col-md-12">
                                <label for="name" class="text-black">{{ __('auth.name') }}<span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                                <p class="name_error error_color" role="alert"></p>
                                <!-- @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror -->
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label class="text-black" for="email">{{ __('auth.email-addr') }}<span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
                                <p class="email_error error_color" role="alert"></p>

                                <!-- @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror -->
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="subject">{{ __('auth.password') }}<span class="text-danger">*</span></label>
                                <input id="password" type="password" class="form-control set_padding_left_right_form @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                <p class="password_error error_color" role="alert"></p>

                                <!-- @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror -->
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="password-confirm">{{ __('auth.confirm-password') }}<span class="text-danger">*</span></label>
                                <input id="password-confirm" type="password" class="form-control set_padding_left_right_form" name="password_confirmation" autocomplete="new-password">
                                <span toggle="#password-confirm" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                            </div>
                        </div>


                        <div class="row form-group">
                            <div class="col-md-12">
                                <div class="g-recaptcha" data-sitekey="6LeXpRIpAAAAANR5q7jXCepgrSKbM91QWgLumZXc"></div>
                                <p class="g-recaptcha_error error_color" role="alert"></p>
                            </div>
                        </div>

                        <input type="hidden" name="user_bio" id="user_bio">

                        <!-- Start Google reCAPTCHA version 2 -->
                        @if($site_global_settings->setting_site_recaptcha_sign_up_enable == \App\Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    {!! htmlFormSnippet() !!}
                                    @error('g-recaptcha-response')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                        <!-- End Google reCAPTCHA version 2 -->
                        
                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary py-2 px-4 text-white rounded">
                                    {{ __('auth.register') }}
                                </button><span class="please_wait">Please Wait..</span>
                            </div>
                            
                        </div>
                        <div class="row form-group">
                            <div class="col-12">
                                <p>{{ __('auth.have-an-account') }}? <a href="{{ route('login') }}">{{ __('auth.login') }}</a></p>
                            </div>
                        </div>

                        @if($social_login_facebook || $social_login_google || $social_login_twitter || $social_login_linkedin || $social_login_github)
                            <!-- <div class="row mt-4 align-items-center">
                                <div class="col-md-5">
                                    <hr>
                                </div>
                                <div class="col-md-2 pl-0 pr-0 text-center">
                                    <span>{{ __('social_login.frontend.or') }}</span>
                                </div>
                                <div class="col-md-5">
                                    <hr>
                                </div>
                            </div>
                            <div class="row align-items-center mb-4 mt-2">
                                <div class="col-md-12 text-center">
                                    <span>{{ __('social_login.frontend.sign-in-with') }}</span>
                                </div>
                            </div> -->
                        @endif

                        @if($social_login_facebook)
                            <!-- <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-facebook btn-block text-white rounded" href="{{ route('auth.login.facebook') }}">
                                        <i class="fab fa-facebook-f pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-facebook') }}
                                    </a>
                                </div>
                            </div> -->
                        @endif

                        @if($social_login_google)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-google btn-block text-white rounded" href="{{ route('auth.login.google') }}">
                                        <i class="fab fa-google pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-google') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($social_login_twitter)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-twitter btn-block text-white rounded" href="{{ route('auth.login.twitter') }}">
                                        <i class="fab fa-twitter pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-twitter') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($social_login_linkedin)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-linkedin btn-block text-white rounded" href="{{ route('auth.login.linkedin') }}">
                                        <i class="fab fa-linkedin-in pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-linkedin') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($social_login_github)
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <a class="btn btn-github btn-block text-white rounded" href="{{ route('auth.login.github') }}">
                                        <i class="fab fa-github pr-2"></i>
                                        {{ __('social_login.frontend.sign-in-github') }}
                                    </a>
                                </div>
                            </div>
                        @endif


                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
    <!-- Youtube Background for Header -->
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif
    <script>
        function validateInput(event) {
        var value = String.fromCharCode(event.which);
        var pattern = new RegExp(/[a-zåäö ][0-9]/i);
        return pattern.test(value);
        }
    </script>
    <script>

        $(document).ready(function(){
            $(".toggle-password").click(function() {

            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
            });

            "use strict";
            $('.val_field').bind('keypress', validateInput);
            $('.please_wait').text('');
            $('.error_color').text('');
            $('#name').on('input', function(e) {
                $('.name_error').text('')
            });
            $('#email').on('input', function(e) {
                $('.email_error').text('');
            });
            $('#password').on('input', function(e) {
                $('.password_error').text('');
            });
            $('#password-confirm').on('input', function(e) {
                $('.password_error').text('');
            });

            @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            @endif

            $('#signUpForm').on('submit',function(e){
                e.preventDefault();
                $('.please_wait').text('Please Wait..');
                // console.log("kdjksjdklsl");

                var formData = new FormData(this);

                    jQuery.ajax({
                        type: 'POST',
                        url: "{{ route('userSignUp') }}",
                        data: formData,
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                        },

                        success: function(response){
                            console.log(response);
                            if(response.status == 'success'){
                                // console.log(response)
                                $(".error_color").text("");
                                $('.please_wait').text('');
                                $('#register_success').text('A verification link is sent to your mail,please click to verify');
                                $('#register_success_error_div').show();
                                $('#signUpForm').trigger('reset');
                                // location.reload(); 
                                // window.location.href = "{{ route('login') }}";                                                      

                            }
                            if(response.status == 'email_reg'){
                                // console.log(response)                       
                                $('.email_error').text(response.msg); 
                                $('.please_wait').text('');
                                $(':input[type="submit"]').prop('disabled', false);                             

                            }
                            if(response.status == 'error'){
                                // console.log(response)
                                $('.please_wait').text('');
                                $('.g-recaptcha_error').text('');
                                $.each(response.msg,function(key,val){                                    
                                    if(response.msg.name){
                                        $('.name_error').text(response.msg.name)
                                    }
                                    if(response.msg.email){
                                        $('.email_error').text(response.msg.email)
                                    }                                    
                                    if(response.msg.password){
                                        $('.password_error').text(response.msg.password)
                                    }
                                    if(key == 'g-recaptcha-response'){
                                        $('.g-recaptcha_error').text(val)
                                    }                           
                                    $(':input[type="submit"]').prop('disabled', false);
                                    
                                });
                                
                            }
                        }
                    });

            });

        });
        

    </script>

@endsection
