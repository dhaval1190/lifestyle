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
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.article.edit-article') }}</h1>
            <p class="mb-4">{{ __('backend.article.edit-article-desc-user') }}</p>
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

            <div class="row border-left-primary mb-4">
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-12">
                            <span class="text-lg text-gray-800">{{ __('backend.article.status') }}: </span>
                            @if($article->item_status == \App\Item::ITEM_SUBMITTED)
                                <span class="text-warning">{{ __('backend.article.submitted') }}</span>
                            @elseif($article->item_status == \App\Item::ITEM_PUBLISHED)
                                <span class="text-success">{{ __('backend.article.published') }}</span>
                            @elseif($article->item_status == \App\Item::ITEM_SUSPENDED)
                                <span class="text-danger">{{ __('backend.article.suspended') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12">
                            <span class="text-lg text-gray-800">{{ __('backend.article.article-link') }}:</span>

                            @if($article->item_status == \App\Item::ITEM_PUBLISHED)
                                <a href="{{ route('page.item', $article->item_slug) }}" target="_blank">{{ route('page.item', $article->item_slug) }}</a>
                            @else
                                <span>{{ route('page.item', $article->item_slug) }}</span>
                            @endif
                            <a class="text-info pl-2" href="#" data-toggle="modal" data-target="#articleSlugModal">
                                <i class="far fa-edit"></i>
                                {{ __('article_slug.update-url') }}
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            {{-- <a class="btn btn-sm btn-primary" href="{{ route('user.articles.sections.index', ['article' => $article]) }}">
                                <i class="fas fa-bars"></i>
                                {{ __('article_section.manage-sections') }}
                            </a> --}}

                            <a class="btn btn-sm btn-outline-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                <i class="far fa-trash-alt"></i>
                                {{ __('backend.article.delete-article') }}
                            </a>
                        </div>
                    </div>

                    <hr/>

                    <div class="row">
                        <div class="col-12">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <span class="text-lg text-gray-800">{{ __('backend.category.category') }}: </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    @foreach($categories as $key => $category)
                                        <div class="pr-1 pb-2 float-left">
                                    <span class="bg-info rounded text-white pl-2 pr-2 pt-1 pb-1">
                                        {{ $category->category_name }}
                                    </span>
                                        </div>
                                    @endforeach

                                    <a class="text-info pl-2 float-left" href="#" data-toggle="modal" data-target="#categoriesModal">
                                        <i class="far fa-edit"></i>
                                        {{ __('categories.update-cat') }}
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('user.articles.update', $article) }}" id="article-create-form">
                        @csrf
                        @method('PUT')

                        <div class="row border-left-primary mb-4">
                            <div class="col-12">
                                <div class="form-row">
                                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                                        <div class="form-check">
                                            <input {{ (old('article_type') ? old('article_type') : $article->item_type) == \App\Item::ITEM_TYPE_REGULAR ? 'checked' : '' }} class="form-check-input" type="radio" name="article_type" id="article_type_regular" value="{{ \App\Item::ITEM_TYPE_REGULAR }}" aria-describedby="article_type_regularHelpBlock">
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
                                            <input {{ (old('article_type') ? old('article_type') : $article->item_type) == \App\Item::ITEM_TYPE_ONLINE ? 'checked' : '' }} class="form-check-input" type="radio" name="article_type" id="article_type_online" value="{{ \App\Item::ITEM_TYPE_ONLINE }}" aria-describedby="article_type_onlineHelpBlock">
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
                        </div>


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
                                    <div class="col-md-6">
                                        <label for="article_title" class="text-black">{{ __('backend.article.title') }}</label>
                                        <input id="article_title" type="text" class="form-control @error('article_title') is-invalid @enderror" name="article_title" value="{{ old('article_title') ? old('article_title') : $article->item_title }}">
                                        @error('article_title')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="article_address" class="text-black">{{ __('backend.article.address') }}</label>
                                        <input id="article_address" type="text" class="form-control @error('article_address') is-invalid @enderror" name="article_address" value="{{ old('article_address') ? old('article_address') : $article->item_address }}">
                                        @error('article_address')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-2">
                                        <label for="select_country_id" class="text-black">{{ __('backend.setting.country') }}</label>
                                        <select id="select_country_id" class="selectpicker form-control @error('country_id') is-invalid @enderror" name="country_id" data-live-search="true">
                                            @foreach($all_countries as $all_countries_key => $country)
                                            @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE || ($country->country_status == \App\Country::COUNTRY_STATUS_DISABLE && $article->country_id == $country->id))
                                            <option value="{{ $country->id }}" {{ (old('country_id') ? old('country_id') : $article->country_id) == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="select_state_id" class="text-black">{{ __('backend.state.state') }}</label>
                                        <select id="select_state_id" class="selectpicker form-control @error('state_id') is-invalid @enderror" name="state_id" data-live-search="true">
                                            @foreach($all_states as $key => $state)
                                            <option {{ $article->state_id == $state->id ? 'selected' : '' }} value="{{ $state->id }}">{{ $state->state_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('state_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="select_city_id" class="text-black">{{ __('backend.city.city') }}</label>
                                        <select id="select_city_id" class="selectpicker form-control @error('city_id') is-invalid @enderror" name="city_id" data-live-search="true">
                                            @foreach($all_cities as $key => $city)
                                            <option {{ $article->city_id == $city->id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->city_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('city_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <label for="article_postal_code" class="text-black">{{ __('backend.article.postal-code') }}</label>
                                        <input id="article_postal_code" type="text" class="form-control @error('article_postal_code') is-invalid @enderror" name="article_postal_code" value="{{ old('article_postal_code') ? old('article_postal_code') : $article->item_postal_code }}">
                                        @error('article_postal_code')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="article_lat" class="text-black">{{ __('backend.article.lat') }} / {{ __('backend.article.lng') }}</label>
                                        <div class="input-group">
                                            <input id="article_lat" type="text" class="form-control @error('article_lat') is-invalid @enderror" name="article_lat" value="{{ old('article_lat') ? old('article_lat') : $article->item_lat }}" aria-describedby="latHelpBlock">
                                            <input id="article_lng" type="text" class="form-control @error('article_lng') is-invalid @enderror" name="article_lng" value="{{ old('article_lng') ? old('article_lng') : $article->item_lng }}"aria-describedby="lngHelpBlock">
                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-primary lat_lng_select_button" type="button">{{ __('backend.article.select-map') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input {{ $article->item_address_hide == \App\Item::ITEM_ADDR_HIDE ? 'checked' : '' }} class="form-check-input" type="checkbox" id="article_address_hide" name="article_address_hide" value="{{ \App\Item::ITEM_ADDR_HIDE }}">
                                            <label class="form-check-label" for="article_address_hide">
                                                {{ __('backend.article.hide-address') }}
                                                <small class="text-muted">
                                                    {{ __('backend.article.hide-address-help') }}
                                                </small>
                                            </label>
                                        </div>
                                        @error('article_address_hide')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="form-row mb-3">
                                    <div class="col-md-3">
                                        <label for="article_youtube_id" class="text-black">{{ __('customization.article.youtube-id') }}</label>
                                        <input id="article_youtube_id" type="text" class="form-control @error('article_youtube_id') is-invalid @enderror" name="article_youtube_id" value="{{ old('article_youtube_id') ? old('article_youtube_id') : $article->item_youtube_id }}">
                                        @error('article_youtube_id')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <label for="article_description" class="text-black">{{ __('backend.article.description') }}</label>
                                        <textarea class="form-control @error('article_description') is-invalid @enderror" id="article_description" rows="5" name="article_description">{{ old('article_description') ? old('article_description') : $article->item_description }}</textarea>
                                        @error('article_description')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <label class="text-black" for="item_keywords">{{ __('backend.article.keyword') }}</label>
                                    <input id="item_keywords" type="text" class="form-control @error('item_keywords') is-invalid @enderror" name="item_keywords" value="{{ old('item_keywords') ? old('item_keywords') : $article->item_keywords }}">
                                    <small class="form-text text-muted">
                                        Separate by comma
                                    </small>
                                    @error('item_keywords')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <!-- Start web & social media -->
                                <div class="form-row mb-3">

                                    <input type="hidden" name="article_featured" value="{{ $article->item_featured }}">
                                    {{-- <div class="col-md-4">
                                        @if($show_article_featured_selector)
                                            <label for="article_featured" class="text-black">{{ __('backend.article.featured') }}</label>
                                            <select class="selectpicker form-control @error('article_featured') is-invalid @enderror" name="article_featured">
                                                <option {{ (old('article_featured') ? old('article_featured') : $article->item_featured) == \App\Item::ITEM_NOT_FEATURED ? 'selected' : '' }} value="{{ \App\Item::ITEM_NOT_FEATURED }}">{{ __('backend.shared.no') }}</option>
                                                <option {{ (old('article_featured') ? old('article_featured') : $article->item_featured) == \App\Item::ITEM_FEATURED ? 'selected' : '' }} value="{{ \App\Item::ITEM_FEATURED }}">{{ __('backend.shared.yes') }}</option>
                                            </select>
                                            @error('article_featured')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        @else
                                            <input type="hidden" name="article_featured" value="{{ $article->item_featured }}">
                                        @endif
                                    </div> --}}

                                    <div class="col-md-3">
                                        <label for="article_phone" class="text-black">{{ __('backend.article.phone') }}</label>
                                        <input id="article_phone" type="text" class="form-control @error('article_phone') is-invalid @enderror" name="article_phone" value="{{ old('article_phone') ? old('article_phone') : $article->item_phone }}" aria-describedby="lngHelpBlock">
                                        @error('article_phone')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="article_social_whatsapp" class="text-black">
                                            <i class="fa-brands fa-whatsapp-square"></i>
                                            {{ __('article_whatsapp_instagram.article-social-whatsapp') }}
                                        </label>
                                        <input id="article_social_whatsapp" type="text" class="form-control @error('article_social_whatsapp') is-invalid @enderror" name="article_social_whatsapp" value="{{ old('article_social_whatsapp') ? old('article_social_whatsapp') : $article->item_social_whatsapp }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('article_whatsapp_instagram.article-social-whatsapp-help') }}
                                        </small>
                                        @error('article_social_whatsapp')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="article_website" class="text-black">
                                            <i class="fa-solid fa-globe"></i>
                                            {{ __('backend.article.website') }}
                                        </label>
                                        <input id="article_website" type="text" class="form-control @error('article_website') is-invalid @enderror" name="article_website" value="{{ old('article_website') ? old('article_website') : $article->item_website }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('article_website')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="article_social_facebook" class="text-black">
                                            <i class="fa-brands fa-facebook-square"></i>
                                            {{ __('backend.article.facebook') }}
                                        </label>
                                        <input id="article_social_facebook" type="text" class="form-control @error('article_social_facebook') is-invalid @enderror" name="article_social_facebook" value="{{ old('article_social_facebook') ? old('article_social_facebook') : $article->item_social_facebook }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('article_social_facebook')
                                        <span class="invalid-tooltip">
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
                                        <input id="article_social_twitter" type="text" class="form-control @error('article_social_twitter') is-invalid @enderror" name="article_social_twitter" value="{{ old('article_social_twitter') ? old('article_social_twitter') : $article->item_social_twitter }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('article_social_twitter')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="article_social_linkedin" class="text-black">
                                            <i class="fa-brands fa-linkedin"></i>
                                            {{ __('backend.article.linkedin') }}
                                        </label>
                                        <input id="article_social_linkedin" type="text" class="form-control @error('article_social_linkedin') is-invalid @enderror" name="article_social_linkedin" value="{{ old('article_social_linkedin') ? old('article_social_linkedin') : $article->item_social_linkedin }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('article_social_linkedin')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        <label for="article_social_instagram" class="text-black">
                                            <i class="fa-brands fa-instagram-square"></i>
                                            {{ __('article_whatsapp_instagram.article-social-instagram') }}
                                        </label>
                                        <input id="article_social_instagram" type="text" class="form-control @error('article_social_instagram') is-invalid @enderror" name="article_social_instagram" value="{{ old('article_social_instagram') ? old('article_social_instagram') : $article->item_social_instagram }}">
                                        <small id="linkHelpBlock" class="form-text text-muted">
                                            {{ __('article_whatsapp_instagram.article-social-instagram-help') }}
                                        </small>
                                        @error('article_social_instagram')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3">
                                        @php
                                            $article_video_url = '';
                                            if($article->medias && count($article->medias) > 0) {
                                                foreach($article->medias()->orderBy('item_media.id','desc')->get() as $media) {
                                                    if($media->media_type == \App\ItemMedia::MEDIA_TYPE_VIDEO && !empty($media->media_url)) {
                                                        $article_video_url = $media->media_url;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <label for="article_video_urls" class="text-black"><i class="fa-brands fa-youtube-square"></i> Video Url</label>
                                        <input id="article_video_urls" type="url" class="form-control @error('article_video_urls') is-invalid @enderror" name="article_video_urls[]" value="{{ $article_video_url }}">
                                        <small id="videoHelpBlock" class="form-text text-muted">
                                            {{ __('backend.shared.url-help') }}
                                        </small>
                                        @error('article_video_urls')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- End web & social media -->
                            </div>
                        </div>

                        <!-- start opening hour section -->
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
                                                <option value="{{ $time_zone_identifier }}" {{ (old('article_hour_time_zone') ? old('article_hour_time_zone') : $article->item_hour_time_zone) == $time_zone_identifier ? 'selected' : '' }}>{{ $time_zone_identifier }}</option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('article_hour.timezone-help') }}
                                        </small>
                                        @error('article_hour_time_zone')
                                        <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                        @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="article_hour_show_hours" class="text-black">{{ __('article_hour.show-open-hour') }}</label>
                                        <select id="article_hour_show_hours" class="selectpicker form-control @error('article_hour_show_hours') is-invalid @enderror" name="article_hour_show_hours" data-live-search="true">
                                            <option value="{{ \App\Item::ITEM_HOUR_SHOW }}" {{ (old('article_hour_show_hours') ? old('article_hour_show_hours') : $article->item_hour_show_hours) == \App\Item::ITEM_HOUR_SHOW ? 'selected' : '' }}>{{ __('article_hour.show-hour') }}</option>
                                            <option value="{{ \App\Item::ITEM_HOUR_NOT_SHOW }}" {{ (old('article_hour_show_hours') ? old('article_hour_show_hours') : $article->item_hour_show_hours) == \App\Item::ITEM_HOUR_NOT_SHOW ? 'selected' : '' }}>{{ __('article_hour.not-show-hour') }}</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            {{ __('article_hour.show-open-hour-help') }}
                                        </small>
                                        @error('article_hour_show_hours')
                                        <span class="invalid-tooltip">
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
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_MONDAY }}">{{ __('article_hour.monday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_TUESDAY }}">{{ __('article_hour.tuesday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_WEDNESDAY }}">{{ __('article_hour.wednesday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_THURSDAY }}">{{ __('article_hour.thursday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_FRIDAY }}">{{ __('article_hour.friday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_SATURDAY }}">{{ __('article_hour.saturday') }}</option>
                                            <option value="{{ \App\ItemHour::DAY_OF_WEEK_SUNDAY }}">{{ __('article_hour.sunday') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_open_time_open_hour" class="text-black">{{ __('article_hour.article-hour-open-hour') }}</label>
                                        <select id="article_hour_open_time_open_hour" class="selectpicker form-control" name="article_hour_open_time_open_hour" data-live-search="true">
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_open_time_open_minute" class="text-black">{{ __('article_hour.article-hour-open-minute') }}</label>
                                        <select id="article_hour_open_time_open_minute" class="selectpicker form-control" name="article_hour_open_time_open_minute" data-live-search="true">
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_open_time_close_hour" class="text-black">{{ __('article_hour.article-hour-close-hour') }}</label>
                                        <select id="article_hour_open_time_close_hour" class="selectpicker form-control" name="article_hour_open_time_close_hour" data-live-search="true">
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_open_time_close_minute" class="text-black">{{ __('article_hour.article-hour-close-minute') }}</label>
                                        <select id="article_hour_open_time_close_minute" class="selectpicker form-control" name="article_hour_open_time_close_minute" data-live-search="true">
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
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
                                    @foreach($article_hours as $article_hours_key => $article_hour)
                                        <div class="col-12 col-md-3">
                                            @if($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_MONDAY)
                                                {{ __('article_hour.monday') }}
                                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_TUESDAY)
                                                {{ __('article_hour.tuesday') }}
                                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_WEDNESDAY)
                                                {{ __('article_hour.wednesday') }}
                                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_THURSDAY)
                                                {{ __('article_hour.thursday') }}
                                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_FRIDAY)
                                                {{ __('article_hour.friday') }}
                                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_SATURDAY)
                                                {{ __('article_hour.saturday') }}
                                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_SUNDAY)
                                                {{ __('article_hour.sunday') }}
                                            @endif
                                            {{ substr($article_hour->item_hour_open_time, 0, -3) . '-' . substr($article_hour->item_hour_close_time, 0, -3) }}
                                            <a class="text-primary" href="#" data-toggle="modal" data-target="#editarticleHourModal_{{ $article_hour->id }}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a class="text-danger" href="#" data-toggle="modal" data-target="#deletearticleHourModal_{{ $article_hour->id }}">
                                                <i class='far fa-trash-alt'></i>
                                            </a>
                                        </div>
                                    @endforeach
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
                                        <input id="article_hour_exception_date" type="text" class="form-control date-picker-input" name="article_hour_exception_date" value="" placeholder="{{ __('article_hour.open-hour-exception-date-placeholder') }}">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_exception_open_time_open_hour" class="text-black">{{ __('article_hour.article-hour-open-hour') }}</label>
                                        <select id="article_hour_exception_open_time_open_hour" class="selectpicker form-control" name="article_hour_exception_open_time_open_hour" data-live-search="true">
                                            <option value="">{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_exception_open_time_open_minute" class="text-black">{{ __('article_hour.article-hour-open-minute') }}</label>
                                        <select id="article_hour_exception_open_time_open_minute" class="selectpicker form-control" name="article_hour_exception_open_time_open_minute" data-live-search="true">
                                            <option value="">{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_exception_open_time_close_hour" class="text-black">{{ __('article_hour.article-hour-close-hour') }}</label>
                                        <select id="article_hour_exception_open_time_close_hour" class="selectpicker form-control" name="article_hour_exception_open_time_close_hour" data-live-search="true">
                                            <option value="">{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_hour=0; $full_hour<=24; $full_hour++)
                                                <option value="{{ $full_hour }}">{{ $full_hour }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label for="article_hour_exception_open_time_close_minute" class="text-black">{{ __('article_hour.article-hour-close-minute') }}</label>
                                        <select id="article_hour_exception_open_time_close_minute" class="selectpicker form-control" name="article_hour_exception_open_time_close_minute" data-live-search="true">
                                            <option value="">{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                            @for($full_minute=0; $full_minute<=59; $full_minute++)
                                                <option value="{{ $full_minute }}">{{ $full_minute }}</option>
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
                                    @foreach($article_hour_exceptions as $article_hour_exceptions_key => $article_hour_exception)
                                        <div class="col-12 col-md-3">
                                            {{ $article_hour_exception->item_hour_exception_date }}
                                            @if(!empty($article_hour_exception->item_hour_exception_open_time) && !empty($article_hour_exception->item_hour_exception_close_time))
                                                {{ substr($article_hour_exception->item_hour_exception_open_time, 0, -3) . '-' . substr($article_hour_exception->item_hour_exception_close_time, 0, -3) }}
                                            @else
                                                {{ __('article_hour.open-hour-exception-close-all-day') }}
                                            @endif
                                            <a class="text-primary" href="#" data-toggle="modal" data-target="#editarticleHourExceptionModal_{{ $article_hour_exception->id }}">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <a class="text-danger" href="#" data-toggle="modal" data-target="#deletearticleHourExceptionModal_{{ $article_hour_exception->id }}">
                                                <i class='far fa-trash-alt'></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- end opening hour section -->

                        <!-- Start feature image and gallery image -->
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
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.article.select-image') }}</button>
                                                @if(empty($article->item_image))
                                                <img id="image_preview" src="{{ asset('backend/images/placeholder/full_article_feature_image.webp') }}" class="img-responsive">
                                                @else
                                                <img id="image_preview" src="{{ Storage::disk('public')->url('item/'. $article->item_image) }}" class="img-responsive">
                                                @endif
                                                <input id="feature_image" type="hidden" name="feature_image">
                                            </div>
                                        </div>

                                        <div class="row mt-1">
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
                                            {{ __('theme_directory_hub.listing.gallery-upload-help', ['gallery_photos_count' => $setting_article_max_gallery_photos]) }}
                                        </small> --}}
                                        @error('image_gallery')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <button id="upload_gallery" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.article.select-images') }}</button>
                                                <div class="row" id="selected-images">
                                                    @foreach($article->galleries as $key => $gallery)
                                                        <div class="col-2 mb-2" id="article_image_gallery_{{ $gallery->id }}">
                                                            <img class="article_image_gallery_img" src="{{ Storage::disk('public')->url('item/gallery/'. $gallery->item_image_gallery_name) }}" style="width: 100%; border-radius: 5px; border: 1px solid #dadada;">
                                                            <br/><button class="btn btn-danger btn-sm text-white mt-1" onclick="$(this).attr('disabled', true); deleteGallery({{ $gallery->id }});">{{ __('backend.shared.delete') }}</button>
                                                        </div>
                                                    @endforeach
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
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.update') }}
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
                                <input id="upload_image_input" type="file" class="custom-file-input">
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

    <!-- Modal Delete Listing -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('backend.shared.delete-message', ['record_type' => __('backend.shared.article'), 'record_name' => $article->item_title]) }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('user.articles.destroy', $article) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
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

    <!-- Modal categories -->
    <div class="modal fade" id="categoriesModal" tabindex="-1" role="dialog" aria-labelledby="categoriesModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('categories.update-cat') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="update-article-category-form" action="{{ route('user.article.category.update', $article) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <select multiple size="{{ count($all_categories) }}" class="selectpicker form-control" name="category[]" id="category" data-live-search="true" data-actions-box="true">
                            @foreach($all_categories as $key => $a_category)
                                <option {{ in_array($a_category['category_id'], $category_ids) ? 'selected' : '' }} value="{{ $a_category['category_id'] }}">{{ $a_category['category_name'] }}</option>
                            @endforeach
                        </select>
                        @error('category')
                        <span class="invalid-tooltip">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button type="button" class="btn btn-success" id="update-article-category-button">{{ __('categories.update-cat') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal article slug -->
    <div class="modal fade" id="articleSlugModal" tabindex="-1" role="dialog" aria-labelledby="articleSlugModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('article_slug.update-url') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="update-article-slug-form" action="{{ route('user.article.slug.update', ['article' => $article]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">{{ '/' }}</div>
                                    </div>
                                    <input type="text" class="form-control" name="article_slug" value="{{ old('article_slug') ? old('article_slug') : $article->item_slug }}" id="article_slug">
                                </div>
                                <small class="form-text text-muted">
                                    {{ __('article_slug.article-slug-help') }}
                                </small>
                                @error('article_slug')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button type="button" class="btn btn-success" id="update-article-slug-button">{{ __('article_slug.update-url') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Start edit article hour & delete article hour -->
    @foreach($article_hours as $article_hours_key => $article_hour)
        <div class="modal fade" id="editarticleHourModal_{{ $article_hour->id }}" tabindex="-1" role="dialog" aria-labelledby="editarticleHourModal_{{ $article_hour->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('article_hour.modal-edit-hours-title') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="update-article-slug-form" action="{{ route('user.articles.hours.update', ['article_hour' => $article_hour]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">

                            @php
                                $article_hour_open_time_hour = intval(substr($article_hour->item_hour_open_time, 0, 2));
                                $article_hour_open_time_minute = intval(substr($article_hour->item_hour_open_time, 3, 2));
                                $article_hour_close_time_hour = intval(substr($article_hour->item_hour_close_time, 0, 2));
                                $article_hour_close_time_minute = intval(substr($article_hour->item_hour_close_time, 3, 2));
                            @endphp

                            <div class="form-row mb-3 align-articles-end">
                                <div class="col-12 col-md-4">
                                    <label for="article_hour_day_of_week" class="text-black">{{ __('article_hour.day-of-week') }}</label>
                                    <select id="article_hour_day_of_week" class="selectpicker form-control" name="article_hour_day_of_week" data-live-search="true">
                                        <option value="{{ \App\ItemHour::DAY_OF_WEEK_MONDAY }}" {{ $article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_MONDAY ? 'selected' :'' }}>{{ __('article_hour.monday') }}</option>
                                        <option value="{{ \App\ItemHour::DAY_OF_WEEK_TUESDAY }}" {{ $article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_TUESDAY ? 'selected' :'' }}>{{ __('article_hour.tuesday') }}</option>
                                        <option value="{{ \App\ItemHour::DAY_OF_WEEK_WEDNESDAY }}" {{ $article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_WEDNESDAY ? 'selected' :'' }}>{{ __('article_hour.wednesday') }}</option>
                                        <option value="{{ \App\ItemHour::DAY_OF_WEEK_THURSDAY }}" {{ $article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_THURSDAY ? 'selected' :'' }}>{{ __('article_hour.thursday') }}</option>
                                        <option value="{{ \App\ItemHour::DAY_OF_WEEK_FRIDAY }}" {{ $article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_FRIDAY ? 'selected' :'' }}>{{ __('article_hour.friday') }}</option>
                                        <option value="{{ \App\ItemHour::DAY_OF_WEEK_SATURDAY }}" {{ $article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_SATURDAY ? 'selected' :'' }}>{{ __('article_hour.saturday') }}</option>
                                        <option value="{{ \App\ItemHour::DAY_OF_WEEK_SUNDAY }}" {{ $article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_SUNDAY ? 'selected' :'' }}>{{ __('article_hour.sunday') }}</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="article_hour_open_time_hour" class="text-black">{{ __('article_hour.article-hour-open-hour') }}</label>
                                    <select id="article_hour_open_time_hour" class="selectpicker form-control" name="article_hour_open_time_hour" data-live-search="true">
                                        @for($full_hour=0; $full_hour<=24; $full_hour++)
                                            <option value="{{ $full_hour }}" {{ $full_hour == $article_hour_open_time_hour ? 'selected' :'' }}>{{ $full_hour }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="article_hour_open_time_minute" class="text-black">{{ __('article_hour.article-hour-open-minute') }}</label>
                                    <select id="article_hour_open_time_minute" class="selectpicker form-control" name="article_hour_open_time_minute" data-live-search="true">
                                        @for($full_minute=0; $full_minute<=59; $full_minute++)
                                            <option value="{{ $full_minute }}" {{ $full_minute == $article_hour_open_time_minute ? 'selected' :'' }}>{{ $full_minute }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="article_hour_close_time_hour" class="text-black">{{ __('article_hour.article-hour-close-hour') }}</label>
                                    <select id="article_hour_close_time_hour" class="selectpicker form-control" name="article_hour_close_time_hour" data-live-search="true">
                                        @for($full_hour=0; $full_hour<=24; $full_hour++)
                                            <option value="{{ $full_hour }}" {{ $full_hour == $article_hour_close_time_hour ? 'selected' :'' }}>{{ $full_hour }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="article_hour_close_time_minute" class="text-black">{{ __('article_hour.article-hour-close-minute') }}</label>
                                    <select id="article_hour_close_time_minute" class="selectpicker form-control" name="article_hour_close_time_minute" data-live-search="true">
                                        @for($full_minute=0; $full_minute<=59; $full_minute++)
                                            <option value="{{ $full_minute }}" {{ $full_minute == $article_hour_close_time_minute ? 'selected' :'' }}>{{ $full_minute }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <div class="col-12">
                                    {{ __('article_hour.open-hour-hours-help') }}
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

        <div class="modal fade" id="deletearticleHourModal_{{ $article_hour->id }}" tabindex="-1" role="dialog" aria-labelledby="deletearticleHourModal_{{ $article_hour->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('article_hour.modal-delete-hours-title') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('article_hour.modal-delete-hours-description') }}</p>
                        <p>
                            @if($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_MONDAY)
                                {{ __('article_hour.monday') }}
                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_TUESDAY)
                                {{ __('article_hour.tuesday') }}
                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_WEDNESDAY)
                                {{ __('article_hour.wednesday') }}
                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_THURSDAY)
                                {{ __('article_hour.thursday') }}
                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_FRIDAY)
                                {{ __('article_hour.friday') }}
                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_SATURDAY)
                                {{ __('article_hour.saturday') }}
                            @elseif($article_hour->item_hour_day_of_week == \App\ItemHour::DAY_OF_WEEK_SUNDAY)
                                {{ __('article_hour.sunday') }}
                            @endif
                            {{ substr($article_hour->item_hour_open_time, 0, -3) . '-' . substr($article_hour->item_hour_close_time, 0, -3) }}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                        <form action="{{ route('user.articles.hours.destroy', ['article_hour' => $article_hour]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($article_hour_exceptions as $article_hour_exceptions_key => $article_hour_exception)
        <div class="modal fade" id="editarticleHourExceptionModal_{{ $article_hour_exception->id }}" tabindex="-1" role="dialog" aria-labelledby="editarticleHourExceptionModal_{{ $article_hour_exception->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('article_hour.modal-edit-hour-exceptions-title') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="update-article-slug-form" action="{{ route('user.articles.hour-exceptions.update', ['article_hour_exception' => $article_hour_exception]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            @php
                                $article_hour_exception_open_time_hour = empty($article_hour_exception->item_hour_exception_open_time) ? null : intval(substr($article_hour_exception->item_hour_exception_open_time, 0, 2));
                                $article_hour_exception_open_time_minute = empty($article_hour_exception->item_hour_exception_open_time) ? null : intval(substr($article_hour_exception->item_hour_exception_open_time, 3, 2));
                                $article_hour_exception_close_time_hour = empty($article_hour_exception->item_hour_exception_close_time) ? null : intval(substr($article_hour_exception->item_hour_exception_close_time, 0, 2));
                                $article_hour_exception_close_time_minute = empty($article_hour_exception->item_hour_exception_close_time) ? null : intval(substr($article_hour_exception->item_hour_exception_close_time, 3, 2));
                            @endphp

                            <div class="form-row mb-3 align-articles-end">
                                <div class="col-12 col-md-4">
                                    <label for="article_hour_exception_date" class="text-black">{{ __('article_hour.open-hour-exception-date') }}</label>
                                    <input id="article_hour_exception_date" type="text" class="form-control date-picker-input" name="article_hour_exception_date" value="{{ $article_hour_exception->item_hour_exception_date }}" placeholder="{{ __('article_hour.open-hour-exception-date-placeholder') }}">
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="article_hour_exception_open_time_hour" class="text-black">{{ __('article_hour.article-hour-open-hour') }}</label>
                                    <select id="article_hour_exception_open_time_hour" class="selectpicker form-control" name="article_hour_exception_open_time_hour" data-live-search="true">
                                        <option value="" {{ is_null($article_hour_exception_open_time_hour) ? 'selected' :'' }}>{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                        @for($full_hour=0; $full_hour<=24; $full_hour++)
                                            <option value="{{ $full_hour }}" {{ (!is_null($article_hour_exception_open_time_hour) && $full_hour == $article_hour_exception_open_time_hour) ? 'selected' :'' }}>{{ $full_hour }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="article_hour_exception_open_time_minute" class="text-black">{{ __('article_hour.article-hour-open-minute') }}</label>
                                    <select id="article_hour_exception_open_time_minute" class="selectpicker form-control" name="article_hour_exception_open_time_minute" data-live-search="true">
                                        <option value="" {{ is_null($article_hour_exception_open_time_minute) ? 'selected' :'' }}>{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                        @for($full_minute=0; $full_minute<=59; $full_minute++)
                                            <option value="{{ $full_minute }}" {{ (!is_null($article_hour_exception_open_time_minute) && $full_minute == $article_hour_exception_open_time_minute) ? 'selected' :'' }}>{{ $full_minute }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="article_hour_exception_close_time_hour" class="text-black">{{ __('article_hour.article-hour-close-hour') }}</label>
                                    <select id="article_hour_exception_close_time_hour" class="selectpicker form-control" name="article_hour_exception_close_time_hour" data-live-search="true">
                                        <option value="" {{ is_null($article_hour_exception_close_time_hour) ? 'selected' :'' }}>{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                        @for($full_hour=0; $full_hour<=24; $full_hour++)
                                            <option value="{{ $full_hour }}" {{ (!is_null($article_hour_exception_close_time_hour) && $full_hour == $article_hour_exception_close_time_hour) ? 'selected' :'' }}>{{ $full_hour }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    <label for="article_hour_exception_close_time_minute" class="text-black">{{ __('article_hour.article-hour-close-minute') }}</label>
                                    <select id="article_hour_exception_close_time_minute" class="selectpicker form-control" name="article_hour_exception_close_time_minute" data-live-search="true">
                                        <option value="" {{ is_null($article_hour_exception_close_time_minute) ? 'selected' :'' }}>{{ __('article_hour.open-hour-exception-close-all-day') }}</option>
                                        @for($full_minute=0; $full_minute<=59; $full_minute++)
                                            <option value="{{ $full_minute }}" {{ (!is_null($article_hour_exception_close_time_minute) && $full_minute == $article_hour_exception_close_time_minute) ? 'selected' :'' }}>{{ $full_minute }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <div class="col-12">
                                    {{ __('article_hour.open-hour-exceptions-help') }}
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

        <div class="modal fade" id="deletearticleHourExceptionModal_{{ $article_hour_exception->id }}" tabindex="-1" role="dialog" aria-labelledby="deletearticleHourExceptionModal_{{ $article_hour_exception->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('article_hour.modal-delete-hour-exception-title') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('article_hour.modal-delete-hour-exception-description') }}</p>
                        <p>
                            {{ $article_hour_exception->item_hour_exception_date }}
                            @if(!empty($article_hour_exception->item_hour_exception_open_time) && !empty($article_hour_exception->item_hour_exception_close_time))
                                {{ substr($article_hour_exception->item_hour_exception_open_time, 0, -3) . '-' . substr($article_hour_exception->item_hour_exception_close_time, 0, -3) }}
                            @else
                                {{ __('article_hour.open-hour-exception-close-all-day') }}
                            @endif
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                        <form action="{{ route('user.articles.hour-exceptions.destroy', ['article_hour_exception' => $article_hour_exception]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- End edit article hour & delete article hour -->

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

        function deleteGallery(domId)
        {
            //$("form :submit").attr("disabled", true);

            var ajax_url = '/ajax/article/gallery/delete/' + domId;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: ajax_url,
                method: 'post',
                data: {
                },
                success: function(result){
                    console.log(result);
                    $('#article_image_gallery_' + domId).remove();

                }});

        }

        $(document).ready(function() {

            "use strict";

            /**
             * Start listing type radio button select
             */
            @if((old('article_type') ? old('article_type') : $article->item_type) == \App\Item::ITEM_TYPE_ONLINE)
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
            @endif
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

            @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
            /**
             * Start map modal
             */
            var map = L.map('map-modal-body', {
                center: [{{ empty($article->item_lat) ? $site_global_settings->setting_site_location_lat : $article->item_lat }}, {{ empty($article->item_lng) ? $site_global_settings->setting_site_location_lng : $article->item_lng }}],
                zoom: 10,
            });

            var layerGroup = L.layerGroup().addTo(map);
            L.marker([{{ empty($article->item_lat) ? $site_global_settings->setting_site_location_lat : $article->item_lat }}, {{ empty($article->item_lng) ? $site_global_settings->setting_site_location_lng : $article->item_lng }}]).addTo(layerGroup);

            var current_lat = {{ empty($article->item_lat) ? $site_global_settings->setting_site_location_lat : $article->item_lat }};
            var current_lng = {{ empty($article->item_lng) ? $site_global_settings->setting_site_location_lng : $article->item_lng }};

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
                            // $('#select_state_id').html("<option selected value='0'>{{ __('backend.article.select-state') }}</option>");
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
                            // $('#select_city_id').html("<option selected value='0'>{{ __('backend.article.select-city') }}</option>");
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
                        // $('#select_state_id').html("<option selected value='0'>{{ __('backend.article.select-state') }}</option>");
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
                        $('#select_city_id').empty();
                        // $('#select_city_id').html("<option selected value='0'>{{ __('backend.article.select-city') }}</option>");
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

            /**
             * Start image gallery upload
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
             * End image gallery upload
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
             * Start category update modal form submit
             */
            $('#update-article-category-button').on('click', function(){
                $('#update-article-category-button').attr("disabled", true);
                $('#update-article-category-form').submit();
            });

            @error('category')
            $('#categoriesModal').modal('show');
            @enderror
            /**
             * End category update modal form submit
             */


            /**
             * Start article slug update modal form submit
             */
            $('#update-article-slug-button').on('click', function(){
                $('#update-article-slug-button').attr("disabled", true);
                $('#update-article-slug-form').submit();
            });

            @error('article_slug')
            $('#articleSlugModal').modal('show');
            @enderror
            /**
             * End article slug update modal form submit
             */

            /**
             * Start delete feature image button
             */
            $('#delete_feature_image_button').on('click', function(){

                $('#delete_feature_image_button').attr("disabled", true);

                var ajax_url = '/ajax/article/image/delete/' + '{{ $article->id }}';

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                jQuery.ajax({
                    url: ajax_url,
                    method: 'post',
                    data: {
                    },
                    success: function(result){
                        console.log(result);

                        $('#image_preview').attr("src", "{{ asset('backend/images/placeholder/full_article_feature_image.webp') }}");
                        $('#feature_image').val("");

                        $('#delete_feature_image_button').attr("disabled", false);
                    }});
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
            $('.date-picker-input').datepicker({
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
                const myLatlng = { lat: {{ empty($article->item_lat) ? $site_global_settings->setting_site_location_lat : $article->item_lat }}, lng: {{ empty($article->item_lng) ? $site_global_settings->setting_site_location_lng : $article->item_lng }} };
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
