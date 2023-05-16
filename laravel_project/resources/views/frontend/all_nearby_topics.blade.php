@extends('frontend.layouts.app')
@section('category_active', 'active')

@section('styles')

    @if ($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <link href="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection


@section('content')
    <style>
        .owl-carousel .owl-item img {
            display: inherit;
            width: unset;
        }
    </style>

    @if ($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        <div class="site-blocks-cover inner-page-cover overlay"
            style="background-image: url( {{ asset('frontend/images/placeholder/header-inner.webp') }});">
        @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR)
            <div class="site-blocks-cover inner-page-cover overlay"
                style="background-color: {{ $site_innerpage_header_background_color }};">
            @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
                <div class="site-blocks-cover inner-page-cover overlay"
                    style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image) }});">
                @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
                    <div class="site-blocks-cover inner-page-cover overlay" style="background-color: #333333;">
    @endif

    @if ($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div data-youtube="{{ $site_innerpage_header_background_youtube_video }}"></div>
    @endif

    <div class="container">
        <div class="row align-items-center justify-content-center text-center">

            <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">


                <div class="row justify-content-center mt-5">
                    <div class="col-md-8 text-center">
                        <h1 style="color: {{ $site_innerpage_header_title_font_color }};">
                            {{ __('frontend.categories.title') }}</h1>
                        <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">
                            {{ __('frontend.categories.description') }}</p>
                    </div>
                </div>
                <div class="form-search-wrap" data-aos="fade-up" data-aos-delay="200">
                    @include('frontend.partials.search.head')
                </div>


            </div>
        </div>
    </div>
    </div>


    <div class="site-section">
        <div class="container">


            @if ($categories->count() > 0)
                <div class="overlap-category mb-5">
                    <div class="text-center">
                        <h2 class="font-weight-light text-primary">I'm looking for information that will help me with...
                        </h2>
                    </div>
                    <div class="row align-items-stretch no-gutters justify-content-center">
                        @foreach ($categories as $categories_key => $category)
                            <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                                @if ($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON)
                                    <a href="{{ route('page.category', $category->category_slug) }}"
                                        class="popular-category h-100 decoration-none">
                                        <span class="icon">
                                            <span>
                                                @if ($category->category_icon)
                                                    <i class="{{ $category->category_icon }}"></i>
                                                @else
                                                    <i class="fa-solid fa-heart"></i>
                                                @endif
                                            </span>
                                        </span>

                                        <span class="caption d-block">{{ $category->category_name }}</span>
                                    </a>
                                @elseif($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_IMAGE)
                                    <a href="{{ route('page.category', $category->category_slug) }}"
                                        class="popular-category h-100 decoration-none image-category">
                                        <span class="icon image-category-span">
                                            <span>
                                                @if ($category->category_image)
                                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('category/' . $category->category_image) }}"
                                                        alt="Image" class="img-fluid rounded image-category-img">
                                                @else
                                                    <img src="{{ asset('frontend/images/placeholder/category-image.webp') }}"
                                                        alt="Image" class="img-fluid rounded image-category-img">
                                                @endif
                                            </span>
                                        </span>
                                        <span
                                            class="caption d-block image-category-caption">{{ $category->category_name }}</span>
                                    </a>
                                @endif

                            </div>
                        @endforeach
                    </div>

                </div>
            @endif

            <!-- Start Filter -->
            <form method="GET" action="{{ route('page.all.recenttopics') }}" id="filter_form">
                <div class="row pt-3 pb-3 ml-1 mr-1 mb-5 rounded border">
                    <div class="col-12">

                        @if ($ads_before_breadcrumb->count() > 0)
                            @foreach ($ads_before_breadcrumb as $ads_before_breadcrumb_key => $ad_before_breadcrumb)
                                <div class="row mb-5">
                                    @if ($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_before_breadcrumb->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_before_breadcrumb->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_before_breadcrumb->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb bg-white pl-0 pr-0">
                                        <li class="breadcrumb-item">
                                            <a href="{{ route('page.home') }}">
                                                <i class="fas fa-bars"></i>
                                                {{ __('frontend.shared.home') }}
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">
                                            {{ __('frontend.item.all-categories') }}</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        @if ($ads_after_breadcrumb->count() > 0)
                            @foreach ($ads_after_breadcrumb as $ads_after_breadcrumb_key => $ad_after_breadcrumb)
                                <div class="row mb-5">
                                    @if ($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_after_breadcrumb->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_after_breadcrumb->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_after_breadcrumb->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                        <div class="row form-group align-items-center">
                            {{-- <div class="col-12 col-md-2">
                                <strong>{{ number_format($total_results) }}</strong>
                                {{ __('theme_directory_hub.filter-results') }}
                            </div>
                            <div class="col-12 col-md-1 text-right pl-0">
                                {{ __('theme_directory_hub.filter-filter-by') }}
                            </div> --}}
                            <div class="col-12 col-md-3 mt-2 pl-0 pl-sm-3">
                                <select class="selectpicker form-control @error('filter_sort_by') is-invalid @enderror"
                                    name="filter_sort_by" id="filter_sort_by">
                                    <option value="{{ \App\Item::ITEMS_SORT_BY_NEWEST_CREATED }}"
                                        {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_NEWEST_CREATED ? 'selected' : '' }}>
                                        {{ __('listings_filter.sort-by-newest') }}</option>
                                    <option value="{{ \App\Item::ITEMS_SORT_BY_OLDEST_CREATED }}"
                                        {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_OLDEST_CREATED ? 'selected' : '' }}>
                                        {{ __('listings_filter.sort-by-oldest') }}</option>
                                    <option value="{{ \App\Item::ITEMS_SORT_BY_HIGHEST_RATING }}"
                                        {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_HIGHEST_RATING ? 'selected' : '' }}>
                                        {{ __('listings_filter.sort-by-highest') }}</option>
                                    <option value="{{ \App\Item::ITEMS_SORT_BY_LOWEST_RATING }}"
                                        {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_LOWEST_RATING ? 'selected' : '' }}>
                                        {{ __('listings_filter.sort-by-lowest') }}</option>
                                    <option value="{{ \App\Item::ITEMS_SORT_BY_NEARBY_FIRST }}"
                                        {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_NEARBY_FIRST ? 'selected' : '' }}>
                                        {{ __('theme_directory_hub.filter-sort-by-nearby-first') }}</option>
                                </select>
                                @error('filter_sort_by')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3 pl-0 pl-sm-3">
                                <select
                                    class="selectpicker form-control @error('filter_preferred_pronouns') is-invalid @enderror"
                                    name="filter_preferred_pronouns" id="filter_preferred_pronouns">
                                    <option value="0" {{ empty($filter_preferred_pronouns) ? 'selected' : '' }}>Any
                                        Preferred Pronouns</option>
                                    {{-- <option value="male" {{ $filter_gender_type == "male" ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $filter_gender_type == "female" ? 'selected' : '' }}>Female</option> --}}
                                    @foreach (\App\User::PREFERRED_PRONOUNS as $prkey => $pronoun)
                                        @php
                                            $selected = '';
                                            if (request()->filter_preferred_pronouns == $prkey) {
                                                $selected = 'selected';
                                            }
                                        @endphp
                                        <option value="{{ $prkey }}" {{ $selected }}>{{ $pronoun }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('filter_preferred_pronouns')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3 pl-0">
                                <select class="selectpicker form-control @error('filter_working_type') is-invalid @enderror"
                                    name="filter_working_type" id="filter_working_type">
                                    <option value="0" {{ empty($filter_working_type) ? 'selected' : '' }}>Any Session
                                        Type</option>
                                    <option value="person-to-person"
                                        {{ $filter_working_type == 'person-to-person' ? 'selected' : '' }}>Person-to-Person
                                    </option>
                                    <option value="remotely" {{ $filter_working_type == 'remotely' ? 'selected' : '' }}>
                                        Remotely</option>
                                    <option value="hybrid" {{ $filter_working_type == 'hybrid' ? 'selected' : '' }}>Hybrid
                                        (Person-to-Person/Remotely)</option>
                                </select>
                                @error('filter_working_type')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3 pl-lg-0">
                                <select class="selectpicker form-control @error('filter_hourly_rate') is-invalid @enderror"
                                    name="filter_hourly_rate" id="filter_hourly_rate">
                                    <option value="0" {{ empty($filter_hourly_rate) ? 'selected' : '' }}>Any Price
                                        Range</option>
                                    <option value="$" {{ $filter_hourly_rate == '$' ? 'selected' : '' }}>$ (Less than
                                        125.00)</option>
                                    <option value="$$" {{ $filter_hourly_rate == '$$' ? 'selected' : '' }}>$$
                                        (125-225)</option>
                                    <option value="$$$" {{ $filter_hourly_rate == '$$$' ? 'selected' : '' }}>$$$
                                        (225-325)</option>
                                    <option value="$$$$" {{ $filter_hourly_rate == '$$$$' ? 'selected' : '' }}>$$$$
                                        (More than 325.00)</option>
                                </select>
                                @error('filter_hourly_rate')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row form-group align-items-center">
                            <div class="col-12 col-md-3 pl-3">
                                <select class="selectpicker form-control @error('filter_country') is-invalid @enderror"
                                    name="filter_country" id="filter_country" data-live-search="true">
                                    <option value="0" {{ empty($filter_country) ? 'selected' : '' }}>
                                        {{ __('prefer_country.all-country') }}</option>
                                    @foreach ($all_countries as $all_countries_key => $country)
                                        <option value="{{ $country->id }}"
                                            {{ $filter_country == $country->id ? 'selected' : '' }}>
                                            {{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                @error('filter_country')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3 pl-3">
                                <select class="selectpicker form-control @error('filter_state') is-invalid @enderror"
                                    name="filter_state" id="filter_state" data-live-search="true">
                                    <option value="0" {{ empty($filter_state) ? 'selected' : '' }}>
                                        {{ __('prefer_country.all-state') }}</option>
                                    @foreach ($all_states as $all_states_key => $state)
                                        <option value="{{ $state->id }}"
                                            {{ $filter_state == $state->id ? 'selected' : '' }}>{{ $state->state_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('filter_state')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3 pl-0">
                                <select class="selectpicker form-control @error('filter_city') is-invalid @enderror"
                                    name="filter_city" id="filter_city" data-live-search="true">
                                    <option value="0" {{ empty($filter_city) ? 'selected' : '' }}>
                                        {{ __('prefer_country.all-city') }}</option>
                                    @foreach ($all_cities as $all_cities_key => $city)
                                        <option value="{{ $city->id }}"
                                            {{ $filter_city == $city->id ? 'selected' : '' }}>{{ $city->city_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('filter_city')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <hr>

                        <div class="row">

                            @foreach ($all_printable_categories as $key => $all_printable_category)
                                @php
                                    if (empty($all_printable_category['is_parent'])) {
                                        continue;
                                    }
                                    if ($all_printable_category['category_name'] == 'Entrepreneurial' || $all_printable_category['category_name'] == 'Productivity') {
                                        continue;
                                    }
                                    
                                @endphp
                                <div class="col-12 col-sm-4 col-md-3">
                                    <div class="form-check filter_category_div">
                                        <input
                                            {{ in_array($all_printable_category['category_id'], $filter_categories) ? 'checked' : '' }}
                                            name="filter_categories[]" class="form-check-input" type="checkbox"
                                            value="{{ $all_printable_category['category_id'] }}"
                                            id="filter_categories_{{ $all_printable_category['category_id'] }}">
                                        <label class="form-check-label"
                                            for="filter_categories_{{ $all_printable_category['category_id'] }}">
                                            {{ $all_printable_category['category_name'] }}
                                        </label>
                                    </div>
                                </div>
                                @error('filter_categories')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <a href="javascript:;"
                                    class="show_more text-sm">{{ __('listings_filter.show-more') }}</a>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-12 text-right">
                                <a class="btn btn-sm btn-outline-primary rounded"
                                    href="{{ route('page.all.recenttopics') }}">
                                    {{ __('theme_directory_hub.filter-link-reset-all') }}
                                </a>
                                <a class="btn btn-sm btn-primary text-white rounded" id="filter_form_submit">
                                    {{ __('theme_directory_hub.filter-button-filter-results') }}
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
            <!-- End Filter -->

            <div class="row">

                <div class="col-lg-12 block-13">

                    <div class="row mb-4">
                        <div class="col-md-12 text-left border-primary">
                            <h2 class="font-weight-light text-primary">{{ __('All Nearby Topics') }}</h2>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12 text-left">
                            <strong>{{ number_format($popular_items->count()) }}</strong>
                            {{ __('theme_directory_hub.filter-results') }}
                        </div>
                        {{-- <div class="col-md-6 text-right">
                            <a href="{{ route('page.all.recenttopics') }}">
                                View All
                            </a>
                            <strong>{{ number_format($total_results) }}</strong>
                            {{ __('theme_directory_hub.filter-results') }}
                        </div> --}}
                    </div>

                    @if ($ads_before_content->count() > 0)
                        @foreach ($ads_before_content as $ads_before_content_key => $ad_before_content)
                            <div class="row mb-5">
                                @if ($ad_before_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <div class="row">

                        {{-- @if ($paid_items->count() > 0)
                            @foreach ($paid_items as $paid_items_key => $item)
                                <div class="col-lg-4">
                                    @include('frontend.partials.paid-item-block')
                                </div>
                            @endforeach
                        @endif --}}

                        {{-- @if ($free_items->count() > 0)
                            @foreach ($free_items as $free_items_key => $item)
                                <div class="col-lg-4">
                                    @include('frontend.partials.all-free-item-block')
                                </div>
                            @endforeach
                        @endif --}}
                        @if ($popular_items->count() > 0)
                            @foreach ($popular_items as $popular_items_key => $item)
                                <div class="col-md-6 mb-4 mb-lg-4 col-lg-4">
                                    <div class="listing-item listing">
                                        <div class="listing-image">
                                            <img src="{{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }}"
                                                alt="Image" class="img-fluid">
                                        </div>
                                        <div class="listing-item-content">

                                            @php
                                                $popular_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                            @endphp
                                            @foreach ($popular_item_getAllCategories as $item_all_categories_key => $category)
                                                <a class="px-3 mb-3 category"
                                                    href="{{ route('page.category', $category->category_slug) }}">
                                                    @if (!empty($category->category_icon))
                                                        <i class="{{ $category->category_icon }}"></i>
                                                    @else
                                                        <i class="fa-solid fa-heart"></i>
                                                    @endif
                                                    {{ $category->category_name }}
                                                </a>
                                            @endforeach

                                            @php
                                                $popular_item_allCategories_count = $item->allCategories()->count();
                                            @endphp
                                            @if ($popular_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                                <span
                                                    class="category">{{ __('categories.and') . ' ' . strval($popular_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . ' ' . __('categories.more') }}</span>
                                            @endif

                                            <h2 class="mb-1 pt-2"><a
                                                    href="{{ route('page.item', $item->item_slug) }}">{{ $item->item_title }}</a>
                                            </h2>

                                            @if (isset($item->state->state_slug) &&
                                                    !empty($item->state->state_slug) &&
                                                    $item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                                <span class="address">
                                                    <a
                                                        href="{{ route('page.city', ['state_slug' => $item->state->state_slug, 'city_slug' => $item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                                    <a
                                                        href="{{ route('page.state', ['state_slug' => $item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                                </span>
                                            @endif

                                            @php
                                                $popular_item_getCountRating = $item->getCountRating();
                                            @endphp
                                            @if ($popular_item_getCountRating > 0)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}"
                                                            data-id="rating_stars_{{ $item->item_slug }}"
                                                            data-rating="{{ $item->item_average_rating }}"></div>
                                                        <address class="mt-1">
                                                            @if ($popular_item_getCountRating == 1)
                                                                <span>{{ '(' . $popular_item_getCountRating . ' ' . __('review.frontend.review') . ')' }}</span>
                                                            @else
                                                                <span>{{ '(' . $popular_item_getCountRating . ' ' . __('review.frontend.reviews') . ')' }}</span>
                                                            @endif
                                                        </address>
                                                    </div>
                                                </div>
                                            @endif

                                            <hr class="item-box-hr item-box-index-nearby-hr">

                                            <div class="row mt-1 align-items-center">

                                                <div class="col-12 col-md-7 pr-0">
                                                    <div class="row align-items-center item-box-user-div">
                                                        <div class="col-3 item-box-user-img-div">
                                                            @if (empty($item->user->user_image))
                                                                <img src="{{ asset('frontend/images/placeholder/profile-' . intval($item->user->id % 10) . '.webp') }}"
                                                                    alt="Image" class="img-fluid rounded-circle">
                                                            @else
                                                                <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                                                    alt="{{ $item->user->name }}"
                                                                    class="img-fluid rounded-circle">
                                                            @endif
                                                        </div>
                                                        <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                            <div class="row pb-1">
                                                                <div class="col-12">
                                                                    {{-- <a class="decoration-none" href="{{ route('page.profile',encrypt($item->user->id)) }}"><span class="font-size-13">{{ str_limit($item->user->name, 14, '.') }}</span></a> --}}
                                                                    <a class="decoration-none"
                                                                        href="{{ route('page.profile', encrypt($item->user->id)) }}"><span
                                                                            class="font-size-13">{{ $item->user->name }}</span></a>
                                                                </div>
                                                            </div>
                                                            <div class="row line-height-1-0">
                                                                <div class="col-12">
                                                                    <span
                                                                        class="review">{{ $item->created_at->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div class="col-7 col-md-5 pl-0 text-right">
                                        @if ($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                            @if ($item->hasOpened())
                                                <span class="item-box-index-nearby-hour-span-opened">{{ __('item_hour.frontend-item-box-hour-opened') }}</span>
                                            @else
                                                <span class="item-box-index-nearby-hour-span-closed">{{ __('item_hour.frontend-item-box-hour-closed') }}</span>
                                            @endif
                                        @endif
                                    </div> --}}

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        @endif

                    </div>

                    {{-- <div class="row">
                        <div class="col-12">

                            {{ $pagination->links() }}
                        </div>
                    </div> --}}

                    @if ($ads_after_content->count() > 0)
                        @foreach ($ads_after_content as $ads_after_content_key => $ad_after_content)
                            <div class="row mt-5">
                                @if ($ad_after_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_after_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_after_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_after_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_after_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_after_content->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                </div>

                <div class="col-lg-6" style="display:none;">
                    <div class="sticky-top" id="mapid-box"></div>
                </div>

            </div>

        </div>
    </div>

    @if ($all_states->count() > 0)
        <!-- <div class="site-section bg-light">
                <div class="container">
                    <div class="row mb-5">
                        <div class="col-md-7 text-left border-primary">
                            <h2 class="font-weight-light text-primary">{{ __('frontend.categories.sub-title-2') }}</h2>
                        </div>
                    </div>
                    <div class="row mt-5">

                        @foreach ($all_states as $all_states_key => $state)
    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <a href="{{ route('page.state', ['state_slug' => $state->state_slug]) }}">{{ $state->state_name }}</a>
                            </div>
    @endforeach

                    </div>
                </div>
            </div> -->
    @endif

@endsection

@section('scripts')

    @if ($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="{{ asset('frontend/vendor/leaflet/leaflet.js') }}"></script>
    @endif

    @if ($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <!-- Youtube Background for Header -->
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif

    <script src="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('frontend.partials.bootstrap-select-locale')
    <script>
        $(document).ready(function() {

            "use strict";

            @if ($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
                /**
                 * Start Initial Youtube Background
                 */
                $("[data-youtube]").youtube_background();
                /**
                 * End Initial Youtube Background
                 */
            @endif

            /**
             * Start initial map box with OpenStreetMap
             */
            @if ($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)

                @if (count($paid_items) || count($free_items))

                    var window_height = $(window).height();
                    $('#mapid-box').css('height', window_height + 'px');

                    var map = L.map('mapid-box', {
                        zoom: 15,
                        scrollWheelZoom: true,
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    var bounds = [];
                    @foreach ($paid_items as $paid_items_key => $paid_item)
                        @if ($paid_item->item_type == \App\Item::ITEM_TYPE_REGULAR)

                            bounds.push([{{ $paid_item->item_lat }}, {{ $paid_item->item_lng }}]);
                            var marker = L.marker([{{ $paid_item->item_lat }}, {{ $paid_item->item_lng }}])
                                .addTo(map);

                            var popup_item_title = '{{ $paid_item->item_title }}';

                            @if ($paid_item->item_address_hide)
                                var popup_item_address =
                                    '{{ $paid_item->city->city_name . ', ' . $paid_item->state->state_name . ' ' . $paid_item->item_postal_code }}';
                            @else
                                var popup_item_address =
                                    '{{ $paid_item->item_address . ', ' . $paid_item->city->city_name . ', ' . $paid_item->state->state_name . ' ' . $paid_item->item_postal_code }}';
                            @endif
                            var popup_item_get_direction = '<a target="_blank" href="' +
                                '{{ 'https://www.google.com/maps/dir/?api=1&destination=' . $paid_item->item_lat . ',' . $paid_item->item_lng }}' +
                                '"><i class="fas fa-directions"></i> ' + '{{ __('google_map.get-directions') }}' +
                                '</a>';

                            @if ($paid_item->getCountRating() > 0)
                                var popup_item_rating = '{{ $paid_item->item_average_rating }}' + '/5';
                                var popup_item_reviews = ' - {{ $paid_item->getCountRating() }}' + ' ' +
                                    '{{ __('category_image_option.map.review') }}';
                            @else
                                var popup_item_rating = '';
                                var popup_item_reviews = '';
                            @endif

                            var popup_item_feature_image_link = '<img src="' +
                                '{{ !empty($paid_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $paid_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp') }}' +
                                '">';
                            var popup_item_link = '<a href="' +
                                '{{ route('page.item', $paid_item->item_slug) }}' + '" target="_blank">' +
                                popup_item_title + '</a>';

                            marker.bindPopup(popup_item_feature_image_link + "<br><br>" + popup_item_link + "<br>" +
                                popup_item_rating + popup_item_reviews + "<br>" + popup_item_address + '<br>' +
                                popup_item_get_direction, {
                                    minWidth: 226,
                                    maxWidth: 226
                                });
                        @endif
                    @endforeach

                    @foreach ($free_items as $free_items_key => $free_item)
                        @if ($free_item->item_type == \App\Item::ITEM_TYPE_REGULAR)

                            bounds.push([{{ $free_item->item_lat }}, {{ $free_item->item_lng }}]);
                            var marker = L.marker([{{ $free_item->item_lat }}, {{ $free_item->item_lng }}])
                                .addTo(map);

                            var popup_item_title = '{{ $free_item->item_title }}';

                            @if ($free_item->item_address_hide)
                                var popup_item_address =
                                    '{{ $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code }}';
                            @else
                                var popup_item_address =
                                    '{{ $free_item->item_address . ', ' . $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code }}';
                            @endif
                            var popup_item_get_direction = '<a target="_blank" href="' +
                                '{{ 'https://www.google.com/maps/dir/?api=1&destination=' . $free_item->item_lat . ',' . $free_item->item_lng }}' +
                                '"><i class="fas fa-directions"></i> ' + '{{ __('google_map.get-directions') }}' +
                                '</a>';

                            @if ($free_item->getCountRating() > 0)
                                var popup_item_rating = '{{ $free_item->item_average_rating }}' + '/5';
                                var popup_item_reviews = ' - {{ $free_item->getCountRating() }}' + ' ' +
                                    '{{ __('category_image_option.map.review') }}';
                            @else
                                var popup_item_rating = '';
                                var popup_item_reviews = '';
                            @endif

                            var popup_item_feature_image_link = '<img src="' +
                                '{{ !empty($free_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $free_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp') }}' +
                                '">';
                            var popup_item_link = '<a href="' +
                                '{{ route('page.item', $free_item->item_slug) }}' + '" target="_blank">' +
                                popup_item_title + '</a>';

                            marker.bindPopup(popup_item_feature_image_link + "<br><br>" + popup_item_link + "<br>" +
                                popup_item_rating + popup_item_reviews + "<br>" + popup_item_address + '<br>' +
                                popup_item_get_direction, {
                                    minWidth: 226,
                                    maxWidth: 226
                                });
                        @endif
                    @endforeach

                    if (bounds.length === 0) {
                        // Destroy mapid-box DOM since no regular listings found
                        $("#mapid-box").remove();
                    } else {
                        map.fitBounds(bounds, {
                            maxZoom: 11
                        });
                    }
                @endif

                $(".listing_for_map_hover").on('mouseover', function() {

                    var map_item_lat = this.getAttribute("data-map-lat");
                    var map_item_lng = this.getAttribute("data-map-lng");
                    var map_item_title = this.getAttribute("data-map-title");
                    var map_item_address = this.getAttribute("data-map-address");

                    var map_item_rating = '';
                    var map_item_reviews = parseInt(this.getAttribute("data-map-reviews"));

                    if (map_item_reviews > 0) {
                        map_item_rating = this.getAttribute("data-map-rating") + '/5';
                        map_item_reviews = ' - ' + this.getAttribute("data-map-reviews") + ' ' +
                            '{{ __('category_image_option.map.review') }}';
                    } else {
                        map_item_rating = '';
                        map_item_reviews = '';
                    }

                    var map_item_link = '<a href="' + this.getAttribute("data-map-link") +
                        '" target="_blank">' + map_item_title + '</a>';
                    var map_item_feature_image_link = '<img src="' + this.getAttribute(
                        "data-map-feature-image-link") + '">';
                    var map_item_get_direction =
                        '<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=' +
                        map_item_lat + ',' + map_item_lng + '"><i class="fas fa-directions"></i> ' +
                        '{{ __('google_map.get-directions') }}' + '</a>';

                    if (map_item_lat !== '' && map_item_lng !== '') {
                        map_item_lat = parseFloat(map_item_lat);
                        map_item_lng = parseFloat(map_item_lng);

                        var this_latlng = L.latLng(map_item_lat, map_item_lng);

                        var tooltipPopup = L.popup({
                                offset: L.point(0, -27),
                                minWidth: 226,
                                maxWidth: 226,
                                keepInView: true
                            })
                            .setLatLng(this_latlng)
                            .setContent(map_item_feature_image_link + "<br><br>" + map_item_link + "<br>" +
                                map_item_rating + map_item_reviews + "<br>" + map_item_address + "<br>" +
                                map_item_get_direction)
                            .openOn(map);
                    }
                });
            @endif
            /**
             * End initial map box with OpenStreetMap
             */

            /**
             * Start show more/less
             */
            //this will execute on page load(to be more specific when document ready event occurs)
            @if (count($filter_categories) == 0)
                if ($(".filter_category_div").length > 7) {
                    $(".filter_category_div:gt(7)").hide();
                    $(".show_more").show();
                } else {
                    $(".show_more").hide();
                }
            @else
                if ($(".filter_category_div").length > 7) {
                    $(".show_more").text("{{ __('listings_filter.show-less') }}");
                    $(".show_more").show();
                } else {
                    $(".show_more").hide();
                }
            @endif


            $(".show_more").on('click', function() {
                //toggle elements with class .ty-compact-list that their index is bigger than 2
                $(".filter_category_div:gt(7)").toggle();
                //change text of show more element just for demonstration purposes to this demo
                $(this).text() === "{{ __('listings_filter.show-more') }}" ? $(this).text(
                    "{{ __('listings_filter.show-less') }}") : $(this).text(
                    "{{ __('listings_filter.show-more') }}");
            });
            /**
             * End show more/less
             */


            /**
             * Start country, state, city selector
             */
            $('#filter_country').on('change', function() {
                $('#filter_state').html(
                    "<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#filter_state').selectpicker('refresh');
                if (this.value > 0) {
                    var ajax_url = '/ajax/states/' + this.value;
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        success: function(result) {
                            $('#filter_state').empty();
                            $('#filter_state').html(
                                "<option selected value='0'>{{ __('prefer_country.all-state') }}</option>"
                                );
                            $.each(JSON.parse(result), function(key, value) {
                                var state_id = value.id;
                                var state_name = value.state_name;
                                $('#filter_state').append('<option value="' + state_id +
                                    '">' + state_name + '</option>');
                            });
                            $('#filter_state').selectpicker('refresh');
                        }
                    });
                }
            });

            $('#filter_state').on('change', function() {
                $('#filter_city').html(
                    "<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#filter_city').selectpicker('refresh');
                if (this.value > 0) {
                    var ajax_url = '/ajax/cities/' + this.value;
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        success: function(result) {
                            $('#filter_city').empty();
                            $('#filter_city').html(
                                "<option selected value='0'>{{ __('prefer_country.all-city') }}</option>"
                                );
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $('#filter_city').append('<option value="' + city_id +
                                    '">' + city_name + '</option>');
                            });

                            $('#filter_city').selectpicker('refresh');
                        }
                    });
                }
            });

            @if (old('country_id'))
                var ajax_url_initial_states = '/ajax/states/{{ old('country_id') }}';
                jQuery.ajax({
                    url: ajax_url_initial_states,
                    method: 'get',
                    success: function(result) {
                        $('#filter_state').empty();
                        // $('#select_state_id').html("<option selected value='0'>{{ __('backend.article.select-state') }}</option>");
                        $.each(JSON.parse(result), function(key, value) {
                            var state_id = value.id;
                            var state_name = value.state_name;
                            $('#filter_state').append('<option value="' + state_id + '">' +
                                state_name + '</option>');
                        });
                        @if (old('state_id'))
                            $('#filter_state').val("{{ old('state_id', '') }}").selectpicker(
                                'refresh');
                            $('#filter_state').val("{{ old('state_id', '') }}").selectpicker(
                                'refresh');
                        @endif
                    }
                });
            @endif

            @if (old('state_id'))
                var ajax_url_initial_cities = '/ajax/cities/{{ old('state_id') }}';
                jQuery.ajax({
                    url: ajax_url_initial_cities,
                    method: 'get',
                    success: function(result) {
                        $('#filter_city').empty();
                        // $('#select_city_id').html("<option selected value='0'>{{ __('backend.article.select-city') }}</option>");
                        $.each(JSON.parse(result), function(key, value) {
                            var city_id = value.id;
                            var city_name = value.city_name;
                            $('#filter_city').append('<option value="' + city_id + '">' +
                                city_name + '</option>');
                        });
                        @if (old('city_id'))
                            $('#filter_city').val("{{ old('city_id', '') }}").selectpicker('refresh');
                            $('#filter_city').val("{{ old('city_id', '') }}").selectpicker('refresh');
                        @endif
                    }
                });
            @endif

            // $('#filter_country').trigger('change');

            /**
             * Start filter form submit
             */
            $("#filter_form_submit").on('click', function() {
                $("#filter_form").submit();
            });
            /**
             * End filter form submit
             */
        });
    </script>

    @if ($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_GOOGLE_MAP)
        <script>
            // Initial the google map
            function initMap() {

                @if (count($paid_items) || count($free_items))

                    var window_height = $(window).height();
                    $('#mapid-box').css('height', window_height + 'px');

                    var locations = [];

                    @foreach ($paid_items as $paid_items_key => $paid_item)
                        @if ($paid_item->item_type == \App\Item::ITEM_TYPE_REGULAR)

                            var popup_item_title = '{{ $paid_item->item_title }}';

                            @if ($paid_item->item_address_hide)
                                var popup_item_address =
                                    '{{ $paid_item->city->city_name . ', ' . $paid_item->state->state_name . ' ' . $paid_item->item_postal_code }}';
                            @else
                                var popup_item_address =
                                    '{{ $paid_item->item_address . ', ' . $paid_item->city->city_name . ', ' . $paid_item->state->state_name . ' ' . $paid_item->item_postal_code }}';
                            @endif
                            var popup_item_get_direction = '<a target="_blank" href="' +
                                '{{ 'https://www.google.com/maps/dir/?api=1&destination=' . $paid_item->item_lat . ',' . $paid_item->item_lng }}' +
                                '"><i class="fas fa-directions"></i> ' + '{{ __('google_map.get-directions') }}' + '</a>';

                            @if ($paid_item->getCountRating() > 0)
                                var popup_item_rating = '{{ $paid_item->item_average_rating }}' + '/5';
                                var popup_item_reviews = ' - {{ $paid_item->getCountRating() }}' + ' ' +
                                    '{{ __('category_image_option.map.review') }}';
                            @else
                                var popup_item_rating = '';
                                var popup_item_reviews = '';
                            @endif

                            var popup_item_feature_image_link = '<img src="' +
                                '{{ !empty($paid_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $paid_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp') }}' +
                                '">';
                            var popup_item_link = '<a href="' + '{{ route('page.item', $paid_item->item_slug) }}' +
                                '" target="_blank">' + popup_item_title + '</a>';

                            locations.push(["<div class='google_map_scrollFix'>" + popup_item_feature_image_link + "<br><br>" +
                                popup_item_link + "<br>" + popup_item_rating + popup_item_reviews + "<br>" +
                                popup_item_address + '<br>' + popup_item_get_direction + "</div>",
                                {{ $paid_item->item_lat }}, {{ $paid_item->item_lng }}
                            ]);
                        @endif
                    @endforeach

                    @foreach ($free_items as $free_items_key => $free_item)
                        @if ($free_item->item_type == \App\Item::ITEM_TYPE_REGULAR)

                            var popup_item_title = '{{ $free_item->item_title }}';

                            @if ($free_item->item_address_hide)
                                var popup_item_address =
                                    '{{ $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code }}';
                            @else
                                var popup_item_address =
                                    '{{ $free_item->item_address . ', ' . $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code }}';
                            @endif
                            var popup_item_get_direction = '<a target="_blank" href="' +
                                '{{ 'https://www.google.com/maps/dir/?api=1&destination=' . $free_item->item_lat . ',' . $free_item->item_lng }}' +
                                '"><i class="fas fa-directions"></i> ' + '{{ __('google_map.get-directions') }}' + '</a>';

                            @if ($free_item->getCountRating() > 0)
                                var popup_item_rating = '{{ $free_item->item_average_rating }}' + '/5';
                                var popup_item_reviews = ' - {{ $free_item->getCountRating() }}' + ' ' +
                                    '{{ __('category_image_option.map.review') }}';
                            @else
                                var popup_item_rating = '';
                                var popup_item_reviews = '';
                            @endif

                            var popup_item_feature_image_link = '<img src="' +
                                '{{ !empty($free_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $free_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp') }}' +
                                '">';
                            var popup_item_link = '<a href="' + '{{ route('page.item', $free_item->item_slug) }}' +
                                '" target="_blank">' + popup_item_title + '</a>';

                            locations.push(["<div class='google_map_scrollFix'>" + popup_item_feature_image_link + "<br><br>" +
                                popup_item_link + "<br>" + popup_item_rating + popup_item_reviews + "<br>" +
                                popup_item_address + '<br>' + popup_item_get_direction + "</div>",
                                {{ $free_item->item_lat }}, {{ $free_item->item_lng }}
                            ]);
                        @endif
                    @endforeach

                    var infowindow = null;
                    var infowindow_hover = null;

                    if (locations.length === 0) {
                        // Destroy mapid-box DOM since no regular listings found
                        $("#mapid-box").remove();
                    } else {
                        var map = new google.maps.Map(document.getElementById('mapid-box'), {
                            zoom: 12,
                            //center: new google.maps.LatLng(-33.92, 151.25),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });

                        //create empty LatLngBounds object
                        var bounds = new google.maps.LatLngBounds();
                        var infowindow = new google.maps.InfoWindow();

                        var marker, i;

                        for (i = 0; i < locations.length; i++) {
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                map: map
                            });

                            //extend the bounds to include each marker's position
                            bounds.extend(marker.position);

                            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                return function() {

                                    if (infowindow_hover) {
                                        infowindow_hover.close();
                                    }

                                    infowindow.setContent(locations[i][0]);
                                    infowindow.open(map, marker);
                                }
                            })(marker, i));
                        }

                        //now fit the map to the newly inclusive bounds
                        map.fitBounds(bounds);

                        var listener = google.maps.event.addListener(map, "idle", function() {
                            if (map.getZoom() > 12) map.setZoom(12);
                            google.maps.event.removeListener(listener);
                        });

                        // Start google map hover event
                        $(".listing_for_map_hover").on('mouseover', function() {

                            var map_item_lat = this.getAttribute("data-map-lat");
                            var map_item_lng = this.getAttribute("data-map-lng");
                            var map_item_title = this.getAttribute("data-map-title");
                            var map_item_address = this.getAttribute("data-map-address");

                            var map_item_rating = '';
                            var map_item_reviews = parseInt(this.getAttribute("data-map-reviews"));

                            if (map_item_reviews > 0) {
                                map_item_rating = this.getAttribute("data-map-rating") + '/5';
                                map_item_reviews = ' - ' + this.getAttribute("data-map-reviews") + ' ' +
                                    '{{ __('category_image_option.map.review') }}';
                            } else {
                                map_item_rating = '';
                                map_item_reviews = '';
                            }

                            var map_item_link = '<a href="' + this.getAttribute("data-map-link") +
                                '" target="_blank">' + map_item_title + '</a>';
                            var map_item_feature_image_link = '<img src="' + this.getAttribute(
                                "data-map-feature-image-link") + '">';
                            var map_item_get_direction =
                                '<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=' +
                                map_item_lat + ',' + map_item_lng + '"><i class="fas fa-directions"></i> ' +
                                '{{ __('google_map.get-directions') }}' + '</a>';

                            if (map_item_lat !== '' && map_item_lng !== '') {
                                var center = new google.maps.LatLng(map_item_lat, map_item_lng);
                                var contentString = "<div class='google_map_scrollFix'>" + map_item_feature_image_link +
                                    "<br><br>" + map_item_link + "<br>" + map_item_rating + map_item_reviews + "<br>" +
                                    map_item_address + "<br>" + map_item_get_direction + "</div>";

                                if (infowindow_hover) {
                                    infowindow_hover.close();
                                }
                                if (infowindow) {
                                    infowindow.close();
                                }

                                infowindow_hover = new google.maps.InfoWindow({
                                    content: contentString,
                                    position: center,
                                    pixelOffset: new google.maps.Size(0, -45)
                                });

                                infowindow_hover.open({
                                    map,
                                    shouldFocus: true,
                                });
                            }
                        });
                        // End google map hover event
                    }
                @endif
            }
        </script>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js??v=quarterly&key={{ $site_global_settings->setting_site_map_google_api_key }}&callback=initMap">
        </script>
    @endif

@endsection
