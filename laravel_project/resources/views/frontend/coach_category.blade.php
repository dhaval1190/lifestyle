@extends('frontend.layouts.app')

@section('styles')

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <link href="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ asset('frontend/images/placeholder/header-inner.webp') }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: {{ $site_innerpage_header_background_color }};">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ $site_innerpage_header_background_image }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: #333333;" data-stellar-background-ratio="0.1">
    @endif

        @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            <div data-youtube="{{ $site_innerpage_header_background_youtube_video }}"></div>
        @endif

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">

                <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">


                    <div class="row justify-content-center mt-5">
                        <div class="col-md-8 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ $category->category_name }}</h1>
                            <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ $category->category_description }}</p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">


            @if($children_categories->count() > 0)
            <div class="overlap-category mb-5">
                <div class="text-center">
                    <h2 class="font-weight-light text-primary">What category would you find helpful?</h2>
                </div>
                <div class="row align-items-stretch no-gutters justify-content-center">
                    @foreach( $children_categories as $key => $children_category )
                    <?php
                        if($children_category->category_slug == 'entrepreneurial' || $children_category->category_slug == 'productivity'){
                            continue;
                        }
                    ?>
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">

                            @if($children_category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON)
                                <a href="{{ route('page.coaches.category', $children_category->category_slug) }}" class="popular-category h-100 decoration-none">
                                    <span class="icon">
                                        <span>
                                            @if($children_category->category_icon)
                                                <i class="{{ $children_category->category_icon }}"></i>
                                            @else
                                                <i class="fa-solid fa-heart"></i>
                                            @endif
                                        </span>
                                    </span>

                                    <span class="caption d-block">{{ $children_category->category_name }}</span>
                                </a>
                            @elseif($children_category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_IMAGE)
                                <a href="{{ route('page.coaches.category', $children_category->category_slug) }}" class="popular-category h-100 decoration-none image-category">
                                    <span class="icon image-category-span">
                                        <span>
                                            @if($children_category->category_image)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('category/'. $children_category->category_image) }}" alt="Image" class="img-fluid rounded image-category-img">
                                            @else
                                                <img src="{{ asset('frontend/images/placeholder/category-image.webp') }}" alt="Image" class="img-fluid rounded image-category-img">
                                            @endif
                                        </span>
                                    </span>
                                    <span class="caption d-block image-category-caption">{{ $children_category->category_name }}</span>
                                </a>
                            @endif

                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Start Filter -->
            <form method="GET" action="{{ route('page.coaches.category', ['category_slug' => $category->category_slug]) }}" id="filter_form">
                <div class="row pt-3 pb-3 ml-1 mr-1 mb-5 rounded border">
                    <div class="col-12">
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
                                        <li class="breadcrumb-item"><a href="{{ route('page.categories') }}">{{ __('frontend.item.all-categories') }}</a></li>
                                        @foreach($parent_categories as $key => $parent_category)
                                            <li class="breadcrumb-item"><a href="{{ route('page.coaches.category', $parent_category->category_slug) }}">{{ $parent_category->category_name }}</a></li>
                                        @endforeach
                                        <li class="breadcrumb-item active" aria-current="page">{{ $category->category_name }}</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <div class="row form-group align-items-center">
                            <div class="col-12 col-md-3 mt-2">
                                <select class="selectpicker form-control @error('filter_sort_by') is-invalid @enderror" name="filter_sort_by" id="filter_sort_by">
                                    <option value="{{ \App\Item::ITEMS_SORT_BY_NEWEST_CREATED }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_NEWEST_CREATED ? 'selected' : '' }}>{{ __('listings_filter.sort-by-newest') }}</option>
                                    <option value="{{ \App\Item::ITEMS_SORT_BY_OLDEST_CREATED }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_OLDEST_CREATED ? 'selected' : '' }}>{{ __('listings_filter.sort-by-oldest') }}</option>
                                    <option value="{{ \App\Item::ITEMS_SORT_BY_HIGHEST_RATING }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_HIGHEST_RATING ? 'selected' : '' }}>{{ __('listings_filter.sort-by-highest') }}</option>
                                    <option value="{{ \App\Item::ITEMS_SORT_BY_LOWEST_RATING }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_LOWEST_RATING ? 'selected' : '' }}>{{ __('listings_filter.sort-by-lowest') }}</option>
                                    <option value="{{ \App\Item::ITEMS_SORT_BY_NEARBY_FIRST }}" {{ $filter_sort_by == \App\Item::ITEMS_SORT_BY_NEARBY_FIRST ? 'selected' : '' }}>{{ __('theme_directory_hub.filter-sort-by-nearby-first') }}</option>
                                </select>
                                @error('filter_sort_by')
                                <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3">
                                <select class="selectpicker form-control @error('filter_preferred_pronouns') is-invalid @enderror" name="filter_preferred_pronouns" id="filter_preferred_pronouns">
                                    <option value="0" {{ empty($filter_preferred_pronouns) ? 'selected' : '' }}>Any Preferred Pronouns</option>
                                    @foreach(\App\User::PREFERRED_PRONOUNS as $prkey => $pronoun)
                                        @php
                                        $selected = '';
                                        if(request()->filter_preferred_pronouns == $prkey){
                                        $selected = "selected";
                                        }
                                        @endphp
                                        <option value="{{ $prkey }}" {{ $selected }} >{{ $pronoun }}</option>
                                    @endforeach
                                </select>
                                @error('filter_preferred_pronouns')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3 pl-0">
                                <select class="selectpicker form-control @error('filter_working_type') is-invalid @enderror" name="filter_working_type" id="filter_working_type">
                                    <option value="0" {{ empty($filter_working_type) ? 'selected' : '' }}>All Working Type</option>
                                    <option value="person-to-person" {{ $filter_working_type == "person-to-person" ? 'selected' : '' }}>Person-to-Person</option>
                                    <option value="remotely" {{ $filter_working_type == "remotely" ? 'selected' : '' }}>Remotely</option>
                                    <option value="hybrid" {{ $filter_working_type == "hybrid" ? 'selected' : '' }}>Hybrid (Person-to-Person/Remotely)</option>
                                </select>
                                @error('filter_working_type')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12 col-md-3 pl-lg-0">
                                <select class="selectpicker form-control @error('filter_hourly_rate') is-invalid @enderror" name="filter_hourly_rate" id="filter_hourly_rate">
                                    <option value="0" {{ empty($filter_hourly_rate) ? 'selected' : '' }}>All Price Range</option>
                                    <option value="$" {{ $filter_hourly_rate == '$' ? 'selected' : '' }}>$ (Less than 125.00)</option>
                                    <option value="$$" {{ $filter_hourly_rate == '$$' ? 'selected' : '' }}>$$ (125-225)</option>
                                    <option value="$$$" {{ $filter_hourly_rate == '$$$' ? 'selected' : '' }}>$$$ (225-325)</option>
                                    <option value="$$$$" {{ $filter_hourly_rate == '$$$$' ? 'selected' : '' }}>$$$$ (More than 325.00)</option>
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
                                <select class="selectpicker form-control @error('filter_country') is-invalid @enderror" name="filter_country" id="filter_country" data-live-search="true">
                                        <option value="0" {{ empty($filter_country) ? 'selected' : '' }}>{{ __('prefer_country.all-country') }}</option>
                                        @foreach($all_countries as $all_countries_key => $country)
                                            <option value="{{ $country->id }}" {{ $filter_country == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('filter_country')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                        </div>
                        <div class="col-12 col-md-3 pl-3">
                            <select class="selectpicker form-control @error('filter_state') is-invalid @enderror" name="filter_state" id="filter_state" data-live-search="true">
                                <option value="0" {{ empty($filter_state) ? 'selected' : '' }}>{{ __('prefer_country.all-state') }}</option>
                                @foreach($all_states as $all_states_key => $state)
                                    <option value="{{ $state->id }}" {{ $filter_state == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                @endforeach
                            </select>
                            @error('filter_state')
                            <span class="invalid-tooltip">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-12 col-md-3 pl-0">
                            <select class="selectpicker form-control @error('filter_city') is-invalid @enderror" name="filter_city" id="filter_city" data-live-search="true">
                                <option value="0" {{ empty($filter_city) ? 'selected' : '' }}>{{ __('prefer_country.all-city') }}</option>
                                @foreach($all_cities as $all_cities_key => $city)
                                    <option value="{{ $city->id }}" {{ $filter_city == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
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

                        @if($children_categories->count() > 0)

                        <div class="row">

                            @foreach($children_categories as $children_categories_key => $children_category)
                            <?php
                                if($children_category->category_name == 'Entrepreneurial' || $children_category->category_name == 'Productivity') continue;
                                ?>
                                <div class="col-6 col-sm-4 col-md-3">
                                    <div class="form-check filter_category_div">
                                        <input {{ in_array($children_category->id, $filter_categories) ? 'checked' : '' }} name="filter_categories[]" class="form-check-input" type="checkbox" value="{{ $children_category->id }}" id="filter_categories_{{ $children_category->id }}">
                                        <label class="form-check-label" for="filter_categories_{{ $children_category->id }}">
                                            {{ $children_category->category_name }}
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
                                <a href="javascript:void(0)" class="show_more text-sm">{{ __('listings_filter.show-more') }}</a>
                            </div>
                        </div>
                        <hr>

                        @endif

                        <div class="row">
                            <div class="col-12 text-right">
                                <a class="btn btn-sm btn-outline-primary rounded" href="{{ route('page.coaches.category', ['category_slug' => $category->category_slug]) }}">
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

                <div class="col-lg-12">

                    <div class="row mb-4">
                        <div class="col-md-12 text-left border-primary">
                            <h2 class="font-weight-light text-primary">{{ $category->category_name }} {{ "Coaches" }}</h2>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12 text-left">
                            <strong>{{ number_format($all_coaches_count) }}</strong>
                            {{ __('theme_directory_hub.filter-results') }}
                        </div>
                    </div>

                    @if($ads_before_content->count() > 0)
                        @foreach($ads_before_content as $ads_before_content_key => $ad_before_content)
                            <div class="row mb-5">
                                @if($ad_before_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
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
                        {{-- @php
                        print_r($all_coaches);exit;
                        @endphp --}}
                        @if($all_coaches->count() > 0)
                            @foreach($all_coaches as $all_coaches_key => $coach)
                                <div class="col-lg-4 col-md-6 col-12 col-xl-4 padding-top-bottom-set-slider carousel_design_set_all_coaches">
                                    <div class="profile-card js-profile-card">
                                        <div class="profile-card__img">
                                        <a href="{{ route('page.profile', encrypt($coach->id)) }}">
                                            @if(empty($coach->user_image))
                                                <img src="{{ asset('frontend/images/placeholder/profile-'. intval($coach->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                            @else
                                                <img src="{{ Storage::disk('public')->url('user/' . $coach->user_image) }}" alt="{{ $coach->name }}" class="img-fluid rounded-circle">
                                            @endif
                                        </a>
                                        </div>

                                        <div class="profile-card__cnt js-profile-cnt">
                                            <a href="{{ route('page.profile', encrypt($coach->id)) }}">
                                                <div class="profile-card__name"><span class="font-size-13">{{ $coach->name }}</span></a>
                                            </div>
                                            <!-- <div class="profile-card__txt">
                                                {{ $coach->email }}
                                            </div> -->
                                            <div class="profile-card__txt">
                                                <!-- <span class="font-size-13" @if(strlen($coach->email) > 25)style="word-break: break-all @endif">{{ $coach->email }}</span> -->
                                                <div class="d-block d-md-flex listing vertical parent_set_listing" style="min-height:0px;">
                                                        @if(isset($coach->category_parent_name) && !empty($coach->category_parent_name))
                                                            @foreach($coach->category_parent_name as $item_all_categories_key => $category)                                            
                                                                <span class="category">
                                                                    @if(!empty($category->category_icon))
                                                                        <i class="{{ $category->category_icon }}"></i>
                                                                    @else
                                                                        <i class="fa-solid fa-heart"></i>
                                                                    @endif
                                                                    {{ $category->category_name }}
                                                                </span>
                                                            @endforeach
                                                        @endif
                                                        @if(!empty($coach->category_icon_one))
                                                            <p>
                                                            @foreach($coach->category_icon_one as $icon_key => $icon)
                                                            <a href="{{ route('page.coaches.category', $coach->category_slug_one[$icon_key]) }}">
                                                            <span class="category_child">
                                                                <i class="{{$icon}}"></i>  {{$coach->category_name_one[$icon_key]}}
                                                            </span>
                                                            </a>
                                                            @endforeach
                                                            </p>
                                                        @endif
                                                </div>
                                                    @php $get_count_rating = $coach->getProfileCountRating(); @endphp
                                                <div class="pl-0 m_auto_set rating_stars rating_stars_{{ $coach->id }}" data-id="rating_stars_{{ $coach->id }}" data-rating="{{ $coach->profile_average_rating}}"></div>
                                                    <address class="mt-1">
                                                        @if($get_count_rating == 1)
                                                            {{ '(' . $get_count_rating . ' ' . __('review.frontend.review') . ')' }}
                                                        @else
                                                            {{ '(' . $get_count_rating . ' ' . __('review.frontend.reviews') . ')' }}
                                                        @endif
                                                    </address>
                                            </div>
                                            <div class="profile-card-loc">
                                                <span class="profile-card-loc__txt">{{ str_limit($coach->company_name, 45, '...') }}</span>
                                            </div>                                                   
                                            <div class="social_content">
                                                <div class="shareButton main">
                                                    <svg class="share" style="width: 24px; height: 24px"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M18,16.08C17.24,16.08 16.56,16.38 16.04,16.85L8.91,12.7C8.96,12.47 9,12.24 9,12C9,11.76 8.96,11.53 8.91,11.3L15.96,7.19C16.5,7.69 17.21,8 18,8A3,3 0 0,0 21,5A3,3 0 0,0 18,2A3,3 0 0,0 15,5C15,5.24 15.04,5.47 15.09,5.7L8.04,9.81C7.5,9.31 6.79,9 6,9A3,3 0 0,0 3,12A3,3 0 0,0 6,15C6.79,15 7.5,14.69 8.04,14.19L15.16,18.34C15.11,18.55 15.08,18.77 15.08,19C15.08,20.61 16.39,21.91 18,21.91C19.61,21.91 20.92,20.61 20.92,19A2.92,2.92 0 0,0 18,16.08Z" />
                                                    </svg>
                                                    <svg class="check" style="width: 24px; height: 24px"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z" />
                                                    </svg>
                                                    <svg class="close" style="width: 24px; height: 24px"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                                    </svg>
                                                </div>
                                                <div class="icons">
                                                    @if($coach->facebook)
                                                        <a href="{{ $coach->facebook }}" target="_blank"><i
                                                        class="fa fa-facebook-f social_icon_design"></i></a>
                                                    @endif
                                                    @if($coach->youtube)
                                                        <a href="{{ $coach->youtube }}" target="_blank">
                                                        <i class="fa fa-youtube social_icon_design"></i></a>
                                                    @endif
                                                    @if($coach->instagram)
                                                        <a href="https://instagram.com/_u/{{ $coach->instagram }}" target="_blank">
                                                        <i class="fa fa-instagram social_icon_design"></i></a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-12">
                          <div class="pagination_set_sm_center">
                            {{ $all_coaches->links() }}
                          </div>
                        </div>
                    </div>

                        @if($ads_after_content->count() > 0)
                            @foreach($ads_after_content as $ads_after_content_key => $ad_after_content)
                                <div class="row mt-5">
                                    @if($ad_after_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
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

@endsection

@section('scripts')

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="{{ asset('frontend/vendor/leaflet/leaflet.js') }}"></script>
    @endif

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <!-- Youtube Background for Header -->
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif

    <script src="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('frontend.partials.bootstrap-select-locale')
    <script>

        $(document).ready(function(){

            "use strict";          

            /**
             * Start show more/less
             */
            //this will execute on page load(to be more specific when document ready event occurs)
            @if(count($filter_categories) == 0)
            if ($(".filter_category_div").length > 8)
            {
                $(".filter_category_div:gt(7)").hide();
                $(".show_more").show();
            }
            else
            {
                $(".show_more").hide();
            }
            @else
            if ($(".filter_category_div").length > 8)
            {
                $(".show_more").text("{{ __('listings_filter.show-less') }}");
                $(".show_more").show();
            }
            else
            {
                $(".show_more").hide();
            }
            @endif

            $(".show_more").on('click', function() {
                //toggle elements with class .ty-compact-list that their index is bigger than 2
                $(".filter_category_div:gt(7)").toggle();
                //change text of show more element just for demonstration purposes to this demo
                $(this).text() === "{{ __('listings_filter.show-more') }}" ? $(this).text("{{ __('listings_filter.show-less') }}") : $(this).text("{{ __('listings_filter.show-more') }}");
            });
            /**
             * End show more/less
             */


            /**
             * Start Country state city selector in filter
             */
            $('#filter_country').on('change', function() {
                $('#filter_state').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                $('#filter_state').selectpicker('refresh');
                if(this.value > 0) {
                    var ajax_url = '/ajax/states/' + this.value;
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        success: function(result){
                            $('#filter_state').empty();
                            $('#filter_state').html("<option selected value='0'>{{ __('prefer_country.all-state')}}</option>");
                            $.each(JSON.parse(result), function(key, value) {
                                var state_id = value.id;
                                var state_name = value.state_name;
                                $('#filter_state').append('<option value="'+ state_id +'">' + state_name + '</option>');
                            });
                            $('#filter_state').selectpicker('refresh');
                        }
                    });
                }
                
            });
            $('#filter_state').on('change', function() {

                if(this.value > 0)
                {
                    $('#filter_city').html("<option selected>{{ __('prefer_country.loading-wait') }}</option>");
                    $('#filter_city').selectpicker('refresh');

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
                            console.log(result);
                            $('#filter_city').html("<option value='0'>{{ __('prefer_country.all-city') }}</option>");
                            $('#filter_city').selectpicker('refresh');
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $('#filter_city').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                            $('#filter_city').selectpicker('refresh');
                        }});
                }
                else
                {
                    $('#filter_city').html("<option value='0'>{{ __('prefer_country.all-city') }}</option>");
                    $('#filter_city').selectpicker('refresh');
                }

            });
            /**
             * End state selector in filter
             */

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

@endsection