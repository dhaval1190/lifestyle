@extends('frontend.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
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
                        <div class="col-md-8 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('theme_directory_hub.pricing.head-title') }}</h1>
                            <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ __('theme_directory_hub.pricing.head-description') }}</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">

            @if(!empty($login_user))
                <div class="row justify-content-center">
                    <div class="col-10 col-md-12">
                        <div class="alert alert-info" role="alert">
                            @if($login_user->isAdmin())
                               {{ __('theme_directory_hub.pricing.info-admin') }}
                            @else
                                @if($login_user->hasPaidSubscription())
                                    {{ __('theme_directory_hub.pricing.info-user-paid', ['site_name' => $site_name]) }}
                                @else
                                    {{ __('theme_directory_hub.pricing.info-user-free', ['site_name' => $site_name]) }}
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="row justify-content-center">
                @foreach($plans as $plans_key => $plan)
                    <div class="col-10 col-md-6 col-lg-3">
                        <div class="card mb-4 box-shadow text-center">
                            <div class="card-header">
                                <h4 class="my-0 font-weight-normal">
                                    @if(!empty($login_user))
                                        @if($login_user->isUser())

                                            @if($login_user->hasPaidSubscription())
                                                @if($login_user->subscription->plan->id == $plan->id)
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                @endif
                                            @else
                                                @if($plan->plan_type == \App\Plan::PLAN_TYPE_FREE)
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                @endif
                                            @endif

                                        @endif
                                    @endif

                                    {{ $plan->plan_name }}
                                </h4>
                            </div>
                            <div class="card-body">
                                <h1 class="card-title pricing-card-title">{{ $site_global_settings->setting_product_currency_symbol . $plan->plan_price }}
                                    {{-- <small class="text-muted">/
                                        @if($plan->plan_period == \App\Plan::PLAN_LIFETIME)
                                            {{ __('backend.plan.lifetime') }}
                                        @elseif($plan->plan_period == \App\Plan::PLAN_MONTHLY)
                                            {{ __('backend.plan.monthly') }}
                                        @elseif($plan->plan_period == \App\Plan::PLAN_QUARTERLY)
                                            {{ __('backend.plan.quarterly') }}
                                        @else
                                            {{ __('backend.plan.yearly') }}
                                        @endif
                                    </small> --}}
                                </h1>
                                <ul class="list-unstyled mt-3 mb-4">
                                    @if(is_null($plan->plan_max_free_listing))
                                        <li>
                                            {{ __('theme_directory_hub.plan.unlimited') . ' ' . __('theme_directory_hub.plan.free-listing') }}
                                        </li>
                                    @else
                                        <li>
                                            {{ $plan->plan_max_free_listing . ' ' . __('theme_directory_hub.plan.free-listing') }}
                                        </li>
                                    @endif

                                    @if(is_null($plan->plan_max_featured_listing))
                                        <li>
                                            {{ __('theme_directory_hub.plan.unlimited') . ' ' . __('theme_directory_hub.plan.featured-listing') }}
                                        </li>
                                    @else
                                        <li>
                                            {{ $plan->plan_max_featured_listing . ' ' . __('theme_directory_hub.plan.featured-listing') }}
                                        </li>
                                    @endif

                                    @if(!empty($plan->plan_features))
                                        @php
                                            $plan_features_array = [];
                                            foreach(preg_split("/((\r?\n)|(\r\n?))/", $plan->plan_features) as $plan_features_line)
                                            {
                                                $plan_features_array[] = $plan_features_line;
                                            }
                                        @endphp

                                        @foreach($plan_features_array as $plan_features_array_key => $a_plan_feature)
                                            <li>
                                                {{ $a_plan_feature }}
                                            </li>
                                        @endforeach

                                    @endif
                                </ul>

                                    @if($plan->plan_type == \App\Plan::PLAN_TYPE_FREE)
                                        @if(empty($login_user))
                                            <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.items.create') }}">
                                                <i class="fas fa-plus mr-1"></i>
                                                {{ __('frontend.header.list-business') }}
                                            </a>
                                        @else
                                            @if($login_user->isAdmin())
                                                <a class="btn btn-block btn-primary text-white rounded" href="{{ route('admin.plans.index') }}">
                                                    <i class="fas fa-tasks"></i>
                                                    {{ __('theme_directory_hub.pricing.manage-pricing') }}
                                                </a>
                                            @else
                                                @if(!$login_user->hasPaidSubscription())
                                                    <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.items.create') }}">
                                                        <i class="fas fa-plus mr-1"></i>
                                                        {{ __('frontend.header.list-business') }}
                                                    </a>
                                                @endif
                                            @endif
                                        @endif
                                    @else

                                        @if(empty($login_user))
                                            <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.subscriptions.index') }}">
                                                <i class="fas fa-plus mr-1"></i>
                                                {{ __('theme_directory_hub.pricing.get-started') }}
                                            </a>
                                        @else
                                            @if($login_user->isAdmin())
                                                <a class="btn btn-block btn-primary text-white rounded" href="{{ route('admin.plans.index') }}">
                                                    <i class="fas fa-tasks"></i>
                                                    {{ __('theme_directory_hub.pricing.manage-pricing') }}
                                                </a>
                                            @else

                                                @if($login_user->hasPaidSubscription())

                                                    @if($login_user->subscription->plan->id == $plan->id)
                                                        <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.items.create') }}">
                                                            <i class="fas fa-plus mr-1"></i>
                                                            {{ __('frontend.header.list-business') }}
                                                        </a>
                                                    @endif

                                                @else
                                                    <a class="btn btn-block btn-primary rounded text-white" href="{{ route('user.subscriptions.edit', ['subscription' => $login_user->subscription->id]) }}">
                                                        <i class="fas fa-shopping-cart"></i>
                                                        {{ __('theme_directory_hub.pricing.upgrade') }}
                                                    </a>
                                                @endif

                                            @endif
                                        @endif

                                    @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(empty($login_user))
                @include('frontend.partials.alert')
                <div class="row justify-content-center">
                    <div class="col-md-12 mb-5">
                        <form method="POST" action="{{ route('register') }}" class="bg-white">
                            @csrf
                            <input type="hidden" name="is_coach" value="2">

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="name" class="text-black">{{ __('auth.name') }}</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-black" for="email">{{ __('auth.email-addr') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label for="phone" class="text-black">Phone</label>
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="off" required>
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="gender" class="text-black">Gender</label>
                                    <select class="custom-select @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }} >Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }} >Female</option>
                                    </select>
                                    @error('gender')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label class="text-black" for="subject">{{ __('auth.password') }}</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-black" for="password-confirm">{{ __('auth.confirm-password') }}</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="category_id" class="text-black">Category</label>
                                    <select class="custom-select @error('category_id') is-invalid @enderror" name="category_id" required>
                                        <option value="">Select Category</option>
                                    @foreach($printable_categories as $key => $printable_category)
                                        @php
                                            if(empty($printable_category["is_parent"])) continue;
                                        @endphp
                                        <option value="{{ $printable_category["category_id"] }}" {{ old('category_id') == $printable_category["category_id"] ? 'selected' : '' }}>{{ $printable_category["category_name"] }}</option>
                                    @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="experience_year" class="text-black">Experience Year</label>
                                    <select class="custom-select @error('experience_year') is-invalid @enderror" name="experience_year" required>
                                        <option value="">Select Experience</option>
                                        <option value="0-2" {{ old('experience_year') == '0-2' ? 'selected' : '' }}>0-2</option>
                                        <option value="3-5" {{ old('experience_year') == '3-5' ? 'selected' : '' }}>3-5</option>
                                        <option value="5-10" {{ old('experience_year') == '5-10' ? 'selected' : '' }}>5-10</option>
                                        <option value="10+" {{ old('experience_year') == '10+' ? 'selected' : '' }}>10 or more</option>
                                    </select>
                                    @error('experience_year')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="availability" class="text-black">Availability</label>
                                    <select class="custom-select @error('availability') is-invalid @enderror" name="availability" required>
                                        <option value="">Select Availability</option>
                                        <option value="midweek" {{ old('availability') == '0-2' ? 'selected' : '' }}>Midweek (Normal Biz hours M-F)</option>
                                        <option value="weekends" {{ old('availability') == '3-5' ? 'selected' : '' }}>Weekends</option>
                                        <option value="nights" {{ old('availability') == '5-10' ? 'selected' : '' }}>Nights</option>
                                        <option value="early-am" {{ old('availability') == '10+' ? 'selected' : '' }}>Early AM</option>
                                    </select>
                                    @error('availability')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="hourly_rate" class="text-black">Hourly Rate</label>
                                    <input id="hourly_rate" type="number" class="form-control @error('hourly_rate') is-invalid @enderror" name="hourly_rate" value="{{ old('hourly_rate') }}" autocomplete="off" required min="0.00">
                                    @error('hourly_rate')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label for="address" class="text-black">Address</label>
                                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" autocomplete="off" required>
                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="country_id" class="text-black">Country</label>
                                    <select id="select_country_id" class="selectpicker form-control @error('country_id') is-invalid @enderror" name="country_id" data-live-search="true" required>
                                        <option selected value="0">{{ __('prefer_country.select-country') }}</option>
                                        @foreach($all_countries as $all_countries_key => $country)
                                            @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE)
                                                <option value="{{ $country->id }}" {{ $country->id == old('country_id') ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="state_id" class="text-black">State</label>
                                    <select id="select_state_id" class="selectpicker form-control @error('state_id') is-invalid @enderror" name="state_id" data-live-search="true" required>
                                        <option selected value="0">{{ __('backend.item.select-state') }}</option>
                                    </select>
                                    @error('state_id')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label for="city_id" class="text-black">City</label>
                                    <select id="select_city_id" class="selectpicker form-control @error('city_id') is-invalid @enderror" name="city_id" data-live-search="true" required>
                                        <option selected value="0">{{ __('backend.item.select-city') }}</option>
                                    </select>
                                    @error('city_id')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6">
                                    <label for="post_code" class="text-black">Post Code</label>
                                    <input id="post_code" type="text" class="form-control @error('post_code') is-invalid @enderror" name="post_code" value="{{ old('post_code') }}" autocomplete="off" required>
                                    @error('post_code')
                                    <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

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
                                <div class="col-12">
                                    <p>{{ __('auth.have-an-account') }}? <a href="{{ route('login') }}">{{ __('auth.login') }}</a></p>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary py-2 px-4 text-white rounded">Signup as Coach
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>

@endsection

@section('scripts')

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <!-- Youtube Background for Header -->
            <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif

    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('backend.user.partials.bootstrap-select-locale')

    <script>

        $(document).ready(function(){

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

            /**
             * Start country, state, city selector
             */
            $('#select_country_id').on('change', function() {

                $('#select_state_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#select_state_id').selectpicker('refresh');

                if(this.value > 0)
                {
                    var ajax_url = '/ajax/states/' + this.value;

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        data: {
                        },
                        success: function(result){

                            $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                            $.each(JSON.parse(result), function(key, value) {
                                var state_id = value.id;
                                var state_name = value.state_name;
                                $('#select_state_id').append('<option value="'+ state_id +'">' + state_name + '</option>');
                            });
                            $('#select_state_id').selectpicker('refresh');
                        }});
                }

            });

            $('#select_state_id').on('change', function() {

                $('#select_city_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#select_city_id').selectpicker('refresh');

                if(this.value > 0)
                {
                    var ajax_url = '/ajax/cities/' + this.value;

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        data: {
                        },
                        success: function(result){

                            $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                            $('#select_city_id').selectpicker('refresh');
                    }});
                }

            });

            @if(old('country_id'))
                var ajax_url_initial_states = '/ajax/states/{{ old('country_id') }}';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: ajax_url_initial_states,
                    method: 'get',
                    data: {
                    },
                    success: function(result){

                        $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                        $.each(JSON.parse(result), function(key, value) {
                            var state_id = value.id;
                            var state_name = value.state_name;

                            if(state_id === {{ old('state_id') }})
                            {
                                $('#select_state_id').append('<option value="'+ state_id +'" selected>' + state_name + '</option>');
                            }
                            else
                            {
                                $('#select_state_id').append('<option value="'+ state_id +'">' + state_name + '</option>');
                            }

                        });
                        $('#select_state_id').selectpicker('refresh');
                }});
            @endif

            @if(old('state_id'))
                var ajax_url_initial_cities = '/ajax/cities/{{ old('state_id') }}';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: ajax_url_initial_cities,
                    method: 'get',
                    data: {
                    },
                    success: function(result){

                        $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                        $.each(JSON.parse(result), function(key, value) {
                            var city_id = value.id;
                            var city_name = value.city_name;

                            if(city_id === {{ old('city_id') }})
                            {
                                $('#select_city_id').append('<option value="'+ city_id +'" selected>' + city_name + '</option>');
                            }
                            else
                            {
                                $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            }
                        });
                        $('#select_city_id').selectpicker('refresh');
                }});
            @endif
            /**
             * End country, state, city selector
             */

        });

    </script>
@endsection
