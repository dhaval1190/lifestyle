@extends('frontend.layouts.app')
@section('login_active', 'active')

<style>
    .disabled-login-error{
        color:red;
    }
    .error{
        color:red;
    }
</style>
@section('styles')
    <!-- Start Google reCAPTCHA version 2 -->
    @if($site_global_settings->setting_site_recaptcha_login_enable == \App\Setting::SITE_RECAPTCHA_LOGIN_ENABLE)
        {!! htmlScriptTagJsApi(['lang' => empty(\Illuminate\Support\Facades\App::getLocale()) ? 'en' : \Illuminate\Support\Facades\App::getLocale()]) !!}
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

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">

                <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">


                    <div class="row justify-content-center mt-5">
                        <div class="col-md-10 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('auth.login') }}</h1>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section bg-light">
        <div class="container">

            @include('frontend.partials.alert')

            @if(is_demo_mode())
            <div class="row justify-content-center">
                <div class="col-12 col-md-7">
                    <div class="alert alert-info alert-dismissible" role="alert">
                        <p><strong>{{ __('demo_login.demo-login-credentials') }}</strong></p>
                        <hr>

                        <div class="row">
                            <div class="col-2"><u>{{ __('demo_login.admin-account') }}</u></div>
                            <div class="col-7 text-right">{{ \App\Setting::DEMO_ADMIN_LOGIN_EMAIL . " / " . \App\Setting::DEMO_ADMIN_LOGIN_PASSWORD }}</div>
                            <div class="col-3 text-right">
                                <a class="btn btn-sm btn-primary rounded text-white" id="copy-admin-login-button">
                                    <i class="fas fa-copy"></i>
                                    {{ __('demo_login.copy-to-login') }}
                                </a>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-2"><u>{{ __('demo_login.user-account') }}</u></div>
                            <div class="col-7 text-right">{{ \App\Setting::DEMO_USER_LOGIN_EMAIL . " / " . \App\Setting::DEMO_USER_LOGIN_PASSWORD }}</div>
                            <div class="col-3 text-right">
                                <a class="btn btn-sm btn-primary rounded text-white" id="copy-user-login-button">
                                    <i class="fas fa-copy"></i>
                                    {{ __('demo_login.copy-to-login') }}
                                </a>
                            </div>
                        </div>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-md-7 mb-5">

                    <form method="POST" action="{{ route('login') }}" class="p-3 p-sm-5 bg-white" id="kt_sign_in_form">
                        @csrf
                        @if(Session::has('email_not_verified'))
                            <div class="alert alert-danger" role="alert">
                                {{ Session::get('email_not_verified') }}
                            </div>
                        @endif
                        <div class="step-01 step_class">
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label class="text-black" for="email">{{ __('auth.email-addr') }}<span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div id="email_val" class="error"></div>
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-12">
                                    <label class="text-black" for="subject">{{ __('auth.password') }}<span class="text-danger">*</span></label>
                                    <input id="password" type="password" class="form-control set_padding_left_right_form @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    <div class="disabled-login-error mt-2"></div>    
                                    <span toggle="#password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
    
                                        <label class="form-check-label" for="remember">
                                            {{ __('auth.remember-me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        

                        <!-- Start Google reCAPTCHA version 2 -->
                        @if($site_global_settings->setting_site_recaptcha_login_enable == \App\Setting::SITE_RECAPTCHA_LOGIN_ENABLE)
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
                            <div class="col-12">
                                {{-- <p>{{ __('auth.no-account-yet') }}? <a href="{{ route('register') }}">{{ __('auth.register') }}</a></p> --}}
                                <p>{{ __('auth.no-account-yet') }}? <a href="{{ route('register') }}">{{ __('Sign up as a user') }}</a></p>
                            </div>                  
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <button type="button" id="kt_login_step_01" class="btn btn-primary py-2 px-4 text-white rounded">
                                        {{ __('auth.login') }}
                                    </button>
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('auth.forgot-password') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        </div>
                        @if($social_login_facebook || $social_login_google || $social_login_twitter || $social_login_linkedin || $social_login_github)
                            {{-- <div class="row mt-4 align-items-center">
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
                            </div> --}}
                        @endif

                        @if($social_login_facebook)
                        {{-- <div class="row form-group">
                            <div class="col-md-12">
                                <a class="btn btn-facebook btn-block text-white rounded" href="{{ route('auth.login.facebook') }}">
                                    <i class="fab fa-facebook-f pr-2"></i>
                                    {{ __('social_login.frontend.sign-in-facebook') }}
                                </a>
                            </div>
                        </div> --}}
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
                        <div class="step-02 step_class" style="display:none">
                            <!-- <div class="fv-row mb-10">
                                <label class="form-label fw-bold6er text-dark fs-6">Enter OTP sent to your email address</label>
                                <input class="form-control otp" id="otp" type="text" placeholder="" name="otp" autocomplete="off" required />
                                <div id="otp_val" class="error"></div>
                            </div> -->
                            <div class="text-center mb-10">
                                <h1 class="text-dark mb-3">
                                    Two-Factor Verification
                                </h1>
                                <div class="text-muted fw-semibold fs-5 mb-5">Enter the verification code we sent to your email</div>
                            </div>
                            <div class="fv-row mb-10">
                                <!-- <div class="fw-bold text-start text-dark fs-6 mb-1 ms-1">Type your 6 digit otp</div> -->
                                {{-- <div class="d-flex flex-wrap flex-stack">
                                    <input type="text" name="code_1" id="code_1" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                    <input type="text" name="code_2" id="code_2" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                    <input type="text" name="code_3" id="code_3" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                    <input type="text" name="code_4" id="code_4" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                    <input type="text" name="code_5" id="code_5" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                    <input type="text" name="code_6" id="code_6" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                </div>--}}
                                <div class="d-flex ap-otp-inputs justify-content-between" data-channel="email" data-length="6" data-form="login">
                                    <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="0" id="code_1">
                                    <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="1" id="code_2">
                                    <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="2" id="code_3">
                                    <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="3" id="code_4">
                                    <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="4" id="code_5">
                                    <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="5" id="code_6">
                                </div>
                                <div id="otp_val" class="error"></div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="fw-semibold fs-5 d-flex w-100 justify-content-center fw-semibold fs-5">
                                    <span class="text-muted me-1" id="resend_login_otp_title">Didn't get the otp ?</span>
                                    <a href="#" id="resend_login_otp" class="link-primary fs-5 me-1">
                                        <span class="indicator-label">{{ __('Resend') }}</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                        </span>
                                    </a>
                                    <div class="login_otp_countdown fw-semibold fs-5"></div>
                                </div>
                            </div>
                            <div class="pb-lg-0 pb-8 mt-3">
                                <button type="button" id="kt_login_step_02" class="btn btn-lg btn-info w-100 mb-5">
                                   Login
                                </button>
                            </div>
                            <a href="#" id="kt_login_prev_step_01" class="d-flex w-100 justify-content-center link-primary fs-5 me-1">
                                <span class="indicator-label">{{ __('Back To Sign in') }}</span>
                                    <!-- <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span> -->
                            </a>
                        </div>
                    </form>
                </div>

            </div>


        </div>
    </div>

@endsection

@section('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/inputmask/inputmask.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/inputmask/jquery.inputmask.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
    <!-- Youtube Background for Header -->
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif
    <script>

        $(document).ready(function(){
            $("#email").css('background-image', 'none');
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

            @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            @endif

            @if(is_demo_mode())
            $('#copy-admin-login-button').on('click', function(){
                $('#email').val("{{ \App\Setting::DEMO_ADMIN_LOGIN_EMAIL }}");
                $('#password').val("{{ \App\Setting::DEMO_ADMIN_LOGIN_PASSWORD }}");
            });

            $('#copy-user-login-button').on('click', function(){
                $('#email').val("{{ \App\Setting::DEMO_USER_LOGIN_EMAIL }}");
                $('#password').val("{{ \App\Setting::DEMO_USER_LOGIN_PASSWORD }}");
            });
            @endif
        });

    </script>

<script>
        Inputmask({
            "mask": "999999"
        }).mask(".otp");

        $('#resend_login_otp').on('click', function() {
            $("#resend_login_otp").attr('disabled', true);
            $("#resend_login_otp").attr('data-kt-indicator', 'on');
            $('#kt_login_step_01').trigger('click');
        });

        let interval_otp;

        $(".ap-otp-input").keyup(function(){
            $('#otp_val').text('');
            otp = $('#code_1').val() + $('#code_2').val()  + $('#code_3').val()  + $('#code_4').val()  + $('#code_5').val()  + $('#code_6').val() ;
            check_otp_length = otp.length;
            if(check_otp_length == 6){
                $('#kt_login_step_02').trigger('click');
            }
        });

        $("#kt_login_step_01").on('click', function() {
            $('#email_val').text('');
            if ($('#kt_sign_in_form').valid()) {
                $("#kt_login_step_01").attr('disabled', true);
                $("#kt_login_step_01").attr('data-kt-indicator', 'on');
                email = $('#email').val();
                password = $('#password').val();
                var email_from = email.substring(0, email.indexOf("@"));
                var num = (email_from.match(/\./g)) ? email_from.match(/\./g).length : 0;
                if(num <= 2)
                {
                    $.ajax({
                        url: "/send-login-email-otp",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "POST",
                        data: {
                            'email': email,
                            'password': password
                        },
                        success: function(response) {
                            console.log(response);

                            if (response.status==false) {
                                $("#kt_login_step_01").removeAttr('disabled');
                                $("#kt_login_step_01").removeAttr('data-kt-indicator');
                                $("#resend_login_otp").removeAttr('disabled');
                                $("#resend_login_otp").removeAttr('data-kt-indicator');
                                if(response.message){
                                    $('.disabled-login-error').text(response.message);
                                    $('.disabled-login-error').show();
                                }
                                // if (response.errors.email) {
                                //     $('#email_val').text(response.errors.email);
                                // }
                            }else{
                                $("#resend_login_otp_title").text("OTP send in");
                                setCountDownLoginOTP();
                                $("#resend_login_otp").hide();
                                $("#kt_login_step_01").removeAttr('disabled');
                                $("#kt_login_step_01").removeAttr('data-kt-indicator');
                                $("#resend_login_otp").removeAttr('disabled');
                                $("#resend_login_otp").removeAttr('data-kt-indicator');
                                $('.disabled-login-error').text('');
                                $('.disabled-login-error').hide();
                                $(".step-01").hide();
                                $(".step-02").show();
                                $("#code_1").focus();
                            }
                        },
                        error: function(err) {
                            $("#kt_login_step_01").removeAttr('disabled');
                            $("#kt_login_step_01").removeAttr('data-kt-indicator');
                            if(!err.responseJSON.status) {
                                $('.disabled-login-error').text(err.responseJSON.message);
                                $('.disabled-login-error').show();
                                // if (err.responseJSON.errors.email) {
                                //     $('#email_val').text(err.responseJSON.errors.email);
                                // }
                            }
                        }
                    });
                }
                else {
                    $("#kt_login_step_01").removeAttr('disabled');
                    $("#kt_login_step_01").removeAttr('data-kt-indicator');
                    $('#email_val').text('Invalid email !');
                }
            }
        });

        $("#kt_login_step_02").on('click', function() {
            if ($('#kt_sign_in_form').valid()) {
                $("#kt_login_step_02").attr('disabled', true);
                $("#kt_login_step_02").attr('data-kt-indicator', 'on');
                otp = $('#code_1').val() + $('#code_2').val() + $('#code_3').val() + $('#code_4').val() + $('#code_5').val() + $('#code_6').val();
                $.ajax({
                    url: "/verify-login-email-otp",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    data: {
                        'otp': otp
                    },
                    success: function(res) {
                        if (res.status == true) {
                            $('#kt_sign_in_form').submit();
                        } else {
                            $("#kt_login_step_02").removeAttr('disabled');
                            $("#kt_login_step_02").removeAttr('data-kt-indicator');
                            $('#otp_val').text(res.message);
                        }
                    },
                    error: function(err) {
                        $("#kt_login_step_02").removeAttr('disabled');
                        $("#kt_login_step_02").removeAttr('data-kt-indicator');
                    }
                });
            }
        });

        $("#kt_login_prev_step_01").on('click', function() {
            $('#otp_val').text('');
            $("#code_1").val('');
            $("#code_2").val('');
            $("#code_3").val('');
            $("#code_4").val('');
            $("#code_5").val('');
            $("#code_6").val('');
            clearInterval(interval_otp);
            $(".step-02").hide();	
            $(".step-01").show();	

        });	

        function setCountDownLoginOTP() {
            $(".login_otp_countdown").html('');
            $(".login_otp_countdown").show();
            var otp_timer2 = "1:00";
            interval_otp = setInterval(function() {
                var timer_otp = otp_timer2.split(':');
                //by parsing integer, I avoid all extra string processing
                var minutes = parseInt(timer_otp[0], 10);
                var seconds = parseInt(timer_otp[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) clearInterval(interval_otp);
                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                //minutes = (minutes < 10) ?  minutes : minutes;
                $('.login_otp_countdown').html(minutes + ':' + seconds);
                otp_timer2 = minutes + ':' + seconds;
                if(minutes < 0) {
                    $(".login_otp_countdown").hide();
                    clearInterval(interval_otp);
                    $("#resend_login_otp").show();
                    $("#resend_login_otp_title").text("Didn't get the otp?");
                }
            }, 1000);
        }

        const $inp = $(".ap-otp-input");
        $inp.on({
            paste(ev) { // Handle Pasting
                const clip = ev.originalEvent.clipboardData.getData('text').trim();
                // Allow numbers only
                if (!/\d{6}/.test(clip)) return ev.preventDefault(); // Invalid. Exit here
                // Split string to Array or characters
                const s = [...clip];
                // Populate inputs. Focus last input.
                $inp.val(i => s[i]).eq(5).focus();
            },
            input(ev) { // Handle typing
                const i = $inp.index(this);
                if (this.value) $inp.eq(i + 1).focus();
            },
            keydown(ev) { // Handle Deleting
                const i = $inp.index(this);
                if (!this.value && ev.key === "Backspace" && i) $inp.eq(i - 1).focus();
            }
        });
</script>

@endsection
