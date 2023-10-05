@extends('backend.admin.layouts.app')

@section('styles')

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <link href="{{ asset('backend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <!-- Image Crop Css -->
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />

    <!-- Bootstrap FD Css-->
    <link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />

    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/css/bootstrap-select.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="{{ asset('backend/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" />

    <!-- <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/dist/ui/trumbowyg.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/dist/plugins/colors/ui/trumbowyg.colors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/trumbowyg/dist/plugins/table/ui/trumbowyg.table.min.css') }}"> -->

@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-8 col-md-8 col-lg-8 col-xl-9">
            <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('backend.article.add-article') }}</h1>
            <p class="mb-4 font-sm-14">{{ __('backend.article.add-article-desc-user') }}</p>
        </div>
        <div class="col-4 col-md-4 col-lg-4 col-xl-3  text-right">
            <a href="{{ route('admin.items.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pb-4 font_icon_color">
        <div class="col-12">          
            <div class="row">
                <div class="col-12">                    
                    <form method="POST" action="{{ route('admin.items.store') }}" id="item-create-form">
                        @csrf
                        <input type="hidden" name="item_type" value="1">
                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                             <i class="fas fa-store"></i>
                                            {{ __('backend.item.general-info') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('item_hour.item-general-info-help') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-6">
                                        <label for="item_title" class="text-black">{{ __('backend.item.title') }}<span class="text-danger">*</span></label>
                                        <input id="item_title" type="text" class="form-control @error('item_title') is-invalid @enderror" name="item_title" value="{{ old('item_title') }}">
                                        @error('item_title')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="user_id" class="text-black">{{ __('role_permission.item.listing-owner') }}</label>
                                        <select id="user_id" class="selectpicker form-control @error('user_id') is-invalid @enderror" name="user_id" data-live-search="true">
                                            {{-- <option value="{{ $login_user->id }}" {{ old('user_id') == $login_user->id ? 'selected' : '' }}>{{ __('role_permission.item.myself') . ' (' . $login_user->email . ')' }}</option> --}}
                                            @foreach($other_users as $other_users_key => $other_user)
                                                <option value="{{ $other_user->id }}" {{ old('user_id') == $other_user->id ? 'selected' : '' }}>{{ $other_user->name . ' (' . $other_user->email . ')' }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">
                                            <!-- {{ __('role_permission.item.listing-owner-help') }} -->
                                        </small>
                                        @error('user_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <label for="input_category_id" class="text-black">{{ __('backend.article.select-category') }}<span class="text-danger">*</span></label>
                                        {{-- <select multiple size="{{ count($all_categories) }}" class="selectpicker form-control input_category_id @error('category') is-invalid @enderror" name="category[]" data-live-search="true" data-actions-box="true" data-size="10" id="input_category_id"> --}}
                                            <select class="form-control form-select category @error('category') is-invalid @enderror" name="category[]" id="input_category_id" multiple>
                                            @foreach($all_categories as $key => $category)
                                                @php
                                                    if(empty($category["is_parent"])) continue;
                                                    if($category["category_name"] == 'Entrepreneurial' || $category["category_name"] == 'Productivity') continue;
                                                @endphp
                                                <option value="{{ $category['category_id'] }}" {{ in_array($category['category_id'], old('category', $category_ids)) ? 'selected' : '' }}>{{ $category['category_name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    {{-- @foreach($category_ids as $key => $category_id)
                                        <input name="category[]" value="{{ $category_id }}" type="hidden" class="input_category_id">
                                    @endforeach --}}
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-5">
                                        <label for="item_status" class="text-black">{{ __('backend.item.status') }}</label>
                                        <select class="selectpicker form-control @error('item_status') is-invalid @enderror" name="item_status">

                                            <option value="{{ \App\Item::ITEM_SUBMITTED }}">{{ __('backend.item.submitted') }}</option>
                                            <option value="{{ \App\Item::ITEM_PUBLISHED }}" selected>{{ __('backend.item.published') }}</option>
                                            <option value="{{ \App\Item::ITEM_SUSPENDED }}">{{ __('backend.item.suspended') }}</option>

                                        </select>
                                        @error('item_status')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <input type="hidden" name="item_featured" value="{{ \App\Item::ITEM_NOT_FEATURED }}">

                                    {{-- <div class="col-md-3">
                                        <label for="item_featured" class="text-black">{{ __('backend.item.featured') }}</label>
                                        <select class="selectpicker form-control @error('item_featured') is-invalid @enderror" name="item_featured">

                                            <option value="{{ \App\Item::ITEM_NOT_FEATURED }}" selected>{{ __('backend.shared.no') }}</option>
                                            <option value="{{ \App\Item::ITEM_FEATURED }}">{{ __('backend.shared.yes') }}</option>

                                        </select>
                                        @error('item_featured')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div> --}}

                                    <div class="col-md-7">
                                        <label for="item_address" class="text-black">{{ __('backend.item.address') }}<span class="text-danger">*</span></label>
                                        <input id="item_address" type="text" class="form-control @error('item_address') is-invalid @enderror" name="item_address" value="{{ old('item_address') }}">
                                        @error('item_address')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                               {{--<div class="form-row mb-3">

                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input {{ old('item_address_hide') == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" id="item_address_hide" name="item_address_hide" value="1">
                                            <label class="form-check-label display-grid-md" for="item_address_hide">
                                                {{ __('backend.item.hide-address') }}
                                                <small class="text-muted text_hide_sm">
                                                    {{ __('backend.item.hide-address-help') }}
                                                </small>
                                            </label>
                                        </div>
                                        @error('item_address_hide')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                            @enderror
                                        </div>
                                </div>--}} 

                                <div class="form-row mb-3">

                                    <div class="col-xl-3 col-md-6">
                                        <label for="select_country_id" class="text-black">{{ __('backend.setting.country') }}<span class="text-danger">*</span></label>
                                        <select id="select_country_id" class="selectpicker form-control @error('country_id') is-invalid @enderror" name="country_id" data-live-search="true">
                                            {{-- <option selected value="0">{{ __('prefer_country.select-country') }}</option> --}}
                                            @foreach($all_countries as $all_countries_key => $country)
                                                @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE)
                                                    <option value="{{ $country->id }}" {{ $country->id == \App\Country::COUNTRY_DEFAULT_SELECTED ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <label for="select_state_id" class="text-black">{{ __('backend.state.state') }}<span class="text-danger">*</span></label>
                                        <select id="select_state_id" class="selectpicker form-control @error('state_id') is-invalid @enderror" name="state_id" data-live-search="true" title="{{ __('backend.item.select-state') }}">
                                            {{-- <option selected value="0">{{ __('backend.item.select-state') }}</option> --}}
                                        </select>
                                        @error('state_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <label for="select_city_id" class="text-black">{{ __('backend.city.city') }}<span class="text-danger">*</span></label>
                                        <select id="select_city_id" class="selectpicker form-control @error('city_id') is-invalid @enderror" name="city_id" data-live-search="true" title="{{ __('backend.item.select-city') }}">
                                            {{-- <option selected value="0">{{ __('backend.item.select-city') }}</option> --}}
                                        </select>
                                        @error('city_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-xl-3 col-md-6">
                                        <label for="item_postal_code" class="text-black">{{ __('backend.item.postal-code') }}<span class="text-danger">*</span></label>
                                        <input id="item_postal_code" type="text" class="form-control @error('item_postal_code') is-invalid @enderror" name="item_postal_code" value="{{ old('item_postal_code') }}">
                                        @error('item_postal_code')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-row mb-3">

                                   {{--<div class="col-xl-3 col-md-6">
                                        <label for="item_lat" class="text-black">{{ __('backend.item.lat') }}</label>
                                        <input id="item_lat" type="text" class="form-control @error('item_lat') is-invalid @enderror" name="item_lat" value="{{ old('item_lat') }}" aria-describedby="latHelpBlock">
                                        <small id="latHelpBlock" class="form-text text-muted">
                                            <a class="lat_lng_select_button btn btn-sm btn-primary text-white">{{ __('backend.item.select-map') }}</a>
                                        </small>
                                        @error('item_lat')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                         </span>
                                        @enderror
                                    </div>--}}

                                   {{--<div class="col-xl-3 col-md-6">
                                        <label for="item_lng" class="text-black">{{ __('backend.item.lng') }}</label>
                                        <input id="item_lng" type="text" class="form-control @error('item_lng') is-invalid @enderror" name="item_lng" value="{{ old('item_lng') }}" aria-describedby="lngHelpBlock">
                                        <small id="lngHelpBlock" class="form-text text-muted">
                                            <a class="lat_lng_select_button btn btn-sm btn-primary text-white">{{ __('backend.item.select-map') }}</a>
                                        </small>
                                        @error('item_lng')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>--}}

                                    <div class="col-xl-3 col-md-6">
                                        <label for="item_phone" class="text-black">{{ __('backend.item.phone') }}</label>
                                        <input id="item_phone" type="text" class="form-control @error('item_phone') is-invalid @enderror" name="item_phone" value="{{ old('item_phone') }}" onkeypress="validatePostalCode(event)">
                                        @error('item_phone')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>                                   

                                </div>

                                <div class="form-row mb-3">

                                    <div class="col-md-12">
                                        <label for="item_description" class="text-black">{{ __('backend.item.description') }}</label>
                                        <textarea class="form-control @error('item_description') is-invalid @enderror" id="item_description" name="item_description">{{ old('item_description') }}</textarea>
                                        @error('item_description')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Start web & social media -->
                                <div class="form-row mb-3 font_icon_color">
                                    <div class="col-md-4">
                                        <label for="item_website" class="text-black">
                                            <i class="fa-solid fa-globe"></i>
                                            {{ __('backend.item.website') }}
                                        </label>
                                        <input id="item_website" type="text" class="form-control @error('item_website') is-invalid @enderror" name="item_website" value="{{ old('item_website') }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            <!-- {{ __('backend.shared.url-help') }} -->
                                        </small>
                                        @error('item_website')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="item_social_facebook" class="text-black">
                                            <i class="fa-brands fa-facebook-square"></i>
                                            {{ __('backend.item.facebook') }}
                                        </label>
                                        <input id="item_social_facebook" type="text" class="form-control @error('item_social_facebook') is-invalid @enderror" name="item_social_facebook" value="{{ old('item_social_facebook') }}">
                                        <span class="err_media_url" style="color:red"></span>
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            <!-- {{ __('backend.shared.url-help') }} -->
                                        </small>
                                        @error('item_social_facebook')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        @if(Session::has('facebook_error'))
                                            <span class="invalid-tooltip">
                                                <strong>{{ Session::get('facebook_error') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="item_social_twitter" class="text-black">
                                            <i class="fa-brands fa-twitter-square"></i>
                                            {{ __('backend.item.twitter') }}
                                        </label>
                                        <input id="item_social_twitter" type="text" class="form-control @error('item_social_twitter') is-invalid @enderror" name="item_social_twitter" value="{{ old('item_social_twitter') }}">
                                        <span class="err_twitter_url" style="color:red"></span>
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            <!-- {{ __('backend.shared.url-help') }} -->
                                        </small>
                                        @error('item_social_twitter')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        @if(Session::has('twitter_error'))
                                            <span class="invalid-tooltip">
                                                <strong>{{ Session::get('twitter_error') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>

                                <div class="form-row mb-3 font_icon_color">
                                    <div class="col-md-4">
                                        <label for="item_social_linkedin" class="text-black">
                                            <i class="fa-brands fa-linkedin"></i>
                                            {{ __('backend.item.linkedin') }}
                                        </label>
                                        <input id="item_social_linkedin" type="text" class="form-control @error('item_social_linkedin') is-invalid @enderror" name="item_social_linkedin" value="{{ old('item_social_linkedin') }}">
                                        <span class="err_linkedin_url" style="color:red"></span>
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            <!-- {{ __('backend.shared.url-help') }} -->
                                        </small>
                                        @error('item_social_linkedin')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        @if(Session::has('linkedin_error'))
                                            <span class="invalid-tooltip">
                                                <strong>{{ Session::get('linkedin_error') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="item_social_instagram" class="text-black">
                                            <i class="fa-brands fa-instagram-square"></i>
                                            {{ __('item_whatsapp_instagram.item-social-instagram') }}
                                        </label>
                                        <input id="item_social_instagram" type="text" class="form-control @error('item_social_instagram') is-invalid @enderror" name="item_social_instagram" value="{{ old('item_social_instagram') }}">
                                        <span class="err_instagram_url" style="color:red"></span>
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            <!-- {{ __('item_whatsapp_instagram.item-social-instagram-help') }} -->
                                        </small>
                                        @error('item_social_instagram')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        @if(Session::has('instagram_error'))
                                            <span class="invalid-tooltip">
                                                <strong>{{ Session::get('instagram_error') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="item_social_whatsapp" class="text-black">
                                            <i class="fa-brands fa-whatsapp-square"></i>
                                            {{ __('item_whatsapp_instagram.item-social-whatsapp') }}
                                        </label>
                                        <input id="item_social_whatsapp" type="text" class="form-control @error('item_social_whatsapp') is-invalid @enderror" name="item_social_whatsapp" value="{{ old('item_social_whatsapp') }}" onkeypress="validatePostalCode(event)">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            <!-- {{ __('item_whatsapp_instagram.item-social-whatsapp-help') }} -->
                                        </small>
                                        @error('item_social_whatsapp')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>
                                <!-- End web & social media -->
                            </div>
                        </div>

                        <!-- start opening hour section -->
                       {{--<div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                            <i class="fas fa-clock"></i>
                                            {{ __('item_hour.open-hour') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('item_hour.open-hour-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="item_hour_time_zone" class="text-black">{{ __('item_hour.timezone') }}</label>
                                        <select id="item_hour_time_zone" class="selectpicker form-control @error('item_hour_time_zone') is-invalid @enderror" name="item_hour_time_zone" data-live-search="true">
                                            @foreach($time_zone_identifiers as $time_zone_identifiers_key => $time_zone_identifier)
                                                <option value="{{ $time_zone_identifier }}" {{ old('item_hour_time_zone') == $time_zone_identifier ? 'selected' : '' }}>{{ $time_zone_identifier }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('item_hour.timezone-help') }}
                                        </small>
                                        @error('item_hour_time_zone')
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="item_hour_show_hours" class="text-black">{{ __('item_hour.show-open-hour') }}</label>
                                        <select id="item_hour_show_hours" class="selectpicker form-control @error('item_hour_show_hours') is-invalid @enderror" name="item_hour_show_hours" data-live-search="true">
                                            <option value="{{ \App\Item::ITEM_HOUR_SHOW }}" {{ old('item_hour_show_hours') == \App\Item::ITEM_HOUR_SHOW ? 'selected' : '' }}>{{ __('item_hour.show-hour') }}</option>
                                            <option value="{{ \App\Item::ITEM_HOUR_NOT_SHOW }}" {{ old('item_hour_show_hours') == \App\Item::ITEM_HOUR_NOT_SHOW ? 'selected' : '' }}>{{ __('item_hour.not-show-hour') }}</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('item_hour.show-open-hour-help') }}
                                        </small>
                                        @error('item_hour_show_hours')
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-12">
                                        <span class="text-gray-800">{{ __('item_hour.open-hour-hours') }}</span>
                                        <small class="form-text text-muted">
                                            {{ __('item_hour.open-hour-hours-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3 align-items-end">
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="item_hour_day_of_week" class="text-black">{{ __('item_hour.day-of-week') }}</label>
                                        <select id="item_hour_day_of_week" class="selectpicker form-control" name="item_hour_day_of_week" data-live-search="true">
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_MONDAY }}">{{ __('item_hour.monday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_TUESDAY }}">{{ __('item_hour.tuesday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_WEDNESDAY }}">{{ __('item_hour.wednesday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_THURSDAY }}">{{ __('item_hour.thursday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_FRIDAY }}">{{ __('item_hour.friday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_SATURDAY }}">{{ __('item_hour.saturday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_SUNDAY }}">{{ __('item_hour.sunday') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="item_hour_open_time_open_hour" class="text-black">{{ __('item_hour.item-hour-open-hour') }}</label>
                                        <select id="item_hour_open_time_open_hour" class="selectpicker form-control" name="item_hour_open_time_open_hour" data-live-search="true">
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="item_hour_open_time_open_minute" class="text-black">{{ __('item_hour.item-hour-open-minute') }}</label>
                                        <select id="item_hour_open_time_open_minute" class="selectpicker form-control" name="item_hour_open_time_open_minute" data-live-search="true">
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="item_hour_open_time_close_hour" class="text-black">{{ __('item_hour.item-hour-close-hour') }}</label>
                                        <select id="item_hour_open_time_close_hour" class="selectpicker form-control" name="item_hour_open_time_close_hour" data-live-search="true">
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                        <div id="message" style="color:red"></div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="item_hour_open_time_close_minute" class="text-black">{{ __('item_hour.item-hour-close-minute') }}</label>
                                        <select id="item_hour_open_time_close_minute" class="selectpicker form-control" name="item_hour_open_time_close_minute" data-live-search="true">
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 mt-3 mt-sm-0">
                                        <a class="btn btn-sm btn-block btn-primary rounded text-white" id="item_hour_create_button">
                                            <i class="fas fa-plus"></i>
                                            {{ __('item_hour.add-open-hour') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="form-row mb-3" id="open_hour_added_hours">
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-12">
                                        <span class="text-gray-800">{{ __('item_hour.open-hour-exceptions') }}</span>
                                        <small class="form-text text-muted">
                                            {{ __('item_hour.open-hour-exceptions-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3 align-items-end">
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="item_hour_exception_date" class="text-black">{{ __('item_hour.open-hour-exception-date') }}</label>
                                        <input id="item_hour_exception_date" type="text" class="form-control" name="item_hour_exception_date" value="" placeholder="{{ __('item_hour.open-hour-exception-date-placeholder') }}">
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="item_hour_exception_open_time_open_hour" class="text-black">{{ __('item_hour.item-hour-open-hour') }}</label>
                                        <select id="item_hour_exception_open_time_open_hour" class="selectpicker form-control" name="item_hour_exception_open_time_open_hour" data-live-search="true">
                                            <option value="">{{ __('item_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="item_hour_exception_open_time_open_minute" class="text-black">{{ __('item_hour.item-hour-open-minute') }}</label>
                                        <select id="item_hour_exception_open_time_open_minute" class="selectpicker form-control" name="item_hour_exception_open_time_open_minute" data-live-search="true">
                                            <option value="">{{ __('item_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="item_hour_exception_open_time_close_hour" class="text-black">{{ __('item_hour.item-hour-close-hour') }}</label>
                                        <select id="item_hour_exception_open_time_close_hour" class="selectpicker form-control" name="item_hour_exception_open_time_close_hour" data-live-search="true">
                                            <option value="">{{ __('item_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                        <div id="exception_close_hour" style="color:red"></div>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3">
                                        <label for="item_hour_exception_open_time_close_minute" class="text-black">{{ __('item_hour.item-hour-close-minute') }}</label>
                                        <select id="item_hour_exception_open_time_close_minute" class="selectpicker form-control" name="item_hour_exception_open_time_close_minute" data-live-search="true">
                                            <option value="">{{ __('item_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4 col-lg-3 mt-3 mt-sm-0">
                                        <a class="btn btn-sm btn-block btn-primary rounded text-white" id="item_hour_exception_create_button">
                                            <i class="fas fa-plus"></i>
                                            {{ __('item_hour.add-open-hour-exception') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="form-row mb-3" id="open_hour_added_exceptions">
                                </div>
                            </div>
                        </div> --}} 
                        <!-- end opening hour section -->

                        <!-- Start custom field section -->
                        <div class="row border-left-primary">
                            <div class="col-12">
                               {{--<div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                            <i class="fas fa-tasks"></i>
                                            {{ __('backend.item.custom-fields') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('backend.item.custom-field-help') }}
                                        </small>
                                    </div>
                                </div>--}}
                                <div class="form-row mb-3">
                                    @foreach($all_customFields as $key => $customField)
                                        <div class="col-md-4 mb-3">
                                            @if($customField->custom_field_type == \App\CustomField::TYPE_TEXT)
                                                <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                                <textarea class="form-control @error(str_slug($customField->custom_field_name . $customField->id)) is-invalid @enderror" id="{{ str_slug($customField->custom_field_name . $customField->id) }}" rows="5" name="{{ str_slug($customField->custom_field_name . $customField->id) }}">{{ old(str_slug($customField->custom_field_name . $customField->id)) }}</textarea>
                                                @error(str_slug($customField->custom_field_name . $customField->id))
                                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            @endif
                                            @if($customField->custom_field_type == \App\CustomField::TYPE_SELECT)
                                                <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                                <select class="selectpicker form-control" name="{{ str_slug($customField->custom_field_name . $customField->id) }}" id="{{ str_slug($customField->custom_field_name . $customField->id) }}" data-live-search="true">
                                                    @foreach(explode(',', $customField->custom_field_seed_value) as $key => $custom_field_value)
                                                        <option value="{{ $custom_field_value }}" {{ old(str_slug($customField->custom_field_name . $customField->id)) == $custom_field_value ? 'selected' : '' }}>{{ $custom_field_value }}</option>
                                                    @endforeach
                                                </select>
                                                @error(str_slug($customField->custom_field_name . $customField->id))
                                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            @endif
                                            @if($customField->custom_field_type == \App\CustomField::TYPE_MULTI_SELECT)
                                                <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                                <select multiple class="selectpicker form-control" name="{{ str_slug($customField->custom_field_name . $customField->id) }}[]" id="{{ str_slug($customField->custom_field_name . $customField->id) }}" data-live-search="true" data-actions-box="true">
                                                    @foreach(explode(',', $customField->custom_field_seed_value) as $key => $custom_field_value)
                                                        <option value="{{ $custom_field_value }}" {{ old(str_slug($customField->custom_field_name . $customField->id)) == $custom_field_value ? 'selected' : '' }}>{{ $custom_field_value }}</option>
                                                    @endforeach
                                                </select>
                                                @error($customField->custom_field_name . $customField->id)
                                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            @endif
                                            @if($customField->custom_field_type == \App\CustomField::TYPE_LINK)
                                                <label for="{{ str_slug($customField->custom_field_name . $customField->id) }}" class="text-black">{{ $customField->custom_field_name }}</label>
                                                <input id="{{ str_slug($customField->custom_field_name . $customField->id) }}" type="text" class="form-control @error(str_slug($customField->custom_field_name . $customField->id)) is-invalid @enderror" name="{{ str_slug($customField->custom_field_name . $customField->id) }}" value="{{ old(str_slug($customField->custom_field_name . $customField->id)) }}" aria-describedby="linkHelpBlock">
                                                <small id="linkHelpBlock" class="form-text text-muted">
                                                    {{ __('backend.shared.url-help') }}
                                                </small>
                                                @error(str_slug($customField->custom_field_name . $customField->id))
                                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- End custom field section -->

                        <!-- Start feature image and gallery image -->
                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                            <i class="fas fa-images"></i>
                                            {{ __('item_hour.item-photos') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('item_hour.item-photos-help') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-6 col-lg-5 col-xl-5">
                                        <span class="text-lg text-gray-800">{{ __('backend.item.feature-image') }}</span>
                                        <small class="form-text text-muted min-height-md-80">
                                            {{ __('backend.item.feature-image-help') }}
                                        </small>
                                        @error('feature_image')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="alert alert-danger alert-dismissible fade show" id="image_error_div" role="alert" style="display: none;">
                                                    <strong id="img_error"></strong>
                                                </div>
                                                <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.item.select-image') }}</button>
                                                <img id="image_preview" src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" class="img-responsive">
                                                <input id="feature_image" type="hidden" name="feature_image">
                                            </div>
                                        </div>

                                        <div class="row mt-1 mb-1">
                                            <div class="col-12">
                                                <a class="btn btn-danger btn-block text-white" id="delete_feature_image_button">
                                                    <i class="fas fa-trash-alt"></i>
                                                    {{ __('role_permission.item.delete-feature-image') }}
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6 col-lg-7 col-xl-7 mt-3 mt-md-0">
                                        <span class="text-lg text-gray-800">{{ __('backend.item.gallery-images') }}</span>
                                        <small class="form-text text-muted min-height-md-80">
                                            {{ __('theme_directory_hub.listing.gallery-upload-help', ['gallery_photos_count' => $setting_item_max_gallery_photos]) }}
                                        </small>
                                        @error('image_gallery')
                                        <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="alert alert-danger alert-dismissible fade show" id="gallery_image_error_div" role="alert" style="display: none;">
                                                    <strong id="gallery_img_error"></strong>
                                                </div>
                                                <button id="upload_gallery" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.item.select-images') }}</button>
                                                <div class="row" id="selected-images">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End feature image and gallery image -->

                        <hr/>
                        <div class="form-row mb-3">
                            <div class="col-md-12 btn-center">
                                <button type="submit" id="submit" class="btn btn-primary py-2 px-4 text-white btn-100-width-sm">
                                    {{ __('backend.shared.create') }}
                                </button>
                            </div>
                        </div>

                    </form>
                    {{-- @endif --}}
                </div>
            </div>
        </div>
    </div>

      <!-- Croppie Modal -->
    <div class="modal fade cropImageModal" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 
        <button type="button" class="close-modal-custom" data-dismiss="modal" aria-label="Close"><i class="feather icon-x"></i></button>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="modal-header-bg"></div>
                    <div class="up-photo-title">
                        <h5 class="modal-title" id="exampleModalLongTitle" style="color: black;">{{ __('backend.item.crop-feature-image') }}</h5>
                    </div>
                    <div class="up-photo-content pb-5">
                    <div class="row">
                                <div class="col-md-12 text-center">
                                    <div class="custom-file mt-5">
                                        <input id="upload_image_input" type="file" class="custom-file-input" accept=".jpg,.jpeg,.png">
                                        <label class="custom-file-label" for="upload_image_input">{{ __('backend.article.choose-image') }}</label>
                                    </div>
                                </div>
                            </div>
                        <div id="upload-demo" class="center-block mt-3">
                            <!-- <h5><i class="fas fa-arrows-alt mr-1"></i> Drag your photo as you require</h5> -->
                            
                        </div>
                        <div class="upload-action-btn text-center px-2">
                            <button type="button" id="cropImageBtn" class="btn btn-default btn-medium bg-blue px-3 mr-2">{{ __('backend.user.crop-image') }}</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- --------------------------------------- -->
    <div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.item.crop-feature-image') }}</h5>
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
                                <label class="custom-file-label" for="upload_image_input">{{ __('backend.item.choose-image') }}</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="crop_image" type="button" class="btn btn-primary">{{ __('backend.item.crop-image') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - map -->
    <div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="map-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.item.select-map-title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">
                            <div id="map-modal-body"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span id="lat_lng_span"></span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="lat_lng_confirm" type="button" class="btn btn-primary">{{ __('backend.shared.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="{{ asset('backend/vendor/leaflet/leaflet.js') }}"></script>
    @endif

    <!-- Image Crop Plugin Js -->
    <script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>

    <!-- Bootstrap Fd Plugin Js-->
    <script src="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.js') }}"></script>

    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('backend.admin.partials.bootstrap-select-locale')

    <script src="{{ asset('backend/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/resizimg/resizable-resolveconflict.min.js') }}"></script> -->
    <script src="{{ asset('backend/vendor/jquery-resizable/dist/jquery-resizable.min.js') }}"></script>
    <!-- <script src="{{ asset('backend/vendor/trumbowyg/dist/trumbowyg.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/base64/trumbowyg.base64.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/resizimg/trumbowyg.resizimg.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/indent/trumbowyg.indent.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/lineheight/trumbowyg.lineheight.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/noembed/trumbowyg.noembed.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/table/trumbowyg.table.min.js') }}"></script> -->

    <script>

            $(document).ready(function() {

                $('.category').select2({
                    maximumSelectionLength: 5
                });

                $('.selectpicker-category').selectpicker({
                    maxOptions: 5
                });

                $('.selectpicker').selectpicker();

                $('#item_hour_create_button').on('click',function(){
                    $('#message').html('');
                        if(parseInt($('#item_hour_open_time_open_hour').val()) > parseInt($('#item_hour_open_time_close_hour').val())) {
                            $('#message').html('please select close hour greater than open hour');
                        }
                });
                $('#item_hour_exception_create_button').on('click',function(){
                    $('#exception_close_hour').html('');
                        if(parseInt($('#item_hour_exception_open_time_open_hour').val()) > parseInt($('#item_hour_exception_open_time_close_hour').val())) {
                            $('#exception_close_hour').html('please select exception close hour greater than exception open hour');
                        }
                });

                $('#item_social_facebook').on('input', function(){
                $('.err_media_url').html('');
                var facebookUrl = $("#item_social_facebook").val();
                console.log(facebookUrl)
                var matchUrl = "facebook";                
                if(facebookUrl.indexOf(matchUrl) == -1){
                    $('.err_media_url').html("Please enter Facebook URL Only");
                    $('#submit').attr("disabled", true);
                    if(facebookUrl.length == 0){
                        $('#submit').attr("disabled", false);
                        $('.err_media_url').html('');
                    }
                    return false;
                }else{
                    $('.err_media_url').html('');
                    $('#submit').attr("disabled", false);

                }
            });

            $('#item_social_twitter').on('input', function(){
                $('.err_twitter_url').html('');
                var twitterUrl = $("#item_social_twitter").val();
                var matchUrl = "twitter";                
                if(twitterUrl.indexOf(matchUrl) == -1){
                    $('.err_twitter_url').html("Please enter Twitter URL Only");
                    $('#submit').attr("disabled", true);
                    if(twitterUrl.length == 0){
                        $('#submit').attr("disabled", false);
                        $('.err_twitter_url').html('');
                    }
                    return false;
                }else{
                    $('.err_twitter_url').html('');
                    $('#submit').attr("disabled", false);

                }
            });
            $('#item_social_linkedin').on('input', function(){
                $('.err_linkedin_url').html('');
                var linkedinUrl = $("#item_social_linkedin").val();
                var matchUrl = "linkedin";                
                if(linkedinUrl.indexOf(matchUrl) == -1){
                    $('.err_linkedin_url').html("Please enter Linkedin URL Only");
                    $('#submit').attr("disabled", true);
                    if(linkedinUrl.length == 0){
                        $('#submit').attr("disabled", false);
                        $('.err_linkedin_url').html('');
                    }
                    return false;
                }else{
                    $('.err_linkedin_url').html('');
                    $('#submit').attr("disabled", false);

                }
            });

            // function isUrl(s) {
            // var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
            // return regexp.test(s);
            // }
            // $('#item_social_instagram').on('input', function(){  
            //     $('.err_instagram_url').html('');             
            //    var instaurl =  isUrl($("#item_social_instagram").val());  
            //    var instaurl1 =  $("#item_social_instagram").val();
            //    matchUrl_insta = 'www.';
            // //    if(instaurl){
            //    if(instaurl || instaurl1.indexOf(matchUrl_insta) > -1 || instaurl1.indexOf('https') > -1 || instaurl1.indexOf('http') > -1 || instaurl1.indexOf('.com') > -1 || instaurl1.indexOf('/') > -1){
            //         $('.err_instagram_url').html("Please enter valid instagram user name Only");
            //         $('#submit').attr("disabled", true);
            //         return false;
            //     }else{
            //         $('.err_instagram_url').html('');
            //         $('#submit').attr("disabled", false);

            //     }
              
            // });
            function isUrl(s) {
                var regexp = /^[a-z0-9_.@]*$/
                return regexp.test(s);
            }
            $('#item_social_instagram').on('input', function() {                
                $('.err_instagram_url').html('');
                var instaurl_article = isUrl($("#item_social_instagram").val());
                var instaurl1_article = $("#item_social_instagram").val();
                //    console.log(instaurl);
                //    if(instaurl){

                if (instaurl_article || instaurl1_article.indexOf('instagram') > -1 && instaurl1_article.indexOf('https') > -1 &&
                    instaurl1_article.indexOf('http') > -1 && instaurl1_article.indexOf('.com') > -1 && instaurl1_article.indexOf('/') > -1 ) {

                    $('.err_instagram_url').html('');
                    $('#submit').attr("disabled", false);
                } else{
                    $('.err_instagram_url').html("Please enter valid instagram Username or URl");
                    $('#submit').attr("disabled", true); 
                    return false;
                }
            });

        });

        $(document).ready(function() {

            "use strict";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
            /**
             * Start map modal
             */
            var map = L.map('map-modal-body', {
                //center: [37.0902, -95.7129],
                center: [{{ $setting_site_location_lat }}, {{ $setting_site_location_lng }}],
                zoom: 5,
            });

            var layerGroup = L.layerGroup().addTo(map);
            var current_lat = 0;
            var current_lng = 0;

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            map.on('click', function(e) {

                // remove all the markers in one go
                layerGroup.clearLayers();
                L.marker([e.latlng.lat, e.latlng.lng]).addTo(layerGroup);

                current_lat = e.latlng.lat;
                current_lng = e.latlng.lng;

                $('#lat_lng_span').text("Lat, Lng : " + e.latlng.lat + ", " + e.latlng.lng);
            });

            $('#lat_lng_confirm').on('click', function(){

                $('#item_lat').val(current_lat);
                $('#item_lng').val(current_lng);
                $('#map-modal').modal('hide');
            });
            $('.lat_lng_select_button').on('click', function(){
                $('#map-modal').modal('show');
                setTimeout(function(){ map.invalidateSize()}, 500);
            });
            /**
             * End map modal
             */
            @endif


            $('#user_id').on('change', function() {
                // console.log("dkslkdlsk");

                $('#input_category_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#input_category_id').selectpicker('refresh');

                if(this.value > 0)
                {
                    var ajax_url = '/admin/ajax/'+this.value+'/categories';

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
                            // console.log(result)
                            $('#input_category_id').empty();
                            $.each(result, function(key, value) {
                                var category_id = value.id;
                                var category_name = value.category_name;
                                $('#input_category_id').append('<option value="'+ category_id +'">' + category_name + '</option>');
                            });
                            $('#input_category_id').selectpicker('refresh');
                        }
                    });
                }
            });

            @if(old('user_id'))
                var ajax_url_initial_categories = '/admin/ajax/{{ old('user_id') }}/categories';
                jQuery.ajax({
                    url: ajax_url_initial_categories,
                    method: 'get',
                    success: function(result){
                        $('#input_category_id').empty();
                        // $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                        $.each(result, function(key, value) {
                            var category_id = value.id;
                            var category_name = value.category_name;
                            $('#input_category_id').append('<option value="'+ category_id +'">' + category_name + '</option>');
                        });
                        var old_categories = JSON.parse('<?php echo json_encode(old('category'), true); ?>');
                        $.each(old_categories, function(okey, ovalue) {
                            $("#input_category_id option[value='"+ovalue+"']").attr('selected','selected');
                        });
                        $('#input_category_id').selectpicker('refresh');
                    }
                });
            @endif


            /**
             * Start country, state, city selector
             */
            $('#select_country_id').on('change', function() {

                $('#select_state_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#select_city_id').html(
                    "<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                //  $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                $('#select_state_id').selectpicker('refresh');
                $('#select_city_id').selectpicker('refresh');

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
                            $('#select_state_id').empty();
                            $('#select_city_id').empty();

                            // $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
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
                            $('#select_city_id').empty();
                            // $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
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
                        $('#select_state_id').empty();
                        // $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                        $.each(JSON.parse(result), function(key, value) {
                            var state_id = value.id;
                            var state_name = value.state_name;
                            if(state_id === "{{ old('state_id') }}")
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
                        $('#select_city_id').empty();
                        // $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                        $.each(JSON.parse(result), function(key, value) {
                            var city_id = value.id;
                            var city_name = value.city_name;

                            if(city_id === "{{ old('city_id') }}")
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

            $('#select_country_id').trigger('change');
            /**
             * End country, state, city selector
             */

            /**
             * Start image gallery uplaod
             */
            $('#upload_gallery').on('click', function(){
                $('#gallery_image_error_div').hide();
                $('#gallery_img_error').text('');
                window.selectedImages = [];

                $.FileDialog({
                    accept: ".jpeg,.jpg,.png",
                }).on("files.bs.filedialog", function (event) {
                    var html = "";
                    for (var a = 0; a < event.files.length; a++) {

                        if(a == 12) {break;}
                        selectedImages.push(event.files[a]);
                        // html += "<div class='col-2 mb-2' id='item_image_gallery_" + a + "'>" +
                        //     "<img style='width: 100%; border-radius: 5px; border: 1px solid #dadada;' src='" + event.files[a].content + "'>" +
                        //     "<br/><button class='btn btn-danger btn-sm text-white mt-1' onclick='$(\"#item_image_gallery_" + a + "\").remove();'>Delete</button>" +
                        //     "<input type='hidden' value='" + event.files[a].content + "' name='image_gallery[]'>" +
                        //     "</div>";

                            var img_str = event.files[a].content;
                            var img_str_split = img_str.split(";base64")[0];
                            var img_ext = img_str_split.split("/")[1];
                            if (img_ext != 'jpeg' && img_ext != 'png' && img_ext != 'jpg') {
                                $('#gallery_img_error').text('Files other than extensions .jpg,.jpeg,.png are skipped');
                                $('#gallery_image_error_div').show();
                                // return false;

                            }else{
                                html += "<div class='col-2 mb-2' id='item_image_gallery_" + a + "'>" +
                                "<img style='width: 100%; border-radius: 5px; border: 1px solid #dadada;' src='" + event.files[a].content + "'>" +
                                "<br/><button class='btn btn-danger btn-sm text-white mt-1' onclick='$(\"#item_image_gallery_" + a + "\").remove();'>Delete</button>" +
                                "<input type='hidden' value='" + event.files[a].content + "' name='image_gallery[]'>" +
                                "</div>";
                            }
                    }
                    document.getElementById("selected-images").innerHTML += html;
                });
            });
            /**
             * End image gallery uplaod
             */

            /**
             * Start the croppie image plugin
             */
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
                $('#cropImagePop').modal('show');
                $('#image_error_div').hide();
                $('#img_error').text('');
            });

            var fileTypes = ['jpg', 'jpeg', 'png'];
            $('#upload_image_input').on('change', function() {
                if(!image_crop) {
                    image_crop = $('#upload-demo').croppie({
                        viewport: {
                            width: 260,
                            height: 260,
                            type: 'sqaure'
                        },
                        enforceBoundary: false,
                        enableExif: true
                    });
                }
                var reader = new FileReader();
                var file = this.files[0]; // Get your file here
                var fileExt = file.type.split('/')[1]; // Get the file extension

                if(fileTypes.indexOf(fileExt) !== -1) {
                    reader.onload = function (event) {
                        image_crop.croppie('bind', {
                            url: event.target.result
                        }).then(function(){
                        });
                    };
                    reader.readAsDataURL(this.files[0]);
                }else{
                    // alert('Please choose only .jpg,.jpeg,.png file');
                    $('#cropImagePop').trigger('reset');
                    $('#cropImagePop').modal('hide');
                    $('#upload_image_input').val('');
                    image_crop = null;
                    $('#upload-demo').croppie('destroy');
                    $('#img_error').text('Please choose only .jpg,.jpeg,.png file');
                    $('#image_error_div').show();
                }
            });

            $('#cropImageBtn').on("click", function(event) {
                image_crop.croppie('result', {
                    type: 'base64',
                    size: 'original'
                }).then(function(response){
                    $('#feature_image').val(response);
                    $('#image_preview').attr("src", response);
                });
                $('#cropImagePop').modal('hide')
            });
            /**
             * End the croppie image plugin
             */

            /**
             * Start listing type radio button select
             */
            $('input:radio[name="item_type"]').change(
                function(){
                    if ($(this).is(':checked') && $(this).val() == '{{ \App\Item::ITEM_TYPE_REGULAR }}') {

                        // enable all location related input
                        $( "#item_address" ).prop( "disabled", false );
                        $( "#item_address_hide" ).prop( "disabled", false );
                        $( "#select_country_id" ).prop( "disabled", false );
                        $( "#select_state_id" ).prop( "disabled", false );
                        $( "#select_city_id" ).prop( "disabled", false );
                        $( "#item_postal_code" ).prop( "disabled", false );
                        $( "#item_lat" ).prop( "disabled", false );
                        $( "#item_lng" ).prop( "disabled", false );

                        $('#select_country_id').selectpicker('refresh');
                        $('#select_state_id').selectpicker('refresh');
                        $('#select_city_id').selectpicker('refresh');
                    }
                    else
                    {
                        // disable all location related input
                        $( "#item_address" ).prop( "disabled", true );
                        $( "#item_address_hide" ).prop( "disabled", true );
                        $( "#select_country_id" ).prop( "disabled", true );
                        $( "#select_state_id" ).prop( "disabled", true );
                        $( "#select_city_id" ).prop( "disabled", true );
                        $( "#item_postal_code" ).prop( "disabled", true );
                        $( "#item_lat" ).prop( "disabled", true );
                        $( "#item_lng" ).prop( "disabled", true );

                        $('#select_country_id').selectpicker('refresh');
                        $('#select_state_id').selectpicker('refresh');
                        $('#select_city_id').selectpicker('refresh');

                    }
                });
            /**
             * End listing type radio button select
             */


            /**
             * Start delete feature image button
             */
            $('#delete_feature_image_button').on('click', function(){

                $('#delete_feature_image_button').attr("disabled", true);

                $('#image_preview').attr("src", "{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}");
                $('#feature_image').val("");

                $('#delete_feature_image_button').attr("disabled", false);
            });
            /**
             * End delete feature image button
             */

            /**
             * Start open hour add button
             */
            $('#item_hour_create_button').on('click', function(){

                var item_hour_day_of_week_text = $("#item_hour_day_of_week option:selected").text();
                var item_hour_day_of_week_value = $("#item_hour_day_of_week").val();
                var item_hour_open_time_open_hour = $("#item_hour_open_time_open_hour").val();
                var item_hour_open_time_open_minute = $("#item_hour_open_time_open_minute").val();
                var item_hour_open_time_close_hour = $("#item_hour_open_time_close_hour").val();
                var item_hour_open_time_close_minute = $("#item_hour_open_time_close_minute").val();

                var item_hours_value = item_hour_day_of_week_value + ' ' + item_hour_open_time_open_hour + ':' + item_hour_open_time_open_minute + ' ' + item_hour_open_time_close_hour + ':' + item_hour_open_time_close_minute;
                var item_hour_span_text = item_hour_day_of_week_text + ' ' + item_hour_open_time_open_hour + ':' + item_hour_open_time_open_minute + '-' + item_hour_open_time_close_hour + ':' + item_hour_open_time_close_minute;

                $( "#open_hour_added_hours" ).append("<div class='col-12 col-md-3'><input type='hidden' name='item_hours[]' value='" + item_hours_value + "'>"+item_hour_span_text+"<a class='btn btn-sm text-danger bg-white' onclick='$(this).parent().remove();'><i class='far fa-trash-alt'></i></a></div>");
            });
            /**
             * End open hour add button
             */

            /**
             * Start open hour exception add button
             */
            $('#item_hour_exception_date').datepicker({
                format: 'yyyy-mm-dd',
            });

            $('#item_hour_exception_create_button').on('click', function(){

                var item_hour_exception_date = $("#item_hour_exception_date").val();
                var item_hour_exception_open_time_open_hour = $("#item_hour_exception_open_time_open_hour").val();
                var item_hour_exception_open_time_open_minute = $("#item_hour_exception_open_time_open_minute").val();
                var item_hour_exception_open_time_close_hour = $("#item_hour_exception_open_time_close_hour").val();
                var item_hour_exception_open_time_close_minute = $("#item_hour_exception_open_time_close_minute").val();

                var item_hours_exception_value = item_hour_exception_date;
                var item_hours_exception_span_text = item_hour_exception_date;

                if(item_hour_exception_open_time_open_hour !== "" && item_hour_exception_open_time_open_minute !== "" && item_hour_exception_open_time_close_hour !== "" && item_hour_exception_open_time_close_minute !== "")
                {
                    item_hours_exception_value += ' ' + item_hour_exception_open_time_open_hour + ':' + item_hour_exception_open_time_open_minute + ' ' + item_hour_exception_open_time_close_hour + ':' + item_hour_exception_open_time_close_minute;
                    item_hours_exception_span_text += ' ' + item_hour_exception_open_time_open_hour + ':' + item_hour_exception_open_time_open_minute + '-' + item_hour_exception_open_time_close_hour + ':' + item_hour_exception_open_time_close_minute;
                }
                else
                {
                    item_hours_exception_span_text += " {{ __('item_hour.open-hour-exception-close-all-day') }}";
                }

                $( "#open_hour_added_exceptions" ).append("<div class='col-12 col-md-3'><input type='hidden' name='item_hour_exceptions[]' value='" + item_hours_exception_value + "'>" + item_hours_exception_span_text + "<a class='btn btn-sm text-danger bg-white' onclick='$(this).parent().remove();'><i class='far fa-trash-alt'></i></a></div>");

            });
            /**
             * End open hour exception add button
             */

            // $('#item_description').trumbowyg({
            //     plugins: {
            //         resizimg: {
            //             minSize: 32,
            //             step: 16,
            //         }
            //     },
            //     btnsDef: {
            //         // Create a new dropdown
            //         image: {
            //             dropdown: ['insertImage', 'base64'],
            //             ico: 'insertImage'
            //         }
            //     },
            //     // Redefine the button pane
            //     btns: [
            //         // ['viewHTML'],
            //         ['formatting'],
            //         ['fontfamily'],
            //         ['strong', 'em', 'del'],
            //         ['superscript', 'subscript'],
            //         ['foreColor', 'backColor'],
            //         ['link','image','noembed'],
            //         // ['table'],
            //         ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            //         ['lineheight'],
            //         ['indent', 'outdent'],
            //         ['unorderedList', 'orderedList'],
            //         ['horizontalRule'],
            //         ['removeformat'],
            //         ['fullscreen']
            //     ]
            // });

            ClassicEditor
                .create( document.querySelector( '#item_description' ),{
                    removePlugins: ['MediaEmbed'],
                    ckfinder:{
                        uploadUrl:'{{ route('admin.article.description.image').'?_token='.csrf_token() }}'
                    }
                } )
                .then(editor => {
                    // console.log(editor);
                })
                .catch( error => {
			    // console.error( error );
		    });

        });
    </script>

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_GOOGLE_MAP)

        <script>
            function initMap()
            {
                const myLatlng = { lat: {{ $site_global_settings->setting_site_location_lat }}, lng: {{ $site_global_settings->setting_site_location_lng }} };
                const map = new google.maps.Map(document.getElementById('map-modal-body'), {
                    zoom: 4,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                let infoWindow = new google.maps.InfoWindow({
                    content: "{{ __('google_map.select-lat-lng-on-map') }}",
                    position: myLatlng,
                });
                infoWindow.open(map);

                var current_lat = 0;
                var current_lng = 0;

                google.maps.event.addListener(map, 'click', function( event ){

                    // Close the current InfoWindow.
                    infoWindow.close();
                    // Create a new InfoWindow.
                    infoWindow = new google.maps.InfoWindow({
                        position: event.latLng,
                    });
                    infoWindow.setContent(
                        JSON.stringify(event.latLng.toJSON(), null, 2)
                    );
                    infoWindow.open(map);

                    current_lat = event.latLng.lat();
                    current_lng = event.latLng.lng();
                    console.log( "Latitude: "+current_lat+" "+", longitude: "+current_lng );
                    $('#lat_lng_span').text("Lat, Lng : " + current_lat + ", " + current_lng);
                });

                $('#lat_lng_confirm').on('click', function(){

                    $('#item_lat').val(current_lat);
                    $('#item_lng').val(current_lng);
                    $('#map-modal').modal('hide');
                });
                $('.lat_lng_select_button').on('click', function(){
                    $('#map-modal').modal('show');
                    //setTimeout(function(){ map.invalidateSize()}, 500);
                });
            }
        </script>

        <script async defer src="https://maps.googleapis.com/maps/api/js??v=quarterly&key={{ $site_global_settings->setting_site_map_google_api_key }}&callback=initMap"></script>
    @endif

@endsection