@extends('frontend.layouts.app')

@section('styles')
    {{-- <link href="{{ asset('frontend/vendor/smart-wizard/smart_wizard.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('frontend/vendor/smart-wizard/smart_wizard_theme_dots.min.css') }}" rel="stylesheet"/> --}}
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/css/bootstrap-select.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style type="text/css">
        .bootstrap-select.form-control { border: 1px solid #ced4da; }
        .dropdown-toggle.btn-default {
            color: #292b2c;
            background-color: #fff;
            border-color: #ccc;
        }

        .bootstrap-select.show>.dropdown-menu>.dropdown-menu {
            display: block;
        }

        div.dropdown-menu.open{
            max-height: 314px !important;
            overflow: hidden;
        }
        ul.dropdown-menu.inner{
            max-height: 260px !important;
            overflow-y: auto;
        }

        .bootstrap-select > .dropdown-menu > .dropdown-menu li.hidden{
            display:none;
        }

        .bootstrap-select > .dropdown-menu > .dropdown-menu li a{
            display: block;
            width: 100%;
            padding: 3px 1.5rem;
            clear: both;
            font-weight: 400;
            color: #292b2c;
            text-align: inherit;
            white-space: nowrap;
            background: 0 0;
            border: 0;
        }

        .dropdown-menu > li.active > a {
            color: #fff !important;
            background-color: #337ab7 !important;
        }

        .bootstrap-select .check-mark::after {
            content: "✓";
        }

        .bootstrap-select button {
            overflow: hidden;
            text-overflow: ellipsis;
        }


        /* Make filled out selects be the same size as empty selects */
        .bootstrap-select.btn-group .dropdown-toggle .filter-option {
        display: inline !important;
        }
        .error_color{
            color: red;
        }
    </style>
@endsection

@section('content')

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ asset('frontend/images/placeholder/header-inner.webp') }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: {{ $site_innerpage_header_background_color }};">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image) }});">

    {{-- @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: #333333;"> --}}
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
            @include('frontend.partials.alert')

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
                        <div class="card mb-4 box-shadow text-center" style="min-height: 300px;">
                            <div class="card-header">
                                <h4 class="my-0 font-weight-normal">
                                    @if(!empty($login_user))
                                        @if($login_user->isUser() || $login_user->isCoach())
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
                                        <li>{{ __('theme_directory_hub.plan.unlimited') . ' ' . __('theme_directory_hub.plan.free-listing') }}</li>
                                    @else
                                        <li>{{ $plan->plan_max_free_listing . ' ' . __('theme_directory_hub.plan.free-listing') }}</li>
                                    @endif

                                    {{-- @if(is_null($plan->plan_max_featured_listing))
                                        <li>{{ __('theme_directory_hub.plan.unlimited') . ' ' . __('theme_directory_hub.plan.featured-listing') }}</li>
                                    @else
                                        <li>{{ $plan->plan_max_featured_listing . ' ' . __('theme_directory_hub.plan.featured-listing') }}</li>
                                    @endif --}}

                                    @if(!empty($plan->plan_features))
                                        @php
                                            $plan_features_array = [];
                                            foreach(preg_split("/((\r?\n)|(\r\n?))/", $plan->plan_features) as $plan_features_line) {
                                                $plan_features_array[] = $plan_features_line;
                                            }
                                        @endphp
                                        @foreach($plan_features_array as $plan_features_array_key => $a_plan_feature)
                                            <li>{{ $a_plan_feature }}</li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="card-footer" style="border: 0; background: #fff;">
                                @if($plan->plan_type == \App\Plan::PLAN_TYPE_FREE)
                                    @if(empty($login_user))
                                        {{-- <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.items.create') }}">
                                            <i class="fas fa-plus mr-1"></i>
                                            {{ __('frontend.header.list-business') }}
                                        </a> --}}
                                        <a href="{{ route('page.register') }}" class="btn btn-block btn-primary text-white rounded coachRegistration>
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
                                            {{-- @if(!$login_user->hasPaidSubscription())
                                                <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.items.create') }}">
                                                    <i class="fas fa-plus mr-1"></i>
                                                    {{ __('frontend.header.list-business') }}
                                                </a>
                                            @endif --}}
                                        @endif
                                    @endif
                                @else
                                    @if(empty($login_user))
                                        {{-- <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.subscriptions.index') }}">
                                            <i class="fas fa-plus mr-1"></i>
                                            {{ __('theme_directory_hub.pricing.get-started') }}
                                        </a> --}}
                                        {{-- <a href="javascript:void" class="btn btn-block btn-primary text-white rounded coachRegistration" data-plan_id="{{$plan->id}}">
                                            <i class="fas fa-plus mr-1"></i>
                                            {{ __('theme_directory_hub.pricing.get-started') }}
                                        </a> --}}
                                    @else
                                        @if($login_user->isAdmin())
                                            <a class="btn btn-block btn-primary text-white rounded" href="{{ route('admin.plans.index') }}">
                                                <i class="fas fa-tasks"></i>
                                                {{ __('theme_directory_hub.pricing.manage-pricing') }}
                                            </a>
                                        @else
                                            @if($login_user->hasPaidSubscription())
                                                {{-- @if($login_user->subscription->plan->id == $plan->id)
                                                    <a class="btn btn-block btn-primary text-white rounded" href="{{ route('user.items.create') }}">
                                                        <i class="fas fa-plus mr-1"></i>
                                                        {{ __('frontend.header.list-business') }}
                                                    </a>
                                                @endif --}}
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
                <div class="modal fade" id="coachRegistrationModal" tabindex="-1" role="dialog" aria-labelledby="coachRegistrationModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="min-width: 1480px;">
                        <div class="modal-content" style="border-color: #f05127;border-width: medium;">
                            <div class="modal-header">
                                <h5 class="modal-title" id="coachRegistrationModalLabel">Coach Registration</h5><p class="email_reg error_color"></p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method="POST" class="bg-white" id="coachRegistrationForm" name="coachRegistrationForm" enctype="multipart/form-data">
                                <div class="modal-body" style="max-height: 600px; overflow: scroll;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="is_coach" value="{{ \App\Role::COACH_ROLE_ID }}">
                                    <input type="hidden" name="plan_id" id="plan_id" value="{{ old('plan_id') }}">

                                    <div class="row mt-3">
                                        <div class="col-sm-2">
                                            <div class="text-center">
                                                <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2" accept=".jpg,.jpeg,.png">Select Profile Picture</button>
                                                <img id="image_preview" src="{{ asset('backend/images/placeholder/profile-1.webp')}}" class="img-responsive" width="200px">
                                                <input id="feature_image" type="hidden" name="user_image" id="user_image" accept=".jpg,.jpeg,.png">
                                            </div>
                                            {{-- <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="upload_image_input" name="user_image" accept=".jpg,.png">
                                                <label class="custom-file-label" for="upload_image_input">Upload Profile Picture</label>
                                            </div> --}}
                                            @error('user_image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-10">
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <label for="category_ids" class="text-black">Category <span class="text-danger">*</span></label>
                                                    <!-- <select class="form-control selectpicker-category @error('category_ids') is-invalid @enderror" name="category_ids[]" required multiple title="Select Categories" data-size="10" data-live-search="true"> -->
                                                    <select class="form-control form-select category_ids @error('category_ids') is-invalid @enderror" name="category_ids[]" id="category_ids" multiple style="width:100%">
                                                        {{-- <option value="">Select Category</option> --}}
                                                        @foreach($printable_categories as $key => $printable_category)
                                                            @php
                                                                if(empty($printable_category["is_parent"])) continue;
                                                            @endphp
                                                            <option value="{{ $printable_category["category_id"] }}" {{ in_array($printable_category["category_id"] ,old('category_ids', [])) ? 'selected' : '' }}>{{ $printable_category["category_name"] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="category_error error_color" role="alert"></p>
                                                    {{-- @error('category_ids')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror --}}
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-3">
                                                    <label for="name" class="text-black">{{ __('auth.name') }} <span class="text-danger">*</span></label>
                                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}">
                                                    <p class="name_error error_color" role="alert"></p>
                                                    {{-- @error('name')
                                                    <span class="invalid-feedback name_error" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror --}}
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="company_name" class="text-black">Company Name</label>
                                                    <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}">
                                                    <p class="company_error error_color" role="alert"></p>
                                                    {{-- @error('company_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror --}}
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="text-black" for="email">{{ __('auth.email-addr') }} <span class="text-danger">*</span></label>
                                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}">
                                                    <p class="email_error error_color" role="alert"></p>
                                                    {{-- @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror --}}
                                                </div>
                                                {{-- <div class="col-sm-3">
                                                    <label for="phone" class="text-black">Phone <span class="text-danger">*</span></label>
                                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                                                    <p class="phone_error error_color" role="alert"></p> --}}
                                                    {{-- @error('phone')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror --}}
                                                {{-- </div> --}}
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-3">
                                                    <label class="text-black" for="subject">{{ __('auth.password') }} <span class="text-danger">*</span></label>
                                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                                    <p class="password_error error_color" role="alert"></p>
                                                    {{-- @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror --}}
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="text-black" for="password-confirm">{{ __('auth.confirm-password') }} <span class="text-danger">*</span></label>
                                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="preferred_pronouns" class="text-black">Preferred Pronouns<span class="text-danger">*</span></label>
                                                    <select class="form-control selectpicker @error('preferred_pronouns') is-invalid @enderror" name="preferred_pronouns" id="preferred_pronouns" title="Select Preferred Pronouns">
                                                        @foreach(\App\User::PREFERRED_PRONOUNS as $prkey => $pronoun)
                                                            <option value="{{ $prkey }}" {{ old('preferred_pronouns') == $prkey ? 'selected' : '' }} >{{ $pronoun }}</option>
                                                        @endforeach
                                                    </select>
                                                    <p class="preferred_pronouns_error error_color" role="alert"></p>
                                                    {{-- @error('preferred_pronouns')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror --}}
                                                </div>
                                                <div class="col-sm-3">
                                                    <label for="website" class="text-black">Website</label>
                                                    <input id="website" type="url" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website') }}">
                                                    @error('website')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-sm-3">
                                            <label for="instagram" class="text-black">IG Handle</label>
                                            <input id="instagram" type="text" class="form-control @error('instagram') is-invalid @enderror" name="instagram" value="{{ old('instagram') }}">
                                            @error('instagram')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="linkedin" class="text-black">LinkedIn</label>
                                            <input id="linkedin" type="text" class="form-control @error('linkedin') is-invalid @enderror" name="linkedin" value="{{ old('linkedin') }}">
                                            @error('linkedin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="facebook" class="text-black">Facebook</label>
                                            <input id="facebook" type="text" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{ old('facebook') }}">
                                            @error('facebook')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="youtube" class="text-black">Youtube</label>
                                            <input id="youtube" type="url" class="form-control @error('youtube') is-invalid @enderror" name="youtube" value="{{ old('youtube') }}">
                                            @error('youtube')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-sm-3">
                                            <label for="hourly_rate_type" class="text-black">Hourly Rate <span class="text-danger">*</span></label>
                                            <select class="form-control selectpicker @error('hourly_rate_type') is-invalid @enderror" name="hourly_rate_type" id="hourly_rate_type" title="Select Hourly Rate">
                                                @foreach(\App\User::HOURLY_RATES as $hrkey => $rate)
                                                    <option value="{{ $hrkey }}" {{ old('hourly_rate_type') == $hrkey ? 'selected' : '' }} >{{ $rate }}</option>
                                                @endforeach
                                            </select>
                                            <p class="hourly_rate_type_error error_color" role="alert"></p>
                                            {{-- @error('hourly_rate_type')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror --}}
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="working_type" class="text-black">Working Method <span class="text-danger">*</span></label>
                                            <select class="form-control selectpicker @error('working_type') is-invalid @enderror" name="working_type" id="working_type" title="Select Working Method">
                                                @foreach(\App\User::WORKING_TYPES as $wtkey => $working_type)
                                                    <option value="{{ $wtkey }}" {{ old('working_type') == $wtkey ? 'selected' : '' }} >{{ $working_type }}</option>
                                                @endforeach
                                            </select>
                                            <p class="working_type_error error_color" role="alert"></p>
                                            {{-- @error('working_type')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror --}}
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="experience_year" class="text-black">Experience Year <span class="text-danger">*</span></label>
                                            <select class="form-control selectpicker @error('experience_year') is-invalid @enderror" name="experience_year" id="experience_year" title="Select Experience">
                                                @foreach(\App\User::EXPERIENCE_YEARS as $eykey => $experience_year)
                                                    <option value="{{ $eykey }}" {{ old('experience_year') == $eykey ? 'selected' : '' }} >{{ $experience_year }}</option>
                                                @endforeach
                                            </select>
                                            <p class="experience_year_error error_color" role="alert"></p>
                                            {{-- @error('experience_year')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror --}}
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="address" class="text-black">Address <span class="text-danger">*</span></label>
                                            <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}">
                                            <p class="address_error error_color" role="alert"></p>
                                            {{-- @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror --}}
                                        </div>
                                    </div>

                                    {{-- <div class="row mt-3">
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
                                            <input id="hourly_rate" type="number" class="form-control @error('hourly_rate') is-invalid @enderror" name="hourly_rate" value="{{ old('hourly_rate') }}" required min="0.00">
                                            @error('hourly_rate')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    <div class="row mt-3">
                                        <div class="col-sm-3">
                                            <label for="country_id" class="text-black">Country <span class="text-danger">*</span></label>
                                            <select id="select_country_id" class="selectpicker form-control @error('country_id') is-invalid @enderror" name="country_id" data-live-search="false" title="{{ __('prefer_country.select-country') }}">
                                                @foreach($all_countries as $all_countries_key => $country)
                                                    @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE)
                                                        <option value="{{ $country->id }}" {{ $country->id == old('country_id') ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <p class="country_error error_color" role="alert"></p>
                                            {{-- @error('country_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror --}}
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="state_id" class="text-black">State <span class="text-danger">*</span></label>
                                            <select id="select_state_id" class="selectpicker form-control @error('state_id') is-invalid @enderror" name="state_id" data-live-search="true" data-size="10" title="{{ __('backend.item.select-state') }}">
                                            </select>
                                            <p class="state_error error_color" role="alert"></p>
                                            {{-- @error('state_id')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror --}}
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="city_id" class="text-black">City <span class="text-danger">*</span></label>
                                            <select id="select_city_id" class="selectpicker form-control @error('city_id') is-invalid @enderror" name="city_id" data-live-search="true" data-size="10" title="{{ __('backend.item.select-city') }}">
                                            </select>
                                            <p class="city_error error_color" role="alert"></p>
                                            {{-- @error('city_id')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror --}}
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="zip" class="text-black">Post Code <span class="text-danger">*</span></label>
                                            <input id="zip" type="text" class="form-control zip @error('post_code') is-invalid @enderror" name="post_code" value="{{ old('post_code') }}">
                                            <p class="zip_error error_color" role="alert"></p>
                                            {{-- @error('post_code')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror --}}
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_terms_read" id="is_terms_read" value="1" required>

                                                <label class="form-check-label" for="i_agree">
                                                    I Agree <a href="agreement" target="_blank">BETA CONTENT CREATOR AGREEMENT</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    @if($site_global_settings->setting_site_recaptcha_sign_up_enable == \App\Setting::SITE_RECAPTCHA_SIGN_UP_ENABLE)
                                        <div class="row">
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
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Signup</button>
                                    <button type="reset" class="btn btn-default" style="border-color: black;">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-color: #f05127;border-width: medium;">
                            <div class="modal-header">
                                {{-- <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.user.crop-profile-image') }}</h5> --}}
                                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Upload profile image') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div id="image_demo"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="custom-file">
                                            <input id="upload_image_input" type="file" class="custom-file-input" accept=".jpg,.jpeg,.png">
                                            <label class="custom-file-label" for="upload_image_input">{{ __('backend.user.choose-image') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                                <button id="crop_image" type="button" class="btn btn-primary">{{ __('backend.user.crop-image') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('scripts')
    {{-- @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif --}}

    @if(empty($login_user))
        {{-- <script src="{{ asset('frontend/vendor/smart-wizard/jquery.smartWizard.min.js') }}"></script> --}}
        <!-- <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/js/bootstrap-select.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @include('backend.user.partials.bootstrap-select-locale')
        <script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>
    @endif

    <script>

        $(document).ready(function(){

            "use strict";
            $('.error_color').text('');
            $('#name').on('input', function(e) {
                $('.name_error').text('')
            });
            $('#email').on('input', function(e) {
                $('.email_error').text('');
            });
            $('#phone').on('input', function(e) {
                $('.phone_error').text('');
            });
            $('#password').on('input', function(e) {
                $('.password_error').text('');
            });
            $('#zip').on('input', function(e) {
                $('.zip_error').text('');
            });
            $('#address').on('input', function(e) {
                $('.address_error').text('');
            });
            $('#preferred_pronouns').change(function(e) {
                $('.preferred_pronouns_error').text('');
            });
            $('#hourly_rate_type').change(function(e) {
                $('.hourly_rate_type_error').text('');
            });
            $('#working_type').change(function(e) {
                $('.working_type_error').text('');
            });
            $('#experience_year').change(function(e) {
                $('.experience_year_error').text('');
            });
            $('#select_city_id').change(function(e) {
                $('.city_error').text('');
            });
            $('#category_ids').change(function(e) {
                $('.category_error').text('');
            });




            @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
                // $("[data-youtube]").youtube_background();
            @endif

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @if(empty($login_user))

                /*$('#smartwizard').smartWizard({
                    selected: 0,
                    theme: 'dots',
                    autoAdjustHeight:true,
                    transitionEffect:'fade',
                    showStepURLhash: false,
                    toolbar: {
                      showNextButton: true, // show/hide a Next button
                      showPreviousButton: true, // show/hide a Previous button
                      position: 'bottom', // none/ top/ both bottom
                      extraHtml: `<button class="btn btn-success" id="btnFinish" disabled onclick="onConfirm()">Finish</button>`
                    },
                });

                $("#smartwizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
                });

                $("#smartwizard").on("leaveStep", function(e, anchorObject, currentStepIndex, nextStepIndex, stepDirection) {
                    return confirm("Do you want to leave the step " + currentStepIndex + "?");
                });*/

                $('.category_ids').select2({
                    maximumSelectionLength: 3
                });

                $('.selectpicker-category').selectpicker({
                    maxOptions: 3,
                    size: 'auto',
                    
                });

                $('.selectpicker').selectpicker();

                /* Start the croppie image plugin */
                var image_crop = null;
                var window_height = $(window).height();
                var window_width = $(window).width();
                var viewport_height = 0;
                var viewport_width = 0;
                if(window_width >= 800) {
                    viewport_width = 800;
                    viewport_height = 687;
                } else {
                    viewport_width = window_width * 0.8;
                    viewport_height = (viewport_width * 687) / 800;
                }

                $('#upload_image').on('click', function(){
                    $('#image-crop-modal').modal('show');
                });

                $('#upload_image_input').on('change', function() {
                    if(!image_crop) {
                        image_crop = $('#image_demo').croppie({
                            enableExif: true,
                            mouseWheelZoom: false,
                            viewport: {
                                width: 600,
                                height: 600,
                                type: 'square'
                            },
                            boundary: {
                                width: 700,
                                height: 700
                            },
                            enableOrientation: true
                        });
                    }
                    var reader = new FileReader();
                    reader.onload = function (event) {
                        image_crop.croppie('bind', {
                            url: event.target.result
                        }).then(function(){
                        });
                    };
                    reader.readAsDataURL(this.files[0]);
                });

                $('#crop_image').on("click", function(event) {
                    image_crop.croppie('result', {
                        type: 'base64',
                        size: 'viewport'
                    }).then(function(response){
                        $('#feature_image').val(response);
                        $('#image_preview').attr("src", response);
                    });
                    $('#image-crop-modal').modal('hide')
                });
                /* End the croppie image plugin */

                /* Start country, state, city selector */
                $('#select_country_id').on('change', function() {
                    $('.country_error').text('');
                    $('#select_state_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                    $('#select_state_id').selectpicker('refresh');
                    if(this.value > 0) {
                        var ajax_url = 'ajax/states/' + this.value;
                        jQuery.ajax({
                            url: ajax_url,
                            method: 'get',
                            success: function(result) {
                                // $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                                $('#select_state_id').empty();
                                $.each(JSON.parse(result), function(key, value) {
                                    var state_id = value.id;
                                    var state_name = value.state_name;
                                    $('#select_state_id').append('<option value="'+ state_id +'">' + state_name + '</option>');
                                });
                                $('#select_state_id').selectpicker('refresh');
                            }
                        });
                    }
                });

                $('#select_state_id').on('change', function() {
                    $('.state_error').text('');
                    $('#select_city_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                    $('#select_city_id').selectpicker('refresh');
                    if(this.value > 0) {
                        var ajax_url = 'ajax/cities/' + this.value;
                        jQuery.ajax({
                            url: ajax_url,
                            method: 'get',
                            success: function(result) {
                                // $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                                $('#select_city_id').empty();
                                $.each(JSON.parse(result), function(key, value) {
                                    var city_id = value.id;
                                    var city_name = value.city_name;
                                    $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                                });
                                $('#select_city_id').selectpicker('refresh');
                            }
                        });
                    }
                });

                @if(old('country_id'))
                    var ajax_url_initial_states = '/ajax/states/{{ old('country_id') }}';
                    jQuery.ajax({
                        url: ajax_url_initial_states,
                        method: 'get',
                        success: function(result) {
                            // $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                            $('#select_state_id').empty();
                            $.each(JSON.parse(result), function(key, value) {
                                var state_id = value.id;
                                var state_name = value.state_name;
                                $('#select_state_id').append('<option value="'+ state_id +'">' + state_name + '</option>');
                            });
                            @if(old('state_id'))
                                $('#select_state_id').val("{{ old('state_id', '') }}").selectpicker('refresh');
                                $('#select_state_id').val("{{ old('state_id', '') }}").selectpicker('refresh');
                            @endif
                        }
                    });
                @endif

                @if(old('state_id'))
                    var ajax_url_initial_cities = '/ajax/cities/{{ old('state_id') }}';
                    jQuery.ajax({
                        url: ajax_url_initial_cities,
                        method: 'get',
                        success: function(result) {
                            // $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                            $('#select_city_id').empty();
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                            @if(old('city_id'))
                                $('#select_city_id').val("{{ old('city_id', '') }}").selectpicker('refresh');
                                $('#select_city_id').val("{{ old('city_id', '') }}").selectpicker('refresh');
                            @endif
                        }
                    });
                @endif
                /* End country, state, city selector */

                $('.coachRegistration').click(function() {
                    // $('#coachRegistrationForm')[0].reset();
                    $('#coachRegistrationForm input:not([name=_token],[name=is_coach])').val('');
                    $('#coachRegistrationForm select').val('').selectpicker('refresh');
                    $('#coachRegistrationForm input#plan_id').val($(this).attr('data-plan_id'));
                    $('#coachRegistrationModal').modal('show');
                    $(".error_color").text("");
                });

                $('#coachRegistrationForm').on('submit',function(e){
                    e.preventDefault();

                    var formData = new FormData(this);

                    jQuery.ajax({
                        type: 'POST',
                        url: "{{ route('register') }}",
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
                                $("#coachRegistrationModal").trigger("reset");
                                $('#coachRegistrationModal').modal('hide'); 
                                $('.error_color').text('');                               

                            }
                            if(response.status == 'email_reg'){
                                // console.log(response)                       
                                $('.email_error').text(response.msg); 
                                $(':input[type="submit"]').prop('disabled', false);                             

                            }
                            if(response.status == 'error'){
                                $('.email_error').text('');
                                // console.log(response)
                                $.each(response.msg,function(key,val){
                                    if(response.msg.category_ids){
                                        $('.category_error').text(response.msg.category_ids)
                                    }
                                    if(response.msg.name){
                                        $('.name_error').text(response.msg.name)
                                    }
                                    if(response.msg.email){
                                        $('.email_error').text(response.msg.email)
                                    }
                                    if(response.msg.phone){
                                        $('.phone_error').text(response.msg.phone)
                                    }
                                    if(response.msg.password){
                                        $('.password_error').text(response.msg.password)
                                    }
                                    if(response.msg.preferred_pronouns){
                                        $('.preferred_pronouns_error').text(response.msg.preferred_pronouns)
                                    }
                                    if(response.msg.hourly_rate_type){
                                        $('.hourly_rate_type_error').text(response.msg.hourly_rate_type)
                                    }
                                    if(response.msg.working_type){
                                        $('.working_type_error').text(response.msg.working_type)
                                    }
                                    if(response.msg.experience_year){
                                        $('.experience_year_error').text(response.msg.experience_year)
                                    }
                                    if(response.msg.address){
                                        $('.address_error').text(response.msg.address)
                                    }
                                    if(response.msg.country_id){
                                        $('.country_error').text(response.msg.country_id)
                                    }
                                    if(response.msg.state_id){
                                        $('.state_error').text(response.msg.state_id)
                                    }
                                    if(response.msg.city_id){
                                        $('.city_error').text(response.msg.city_id)
                                    }
                                    if(response.msg.post_code){
                                        $('.zip_error').text(response.msg.post_code)
                                    }
                                    $(':input[type="submit"]').prop('disabled', false);
                                    
                                });
                            }
                        }
                    });



                });

                // @if (count($errors) > 0)
                //     $('#coachRegistrationModal').modal('show');
                // @endif
            @endif

            

        });

    </script>
@endsection
