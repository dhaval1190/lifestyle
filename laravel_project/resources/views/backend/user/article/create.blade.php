@extends('backend.user.layouts.app')

@section('styles')

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <link href="{{ asset('backend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <!-- Image Crop Css -->
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />

    <!-- Bootstrap FD Css-->
    <link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />

    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />

    <link href="{{ asset('backend/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/dist/ui/trumbowyg.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/dist/plugins/colors/ui/trumbowyg.colors.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/trumbowyg/dist/plugins/table/ui/trumbowyg.table.min.css') }}">

@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.article.add-article') }}</h1>
            <p class="mb-4">{{ __('backend.article.add-article-desc-user') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('user.articles.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

           {{-- @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif --}}

            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('user.articles.store') }}" id="article-create-form">
                        @csrf
                        <!-- <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row">
                                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                                        <div class="form-check">
                                            <input checked class="form-check-input" type="radio" name="article_type" id="article_type_regular" value="{{ \App\Item::ITEM_TYPE_REGULAR }}" aria-describedby="article_type_regularHelpBlock">
                                            <label class="form-check-label" for="article_type_regular">
                                                {{ __('theme_directory_hub.online-listing.regular-listing') }}
                                            </label>
                                            <small id="article_type_regularHelpBlock" class="form-text text-muted">
                                                {{ __('theme_directory_hub.online-listing.regular-listing-help') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="article_type" id="article_type_online" value="{{ \App\Item::ITEM_TYPE_ONLINE }}" aria-describedby="article_type_onlineHelpBlock">
                                            <label class="form-check-label" for="article_type_online">
                                                {{ __('theme_directory_hub.online-listing.online-listing') }}
                                            </label>
                                            <small id="article_type_onlineHelpBlock" class="form-text text-muted">
                                                {{ __('theme_directory_hub.online-listing.online-listing-help') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <input type="hidden" name="article_type" value="1">

                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                            <i class="fas fa-store"></i>
                                            {{ __('backend.article.general-info') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('article_hour.article-general-info-help') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <label for="input_category_id" class="text-black">{{ __('backend.article.select-category') }}</label>
                                        <select multiple size="{{ count($all_categories) }}" class="selectpicker form-control input_category_id @error('category') is-invalid @enderror" name="category[]" data-live-search="true" data-actions-box="true" data-size="10" id="input_category_id">
                                            @foreach($all_categories as $key => $category)
                                            <option value="{{ $category['category_id'] }}" {{ in_array($category['category_id'], old('category', [])) ? 'selected' : '' }}>{{ $category['category_name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-6">
                                        <label for="article_title" class="text-black">{{ __('backend.article.title') }}</label>
                                        <input id="article_title" type="text" class="form-control @error('article_title') is-invalid @enderror" name="article_title" value="{{ old('article_title') }}">
                                        @error('article_title')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="article_address" class="text-black">{{ __('backend.article.address') }}</label>
                                        <input id="article_address" type="text" class="form-control @error('article_address') is-invalid @enderror" name="article_address" value="{{ old('article_address', $login_user->address) }}">
                                        @error('article_address')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">

                                    <div class="col-md-2">
                                        <label for="select_country_id" class="text-black">{{ __('backend.setting.country') }}</label>
                                        <select id="select_country_id" class="selectpicker form-control @error('country_id') is-invalid @enderror" name="country_id" data-live-search="true">
                                            {{-- <option selected value="0">{{ __('prefer_country.select-country') }}</option> --}}
                                            @foreach($all_countries as $all_countries_key => $country)
                                            @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE)
                                            <option value="{{ $country->id }}" {{ $country->id == \App\Country::COUNTRY_DEFAULT_SELECTED ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="select_state_id" class="text-black">{{ __('backend.state.state') }}</label>
                                        <select id="select_state_id" class="selectpicker form-control @error('state_id') is-invalid @enderror" name="state_id" data-live-search="true" title="{{ __('backend.item.select-state') }}">
                                            {{-- <option selected value="0">{{ __('backend.article.select-state') }}</option> --}}
                                        </select>
                                        @error('state_id')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="select_city_id" class="text-black">{{ __('backend.city.city') }}</label>
                                        <select id="select_city_id" class="selectpicker form-control @error('city_id') is-invalid @enderror" name="city_id" data-live-search="true" title="{{ __('backend.item.select-city') }}">
                                            {{-- <option selected value="0">{{ __('backend.article.select-city') }}</option> --}}
                                        </select>
                                        @error('city_id')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-2">
                                        <label for="article_postal_code" class="text-black">{{ __('backend.article.postal-code') }}</label>
                                        <input id="article_postal_code" type="text" class="form-control @error('article_postal_code') is-invalid @enderror" name="article_postal_code" value="{{ old('article_postal_code', $login_user->post_code) }}">
                                        @error('article_postal_code')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="article_lat" class="text-black">{{ __('backend.article.lat') }} / {{ __('backend.article.lng') }}</label>
                                        <div class="input-group">
                                            <input id="article_lat" type="text" class="form-control @error('article_lat') is-invalid @enderror" name="article_lat" value="{{ old('article_lat') }}" aria-describedby="latHelpBlock">
                                            <input id="article_lng" type="text" class="form-control @error('article_lng') is-invalid @enderror" name="article_lng" value="{{ old('article_lng') }}" aria-describedby="lngHelpBlock">
                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-primary lat_lng_select_button" type="button">{{ __('backend.article.select-map') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input {{ old('article_address_hide') == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" id="article_address_hide" name="article_address_hide" value="1">
                                            <label class="form-check-label" for="article_address_hide">
                                                {{ __('backend.article.hide-address') }}
                                                <small class="text-muted">
                                                    {{ __('backend.article.hide-address-help') }}
                                                </small>
                                            </label>
                                        </div>
                                        @error('article_address_hide')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="form-row mb-3">
                                    <div class="col-md-3">
                                        <label for="article_lat" class="text-black">{{ __('backend.article.lat') }}</label>
                                        <input id="article_lat" type="text" class="form-control @error('article_lat') is-invalid @enderror" name="article_lat" value="{{ old('article_lat') }}" aria-describedby="latHelpBlock">
                                        <small id="latHelpBlock" class="form-text text-muted">
                                            <a class="lat_lng_select_button btn btn-sm btn-primary text-white">{{ __('backend.article.select-map') }}</a>
                                        </small>
                                        @error('article_lat')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="article_lng" class="text-black">{{ __('backend.article.lng') }}</label>
                                        <input id="article_lng" type="text" class="form-control @error('article_lng') is-invalid @enderror" name="article_lng" value="{{ old('article_lng') }}" aria-describedby="lngHelpBlock">
                                        <small id="lngHelpBlock" class="form-text text-muted">
                                            <a class="lat_lng_select_button btn btn-sm btn-primary text-white">{{ __('backend.article.select-map') }}</a>
                                        </small>
                                        @error('article_lng')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-7">
                                        <label for="article_youtube_id" class="text-black">{{ __('customization.article.youtube-id') }}</label>
                                        <input id="article_youtube_id" type="text" class="form-control @error('article_youtube_id') is-invalid @enderror" name="article_youtube_id" value="{{ old('article_youtube_id', $login_user->youtube) }}">
                                        @error('article_youtube_id')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                 --}}

                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <label for="article_description" class="text-black">{{ __('backend.article.description') }}</label>
                                        <textarea id="article_description" type="text" class="form-control @error('article_description') is-invalid @enderror" name="article_description">{{ old('article_description') }}</textarea>
                                        @error('article_description')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <label class="text-black" for="item_keywords">{{ __('backend.article.keyword') }}</label>
                                    <input id="item_keywords" type="text" class="form-control @error('item_keywords') is-invalid @enderror" name="item_keywords" value="{{ old('item_keywords') ? old('item_keywords') : $login_user->item_keywords }}">
                                    <small class="form-text text-muted">
                                        Separate by comma
                                    </small>
                                    @error('item_keywords')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-row mb-3">
                                    <input type="hidden" name="article_featured" value="{{ \App\Item::ITEM_NOT_FEATURED }}">
                                    {{-- <div class="col-md-3">
                                        @if($show_article_featured_selector)
                                            <label for="article_featured" class="text-black">{{ __('backend.article.featured') }}</label>
                                            <select class="selectpicker form-control @error('article_featured') is-invalid @enderror" name="article_featured">
                                                <option value="{{ \App\Item::ITEM_NOT_FEATURED }}" selected>{{ __('backend.shared.no') }}</option>
                                                <option value="{{ \App\Item::ITEM_FEATURED }}">{{ __('backend.shared.yes') }}</option>
                                            </select>
                                            @error('article_featured')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        @else
                                            <input type="hidden" name="article_featured" value="{{ \App\Item::ITEM_NOT_FEATURED }}">
                                        @endif
                                    </div> --}}
                                    <div class="col-md-3">
                                        <label for="article_phone" class="text-black">{{ __('backend.article.phone') }}</label>
                                        <input id="article_phone" type="text" class="form-control @error('article_phone') is-invalid @enderror" name="article_phone" value="{{ old('article_phone', $login_user->phone) }}">
                                        @error('article_phone')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="article_social_whatsapp" class="text-black">
                                            <i class="fa-brands fa-whatsapp-square"></i>
                                            {{ __('article_whatsapp_instagram.article-social-whatsapp') }}
                                        </label>
                                        <input id="article_social_whatsapp" type="text" class="form-control @error('article_social_whatsapp') is-invalid @enderror" name="article_social_whatsapp" value="{{ old('article_social_whatsapp') }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('article_whatsapp_instagram.article-social-whatsapp-help') }}
                                        </small>
                                        @error('article_social_whatsapp')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="article_website" class="text-black">
                                            <i class="fa-solid fa-globe"></i>
                                            {{ __('backend.article.website') }}
                                        </label>
                                        <input id="article_website" type="text" class="form-control @error('article_website') is-invalid @enderror" name="article_website" value="{{ old('article_website', $login_user->website) }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('article_website')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="article_social_facebook" class="text-black">
                                            <i class="fa-brands fa-facebook-square"></i>
                                            {{ __('backend.article.facebook') }}
                                        </label>
                                        <input id="article_social_facebook" type="text" class="form-control @error('article_social_facebook') is-invalid @enderror" name="article_social_facebook" value="{{ old('article_social_facebook', $login_user->facebook) }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('article_social_facebook')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">

                                    <div class="col-md-3">
                                        <label for="article_social_twitter" class="text-black">
                                            <i class="fa-brands fa-twitter-square"></i>
                                            {{ __('backend.article.twitter') }}
                                        </label>
                                        <input id="article_social_twitter" type="text" class="form-control @error('article_social_twitter') is-invalid @enderror" name="article_social_twitter" value="{{ old('article_social_twitter') }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('article_social_twitter')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="article_social_linkedin" class="text-black">
                                            <i class="fa-brands fa-linkedin"></i>
                                            {{ __('backend.article.linkedin') }}
                                        </label>
                                        <input id="article_social_linkedin" type="text" class="form-control @error('article_social_linkedin') is-invalid @enderror" name="article_social_linkedin" value="{{ old('article_social_linkedin', $login_user->linkedin) }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('article_social_linkedin')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-3">
                                        <label for="article_social_instagram" class="text-black">
                                            <i class="fa-brands fa-instagram-square"></i>
                                            {{ __('article_whatsapp_instagram.article-social-instagram') }}
                                        </label>
                                        <input id="article_social_instagram" type="text" class="form-control @error('article_social_instagram') is-invalid @enderror" name="article_social_instagram" value="{{ old('article_social_instagram', $login_user->instagram) }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('article_whatsapp_instagram.article-social-instagram-help') }}
                                        </small>
                                        @error('article_social_instagram')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <!-- <div class="col-md-3">
                                        <label for="article_video_urls" class="text-black"><i class="fa-brands fa-youtube-square"></i> Video Url</label>
                                        <input id="article_video_urls" type="url" class="form-control @error('article_video_urls') is-invalid @enderror" name="article_video_urls[]">
                                        <small id="videoHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('article_video_urls')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div> -->

                                </div>
                            </div>
                        </div>

                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                            <i class="fas fa-clock"></i>
                                            {{ __('article_hour.open-hour') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('article_hour.open-hour-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="article_hour_time_zone" class="text-black">{{ __('article_hour.timezone') }}</label>
                                        <select id="article_hour_time_zone" class="selectpicker form-control @error('article_hour_time_zone') is-invalid @enderror" name="article_hour_time_zone" data-live-search="true">
                                            @foreach($time_zone_identifiers as $time_zone_identifiers_key => $time_zone_identifier)
                                            <option value="{{ $time_zone_identifier }}" {{ old('article_hour_time_zone') == $time_zone_identifier ? 'selected' : '' }}>{{ $time_zone_identifier }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('article_hour.timezone-help') }}
                                        </small>
                                        @error('article_hour_time_zone')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="article_hour_show_hours" class="text-black">{{ __('article_hour.show-open-hour') }}</label>
                                        <select id="article_hour_show_hours" class="selectpicker form-control @error('article_hour_show_hours') is-invalid @enderror" name="article_hour_show_hours" data-live-search="true">
                                            <option value="{{ \App\Item::ITEM_HOUR_SHOW }}" {{ old('article_hour_show_hours') == \App\Item::ITEM_HOUR_SHOW ? 'selected' : '' }}>{{ __('article_hour.show-hour') }}</option>
                                            <option value="{{ \App\Item::ITEM_HOUR_NOT_SHOW }}" {{ old('article_hour_show_hours') == \App\Item::ITEM_HOUR_NOT_SHOW ? 'selected' : '' }}>{{ __('article_hour.not-show-hour') }}</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('article_hour.show-open-hour-help') }}
                                        </small>
                                        @error('article_hour_show_hours')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-12">
                                        <span class="text-gray-800">{{ __('article_hour.open-hour-hours') }}</span>
                                        <small class="form-text text-muted">
                                            {{ __('article_hour.open-hour-hours-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3 align-articles-end">
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_day_of_week" class="text-black">{{ __('article_hour.day-of-week') }}</label>
                                        <select id="article_hour_day_of_week" class="selectpicker form-control" name="article_hour_day_of_week" data-live-search="true">
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_MONDAY }}" {{ !empty($items_hours->item_hour_day_of_week) && $items_hours->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_MONDAY ? 'selected' :'' }}>{{ __('article_hour.monday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_TUESDAY }}" {{ !empty($items_hours->item_hour_day_of_week) && $items_hours->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_TUESDAY ? 'selected' :'' }}>{{ __('article_hour.tuesday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_WEDNESDAY }}" {{ !empty($items_hours->item_hour_day_of_week) && $items_hours->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_WEDNESDAY ? 'selected' :'' }}>{{ __('article_hour.wednesday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_THURSDAY }}" {{ !empty($items_hours->item_hour_day_of_week) && $items_hours->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_THURSDAY ? 'selected' :'' }}>{{ __('article_hour.thursday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_FRIDAY }}" {{ !empty($items_hours->item_hour_day_of_week) && $items_hours->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_FRIDAY ? 'selected' :'' }}>{{ __('article_hour.friday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_SATURDAY }}" {{ !empty($items_hours->item_hour_day_of_week) && $items_hours->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_SATURDAY ? 'selected' :'' }}>{{ __('article_hour.saturday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_SUNDAY }}" {{ !empty($items_hours->item_hour_day_of_week) && $items_hours->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_SUNDAY ? 'selected' :'' }}>{{ __('article_hour.sunday') }}</option>
                                        </select>
                                    </div>
                                    @php
                                    $item_hour_open_time = isset($items_hours->item_hour_open_time) && !empty($items_hours->item_hour_open_time) ? explode(':',$items_hours->item_hour_open_time) : [0=>0,1=>0];
                                    $item_hour_close_time = isset($items_hours->item_hour_close_time) && !empty($items_hours->item_hour_close_time) ? explode(':',$items_hours->item_hour_close_time) : [0=>0,1=>0];
                                    $item_hour_exception_open_time = isset($items_hours_exceptions->item_hour_exception_open_time) && !empty($items_hours_exceptions->item_hour_exception_open_time) ? explode(':',$items_hours_exceptions->item_hour_exception_open_time) : [0=>0,1=>0];
                                    $item_hour_exception_close_time = isset($items_hours_exceptions->item_hour_exception_open_time) && !empty($items_hours_exceptions->item_hour_exception_open_time) ? explode(':',$items_hours_exceptions->item_hour_exception_close_time) : [0=>0,1=>0];
                                    @endphp
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_open_time_open_hour" class="text-black">{{ __('article_hour.article-hour-open-hour') }}</label>
                                        <select id="article_hour_open_time_open_hour" class="selectpicker form-control" name="article_hour_open_time_open_hour" data-live-search="true">
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                            <option value="{{ $full_hour }}" {{ $full_hour == $item_hour_open_time[0] ? 'selected' :'' }}>{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_open_time_open_minute" class="text-black">{{ __('article_hour.article-hour-open-minute') }}</label>
                                        <select id="article_hour_open_time_open_minute" class="selectpicker form-control" name="article_hour_open_time_open_minute" data-live-search="true">
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                            <option value="{{ $full_minute }}" {{ $full_minute == $item_hour_open_time[1] ? 'selected' :'' }}>{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_open_time_close_hour" class="text-black">{{ __('article_hour.article-hour-close-hour') }}</label>
                                        <select id="article_hour_open_time_close_hour" class="selectpicker form-control" name="article_hour_open_time_close_hour" data-live-search="true">
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                            <option value="{{ $full_hour }}" {{ $full_hour == $item_hour_close_time[0] ? 'selected' :'' }}>{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_open_time_close_minute" class="text-black">{{ __('article_hour.article-hour-close-minute') }}</label>
                                        <select id="article_hour_open_time_close_minute" class="selectpicker form-control" name="article_hour_open_time_close_minute" data-live-search="true">
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                            <option value="{{ $full_minute }} {{ $full_minute == $item_hour_close_time[0] ? 'selected' :'' }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <a class="btn btn-sm btn-block btn-primary rounded text-white" id="article_hour_create_button">
                                            <i class="fas fa-plus"></i>
                                            {{ __('article_hour.add-open-hour') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="form-row mb-3" id="open_hour_added_hours">
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-12">
                                        <span class="text-gray-800">{{ __('article_hour.open-hour-exceptions') }}</span>
                                        <small class="form-text text-muted">
                                            {{ __('article_hour.open-hour-exceptions-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3 align-articles-end">
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_exception_date" class="text-black">{{ __('article_hour.open-hour-exception-date') }}</label>
                                        <input id="article_hour_exception_date" type="text" class="form-control" name="article_hour_exception_date" value="{{!empty($items_hours_exceptions->item_hour_exception_date) ? $items_hours_exceptions->item_hour_exception_date : ''}}" placeholder="{{ __('article_hour.open-hour-exception-date-placeholder') }}">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_exception_open_time_open_hour" class="text-black">{{ __('article_hour.article-hour-open-hour') }}</label>
                                        <select id="article_hour_exception_open_time_open_hour" class="selectpicker form-control" name="article_hour_exception_open_time_open_hour" data-live-search="true">
                                            <option value="">{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                            <option value="{{ $full_hour }}" {{ $full_hour == $item_hour_exception_open_time[0] ? 'selected' :'' }}>{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_exception_open_time_open_minute" class="text-black">{{ __('article_hour.article-hour-open-minute') }}</label>
                                        <select id="article_hour_exception_open_time_open_minute" class="selectpicker form-control" name="article_hour_exception_open_time_open_minute" data-live-search="true">
                                            <option value="">{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                            <option value="{{ $full_minute }}" {{ $full_minute == $item_hour_exception_open_time[1] ? 'selected' :'' }}>{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_exception_open_time_close_hour" class="text-black">{{ __('article_hour.article-hour-close-hour') }}</label>
                                        <select id="article_hour_exception_open_time_close_hour" class="selectpicker form-control" name="article_hour_exception_open_time_close_hour" data-live-search="true">
                                            <option value="">{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                            <option value="{{ $full_hour }}" {{ $full_hour == $item_hour_exception_close_time[0] ? 'selected' :'' }}>{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_exception_open_time_close_minute" class="text-black">{{ __('article_hour.article-hour-close-minute') }}</label>
                                        <select id="article_hour_exception_open_time_close_minute" class="selectpicker form-control" name="article_hour_exception_open_time_close_minute" data-live-search="true">
                                            <option value="">{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                            <option value="{{ $full_minute }}" {{ $full_minute == $item_hour_exception_close_time[1] ? 'selected' :'' }}>{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <a class="btn btn-sm btn-block btn-primary rounded text-white" id="article_hour_exception_create_button">
                                            <i class="fas fa-plus"></i>
                                            {{ __('article_hour.add-open-hour-exception') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="form-row mb-3" id="open_hour_added_exceptions">
                                </div>
                            </div>
                        </div>

                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row mb-4 bg-primary pl-1 pt-1 pb-1">
                                    <div class="col-md-12">
                                        <span class="text-lg text-white">
                                            <i class="fas fa-images"></i>
                                            {{ __('article_hour.article-photos') }}
                                        </span>
                                        <small class="form-text text-white">
                                            {{ __('article_hour.article-photos-help') }}
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-md-3">
                                        <span class="text-lg text-gray-800">{{ __('backend.article.feature-image') }}</span>
                                        <small class="form-text text-muted">{{ __('backend.article.feature-image-ratio') }}</small>
                                        <small class="form-text text-muted">{{ __('backend.article.feature-image-size') }}</small>
                                        @error('feature_image')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.article.select-image') }}</button>
                                                {{-- <img id="image_preview" src="{{ asset('backend/images/placeholder/full_article_feature_image.webp') }}" class="img-responsive"> --}}
                                                <img id="image_preview" src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" class="img-responsive">
                                                <input id="feature_image" type="hidden" name="feature_image">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <a class="btn btn-danger btn-block text-white" id="delete_feature_image_button">
                                                    <i class="fas fa-trash-alt"></i>
                                                    {{ __('role_permission.article.delete-feature-image') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <span class="text-lg text-gray-800">{{ __('backend.article.gallery-images') }}</span>
                                        <small class="form-text text-muted">{{ __('backend.article.gallery-images-max-upload') }}</small>
                                        <small class="form-text text-muted">{{ __('backend.article.gallery-images-size') }}</small>
                                        {{-- <small class="form-text text-muted">
                                            {{ __('theme_directory_hub.listing.max-upload', ['gallery_photos_count' => $setting_article_max_gallery_photos]) }}
                                        </small> --}}
                                        @error('image_gallery')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button id="upload_gallery" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.article.select-images') }}</button>
                                                <div class="row" id="selected-images"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr/>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.create') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - feature image -->
    <div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.article.crop-feature-image') }}</h5>
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
                                <label class="custom-file-label" for="upload_image_input">{{ __('backend.article.choose-image') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="crop_image" type="button" class="btn btn-primary">{{ __('backend.article.crop-image') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal - map -->
    <div class="modal fade" id="map-modal" tabindex="-1" role="dialog" aria-labelledby="map-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.article.select-map-title') }}</h5>
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
    @include('backend.user.partials.bootstrap-select-locale')

    <script src="{{ asset('backend/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/resizimg/resizable-resolveconflict.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/jquery-resizable/dist/jquery-resizable.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/trumbowyg.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/base64/trumbowyg.base64.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/resizimg/trumbowyg.resizimg.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/indent/trumbowyg.indent.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/lineheight/trumbowyg.lineheight.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/noembed/trumbowyg.noembed.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/table/trumbowyg.table.min.js') }}"></script>

    <script>

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

                $('#article_lat').val(current_lat);
                $('#article_lng').val(current_lng);
                $('#map-modal').modal('hide')
            });
            $('.lat_lng_select_button').on('click', function(){
                $('#map-modal').modal('show');
                setTimeout(function(){ map.invalidateSize()}, 500);
            });
            /**
             * End map modal
             */
            @endif

            /**
             * Start country, state, city selector
             */
            $('#select_country_id').on('change', function() {
                $('#select_state_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#select_state_id').selectpicker('refresh');
                if(this.value > 0) {
                    var ajax_url = '/ajax/states/' + this.value;
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        success: function(result){
                            $('#select_state_id').empty();
                            // $('#select_state_id').html("<option selected value='0'>{{ __('backend.article.select-state') }}</option>");
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
                $('#select_city_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#select_city_id').selectpicker('refresh');
                if(this.value > 0) {
                    var ajax_url = '/ajax/cities/' + this.value;
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        success: function(result){
                            $('#select_city_id').empty();
                            // $('#select_city_id').html("<option selected value='0'>{{ __('backend.article.select-city') }}</option>");
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                            // $('#select_state_id').val("{{ old('state_id', $login_user->state_id) }} ").selectpicker('refresh');
                            // $('#select_state_id').val("{{ old('state_id', $login_user->state_id) }} ").selectpicker('refresh');
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
                    success: function(result){
                        $('#select_state_id').empty();
                        // $('#select_state_id').html("<option selected value='0'>{{ __('backend.article.select-state') }}</option>");
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
                    success: function(result){
                        $('#select_city_id').empty();
                        // $('#select_city_id').html("<option selected value='0'>{{ __('backend.article.select-city') }}</option>");
                        $.each(JSON.parse(result), function(key, value) {
                            var city_id = value.id;
                            var city_name = value.city_name;
                            $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                        });
                        @if(old('city_id'))
                            $('#select_city_id').val("{{ old('city_id', '') }}").selectpicker('refresh');
                            $('#select_city_id').val("{{ old('city_id', '') }}").selectpicker('refresh');
                        @endif
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
                window.selectedImages = [];
                $.FileDialog({
                    accept: "image/jpeg",
                }).on("files.bs.filedialog", function (event) {
                    var html = "";
                    for (var a = 0; a < event.files.length; a++) {
                        if(a == 12) {break;}
                        selectedImages.push(event.files[a]);
                        html += "<div class='col-2 mb-2' id='article_image_gallery_" + a + "'>" +
                            "<img style='width: 100%; border-radius: 5px; border: 1px solid #dadada;' src='" + event.files[a].content + "'>" +
                            "<br/><button class='btn btn-danger btn-sm text-white mt-1' onclick='$(\"#article_image_gallery_" + a + "\").remove();'>Delete</button>" +
                            "<input type='hidden' value='" + event.files[a].content + "' name='image_gallery[]'>" +
                            "</div>";
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
                $('#image-crop-modal').modal('hide');
            });
            /**
             * End the croppie image plugin
             */

            /**
             * Start listing type radio button select
             */
            $('input:radio[name="article_type"]').change(
                function(){
                    if ($(this).is(':checked') && $(this).val() == '{{ \App\Item::ITEM_TYPE_REGULAR }}') {

                        // enable all location related input
                        $( "#article_address" ).prop( "disabled", false );
                        $( "#article_address_hide" ).prop( "disabled", false );
                        $( "#select_country_id" ).prop( "disabled", false );
                        $( "#select_state_id" ).prop( "disabled", false );
                        $( "#select_city_id" ).prop( "disabled", false );
                        $( "#article_postal_code" ).prop( "disabled", false );
                        $( "#article_lat" ).prop( "disabled", false );
                        $( "#article_lng" ).prop( "disabled", false );

                        $('#select_country_id').selectpicker('refresh');
                        $('#select_state_id').selectpicker('refresh');
                        $('#select_city_id').selectpicker('refresh');
                    }
                    else
                    {
                        // disable all location related input
                        $( "#article_address" ).prop( "disabled", true );
                        $( "#article_address_hide" ).prop( "disabled", true );
                        $( "#select_country_id" ).prop( "disabled", true );
                        $( "#select_state_id" ).prop( "disabled", true );
                        $( "#select_city_id" ).prop( "disabled", true );
                        $( "#article_postal_code" ).prop( "disabled", true );
                        $( "#article_lat" ).prop( "disabled", true );
                        $( "#article_lng" ).prop( "disabled", true );

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

                // $('#image_preview').attr("src", "{{ asset('backend/images/placeholder/full_article_feature_image.webp') }}");
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
            $('#article_hour_create_button').on('click', function(){

                var article_hour_day_of_week_text = $("#article_hour_day_of_week option:selected").text();
                var article_hour_day_of_week_value = $("#article_hour_day_of_week").val();
                var article_hour_open_time_open_hour = $("#article_hour_open_time_open_hour").val();
                var article_hour_open_time_open_minute = $("#article_hour_open_time_open_minute").val();
                var article_hour_open_time_close_hour = $("#article_hour_open_time_close_hour").val();
                var article_hour_open_time_close_minute = $("#article_hour_open_time_close_minute").val();

                var article_hours_value = article_hour_day_of_week_value + ' ' + article_hour_open_time_open_hour + ':' + article_hour_open_time_open_minute + ' ' + article_hour_open_time_close_hour + ':' + article_hour_open_time_close_minute;
                var article_hour_span_text = article_hour_day_of_week_text + ' ' + article_hour_open_time_open_hour + ':' + article_hour_open_time_open_minute + '-' + article_hour_open_time_close_hour + ':' + article_hour_open_time_close_minute;

                $( "#open_hour_added_hours" ).append("<div class='col-12 col-md-3'><input type='hidden' name='article_hours[]' value='" + article_hours_value + "'>"+article_hour_span_text+"<a class='btn btn-sm text-danger bg-white' onclick='$(this).parent().remove();'><i class='far fa-trash-alt'></i></a></div>");
            });
            /**
             * End open hour add button
             */

            /**
             * Start open hour exception add button
             */
            $('#article_hour_exception_date').datepicker({
                format: 'yyyy-mm-dd',
            });

            $('#article_hour_exception_create_button').on('click', function(){

                var article_hour_exception_date = $("#article_hour_exception_date").val();
                var article_hour_exception_open_time_open_hour = $("#article_hour_exception_open_time_open_hour").val();
                var article_hour_exception_open_time_open_minute = $("#article_hour_exception_open_time_open_minute").val();
                var article_hour_exception_open_time_close_hour = $("#article_hour_exception_open_time_close_hour").val();
                var article_hour_exception_open_time_close_minute = $("#article_hour_exception_open_time_close_minute").val();

                var article_hours_exception_value = article_hour_exception_date;
                var article_hours_exception_span_text = article_hour_exception_date;

                if(article_hour_exception_open_time_open_hour !== "" && article_hour_exception_open_time_open_minute !== "" && article_hour_exception_open_time_close_hour !== "" && article_hour_exception_open_time_close_minute !== "")
                {
                    article_hours_exception_value += ' ' + article_hour_exception_open_time_open_hour + ':' + article_hour_exception_open_time_open_minute + ' ' + article_hour_exception_open_time_close_hour + ':' + article_hour_exception_open_time_close_minute;
                    article_hours_exception_span_text += ' ' + article_hour_exception_open_time_open_hour + ':' + article_hour_exception_open_time_open_minute + '-' + article_hour_exception_open_time_close_hour + ':' + article_hour_exception_open_time_close_minute;
                }
                else
                {
                    article_hours_exception_span_text += " {{ __('article_hour.open-hour-exception-close-all-day') }}";
                }

                $( "#open_hour_added_exceptions" ).append("<div class='col-12 col-md-3'><input type='hidden' name='article_hour_exceptions[]' value='" + article_hours_exception_value + "'>" + article_hours_exception_span_text + "<a class='btn btn-sm text-danger bg-white' onclick='$(this).parent().remove();'><i class='far fa-trash-alt'></i></a></div>");

            });
            /**
             * End open hour exception add button
             */

            $('#article_description').trumbowyg({
                plugins: {
                    resizimg: {
                        minSize: 32,
                        step: 16,
                    }
                },
                btnsDef: {
                    // Create a new dropdown
                    image: {
                        dropdown: ['insertImage', 'base64'],
                        ico: 'insertImage'
                    }
                },
                // Redefine the button pane
                btns: [
                    // ['viewHTML'],
                    ['formatting'],
                    ['fontfamily'],
                    ['strong', 'em', 'del'],
                    ['superscript', 'subscript'],
                    ['foreColor', 'backColor'],
                    ['link','image','noembed'],
                    // ['table'],
                    ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                    ['lineheight'],
                    ['indent', 'outdent'],
                    ['unorderedList', 'orderedList'],
                    ['horizontalRule'],
                    ['removeformat'],
                    ['fullscreen']
                ]
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

                    $('#article_lat').val(current_lat);
                    $('#article_lng').val(current_lng);
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
