@extends('backend.user.layouts.app')

@section('styles')
<link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
<link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/css/bootstrap-select.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
    .bootstrap-select.form-control {
        border: 1px solid #ced4da;
    }

    .dropdown-toggle.btn-default {
        color: #292b2c;
        background-color: #fff;
        border-color: #ccc;
    }

    .bootstrap-select.show>.dropdown-menu>.dropdown-menu {
        display: block;
    }

    div.dropdown-menu.open {
        max-height: 314px !important;
        overflow: hidden;
    }

    ul.dropdown-menu.inner {
        max-height: 260px !important;
        overflow-y: auto;
    }

    .bootstrap-select>.dropdown-menu>.dropdown-menu li.hidden {
        display: none;
    }

    .bootstrap-select>.dropdown-menu>.dropdown-menu li a {
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

    .dropdown-menu>li.active>a {
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

    .canvasjs-chart-credit {
        display: none;
    }

    .level_two {
        position: relative;
    }

    .level_two::after {
        position: absolute;
        content: "";
        width: 100%;
        background: #FFEEF9 0% 0% no-repeat padding-box;
        width: 100%;
        height: 10px;
        display: block;
        bottom: 0px;
    }

    #chartContainer {
        background: none !important;
    }

    /* Make filled out selects be the same size as empty selects */
    .bootstrap-select.btn-group .dropdown-toggle .filter-option {
        display: inline !important;
    }

    .image_btn_set {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }

    .form-control {
        background: #FFFFFF 0% 0% no-repeat padding-box;
        border-radius: 5px;
        opacity: 1;
        border: none !important;
    }

    .dropdown-toggle.btn-default {
        background: #FFFFFF 0% 0% no-repeat padding-box;
        border-radius: 5px;
        opacity: 1;
        border: none !important;
    }

    .bootstrap-select.form-control {
        border: none !important;
    }

    .font_icon_color_diff i {
        color: #FF0000 !important;
        font-size: 24px;
        padding-right: 5px;
    }

    .edit_delete_btn {
        text-align: end;
    }

    .bootstrap-select .dropdown-toggle:focus {
        outline: none !important;
    }
</style>
@endsection

@section('content')

@php
$chk_post = Auth::user()->phone;

@endphp


<div class="row">
    <div class="col-lg-12 co-12">
        <h1 class="h3 mb-2 font-set-sm text-orange-800">{{ __('backend.user.edit-profile') }}</h1>
        <p class="mb-2">{{ __('backend.user.edit-profile-desc') }}</p>
        @if(Auth::user()->isCoach())
        <p class="mb-4 ">{{ __('How it works? ') }}<a href="{{ route('page.earn.points') }}" target="_blank" class="text-orange-700">{{ __('Learn Here') }}</a></p>
        <p class="mb-4 "><a href="javascript:void(0)" aria-hidden="true" title="info" id="profileCompleteModalBtn" >
            <button type="button" class="btn btn-sm btn-primary">{{ __('See Profile Progress') }}
            </button></a>
        </p>
        @endif
    </div>

</div>




<!-- Content Row -->
<div class="row pt-4 pl-3 pr-3 pb-4">
    <div class="col-12 p-0">
        <div class="row font_icon_color">
            <div class="col-12">
                @if(Auth::user()->isCoach() && (Auth::user()->categories()->count() == 0) &&
                !isset(Auth::user()->hourly_rate_type) && !isset(Auth::user()->experience_year) && !isset(Auth::user()
                ->preferred_pronouns))
                <div class="alert alert-danger" role="alert">
                    Please enter required details to access other menus
                </div>
                @endif
                <div class="alert alert-danger alert-dismissible fade show" id="image_error_div" role="alert" style="display:none">
                    <strong id="img_error"></strong>
                </div>
                <form method="POST" action="{{ route('user.profile.update') }}" class="" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="is_coach" id="is_coach" value="{{ Auth::user()->isCoach() ? 2 : null }}">

                    @if($login_user->isCoach())
                    <div class="row">
                        <div class="col-sm-6 col-12 col-lg-2 col-md-12 col-lg-4 col-xl-2">
                            {{-- <span class="text-lg text-gray-800">{{ __('backend.user.profile-image') }}</span> --}}
                            {{-- <small class="form-text text-muted">{{ __('backend.user.profile-image-help') }}</small>
                            --}}
                            @error('user_image')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <div class="border_bg_set">
                                <div class="row mt-3">
                                    <div class="col-12">
                                        @if(empty($login_user->user_image))
                                        <img id="image_preview" src="{{ asset('backend/images/placeholder/profile-' . intval($login_user->id % 10) . '.webp') }}" class="img-responsive">
                                        @else
                                        <img id="image_preview" src="{{ Storage::disk('public')->url('user/'. $login_user->user_image) }}" class="img-responsive">
                                        @endif
                                        <input id="feature_image" type="hidden" name="user_image">
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-xl-12 col-12">
                                        <button id="upload_image" type="button" class="btn btn-primary w-100 mb-2">{{ __('backend.user.select-image') }}</button>
                                    </div>
                                    @if(isset($login_user->user_image))
                                    <div class="col-xl-12 col-12">
                                        <a class="btn btn-danger text-white w-100" id="delete_user_profile_image_button">
                                            <!-- <i class="fas fa-trash-alt"></i> -->
                                            {{ __('role_permission.user.delete-profile-image') }}
                                        </a>
                                    </div>
                                    @endif
                                </div>
                                <div class="row mt-1">
                                    <div class="col-12">
                                        <span class="text-lg text-gray-900 mt-5">{{ __('Select Profile Image') }}</span>
                                        <small class="form-text text-muted">{{ __('backend.article.feature-image-ratio') }}</small>
                                        <small class="form-text text-muted">{{ __('backend.article.feature-image-size') }}</small>
                                        <small class="form-text text-muted">{{ __('Accepts only JPG,JPEG and PNG image type') }}</small>
                                    </div>
                                </div>
                            </div>

                            @if(Auth::user()->isCoach())
                            <div class="col-lg-12 col-12 p-0 mt-2">
                                <div class="coach_sidebar display_flex_center">
                                    <div class="sidebar_info">
                                        <div class="level">
                                            <h5>{{ $progress_data['profile'] }}</h5>
                                            <h6>{{ $progress_data['percentage'] }}% Completed</h6>
                                        </div>
                                    </div>
                                    <div id="chartContainer" style="height: 200px; width: 100%;" class="level_two"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-sm-12 col-md-12 col-xl-10 col-lg-8">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="category_ids" class="text-black">Category<span class="text-danger">*</span></label>
                                    <!-- <select class="form-control selectpicker-category @error('category_ids') is-invalid @enderror" name="category_ids[]" required multiple title="Select Categories" data-size="10" data-live-search="true"> -->
                                    <select class="form-control form-select category_ids @error('category_ids') is-invalid @enderror" name="category_ids[]" multiple>
                                        {{-- <option value="">Select Category</option> --}}
                                        @foreach($printable_categories as $key => $printable_category)
                                        @php
                                        if(empty($printable_category["is_parent"])) continue;
                                        if($printable_category["category_name"] == 'Entrepreneurial' ||
                                        $printable_category["category_name"] == 'Productivity') continue;
                                        @endphp
                                        <option value="{{ $printable_category["category_id"] }}" {{ in_array($printable_category["category_id"] ,old('category_ids', $login_user->categories()->pluck('categories.id')->all())) ? 'selected' : '' }}>
                                            {{ $printable_category["category_name"] }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('category_ids')
                                    <span class="invalid-tooltip" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-6 col-12 col-xl-3 col-lg-6  ">
                                    <label for="name" class="text-black">{{ __('auth.name') }}<span class="text-danger">*</span></label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $login_user->name) }}">
                                    @error('name')
                                    <span class="invalid-tooltip" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6 col-12 col-xl-3 col-lg-6 ">
                                    <label for="company_name" class="text-black">Company Name</label>
                                    <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name', $login_user->company_name) }}">
                                    @error('company_name')
                                    <span class="invalid-tooltip" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6 col-12 col-xl-3 col-lg-6 ">
                                    <label class="text-black" for="email">{{ __('auth.email-addr') }}<span class="text-danger">*</span></label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $login_user->email) }}">
                                    @error('email')
                                    <span class="invalid-tooltip" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-6 col-12 col-xl-3 col-lg-6 ">
                                    <label for="phone" class="text-black">Phone<span class="text-danger">*</span></label>
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $login_user->phone) }}" onkeypress="validatePostalCode(event)">
                                    @error('phone')
                                    <span class="invalid-tooltip" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mt-3">
                                {{-- <div class="col-sm-5">
                                            <div class="row mt-3"> --}}
                                {{-- <div class="col-sm-6">
                                                    <label for="gender" class="text-black">Gender</label>
                                                    <select class="form-control selectpicker @error('gender') is-invalid @enderror" name="gender" required title="Select Gender">
                                                        @foreach(\App\User::GENDER_TYPES as $gkey => $gender)
                                                            <option value="{{ $gkey }}"
                                {{ old('gender', $login_user->gender) == $gkey ? 'selected' : '' }}>{{ $gender }}
                                </option>
                                @endforeach
                                </select>
                                @error('gender')
                                <span class="invalid-tooltip" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div> --}}
                            <div class="col-sm-6 col-12 col-xl-3 col-lg-6">
                                <label for="preferred_pronouns" class="text-black">Preferred Pronouns<span class="text-danger">*</span></label>
                                <select class="form-control selectpicker @error('preferred_pronouns') is-invalid @enderror" name="preferred_pronouns" title="Select Preferred Pronouns">
                                    @foreach(\App\User::PREFERRED_PRONOUNS as $prkey => $pronoun)
                                    <option value="{{ $prkey }}" {{ old('preferred_pronouns', $login_user->preferred_pronouns) == $prkey ? 'selected' : '' }}>
                                        {{ $pronoun }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('preferred_pronouns')
                                <span class="invalid-tooltip" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="row mt-3"> --}}
                            <div class="col-sm-6 col-12 col-xl-3 col-lg-6">
                                <label for="hourly_rate_type" class="text-black">Hourly Rate<span class="text-danger">*</span></label>
                                <select class="form-control selectpicker @error('hourly_rate_type') is-invalid @enderror" name="hourly_rate_type" title="Select Hourly Rate">
                                    @foreach(\App\User::HOURLY_RATES as $hrkey => $rate)
                                    <option value="{{ $hrkey }}" {{ old('hourly_rate_type', $login_user->hourly_rate_type) == $hrkey ? 'selected' : '' }}>
                                        {{ $rate }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('hourly_rate_type')
                                <span class="invalid-tooltip" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-12 col-xl-3 col-lg-6">
                                {{-- <label for="working_type" class="text-black">Session Method<span class="text-danger">*</span></label> --}}
                                <label for="working_type" class="text-black">Working Method<span class="text-danger">*</span></label>
                                <select class="form-control selectpicker @error('working_type') is-invalid @enderror" name="working_type" required title="Select Session Method">
                                    @foreach(\App\User::WORKING_TYPES as $wtkey => $working_type)
                                    <option value="{{ $wtkey }}" {{ old('working_type', $login_user->working_type) == $wtkey ? 'selected' : '' }}>
                                        {{ $working_type }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('working_type')
                                <span class="invalid-tooltip" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-12 col-xl-3 col-lg-6">
                                <label for="experience_year" class="text-black">Experience Year<span class="text-danger">*</span></label>
                                <select class="form-control selectpicker @error('experience_year') is-invalid @enderror" name="experience_year" title="Select Experience">
                                    @foreach(\App\User::EXPERIENCE_YEARS as $eykey => $experience_year)
                                    <option value="{{ $eykey }}" {{ old('experience_year', $login_user->experience_year) == $eykey ? 'selected' : '' }}>
                                        {{ $experience_year }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('experience_year')
                                <span class="invalid-tooltip" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            {{-- </div>
                                        </div> --}}
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="text-black" for="user_about">{{ __('backend.user.user-about') }}</label>
                                <textarea id="user_about" class="form-control @error('user_about') is-invalid @enderror" name="user_about" rows="14">{{ old('user_about', $login_user->user_about) }}</textarea>
                                @error('user_about')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-10 col-md-8">
                    <div class="row">
                        <div class="col-sm-6 col-12 col-lg-3">
                            <label for="website" class="text-black">Website</label>
                            <input id="website" type="url" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website', $login_user->website) }}">
                            <small id="linkHelpBlock" class="form-text text-muted">
                                {{ __('backend.shared.url-help') }}
                            </small>
                            @error('website')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-sm-6 col-12 col-lg-3">
                            <label for="instagram" class="text-black">IG Handle</label>
                            <input id="instagram" type="text" class="form-control @error('instagram') is-invalid @enderror" name="instagram" value="{{ old('instagram', $login_user->instagram) }}">
                            <span class="err_instagram_url" style="color:red"></span>
                            <small id="linkHelpBlock" class="form-text text-muted">
                                {{ __('article_whatsapp_instagram.article-social-instagram-help') }}
                            </small>
                            @error('instagram')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @if(Session::has('instagram_error'))
                            <span class="invalid-tooltip">
                                <strong>{{ Session::get('instagram_error') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-sm-6 col-12 col-lg-3">
                            <label for="linkedin" class="text-black">LinkedIn</label>
                            <input id="linkedin" type="text" class="form-control @error('linkedin') is-invalid @enderror" name="linkedin" value="{{ old('linkedin', $login_user->linkedin) }}">
                            <span class="err_linkedin_url" style="color:red"></span>
                            <small id="linkHelpBlock" class="form-text text-muted">
                                {{ __('Only linkedin URL allowed (include http:// or https://)') }}
                            </small>
                            @error('linkedin')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @if(Session::has('linkedin_error'))
                            <span class="invalid-tooltip">
                                <strong>{{ Session::get('linkedin_error') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="col-sm-6 col-12 col-lg-3">
                            <label for="facebook" class="text-black">Facebook</label>
                            <input id="facebook" type="text" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{ old('facebook', $login_user->facebook) }}">
                            <span class="err_facebook_url" style="color:red"></span>
                            <small id="linkHelpBlock" class="form-text text-muted">
                                {{ __('Only facebook URL allowed (include http:// or https://)') }}
                            </small>
                            @error('facebook')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @if(Session::has('facebook_error'))
                            <span class="invalid-tooltip">
                                <strong>{{ Session::get('facebook_error') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>


                </div>
                <div class="col-lg-2 col-md-4">
                    <div class="row">
                        <div class="col-sm-12 col-12 col-lg-12">
                            <label for="youtube" class="text-black">Youtube</label>
                            <input id="youtube" type="url" class="form-control @error('youtube') is-invalid @enderror" name="youtube" value="{{ old('youtube', $login_user->youtube) }}">
                            <span class="err_youtube_url" style="color:red"></span>
                            <span class="youtube_error" style="color:red"></span>
                            <small id="linkHelpBlock" class="form-text text-muted">
                                {{ __('Only youtube URL allowed (include http:// or https://)') }}
                            </small>
                            @error('youtube')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @if(Session::has('youtube_error'))
                            <span class="invalid-tooltip">
                                <strong>{{ Session::get('youtube_error') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-6 col-xl-10 col-md-6">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 col-xl-12 col-12">
                            <label for="address" class="text-black">Address<span class="text-danger">*</span></label>
                            <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $login_user->address) }}" required>
                            @error('address')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>
                </div>
                <div class="col-xl-2 col-lg-6 col-md-6">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12 col-xl-12 col-12">
                            <label for="country_id" class="text-black">Country<span class="text-danger">*</span></label>
                            <select id="select_country_id" class="selectpicker form-control @error('country_id') is-invalid @enderror" name="country_id" data-live-search="true" title="{{ __('prefer_country.select-country') }}">
                                @foreach($all_countries as $all_countries_key => $country)
                                @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE || ($country->country_status
                                == \App\Country::COUNTRY_STATUS_DISABLE && $login_user->country_id == $country->id))
                                <option value="{{ $country->id }}" {{ $country->id == old('country_id', $login_user->country_id) ? 'selected' : '' }}>
                                    {{ $country->country_name }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                            @error('country_id')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>
            <div class="row mt-3">
                <div class="col-sm-6 col-12 col-lg-4 col-xl-3">
                    <label for="state_id" class="text-black">State<span class="text-danger">*</span></label>
                    <select id="select_state_id" class="selectpicker form-control @error('state_id') is-invalid @enderror" name="state_id" data-live-search="true" data-size="10" title="{{ __('backend.item.select-state') }}">
                        @if($all_states)
                        @foreach($all_states as $key => $state)
                        @error('state_id')<option {{ $state->id == old('state_id', $login_user->state_id) }} value="{{ $state->id }}">
                            {{ $state->state_name }}
                        </option>
                        @else
                        <option {{ $login_user->state_id == $state->id ? 'selected' : '' }} value="{{ $state->id }}">
                            {{ $state->state_name }}
                        </option>
                        @enderror
                        @endforeach
                        @endif
                    </select>
                    @error('state_id')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-sm-6 col-12 col-lg-4 col-xl-3">
                    <label for="city_id" class="text-black">City<span class="text-danger">*</span></label>
                    <select id="select_city_id" class="selectpicker form-control @error('city_id') is-invalid @enderror" name="city_id" data-live-search="true" data-size="10" title="{{ __('backend.item.select-city') }}">
                        @if($all_cities)
                        @foreach($all_cities as $key => $city)
                        @error('state_id') <option {{ $city->id == old('city_id', $login_user->city_id) }} value="{{ $city->id }}">
                            {{ $city->state_name }}
                        </option>
                        @else
                        <option {{ $login_user->city_id == $city->id ? 'selected' : '' }} value="{{ $city->id }}">
                            {{ $city->city_name }}
                        </option>
                        @enderror
                        @endforeach
                        @endif
                    </select>
                    @error('city_id')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-sm-6 col-12 col-lg-4 col-xl-3">
                    <label for="post_code" class="text-black">Post Code<span class="text-danger">*</span></label>
                    <input id="post_code" type="text" class="form-control @error('post_code') is-invalid @enderror" name="post_code" value="{{ old('post_code', $login_user->post_code) }}">
                    @error('post_code')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mt-3 mb-5 break_line_section">
                <div class="col-md-6">
                    <label class="text-black" for="certifications">{{ __('Certifications') }}</label>
                    <textarea id="certifications" class="form-control @error('certifications') is-invalid @enderror" name="certifications" rows="4">{{ old('certifications', $login_user->certifications) }}</textarea>
                    @error('certifications')
                    <span class="invalid-tooltip">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="text-black" for="awards">{{ __('Awards') }}</label>
                    <textarea id="awards" class="form-control @error('awards') is-invalid @enderror" name="awards" rows="4">{{ old('awards', $login_user->awards) }}</textarea>
                    @error('awards')
                    <span class="invalid-tooltip">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="alert alert-danger alert-dismissible fade show" id="cover_image_error_div" role="alert" style="display:none;margin:4px;">
                <strong id="cover_img_error"></strong>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div>
                        <h3 class="h3 mb-4 font-set-sm text-orange-700">YouTube Details</h3>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-7 col-xl-6">
                    <div class="border_bg_set">
                        @error('user_cover_image')
                        <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        @if(empty($login_user->user_cover_image))
                        <img id="cover_image_preview" src="{{ asset('frontend/images/main_upper_logo.png') }}" style="width:100%;">
                        @else
                        <img id="cover_image_preview" src="{{ Storage::disk('public')->url('user/'. $login_user->user_cover_image) }}" style="width:100%;">
                        {{-- <img id="cover_image_preview" src="{{ Storage::url('user/'. $login_user->user_cover_image) }}"
                        style="width:100%;"> --}}
                        @endif
                        <input id="feature_cover_image" type="hidden" name="user_cover_image">                        
                        <div class="mt-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <button id="upload_cover_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.user.select-cover-image') }}</button>

                                </div>
                                @if(!empty($login_user->user_cover_image))
                                    <div class="col-md-6">
                                        <a class="btn btn-danger btn-block text-white" id="delete_user_cover_image_button">
                                            <!-- <i class="fas fa-trash-alt"></i> -->
                                            {{ __('role_permission.user.delete-profile-image') }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-5 col-xl-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="youtube_intro_title" class="text-black">Youtube Intro Title</label>
                            <input id="youtube_intro_title" type="text" class="form-control @error('youtube_intro_title') is-invalid @enderror" name="youtube_intro_title" value="{{ old('youtube_intro_title', $login_user->youtube_intro_title) }}">
                            <small id="linkHelpBlock" class="form-text text-muted">
                                {{ __('Only youtube URL allowed (include http:// or https://)') }}
                            </small>
                            @error('youtube_intro_title')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="text-black" for="youtube_intro_description">{{ __('Youtube Intro Description') }}</label>
                            <textarea id="youtube_intro_description" class="form-control @error('youtube_intro_description') is-invalid @enderror" name="youtube_intro_description" rows="4">{{ old('youtube_intro_description', $login_user->youtube_intro_description) }}</textarea>
                            @error('youtube_intro_description')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">

                <div class="col-12 col-md-6 col-lg-12 col-xl-4 mt-3">
                    <label class="text-black">Youtube</label>
                    <select id="media_type" class="form-control selectpicker @error('media_type') is-invalid @enderror" name="media_type" title="Select Type">
                        @foreach(\App\MediaDetail::VIDEO_MEDIA_TYPE as $mkey => $mvalue)
                        <option value="{{ $mkey }}" selected>{{ $mvalue }}</option>
                        @endforeach
                    </select>
                    @error('media_type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-12 col-md-12 col-lg-12 col-xl-4 mt-3">
                    <label for="media_url" class="text-black">Youtube Video URL</label>
                    <span class="err_media_url" style="color:red"></span>
                    <input id="media_url" type="url" class="form-control @error('media_url') is-invalid @enderror" name="media_url" value="{{ old('media_url', $login_user->media_url) }}">
                    <small id="linkHelpBlock" class="form-text text-muted">
                        {{ __('Only youtube URL allowed (include http:// or https://)') }}
                    </small>
                    @error('media_url')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-12 col-md-12 col-lg-12 col-xl-4 mt-3">
                    <label for="media_url" class="text-black">&nbsp;</label>
                    <a class="btn btn-sm btn-block btn-primary rounded text-white align_set_center_all mb-set-sm" id="media_type_create_button">
                        <i class="fas fa-plus"></i>
                        {{ __('Add') }}
                    </a>
                </div>
            </div>

            <div class="row mt-3 font_icon_color_diff bg-white mb-5 break_line_section">
                <div class="col-12 pt-md-4 pb-md-4" id="media_details_added">
                    @foreach($video_media_array as $video_media_key => $video_media_value)
                    <div class="col-12 p-0 ">
                        <div class="row border_set_row">
                            <div class="col-md-6 col-9">
                                <span><img src="{{ asset('frontend/images/youtube_icon.png') }}" alt="" height="25px"></span>
                                <span class="set_width">{{ \App\MediaDetail::MEDIA_TYPE[$video_media_value->media_type] }}
                                    : {{ $video_media_value->media_url }}</span>

                            </div>
                            <div class="col-md-6 col-3">
                                <div class="edit_delete_btn">
                                    <a class="text-primary" href="#" data-toggle="modal" data-target="#editMediaModal_{{ $video_media_value->id }}">
                                        {{-- <i class="far fa-edit"></i> --}}
                                        <img src="{{ asset('frontend/images/edit_icon.png') }}" alt="" height="25px">
                                    </a>
                                    <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteMediaModal_{{ $video_media_value->id }}">
                                        {{-- <i class='far fa-trash-alt'></i> --}}
                                        <img src="{{ asset('frontend/images/delete_icon.png') }}" alt="" height="25px">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- <div class="row mt-3 mb-5 break_line_section">
                <div class="col-12">
                    <div>
                        <h3 class="h3 mb-4 font-set-sm text-orange-700">Ebook Details</h3>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-3">
                            <label class="text-black">Ebook</label>
                            <select id="media_type" class="form-control selectpicker @error('media_type') is-invalid @enderror" name="media_type" title="Select Type">
                                @foreach(\App\MediaDetail::EBOOK_MEDIA_TYPE as $mkey => $mvalue)
                                <option value="{{ $mkey }}" selected>{{ $mvalue }}</option>
                                @endforeach
                            </select>
                            @error('media_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-12 col-lg-3">
                            <label class="text-black">Ebook PDF Title</label>
                            <input id="media_name" type="text" class="form-control @error('media_name') is-invalid @enderror" name="media_name" value="{{ old('media_name', $login_user->media_name) }}" placeholder="Book Title">
                            @error('media_name')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-12 col-lg-3">
                            <label class="text-black">Ebook PDF</label>
                            <input id="media_image" type="file" class="form-control @error('media_image') is-invalid @enderror" name="media_image" accept=".pdf">
                            @error('media_image')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-12 col-lg-3">
                            <label class="text-black">Ebook Cover</label>
                            <input id="media_cover" type="file" class="form-control @error('media_cover') is-invalid @enderror" name="media_cover" accept=".jpg,.jpeg,.png">
                            <small class="form-text text-muted">
                                {{ __('backend.item.feature-image-help') }}
                            </small>
                            @error('media_cover')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-12 col-lg-12 col-xl-12 font_icon_color_diff bg-white pt-md-4 pb-md-4">
                            @foreach($ebook_media_array as $ebook_media_key => $ebook_media_value)
                            <div class="col-12 col-md-12 col-lg-12 p-0">
                                <div class="row border_set_row">
                                    <div class="col-md-6 col-89">
                                        <span class="set_width">
                                            {{ \App\MediaDetail::MEDIA_TYPE[$ebook_media_value->media_type] }} :
                                            {{ $ebook_media_value->media_name }}</span>

                                    </div>
                                    <div class="col-md-6 col-3">
                                        <div class="edit_delete_btn">
                                            <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteEbookMediaModal_{{ $ebook_media_value->id }}">
                                                <i class='far fa-trash-alt'></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div> --}}

            {{-- <div class="row mt-3 mb-5 break_line_section">
                <div class="col-12">
                    <div>
                        <h3 class="h3 mb-2 font-set-sm text-orange-700">Podcast Details</h3>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-12 col-lg-4">
                            <label class="text-black">Podcast</label>
                            <select id="podcast_type"
                                class="form-control selectpicker @error('podcast_type') is-invalid @enderror"
                                name="podcast_type" title="Select Type">
                                @foreach(\App\MediaDetail::PODCAST_MEDIA_TYPE as $mkey => $mvalue)
                                <option value="{{ $mkey }}" selected>{{ $mvalue }}</option>
                            @endforeach
                            </select>

                            <input type="hidden" name="podcast_type" value="podcast">
                            <span class="podcast_type_err_media_url" style="color:red"></span>
                            <select id="podcast_web_type" class="form-control selectpicker @error('podcast_web_type') is-invalid @enderror" name="podcast_web_type" title="Select Type">
                                <option name="apple_podcast" value="apple_podcast">Apple Podcast</option>
                                <option name="stitcher_podcast" value="stitcher_podcast">Stitcher Podcast</option>
                                <option name="google_podcast" value="google_podcast">Google Podcast</option>
                                <option name="spotify_podcast" value="spotify_podcast">Spotify Podcast</option>
                            </select>
                            <span class="podcast_success_msg" style="color:green"></span>
                            @error('media_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-12 col-lg-4" id="podcast_div_title" style="display:none">
                            <label class="text-black">Podcast Title</label>
                            <input id="podcast_name" type="text" class="form-control @error('podcast_name') is-invalid @enderror" name="podcast_name" value="{{ old('podcast_name', $login_user->podcast_name) }}" placeholder="Podcast Title">
                            @error('podcast_name')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-12 col-lg-4" id="podcast_div_mp3" style="display:none">
                            <label class="text-black">Podcast MP3</label>
                            <input id="podcast_image" type="file"
                                class="form-control @error('podcast_image') is-invalid @enderror" name="podcast_image"
                                accept=".mp3">
                            @error('podcast_image')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-12 col-md-12 col-lg-4" id="podcast_div_mp3_url">
                        <label for="podcast_image" class="text-black">Podcast MP3 URL</label>
                        <span class="podcast_err_media_url" style="color:red"></span>
                        <input id="podcast_image" type="url" class="form-control @error('podcast_image') is-invalid @enderror" name="podcast_image" value="{{ old('podcast_image', $login_user->podcast_image) }}">
                        <small id="linkHelpBlock" class="form-text text-muted">
                            {{ __('Only URL allowed (include http:// or https://)') }}
                        </small>
                        @error('podcast_image')
                        <span class="invalid-tooltip" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        @if(Session::has('podcast_error'))
                        <span class="invalid-tooltip" role="alert">
                            <strong>{{ Session::get('podcast_error') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="col-12 col-md-12 col-lg-4" id="podcast_div_mp3_file" style="display:none">
                            <label for="podcast_image" class="text-black">Podcast MP3 File</label>
                            <span class="podcast_err_media_url" style="color:red"></span>
                            <input id="podcast_image" type="file" class="form-control @error('podcast_image') is-invalid @enderror" name="podcast_image" value="{{ old('podcast_image', $login_user->podcast_image) }}">
                    <small id="linkHelpBlock" class="form-text text-muted">
                        {{ __('Only URL allowed (include http:// or https://)') }}
                    </small>
                    @error('podcast_image')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    @if(Session::has('podcast_error'))
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ Session::get('podcast_error') }}</strong>
                    </span>
                    @endif
                </div>
                <div class="col-12 col-md-12 col-lg-4" id="podcast_cover_image" style="display:none">
                    <label class="text-black">Podcast Cover</label>
                    <input id="podcast_cover" type="file" class="form-control @error('podcast_cover') is-invalid @enderror" name="podcast_cover" accept=".jpg,.jpeg,.png">
                    <small class="form-text text-muted">
                        {{ __('backend.item.feature-image-help') }}
                    </small>
                    @error('podcast_cover')
                    <span class="invalid-tooltip">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-12 col-md-12 col-lg-4">
                    <label for="podcast_url" class="text-black">&nbsp;</label>
                    <a class="btn btn-sm btn-block btn-primary rounded text-white align_set_center_all mb-set-sm" id="podcast_create_button">
                        <i class="fas fa-plus"></i>
                        {{ __('Add') }}
                    </a>
                </div>
                <div class="col-12 col-md-12 col-lg-12 col-xl-12 font_icon_color_diff bg-white pt-md-4 pb-md-4" id="podcast_details_added">
                    @foreach($podcast_media_array as $podcast_media_key => $podcast_media_value)
                    <div class="col-12 col-md-12 col-lg-12 p-0 ">
                        <div class="row border_set_row">
                            <div class="col-md-6 col-9">
                                <span class="set_width">
                                    {{ \App\MediaDetail::MEDIA_TYPE[$podcast_media_value->media_type] }} :
                                    {{ $podcast_media_value->media_name }}</span>

                            </div>
                            <div class="col-md-6 col-3">
                                <div class="edit_delete_btn">
                                    <a class="text-primary" href="#" data-toggle="modal" data-target="#editPodcastMediaModal_{{ $podcast_media_value->id }}" data-id="{{ $podcast_media_value->media_name }}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a class="text-danger" href="#" data-toggle="modal" data-target="#deletePodcastMediaModal_{{ $podcast_media_value->id }}">
                                        <i class='far fa-trash-alt'></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div> --}}
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-sm-6 col-12 col-lg-2 col-md-12 col-lg-4 col-xl-2">
            {{-- <span class="text-lg text-gray-800">{{ __('backend.user.profile-image') }}</span> --}}
            {{-- <small class="form-text text-muted">{{ __('backend.user.profile-image-help') }}</small> --}}
            @error('user_image')
            <span class="invalid-tooltip">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
            <div class="row mt-3">
                <div class="col-12">
                    <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.user.select-image') }}</button>
                    @if(empty($login_user->user_image))
                    <img id="image_preview" src="{{ asset('backend/images/placeholder/profile-' . intval($login_user->id % 10) . '.webp') }}" class="img-responsive">
                    @else
                    <img id="image_preview" src="{{ Storage::disk('public')->url('user/'. $login_user->user_image) }}" class="img-responsive">
                    @endif
                    <input id="feature_image" type="hidden" name="user_image">
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-12">
                    <a class="btn btn-danger btn-block text-white" id="delete_user_profile_image_button">
                        <i class="fas fa-trash-alt"></i>
                        {{ __('role_permission.user.delete-profile-image') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-10 col-md-12 col-lg-8 col-xl-10">
            <div class="row mt-3">
                <div class="col-sm-6 col-12 col-lg-3 col-md-12 col-lg-6 col-xl-3">
                    <label for="name" class="text-black labet_set">{{ __('auth.name') }}<span class="text-danger">*</span></label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $login_user->name) }}">
                    @error('name')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-sm-6 col-12 col-lg-3 col-md-12 col-lg-6 col-xl-3">
                    <label class="text-black labet_set" for="email">{{ __('auth.email-addr') }}<span class="text-danger">*</span></label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $login_user->email) }}">
                    @error('email')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-sm-6 col-12 col-lg-3 col-md-12 col-lg-6 col-xl-3">
                    <label for="phone" class="text-black labet_set">Phone</label>
                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $login_user->phone) }}" onkeypress="validatePostalCode(event)">
                    @error('phone')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col-sm-6 col-12 col-lg-3 col-md-12 col-lg-6 col-xl-3">
                    <label for="gender" class="text-black labet_set">Gender</label>
                    <select class="form-control selectpicker @error('gender') is-invalid @enderror" name="gender" title="Select Gender">
                        @foreach(\App\User::GENDER_TYPES as $gkey => $gender)
                        <option value="{{ $gkey }}" {{ old('gender', $login_user->gender) == $gkey ? 'selected' : '' }}>{{ $gender }}
                        </option>
                        @endforeach
                    </select>
                    @error('gender')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label class="text-black" for="user_about">{{ __('backend.user.user-about') }}</label>
                    <textarea id="user_about" class="form-control @error('user_about') is-invalid @enderror" name="user_about" rows="8">{{ old('user_about', $login_user->user_about) }}</textarea>
                    @error('user_about')
                    <span class="invalid-tooltip">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- <hr class="mt-5"> -->

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="">
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" id="frmSubmit" class="btn btn-primary w-100 text-white first_btn ">
                            {{ __('backend.shared.update') }}
                        </button>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-warning text-white first_btn w-100" href="{{ route('user.profile.password.edit') }}">
                            {{ __('backend.user.change-password') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
{{--  Ebook form --}}
<form method="POST" action="" class="" enctype="multipart/form-data" name="ebookFrm" id="ebookFrm">
    <div class="row mt-3 mb-5 break_line_section">
        <div class="col-12">
            <div>
                <h3 class="h3 mb-4 font-set-sm text-orange-700">Ebook Details</h3>
            </div>
            <div class="row">
                <div class="col-12 col-md-12 col-lg-3">
                    <label class="text-black">Ebook</label>
                    <select id="media_type" class="form-control selectpicker @error('media_type') is-invalid @enderror" name="media_type" title="Select Type">
                        @foreach(\App\MediaDetail::EBOOK_MEDIA_TYPE as $mkey => $mvalue)
                        <option value="{{ $mkey }}" selected>{{ $mvalue }}</option>
                        @endforeach
                    </select>
                    @error('media_type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    {{-- <span class="ebook_success_msg" style="color:green"></span> --}}
                </div>
                <div class="col-12 col-md-12 col-lg-3">
                    <label class="text-black">Ebook PDF Title</label>
                    <input id="media_name" type="text" class="form-control @error('media_name') is-invalid @enderror" name="media_name" value="{{ old('media_name', $login_user->media_name) }}" placeholder="Book Title">
                    @error('media_name')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <p class="error_color media_name"></p>
                </div>
                <div class="col-12 col-md-12 col-lg-3">
                    <label class="text-black">Ebook PDF</label>
                    <input id="media_image" type="file" class="form-control @error('media_image') is-invalid @enderror" name="media_image" accept=".pdf">
                    @error('media_image')
                    <span class="invalid-tooltip">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <p class="error_color media_image"></p>
                </div>
                <div class="col-12 col-md-12 col-lg-3">
                    <label class="text-black">Ebook Cover</label>
                    <input id="media_cover" type="file" class="form-control @error('media_cover') is-invalid @enderror" name="media_cover" accept=".jpg,.jpeg,.png">
                    <small class="form-text text-muted">
                        {{ __('backend.item.feature-image-help') }}
                    </small>
                    @error('media_cover')
                    <span class="invalid-tooltip">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <p class="error_color media_cover"></p>
                </div>
                <div class="col-12 col-md-12 col-lg-3 mb-5">
                    <label for="podcast_url" class="text-black"></label>
                    {{-- <a class="btn btn-sm btn-block btn-primary rounded text-white align_set_center_all mb-set-sm" id="podcast_create_button">
                        <i class="fas fa-plus"></i>
                        {{ __('Add') }}
                    </a> --}}
                    <button type="submit" class="btn btn-primary  btn-block btn-primary rounded text-white align_set_center_all mb-set-sm" id="ebookSubmitBtn">
                        <i class="fas fa-plus"></i>{{ __('Add') }}
                    </button>
                    <strong><span class="ebook_success_msg" style="color:green"></span></strong>
        
                </div>
                <div class="col-12 col-md-12 col-lg-12 col-xl-12 font_icon_color_diff bg-white pt-md-4 pb-md-4" id="ebook_details_added">
                    @foreach($ebook_media_array as $ebook_media_key => $ebook_media_value)
                    <div class="col-12 col-md-12 col-lg-12 p-0">
                        <div class="row border_set_row">
                            <div class="col-md-6 col-89">
                                <span class="set_width">
                                    {{ \App\MediaDetail::MEDIA_TYPE[$ebook_media_value->media_type] }} :
                                    {{ $ebook_media_value->media_name }}</span>

                            </div>
                            <div class="col-md-6 col-3">
                                <div class="edit_delete_btn">
                                    <a class="text-danger" href="#" data-toggle="modal" data-target="#deleteEbookMediaModal_{{ $ebook_media_value->id }}">
                                        {{-- <i class='far fa-trash-alt'></i> --}}
                                        <img src="{{ asset('frontend/images/delete_icon.png') }}" alt="" height="25px">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>

{{--  Podcast form --}}
<form method="POST" action="" class="" enctype="multipart/form-data" name="podcastFrm" id="podcastFrm">    
    <div class="row mt-3 mb-5 break_line_section">
        <div class="col-12">
            <div>
                <h3 class="h3 mb-2 font-set-sm text-orange-700">Podcast Details</h3>
            </div>
            <div class="row">
                <div class="col-12 col-md-12 col-lg-4">
                    <label class="text-black">Podcast Type</label>                    

                    <input type="hidden" name="podcast_type" value="podcast">
                    {{-- <span class="podcast_type_err_media_url" style="color:red"></span> --}}
                    {{-- <select id="podcast_web_type" class="form-control selectpicker @error('podcast_web_type') is-invalid @enderror" name="podcast_web_type" title="Select Type"> --}}
                        <select id="podcast_web_type" class="form-control @error('podcast_web_type') is-invalid @enderror" name="podcast_web_type" title="Select Type">
                        <option name="apple_podcast" value="">--Select--</option>
                        <option name="apple_podcast" value="apple_podcast">Apple Podcast</option>
                        <option name="stitcher_podcast" value="stitcher_podcast">Stitcher Podcast</option>
                        <option name="google_podcast" value="google_podcast">Google Podcast</option>
                        <option name="spotify_podcast" value="spotify_podcast">Spotify Podcast</option>
                    </select>
                    {{-- <span class="podcast_success_msg" style="color:green"></span> --}}
                    @error('media_type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <p class="error_color podcast_web_type"></p>
                </div>
                <div class="col-12 col-md-12 col-lg-4" id="podcast_div_title" style="display:none">
                    <label class="text-black">Podcast Title</label>
                    <input id="podcast_name" type="text" class="form-control @error('podcast_name') is-invalid @enderror" name="podcast_name" value="{{ old('podcast_name', $login_user->podcast_name) }}" placeholder="Podcast Title">
                    @error('podcast_name')
                    <span class="invalid-tooltip" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <p class="error_color podcast_name"></p>
                </div>
                
            <div class="col-12 col-md-12 col-lg-4" id="podcast_div_mp3_url">
                <label for="podcast_image" class="text-black">Podcast MP3 URL</label>
                <span class="podcast_err_media_url" style="color:red"></span>
                <input id="podcast_image" type="url" class="form-control @error('podcast_image') is-invalid @enderror" name="podcast_image" value="{{ old('podcast_image', $login_user->podcast_image) }}">
                <small id="linkHelpBlock" class="form-text text-muted">
                    {{ __('Only URL allowed (include http:// or https://)') }}
                </small>
                @error('podcast_image')
                <span class="invalid-tooltip" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
                @if(Session::has('podcast_error'))
                <span class="invalid-tooltip" role="alert">
                    <strong>{{ Session::get('podcast_error') }}</strong>
                </span>
                @endif
                <p class="error_color podcast_image"></p>
            </div>
            
        <div class="col-12 col-md-12 col-lg-4" id="podcast_cover_image" style="display:none">
            <label class="text-black">Podcast Cover</label>
            <input id="podcast_cover" type="file" class="form-control @error('podcast_cover') is-invalid @enderror" name="podcast_cover" accept=".jpg,.jpeg,.png">
            <small class="form-text text-muted">
                {{ __('backend.item.feature-image-help') }}
            </small>
            @error('podcast_cover')
            <span class="invalid-tooltip">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
            <p class="error_color podcast_cover"></p>
        </div>
        {{-- <div class="col-12 col-md-12 col-lg-4">
            <label for="podcast_url" class="text-black">&nbsp;</label>
            <a class="btn btn-sm btn-block btn-primary rounded text-white align_set_center_all mb-set-sm" id="podcast_create_button">
                <i class="fas fa-plus"></i>
                {{ __('Add') }}
            </a>
        </div> --}}
        <div class="col-12 col-md-12 col-lg-3">
            <label for="podcast_url" class="text-black">&nbsp;</label>
            {{-- <a class="btn btn-sm btn-block btn-primary rounded text-white align_set_center_all mb-set-sm" id="podcast_create_button">
                <i class="fas fa-plus"></i>
                {{ __('Add') }}
            </a> --}}
            <button type="submit" class="btn btn-sm btn-block btn-primary rounded text-white align_set_center_all mb-set-sm" id="podcastSubmitBtn">
                <i class="fas fa-plus"></i>{{ __('Add') }}
            </button>
            <strong><span class="podcast_success_msg" style="color:green;font"></span></strong>

        </div>
        <div class="col-12 col-md-12 col-lg-12 col-xl-12 font_icon_color_diff bg-white pt-md-4 pb-md-4" id="podcast_details_added">
            @foreach($podcast_media_array as $podcast_media_key => $podcast_media_value)
            <div class="col-12 col-md-12 col-lg-12 p-0 ">
                <div class="row border_set_row">
                    <div class="col-md-6 col-9">
                        <span class="set_width">
                            {{-- {{ \App\MediaDetail::MEDIA_TYPE[$podcast_media_value->media_type] }} : --}}
                            <div class="row">
                                <div class="col-md-1">
                                    @if($podcast_media_value->podcast_web_type == 'stitcher_podcast')
                                        <img src="{{ Storage::disk('public')->url('media_files/' . $podcast_media_value->media_cover) }}" alt="" width="30px">
                                    @else
                                        <img src="{{ $podcast_media_value->media_cover }}" alt="" width="30px">
                                    @endif
                                </div>
                                <div class="col-md-11">
                                    {{ $podcast_media_value->media_name }}</span> 
                                </div>
                            </div>
                          

                    </div>
                    <div class="col-md-6 col-3">
                        <div class="edit_delete_btn">
                            <a class="text-primary" href="#" data-toggle="modal" data-target="#editPodcastMediaModal_{{ $podcast_media_value->id }}" data-id="{{ $podcast_media_value->media_name }}">
                                {{-- <i class="far fa-edit"></i> --}}
                                <img src="{{ asset('frontend/images/edit_icon.png') }}" alt="" height="25px">
                            </a>
                            <a class="text-danger" href="#" data-toggle="modal" data-target="#deletePodcastMediaModal_{{ $podcast_media_value->id }}">
                                {{-- <i class='far fa-trash-alt'></i> --}}
                                <img src="{{ asset('frontend/images/delete_icon.png') }}" alt="" height="25px">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    </form>
    @if($free_items->count() >0)
    <div class="row">
        <div class="col-md-12">
            <div class="below_info p-2">
                <h3>Topics</h3>
                @if(isset($free_items) && !empty($free_items) && $free_items->count() >=4 )
                <a href="{{ route('user.articles.index', $user_detail['id']) }}">View all</a>
                @endif
            </div>
        </div>
        <div class="col-lg-12 ">

            <div class="row">
                <div class="col-12">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-info text-white">
                                    <!-- <th>{{ __('importer_csv.select') }}</th> -->
                                    <th>{{ __('backend.article.article') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($free_items as $items_key => $item)
                                <tr>
                                    <!-- <td>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input items_table_index_checkbox" type="checkbox" id="item_index_data_checkbox_{{ $item->id }}" value="{{ $item->id }}">
                                                </div>
                                            </td> -->
                                    <td>

                                        <div class="row">
                                            <div class="col-12 col-md-4 col-lg-3 col-xl-2 img_width_set_100">
                                                @if(!empty($item->item_image_tiny))
                                                <img src="{{ Storage::disk('public')->url('item/' . $item->item_image_tiny) }}" alt="Image" class="img-fluid rounded">
                                                @elseif(!empty($item->item_image))
                                                <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image" class="img-fluid rounded">
                                                @else
                                                <img src="{{ asset('backend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                @endif
                                            </div>
                                            <div class="col-12 col-md-8 col-lg-9 col-xl-10">
                                                @if($item->item_status == \App\Item::ITEM_SUBMITTED)
                                                <span class="text-warning"><i class="fas fa-exclamation-circle"></i></span>
                                                @elseif($item->item_status == \App\Item::ITEM_PUBLISHED)
                                                <span class="text-success"><i class="fas fa-check-circle"></i></span>
                                                @elseif($item->item_status == \App\Item::ITEM_SUSPENDED)
                                                <span class="text-danger"><i class="fas fa-ban"></i></span>
                                                @endif
                                                <span class="text-gray-800">{{ $item->item_title }}</span>
                                                @if($item->item_featured == \App\Item::ITEM_FEATURED)
                                                <span class="text-white bg-info pl-1 pr-1 rounded">{{ __('prefer_country.featured') }}</span>
                                                @endif
                                                <div class="pt-1 pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ empty($item->item_average_rating) ? 0 : $item->item_average_rating }}">
                                                </div>
                                                <span>
                                                    {{ '(' . $item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                                </span>

                                                <br>

                                                @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                                <div class="d-flex align-items-baseline border_set_sm">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $item->item_address }},
                                                    {{ $item->city->city_name }},
                                                    {{ $item->state->state_name }},
                                                    {{ $item->country->country_name }}
                                                    {{ $item->item_postal_code }}
                                                </div>
                                                @else

                                                <span class="bg-primary text-white pl-1 pr-1 rounded">{{ __('theme_directory_hub.online-listing.online-listing') }}</span>
                                                @endif

                                                <div class="pt-2 sub_set_btn">
                                                    @foreach($item->allCategories()->get() as $categories_key =>
                                                    $category)
                                                    <span class="border border-info text-info pl-1 pr-1 rounded">{{ $category->category_name }}</span>
                                                    @endforeach
                                                </div>
                                                <hr class="mt-3 mb-2">
                                                <div class="two_btn_set">
                                                    @if($item->item_status == \App\Item::ITEM_PUBLISHED)
                                                    <a href="{{ route('page.item', $item->item_slug) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-external-link-alt"></i>
                                                        {{ __('prefer_country.view-item') }}
                                                    </a>
                                                    @endif
                                                    <a href="{{ route('user.articles.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="far fa-edit"></i>
                                                        {{ __('backend.shared.edit') }}
                                                    </a>
                                                </div>
                                                <hr class="mt-2 mb-2">
                                                <div class="two_btn_set_bottom">
                                                    <span class="text-info">
                                                        <i class="far fa-plus-square"></i>
                                                        {{ __('review.backend.posted-at') . ' ' . \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                                    </span>
                                                    @if($item->created_at != $item->updated_at)
                                                    <span class="text-info">
                                                        |
                                                        <i class="far fa-edit"></i>
                                                        {{ __('review.backend.updated-at') . ' ' . \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }}
                                                    </span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- <hr class="mt-5">

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success m-2 text-white">
                                    {{ __('backend.shared.update') }}
    </button>
    <a class="btn btn-warning m-2 text-white" href="{{ route('user.profile.password.edit') }}">
        {{ __('backend.user.change-password') }}
    </a>
</div>
</div> --}}


{{-- </form> --}}
</div>
</div>
</div>
</div>

<!-- Croppie Modal -->
<div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.user.crop-profile-image') }}</h5>
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

<div class="modal fade" id="cover-image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="cover-image-crop-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width: 1000px !important;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.user.crop-profile-image') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div id="cover_image_demo"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="custom-file">
                            <input id="upload_cover_image_input" type="file" class="custom-file-input" accept=".jpg,.jpeg,.png">
                            <label class="custom-file-label" for="upload_cover_image_input">{{ __('backend.user.choose-image') }}</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                <button id="crop_cover_image" type="button" class="btn btn-primary">{{ __('backend.user.crop-image') }}</button>
            </div>
        </div>
    </div>
</div>

@foreach($video_media_array as $video_media_key => $video_media_value)
<div class="modal fade" id="editMediaModal_{{ $video_media_value->id }}" tabindex="-1" role="dialog" aria-labelledby="editMediaModal_{{ $video_media_value->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Media') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="update-article-slug-form" action="{{ route('media.update', ['media_detail' => $video_media_value]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3">
                            <label for="media_type" class="text-black">Type</label>
                            <select id="media_type" class="form-control selectpicker @error('media_type') is-invalid @enderror" name="media_type" title="Select Type">
                                @foreach(\App\MediaDetail::VIDEO_MEDIA_TYPE as $mkey => $mvalue)
                                <option value="{{ $mkey }}" {{ $video_media_value->media_type == $mkey ? 'selected' : '' }}>{{ $mvalue }}
                                </option>
                                @endforeach
                            </select>
                            @error('media_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-9">
                            <label for="media_url" class="text-black">URL</label>
                            <input id="media_url" type="url" class="form-control @error('media_url') is-invalid @enderror" name="media_url" value="{{ $video_media_value->media_url }}">
                            @error('media_url')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('backend.shared.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteMediaModal_{{ $video_media_value->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteMediaModal_{{ $video_media_value->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Delete Media') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ \App\MediaDetail::MEDIA_TYPE[$video_media_value->media_type] . ' : ' . $video_media_value->media_url }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                <form action="{{ route('media.destroy', ['media_detail' => $video_media_value]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($ebook_media_array as $ebook_media_key => $ebook_media_value)
<div class="modal fade" id="deleteEbookMediaModal_{{ $ebook_media_value->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteEbookMediaModal_{{ $ebook_media_value->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle" style="color: black;">{{ __('Delete Media') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ \App\MediaDetail::MEDIA_TYPE[$ebook_media_value->media_type] . ' : ' . $ebook_media_value->media_name }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                <form action="{{ route('ebookmedia.destroy', ['media_detail' => $ebook_media_value]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($podcast_media_array as $podcast_media_key => $podcast_media_value)
<div class="modal fade" id="editPodcastMediaModal_{{ $podcast_media_value->id }}" tabindex="-1" role="dialog" aria-labelledby="editPodcastMediaModal_{{ $podcast_media_value->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle" style="color: #292b2c">{{ __('Edit Podcast') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="update-article-slug-form" action="{{ route('podcastmedia.update', ['podcate_detail' => $podcast_media_value]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-3">
                            <label for="media_type" class="text-black">Type</label>
                            <select id="media_type" class="form-control selectpicker @error('media_type') is-invalid @enderror" name="podcast_web_type" title="Select Type">
                                <option name="apple_podcast" value="apple_podcast" {{ $podcast_media_value->podcast_web_type == 'apple_podcast' ? 'selected' : '' }} disabled>Apple Podcast</option>
                                <option name="spotify_podcast" value="spotify_podcast" {{ $podcast_media_value->podcast_web_type == 'spotify_podcast' ? 'selected' : '' }} disabled>Spotify Podcast</option>
                                <option name="stitcher_podcast" value="stitcher_podcast" {{ $podcast_media_value->podcast_web_type == 'stitcher_podcast' ? 'selected' : '' }} disabled>Stitcher Podcast</option>
                                <option name="google_podcast" value="google_podcast" {{ $podcast_media_value->podcast_web_type == 'google_podcast' ? 'selected' : '' }} disabled>Google Podcast</option>
                            </select>
                            @error('media_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-9">
                            <label for="media_url" class="text-black">Podcast Title</label>
                            <input type="hidden" name="podcast_media_id" value="{{ $podcast_media_value->id }}">
                            <input id="podcast_name" type="text" class="form-control @error('podcast_name') is-invalid @enderror" name="podcast_name" value="{{ $podcast_media_value->media_name }}">
                            @error('media_url')
                            <span class="invalid-tooltip" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('backend.shared.update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePodcastMediaModal_{{ $podcast_media_value->id }}" tabindex="-1" role="dialog" aria-labelledby="deletePodcastMediaModal_{{ $podcast_media_value->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle" style="color: black;">{{ __('Delete Media') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ \App\MediaDetail::MEDIA_TYPE[$podcast_media_value->media_type] . ' : ' . $podcast_media_value->media_name }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                <form action="{{ route('podcastmedia.destroy', ['media_detail' => $podcast_media_value]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="earn_points_detail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle" style="color: black;">Profile Progress</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="basic_content">
                    <div class="col-md-12">
                        <span class="bold">Basic Profile complete includes</span>
                    </div>
                    <div class="col-md-6">
                        <ul class="list_design_ptb-30">
                            <li class="user_image">Picture</li>
                            <li class="category">Category</li>
                            <li class="name">Name</li>
                            <li class="company_name">Company Name</li>
                            <li class="phone">Phone</li>
                            <li class="email">Email</li>
                            <li class="working_type">Working Method</li>
                            <li class="preferred_pronouns">Pronouns</li>
                            <li class="hourly_rate_type">Rate</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list_design_ptb-30">
                            <li class="experience_year">Years Exp.</li>
                            {{-- <li>Certifications</li>
                            <li>Awards</li> --}}
                            <li class="address">Address</li>
                            <li class="country_id">Country</li>
                            <li class="state_id">State</li>
                            <li class="city_id">City</li>
                            <li class="post_code">Zip Code</li>
                        </ul>
                    </div>
                </div>
                <div class="row" id="social_content">
                    <div class="col-md-12">
                        <span class="bold">Basic + Social Profile complete includes</span>
                        <ul class="list_design_ptb-30">
                            <li class="website">Website</li>
                            <li class="instagram">IG Handle*</li>
                            <li class="facebook">Facebook*</li>
                            <li class="linkedin">LinkedIn*</li>
                            <li class="youtube">YouTube*</li>
                        </ul>
                        <p class="content">*Must use /have a min of 3 platforms</p>
                    </div>
                </div>
                <div class="row" id="bronze_content">
                    <div class="col-md-12">
                        <span class="bold">Basic + Social + Bronze Level complete includes</span>
                        <ul class="list_design_ptb-30 d-flex">
                            <li id="ten_content">10 pieces of content uploaded</li>&nbsp;<span id="ten_content_count"></span>
                        </ul>
                    </div>
                </div>
                <div class="row" id="silver_content">
                    <div class="col-md-12">
                        <span class="bold">Basic + Social + Bronze + Silver Level complete includes</span>
                        <ul class="list_design_ptb-30">
                            <div class="">
                                <li id="twenty_content">20 Pieces of content AND must include at least one from each type (Video, Podcast, Ebook)</li>&nbsp;<span id="twenty_content_count"></span>
                            </div>
                        </ul>
                    </div>
                </div>
                <div class="row" id="gold_content">
                    <div class="col-md-12">
                        <span class="bold">Basic + Social + Bronze + Silver + Gold Level complete</span>
                        <ul class="list_design_ptb-30 d-flex">
                            <li id="three_client_review">3 Client Reviews </li>&nbsp;<span id="three_client_review_count"></span>
                        </ul>
                    </div>
                </div>
                <div class="row" id="platinum_content">
                    <div class="col-md-12">
                        <span class="bold">Basic + Social + Bronze + Silver + Gold + Platinum Level complete</span>
                        <ul class="list_design_ptb-30">
                            <div class="d-flex">
                                <li id="thirty_content">30 Pieces of content</li>&nbsp;<span id="thirty_content_count"></span>
                            </div>
                              <div class="d-flex">
                                  <li id="seven_client_review">7 Client Reviews</li>&nbsp;<span id="seven_client_review_count"></span>
                             </div>
                            <li id="one_client_referral">1 Client Referral</li>
                        </ul>
                    </div>
                </div>
                <div class="row" id="rhodium_content">
                    <div class="col-md-12">
                        <span class="bold">Basic + Social + Bronze + Silver + Gold + Platinum + Rhodium Level complete</span>
                        <ul class="list_design_ptb-30 d-flex">
                            <li id="referrals">5 Client referrals</li>&nbsp;<span id="referal_count"></span>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
<!-- <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@include('backend.user.partials.bootstrap-select-locale')
<script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>
<script>
    $(document).ready(function() {
        @error('state_id')
        $('#select_country_id').val(0);
        $('#select_state_id').empty();
        $('#select_city_id').empty();
        $('#select_country_id').selectpicker('refresh');
        $('#select_state_id').selectpicker('refresh');
        $('#select_city_id').selectpicker('refresh');
        @enderror
            "use strict";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.category_ids').select2({
            maximumSelectionLength: 5
        });

        $('.selectpicker-category').selectpicker({
            maxOptions: 5
        });

        $('.selectpicker').selectpicker();

        /* Start the croppie image plugin */
        var image_crop = null;
        $('#upload_image').on('click', function() {
            $('#image-crop-modal').modal('show');
            $('#image_error_div').hide();
            $('#img_error').text('');
        });

        var fileTypes = ['jpg', 'jpeg', 'png'];
        $('#upload_image_input').on('change', function() {
            if (!image_crop) {
                image_crop = $('#image_demo').croppie({
                    enableExif: true,
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'square'
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    },
                    enableOrientation: true
                });
            }
            var reader = new FileReader();
            var file = this.files[0]; // Get your file here
            var fileExt = file.type.split('/')[1]; // Get the file extension
            // console.log(fileExt);

            if (fileTypes.indexOf(fileExt) !== -1) {

                reader.onload = function(event) {
                    image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function() {
                        // console.log('jQuery bind complete');
                    });
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                // alert('Please choose only .jpg,.jpeg,.png file');
                $('#image-crop-modal').trigger('reset');
                $('#image-crop-modal').modal('hide');
                $('#upload_image_input').val('');
                image_crop = null;
                $('#image_demo').croppie('destroy');
                $('#img_error').text('Please choose only .jpg,.jpeg,.png file');
                $('#image_error_div').show();
            }
        });

        $('#crop_image').on("click", function(event) {
            image_crop.croppie('result', {
                type: 'base64',
                size: 'viewport'
            }).then(function(response) {
                $('#feature_image').val(response);
                $('#image_preview').attr("src", response);
            });
            $('#image-crop-modal').modal('hide')
        });

        var cover_image_crop = null;
        $('#upload_cover_image').on('click', function() {
            $('#cover-image-crop-modal').modal('show');
            $('#cover_image_error_div').hide();
        });

        var fileTypes = ['jpg', 'jpeg', 'png'];
        $('#upload_cover_image_input').on('change', function() {
            if (!cover_image_crop) {
                cover_image_crop = $('#cover_image_demo').croppie({
                    enableExif: true,
                    viewport: {
                        width: 999,
                        height: 312,
                    },
                    boundary: {
                        width: 950,
                        height: 650
                    },
                    showZoomer: false,
                    enableOrientation: true
                });
            }
            var reader = new FileReader();
            var file = this.files[0]; // Get your file here
            var fileExt = file.type.split('/')[1]; // Get the file extension
            if (fileTypes.indexOf(fileExt) !== -1) {
                reader.onload = function(event) {
                    cover_image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function() {
                        // console.log('jQuery bind complete');
                    });
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                $('#cover-image-crop-modal').trigger('reset');
                $('#cover-image-crop-modal').modal('hide');
                $('#upload_cover_image_input').val('');
                cover_image_crop = null;
                $('#cover_image_demo').croppie('destroy');
                $('#cover_img_error').text('Please choose only .jpg,.jpeg,.png file');
                $('#cover_image_error_div').show();

            }
        });

        $('#crop_cover_image').on("click", function(event) {
            cover_image_crop.croppie('result', {
                type: 'base64',
                size: 'original',
                format: 'png',
                quality: 1
            }).then(function(response) {
                $('#feature_cover_image').val(response);
                $('#cover_image_preview').attr("src", response);
            });
            $('#cover-image-crop-modal').modal('hide')
        });
        /* End the croppie image plugin */

        /* Start delete feature image button */
        $('#delete_user_profile_image_button').on('click', function() {
            $('#delete_user_profile_image_button').attr("disabled", true);
            var ajax_url = '/ajax/user/image/delete/' + '{{ $login_user->id }}';
            jQuery.ajax({
                url: ajax_url,
                method: 'post',
                success: function(result) {
                    // console.log(result);

                    $('#image_preview').attr("src",
                        "{{ asset('backend/images/placeholder/profile-' . intval($login_user->id % 10) . '.webp') }}"
                    );
                    $('#feature_image').val("");

                    $('#delete_user_profile_image_button').attr("disabled", false);
                }
            });
        });
        /* End delete feature image button */
        /* Start delete cover image button */
        $('#delete_user_cover_image_button').on('click', function() {
            // console.log("fkldfkldk")
            $('#delete_user_cover_image_button').attr("disabled", true);
            // var id = '<?php echo $login_user->user_cover_image; ?>';
            // var url = "{{route('user.coverimage.delete', 0)}}";
            // url = url.replace('0', id);
            var ajax_url = '/ajax/user/coverimage/delete/' + '{{ $login_user->user_cover_image }}';
            // console.log(url);
            jQuery.ajax({
                url: ajax_url,
                method: 'post',
                success: function(result) {
                    // console.log(result);

                    $('#image_preview').attr("src",
                        "{{ asset('backend/images/placeholder/profile-' . intval($login_user->id % 10) . '.webp') }}"
                    );
                    $('#feature_image').val("");

                    $('#delete_user_cover_image_button').attr("disabled", false);
                    location.reload();
                }
            });
        });
        /* End delete cover image button */

        /* Start country, state, city selector */
        $('#select_country_id').on('change', function() {
            $('#select_state_id').html(
                "<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
            $('#select_city_id').html(
                "<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
            //  $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
            $('#select_state_id').selectpicker('refresh');
            $('#select_city_id').selectpicker('refresh');

            if (this.value > 0) {
                var ajax_url = '/ajax/states/' + this.value;
                // var id = this.value;
                // var url = "{{route('json.state', 0)}}";
                // url = url.replace('0', id);
                jQuery.ajax({
                    url: ajax_url,
                    method: 'get',
                    success: function(result) {
                        // $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                        $('#select_state_id').empty();
                        $('#select_city_id').empty();

                        $.each(JSON.parse(result), function(key, value) {
                            var state_id = value.id;
                            var state_name = value.state_name;
                            $('#select_state_id').append('<option value="' + state_id +
                                '">' + state_name + '</option>');
                        });
                        $('#select_state_id').selectpicker('refresh');
                        $('#select_city_id').selectpicker('refresh');

                    }
                });
            }
        });

        $('#select_state_id').on('change', function() {
            $('#select_city_id').html(
                "<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
            $('#select_city_id').selectpicker('refresh');
            if (this.value > 0) {
                var ajax_url = '/ajax/cities/' + this.value;
                // var id = this.value;
                // var url = "{{route('json.city', 0)}}";
                // url = url.replace('0', id);
                jQuery.ajax({
                    url: ajax_url,
                    method: 'get',
                    success: function(result) {
                        // $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                        $('#select_city_id').empty();
                        $.each(JSON.parse(result), function(key, value) {
                            var city_id = value.id;
                            var city_name = value.city_name;
                            $('#select_city_id').append('<option value="' + city_id +
                                '">' + city_name + '</option>');
                        });
                        $('#select_city_id').selectpicker('refresh');
                    }
                });
            }
        });

        // @if(old('country_id'))
        // var ajax_url_initial_states = '/ajax/states/{{ old('
        // country_id ') }}';
        // jQuery.ajax({
        //     url: ajax_url_initial_states,
        //     method: 'get',
        //     success: function(result) {
        //         // $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
        //         $('#select_state_id').empty();
        //         $.each(JSON.parse(result), function(key, value) {
        //             var state_id = value.id;
        //             var state_name = value.state_name;
        //             if (state_id === {
        //                     {
        //                         old('state_id')
        //                     }
        //                 }) {
        //                 $('#select_state_id').append('<option value="' + state_id +
        //                     '" selected>' + state_name + '</option>');
        //             } else {
        //                 $('#select_state_id').append('<option value="' + state_id + '">' +
        //                     state_name + '</option>');
        //             }
        //         });
        //         $('#select_state_id').selectpicker('refresh');
        //     }
        // });
        // @endif

        // @if(old('state_id'))
        // var ajax_url_initial_cities = '/ajax/cities/{{ old('
        // state_id ') }}';
        // jQuery.ajax({
        //     url: ajax_url_initial_cities,
        //     method: 'get',
        //     success: function(result) {
        //         // $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
        //         $('#select_city_id').empty();
        //         $.each(JSON.parse(result), function(key, value) {
        //             var city_id = value.id;
        //             var city_name = value.city_name;
        //             if (city_id === {
        //                     {
        //                         old('city_id')
        //                     }
        //                 }) {
        //                 $('#select_city_id').append('<option value="' + city_id +
        //                     '" selected>' + city_name + '</option>');
        //             } else {
        //                 $('#select_city_id').append('<option value="' + city_id + '">' +
        //                     city_name + '</option>');
        //             }
        //         });
        //         $('#select_city_id').selectpicker('refresh');
        //     }
        // });
        // @endif
        /* End country, state, city selector */
        //$('#select_country_id').trigger('change');

    });

    $('#media_type_create_button').on('click', function() {
        $('.err_media_url').html("");
        if ($("#media_url").val() == '') {
            $('.err_media_url').html("Please enter Youtube Video URL");
            return false;
        }

        var youtubeUrl = $("#media_url").val();
        // console.log(youtubeUrl);
        var matchUrl = ".youtube";
        var matchUrl1 = "youtu.be";
        var matchUrl2 = "channel";

        if (youtubeUrl.indexOf(matchUrl) == -1 && youtubeUrl.indexOf(matchUrl1) == -1 && youtubeUrl.indexOf(
                matchUrl3) > -1) {
            $('.err_media_url').html("Please enter Youtube URL Only");
            return false;
        }

        if (youtubeUrl.indexOf(matchUrl) > -1 || youtubeUrl.indexOf(matchUrl1) > -1) {
            if (youtubeUrl.indexOf('embed') > -1) {
                $('.err_media_url').html("Please enter proper youtube URL");
                return false;
            } else {
                if (youtubeUrl.indexOf(matchUrl) > -1 && youtubeUrl.indexOf(matchUrl2) == -1) {
                    var id = youtubeUrl.split("?v=")[1];
                    var media_url = "https://www.youtube.com/embed/" + id;

                } else if (youtubeUrl.indexOf(matchUrl1) > -1 && youtubeUrl.indexOf(matchUrl2) == -1) {
                    var id = youtubeUrl.split(".be/")[1];
                    var media_url = "https://www.youtube.com/embed/" + id;

                } else if (youtubeUrl.indexOf(matchUrl2) > -1) {
                    $('.err_media_url').html("Please enter youtube video URL only");
                    return false;
                } else {
                    $('.err_media_url').html("Please enter proper youtube URL");
                    return false;
                }

            }

        }


        var media_type_text = $("#media_type option:selected").text();
        var media_type_value = $("#media_type").val();
        // var media_type_value = media_url;
        // var media_url = $("#media_url").val();

        var media_detail_value = media_type_value + '||' + media_url;
        var media_detail_text = media_type_text + ' : ' + media_url;

        $("#media_details_added").append("<div class='col-12'><input type='hidden' name='media_details[]' value='" +
            media_detail_value + "'>" + media_detail_text +
            "<a class='btn btn-sm text-danger bg-white' onclick='$(this).parent().remove();'><i class='far fa-trash-alt'></i></a></div>"
        );
        $("#media_url").val('');

    });


//for podcast
$('#podcastFrm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $('.podcast_err_media_url').html("");
        $('.podcast_success_msg').html('');
        $('.podcast_image').text('');                
        $('.podcast_web_type').text('');                
        $('.podcast_cover').text('');                
        $('.podcast_name').text('');

        var ajax_url = '/user/podcast/details';
        jQuery.ajax({
            url: ajax_url,
            data: formData,
            method: 'post',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,            

            success: function(result) {  
                
                console.log(result);

                $('#podcastSubmitBtn').prop("disabled", false);
                $('#frmSubmit').prop("disabled", false);
                $('#ebookSubmitBtn').prop("disabled", false);
                // $('#podcast_web_type').prop("disabled", false);
                $('#podcast_web_type').removeAttr('disabled');

                // console.log(result.status);
                if (result.status == 'success') {
                    let podcast_id = result.data.podcast_id;
                    let podcast_name = result.data.podcast_name;
                    // console.log(podcast_name);
                    $("#podcast_details_added").append('<div class="col-12 col-md-12 col-lg-12 p-0 "><div class="row border_set_row"><div class="col-md-6 col-9"><span class="set_width">Podcast : '+podcast_name+'</span></div><div class="col-md-6 col-3"><div class="edit_delete_btn"><a class="text-primary" href="#" data-toggle="modal" data-target="#editPodcastMediaModal_'+podcast_id+'" data-id="'+podcast_name+'"><img src="{{ asset("frontend/images/edit_icon.png") }}" alt="Edit" height="25px"></a><a class="text-danger" href="#" data-toggle="modal" data-target="#deletePodcastMediaModal_'+podcast_id+'"><img src="{{ asset("frontend/images/delete_icon.png") }}" alt="Delete" height="25px"></a></div></div></div></div>');
                    location.reload();
                    // $("#podcast_web_type").val('');
                    $("#podcast_image").val('');
                    $("#podcast_cover").val('');
                    $("#podcast_name").val('');
                    $('.podcast_success_msg').html('Podcast added successfully!');

                }
                if (result.status == 'error') {
                    $.each(result.msg, function(key, val) {
                        if (result.msg.podcast_image) {
                            $('.podcast_image').text(result.msg.podcast_image);
                        }
                        if (result.msg.podcast_web_type) {
                            $('.podcast_web_type').text(result.msg.podcast_web_type);
                        }
                        if (result.msg.podcast_cover) {
                            $('.podcast_cover').text(result.msg.podcast_cover);
                        }
                        if (result.msg.podcast_name) {
                            $('.podcast_name').text(result.msg.podcast_name);
                        }
                    });
                }
                if (result.status == 'url_error') {                                           
                    $('.podcast_image').text(result.msg);                       
                }
            }
        });

    });

    //for Ebook
    $('#ebookFrm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $('.ebook_success_msg').html('');
        $("#media_name").val('');
        $("#media_image").val('');
        $("#media_cover").val('');
        // $('.podcast_err_media_url').html("");
        // $('.podcast_success_msg').html('');
        // if ($("#podcast_web_type").val() == '') {
        //     $('.podcast_type_err_media_url').html("Please select podcast type");
        //     return false;
        // }
        // if ($("#podcast_image").val() == '') {
        //     $('.podcast_err_media_url').html("Please enter Podcast URL");
        //     return false;
        // }



        var ajax_url = '/user/ebook/details';
        jQuery.ajax({
            url: ajax_url,
            data: formData,
            method: 'post',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            

            success: function(result) {

                console.log(result);
                $('#podcastSubmitBtn').prop( "disabled", false);
                $('#frmSubmit').prop( "disabled", false);
                $('#ebookSubmitBtn').prop( "disabled", false);
                $('#podcast_web_type').prop( "disabled", false);

                if (result.status == 'success') {
                    let ebook_name = result.data.media_name;
                    let ebook_id = result.data.ebook_id;
                    let ebook_msg = result.data.msg;
                    // console.log(result)

                    // $('.media_cover').text(result.data.media_cover);
                    // $('.media_image').text(result.data.media_image);
                    // $('.media_name').text(result.data.media_name);

                    $("#ebook_details_added").append('<div class="col-12 col-md-12 col-lg-12 p-0"><div class="row border_set_row"><div class="col-md-6 col-89"><span class="set_width">E Book '+ ebook_name +'</span></div><div class="col-md-6 col-3"><div class="edit_delete_btn"><a class="text-danger" href="#" data-toggle="modal" data-target="#deleteEbookMediaModal_'+ ebook_id +'"><img src="{{ asset("frontend/images/delete_icon.png") }}" alt="" height="25px"></a></div></div></div></div>');
                    location.reload();
                    // $("#podcast_web_type").val('');
                    
                    $('.ebook_success_msg').html(ebook_msg);

                }
                if (result.status == 'error') {
                    console.log(result);
                    $.each(result.msg, function(key, val) {
                        if (result.msg.media_cover) {
                            $('.media_cover').text(result.msg.media_cover);
                        }
                        if (result.msg.media_image) {
                            $('.media_image').text(result.msg.media_image);
                        }
                        if (result.msg.media_name) {
                            $('.media_name').text(result.msg.media_name);
                        }
                    });
                }


            }
        });

    });

    // $('.edit_delete_btn').on('click',function(){
    //     alert("skjlls");
    // })


    $('#podcast_web_type').on('change', function() {
        var podcast_web_type_value = $(this).val();
        // console.log(podcast_web_type_value);

        $('.podcast_err_media_url').html("");
        $('.podcast_success_msg').html('');
        $('.podcast_image').text('');                
        $('.podcast_web_type').text('');                
        $('.podcast_cover').text('');                
        $('.podcast_name').text('');
        $('.ebook_success_msg').html('');
        $("#media_name").val('');
        $("#media_image").val('');
        $("#media_cover").val('');
        if (podcast_web_type_value == 'stitcher_podcast') {
            $('#podcast_div_title').css("display", "block");
            $('#podcast_cover_image').css("display", "block");

        } else {
            $('#podcast_div_title').css("display", "none");
            $('#podcast_cover_image').css("display", "none");
        }
    });

    $('#media_url').on('input', function() {
        $('.err_media_url').text('');
    });

    $('#facebook').on('input', function() {
        $('.err_facebook_url').html('');
        var facebookUrl = $("#facebook").val();
        var matchUrl = "facebook";
        if (facebookUrl.indexOf(matchUrl) == -1) {
            $('.err_facebook_url').html("Please enter Facebook URL Only");
            $('#submit').attr("disabled", true);
            if (facebookUrl.length == 0) {
                $('#submit').attr("disabled", false);
                $('.err_facebook_url').html('');
            }
            return false;
        } else {
            $('.err_facebook_url').html('');
            $('#submit').attr("disabled", false);

        }
    });

    $('#linkedin').on('input', function() {
        $('.err_linkedin_url').html('');
        var linkedinUrl = $("#linkedin").val();
        var matchUrl = "linkedin";
        if (linkedinUrl.indexOf(matchUrl) == -1) {
            $('.err_linkedin_url').html("Please enter Linkedin URL Only");
            $('#submit').attr("disabled", true);
            if (linkedinUrl.length == 0) {
                $('#submit').attr("disabled", false);
                $('.err_linkedin_url').html('');
            }
            return false;
        } else {
            $('.err_linkedin_url').html('');
            $('#submit').attr("disabled", false);

        }
    });
    $('#youtube').on('input', function() {
        $('.err_youtube_url').html('');
        var youtubeinUrl = $("#youtube").val();
        var matchUrl = ".youtube";
        var matchUrl1 = "youtu.be";
        if (youtubeinUrl.indexOf(matchUrl) == -1 && youtubeinUrl.indexOf(matchUrl1) == -1) {
            console.log("dkslkdlsjkl");
            $('.err_youtube_url').html("Please enter Youtube URL Only");
            $('#submit').attr("disabled", true);
            if (youtubeinUrl.length == 0) {
                $('#submit').attr("disabled", false);
                $('.err_youtube_url').html('');
            }
            return false;
        }
        // else {
        //     if (youtubeinUrl.indexOf('@') > -1) {
        //         // console.log("lllllllllllll")
        //         $('.err_youtube_url').html("Please enter Youtube URL Only");
        //         $('#submit').attr("disabled", true);
        //         // return false;
        //     } else {
        //         $('.err_youtube_url').html('');
        //         $('#submit').attr("disabled", false);

        //     }

        //}
    });

    function isUrl(s) {
        var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
        return regexp.test(s);
    }
    $('#instagram').on('input', function() {
        $('.err_instagram_url').html('');
        var instaurl = isUrl($("#instagram").val());
        var instaurl1 = $("#instagram").val();
        matchUrl_insta_www = 'www.';
        //    console.log(instaurl);
        //    if(instaurl){
        if (instaurl || instaurl1.indexOf(matchUrl_insta_www) > -1 || instaurl1.indexOf('https') > -1 || instaurl1
            .indexOf('http') > -1 || instaurl1.indexOf('.com') > -1 || instaurl1.indexOf('/') > -1) {
            $('.err_instagram_url').html("Please enter valid instagram user name Only");
            $('#submit').attr("disabled", true);
            return false;
        } else {
            $('.err_instagram_url').html('');
            $('#submit').attr("disabled", false);

        }

    });
    window.onload = function() {
        var chartData = {
            "Profile Progress": [{
                cursor: "pointer",
                explodeOnClick: false,
                innerRadius: "75%",
                legendMarkerType: "square",
                radius: "70%",
                startAngle: 280,
                type: "doughnut",
                indexLabelLineThickness: 4,
                dataPoints: <?php echo json_encode($data_points, JSON_NUMERIC_CHECK); ?>
            }],
        };

        var dataOptions = {
            backgroundColor: "#FFEEF9",
            animationEnabled: true,
            theme: "light2",
        };

        var chart = new CanvasJS.Chart("chartContainer", dataOptions);
        chart.options.data = chartData["Profile Progress"];
        chart.render();


    }
</script>
<script>
        $(document).ready(function(){
            $('#profileCompleteModalBtn').on('click',function(){
                //console.log("kskdklskldlksdlk");
                let user_id = '<?php echo $login_user['id']; ?>';
                //console.log(user_id);

                $.ajax({
                    type: 'GET',                    
                    url: '/user/profile-completed/'+user_id,                    
                    success: function(response) {
                        // console.log(response);
                        if(response.status == 'success'){
                            $.each(response.data.user_detail, function(key, val) {
                                if(val !== null){
                                    $('.'+key).css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                                    // key = null;
                                    // console.log(key);
                                    // console.log(val);
                                }

                            });
                            let referrals = response.data.referrals;
                            let all_content_count = response.data.all_content_count;
                            let review_count = response.data.review_count;
                            let category = response.data.categories;
                            // console.log(referrals);

                            if(category >= 1){
                                $('.category').css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                                // $('#referal_count').text('(5/'+referrals +')')
                            }
                            if(referrals >= 1){
                                $('#one_client_referral').css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                                // $('#referal_count').text('(5/'+referrals +')')
                            }
                            if(referrals >= 5){
                                // referrals = 5;
                                $('#referrals').css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                                $('#referal_count').text('('+referrals+'/5)').css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                            }else{
                                $('#referal_count').text('('+referrals+'/5)')
                            }
                            if(all_content_count >= 10){
                                // all_content_count = 10;
                                $('#ten_content').css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                                $('#ten_content_count').text('('+all_content_count +'/10)')
                            }
                            if(all_content_count >= 20){
                                // all_content_count = 20;
                                $('#twenty_content').css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                            }
                            $('#twenty_content_count').text('('+all_content_count +'/20)');

                            if(all_content_count >= 30){
                                // all_content_count = 30;
                                $('#thirty_content').css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                                $('#thirty_content_count').text('('+all_content_count +'/30)').css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                            }else{
                                $('#thirty_content_count').text('('+all_content_count +'/30)');
                            }
                            

                            if(review_count >= 3){
                                // review_count = 3;
                                $('#three_client_review').css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                            }
                            $('#three_client_review_count').text('('+review_count +'/3)');

                            if(review_count >= 7){
                                // review_count = 7;
                                $('#seven_client_review').css("list-style-image","url({{ asset('frontend/images/green_tick.png') }})");
                            }
                            $('#seven_client_review_count').text('('+review_count +'/7)');



                            $('#earn_points_detail').modal('show');
                        }
                    }
                });
            })
        })    
    </script>

@endsection