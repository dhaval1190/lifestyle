@extends('frontend.layouts.app')

@section('styles')
    <link rel="preload" href="{{ asset('frontend/images/placeholder/header-1.webp') }}" as="image">
    <style>
        #nda_modal_content{      
            height: 800px;
            overflow-y: scroll;
        }
    </style>
@endsection

@section('content')

    @if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        <div class="site-blocks-cover overlay" style="background-image: url( {{ asset('frontend/images/placeholder/health-wellness-coach-with-client.jpg') }});">

    @elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_COLOR)
        <div class="site-blocks-cover overlay" style="background-color: {{ $site_homepage_header_background_color }};">

    @elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
        <div class="site-blocks-cover overlay" style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_homepage_header_background_image) }});">

    @elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div class="site-blocks-cover overlay" style="background-color: #333333;">
    @endif

            @if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            <div data-youtube="{{ $site_homepage_header_background_youtube_video }}"></div>
            @endif

    <div class="container">

        <!-- Start hero section desktop view-->
        <div class="row align-items-center justify-content-center text-center d-none d-md-flex">

            <div class="col-md-12">
                <div class="row justify-content-center mb-1">
                    <div class="col-md-12 text-center">
                        <h1 class="" data-aos="fade-up" style="color: {{ $site_homepage_header_title_font_color }};">{{ __('frontend.homepage.title') }}</h1>
                        <p data-aos="fade-up" data-aos-delay="100" style="color: {{ $site_homepage_header_paragraph_font_color }};">{{ __('frontend.homepage.description') }}</p>
                    </div>
                </div>
                <div class="form-search-wrap" data-aos="fade-up" data-aos-delay="200">
                    @include('frontend.partials.search.head')
                </div>
                <div class="row justify-content-center mt-5">
                    <div class="col-md-6 text-center">
                        <a href="{{ route('page.coaches') }}" class="btn btn-primary btn-lg btn-block rounded text-white" style="font-size:30px">
                            {{ __('I want to find a great fit coach!') }}
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <!-- End hero section desktop view-->

        <!-- Start hero section mobile view-->
        <div class="row align-items-center justify-content-center text-center d-md-none mt-5">

            <div class="col-md-12">
                <div class="row justify-content-center mb-1">
                    <div class="col-md-10 text-center">
                        <h1 class="" data-aos="fade-up">{{ __('frontend.homepage.title') }}</h1>
                        <p data-aos="fade-up" data-aos-delay="100">{{ __('frontend.homepage.description') }}</p>
                    </div>
                </div>
                <div class="form-search-wrap" data-aos="fade-up" data-aos-delay="200">
                    @include('frontend.partials.search.head')
                </div>
            </div>

        </div>
        <!-- End hero section mobile view-->

    </div>
</div>

<div class="site-section bg-light">
    <div class="container">

        <!-- Start categories section desktop view-->
        <div class="overlap-category mb-4 d-none d-md-block">
            <div class="text-center">
                <h2 class="font-weight-light text-primary">I'm looking for information that will help me with...</h2>
            </div>
            <div class="row align-items-stretch no-gutters justify-content-center">

                @php
                    $categories_count = $categories->count();
                    $is_login = isset(Auth::user()->id) && !empty(Auth::user()->id) && (Auth::user()->role_id == 2) ? 1 : 0;
                    $is_terms_read = isset(Auth::user()->is_terms_read) && !empty(Auth::user()->is_terms_read) ? 1 : 0;
                @endphp

                @if($categories_count > 0)
                    @foreach($categories as $categories_key => $category)
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">

                            @if($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON)
                                <a href="{{ route('page.category', $category->category_slug) }}" class="popular-category h-100 decoration-none">
                                    <span class="icon">
                                        <span>
                                            @if($category->category_icon)
                                                <i class="{{ $category->category_icon }}"></i>
                                            @else
                                                <i class="fa-solid fa-heart"></i>
                                            @endif
                                        </span>
                                    </span>

                                    <span class="caption d-block">{{ $category->category_name }}</span>
                                </a>
                            @elseif($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_IMAGE)
                                <a href="{{ route('page.category', $category->category_slug) }}" class="popular-category h-100 decoration-none image-category">
                                    <span class="icon image-category-span">
                                        <span>
                                            @if($category->category_image)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('category/'. $category->category_image) }}" alt="Image" class="img-fluid rounded image-category-img">
                                            @else
                                                <img src="{{ asset('frontend/images/placeholder/category-image.webp') }}" alt="Image" class="img-fluid rounded image-category-img">
                                            @endif
                                        </span>
                                    </span>
                                    <span class="caption d-block image-category-caption">{{ $category->category_name }}</span>
                                </a>
                            @endif

                        </div>
                    @endforeach
                @else
                    <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                        <p>{{ __('frontend.homepage.no-categories') }}</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- End categories section desktop view-->

        <!-- Start categories section mobile view-->
        <div class="overlap-category-sm mb-4 d-md-none">
            <div class="row align-items-stretch no-gutters justify-content-center">

                @if($categories_count > 0)
                    @foreach($categories as $categories_key => $category)
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                            @if($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON)
                                <a href="{{ route('page.category', $category->category_slug) }}" class="popular-category h-100 decoration-none">
                                    <span class="icon">
                                        <span>
                                            @if($category->category_icon)
                                                <i class="{{ $category->category_icon }}"></i>
                                            @else
                                                <i class="fa-solid fa-heart"></i>
                                            @endif
                                        </span>
                                    </span>

                                    <span class="caption d-block">{{ $category->category_name }}</span>
                                </a>
                            @elseif($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_IMAGE)
                                <a href="{{ route('page.category', $category->category_slug) }}" class="popular-category h-100 decoration-none image-category">
                                    <span class="icon image-category-span">
                                        <span>
                                            @if($category->category_image)
                                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('category/'. $category->category_image) }}" alt="Image" class="img-fluid rounded image-category-img">
                                            @else
                                                <img src="{{ asset('frontend/images/placeholder/category-image.webp') }}" alt="Image" class="img-fluid rounded image-category-img">
                                            @endif
                                        </span>
                                    </span>
                                    <span class="caption d-block image-category-caption">{{ $category->category_name }}</span>
                                </a>
                            @endif
                        </div>
                    @endforeach

                @else
                    <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                        <p>{{ __('frontend.homepage.no-categories') }}</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- End categories section mobile view-->

        <div class="row mt-5 mb-5">
            <div class="col-12 text-center">
                <a href="{{ route('page.categories') }}" class="btn btn-primary rounded text-white">
                    <i class="fas fa-th"></i>
                    {{ __('frontend.homepage.all-categories') }}
                </a>
            </div>
        </div>
        @if($paid_items->count() > 0)
            <div class="row mb-4">
                <div class="col-md-7 text-left border-primary">
                    <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.featured-ads') }}</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12  block-13">
                    <div class="owl-carousel nonloop-block-13">
                            @foreach($paid_items as $paid_items_key => $item)
                                <div class="d-block d-md-flex listing vertical">
                                    <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})"></a>
                                    <div class="lh-content">

                                        @php
                                        $paid_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                        @endphp
                                        @foreach($paid_item_getAllCategories as $item_all_categories_key => $category)
                                            <a href="{{ route('page.category', $category->category_slug) }}">
                                                <span class="category">
                                                    @if(!empty($category->category_icon))
                                                        <i class="{{ $category->category_icon }}"></i>
                                                    @else
                                                        <i class="fa-solid fa-heart"></i>
                                                    @endif
                                                    {{ $category->category_name }}
                                                </span>
                                            </a>
                                        @endforeach

                                        @php
                                        $paid_item_allCategories_count = $item->allCategories()->count();
                                        @endphp
                                        @if($paid_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                            <span class="category">{{ __('categories.and') . " " . strval($paid_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . " " . __('categories.more') }}</span>
                                        @endif

                                        <h3 class="pt-2"><a href="{{ route('page.item', $item->item_slug) }}">{{ str_limit($item->item_title, 44, '...') }}</a></h3>

                                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                        <address>
                                            <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                            <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                        </address>
                                        @endif

                                        @php
                                        $paid_item_getCountRating = $item->getCountRating();
                                        @endphp
                                        @if($paid_item_getCountRating > 0)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->item_average_rating }}"></div>
                                                    <address class="mt-1">
                                                        @if($paid_item_getCountRating == 1)
                                                            {{ '(' . $paid_item_getCountRating . ' ' . __('review.frontend.review') . ')' }}
                                                        @else
                                                            {{ '(' . $paid_item_getCountRating . ' ' . __('review.frontend.reviews') . ')' }}
                                                        @endif
                                                    </address>
                                                </div>
                                            </div>
                                        @endif

                                        <hr class="item-box-hr">

                                        <div class="row align-items-center">

                                            <div class="col-5 col-md-7 pr-0">
                                                <div class="row align-items-center item-box-user-div">
                                                    <div class="col-3 item-box-user-img-div">
                                                        @if(empty($item->user->user_image))
                                                            <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                                        @else
                                                            <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                                        @endif
                                                    </div>
                                                    <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                        <div class="row pb-1">
                                                            <div class="col-12">
                                                                <a class="decoration-none" href="{{ route('page.profile', encrypt($item->user->id)) }}"><span class="font-size-13">{{ str_limit($item->user->name, 12, '.') }}</span></a>
                                                            </div>
                                                        </div>
                                                        <div class="row line-height-1-0">
                                                            <div class="col-12">
                                                                <span class="review">{{ $item->created_at->diffForHumans() }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- <div class="col-7 col-md-5 pl-0 text-right">
                                                @if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                                    @if($item->hasOpened())
                                                        <span class="item-box-hour-span-opened">{{ __('item_hour.frontend-item-box-hour-opened') }}</span>
                                                    @else
                                                        <span class="item-box-hour-span-closed">{{ __('item_hour.frontend-item-box-hour-closed') }}</span>
                                                    @endif
                                                @endif
                                            </div> --}}

                                        </div>

                                    </div>
                                </div>
                            @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.nearby-listings') }}</h2>
                <p class="color-black-opacity-5">{{ __('frontend.homepage.popular-listings') }}</p>
            </div>
        </div>

        <div class="row">

            @if($popular_items->count() > 0)
                @foreach($popular_items as $popular_items_key => $item)
                    <div class="col-md-6 mb-4 mb-lg-4 col-lg-4">

                        <div class="listing-item listing">
                            <div class="listing-image">
                                <img src="{{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }}" alt="Image" class="img-fluid">
                            </div>
                            <div class="listing-item-content">

                                @php
                                    $popular_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                @endphp
                                @foreach($popular_item_getAllCategories as $item_all_categories_key => $category)
                                    <a class="px-3 mb-3 category" href="{{ route('page.category', $category->category_slug) }}">
                                        @if(!empty($category->category_icon))
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
                                @if($popular_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                    <span class="category">{{ __('categories.and') . " " . strval($popular_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . " " . __('categories.more') }}</span>
                                @endif

                                <h2 class="mb-1 pt-2"><a href="{{ route('page.item', $item->item_slug) }}">{{ $item->item_title }}</a></h2>

                                @if(isset($item->state->state_slug) && !empty($item->state->state_slug) && $item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                <span class="address">
                                    <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                    <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                </span>
                                @endif

                                @php
                                    $popular_item_getCountRating = $item->getCountRating();
                                @endphp
                                @if($popular_item_getCountRating > 0)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->item_average_rating }}"></div>
                                            <address class="mt-1">
                                                @if($popular_item_getCountRating == 1)
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

                                    <div class="col-5 col-md-7 pr-0">
                                        <div class="row align-items-center item-box-user-div">
                                            <div class="col-3 item-box-user-img-div">
                                                @if(empty($item->user->user_image))
                                                    <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                                @else
                                                    <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                                @endif
                                            </div>
                                            <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                <div class="row pb-1">
                                                    <div class="col-12">
                                                        <a class="decoration-none" href="{{ route('page.profile',encrypt($item->user->id)) }}"><span class="font-size-13">{{ str_limit($item->user->name, 14, '.') }}</span></a>
                                                    </div>
                                                </div>
                                                <div class="row line-height-1-0">
                                                    <div class="col-12">
                                                        <span class="review">{{ $item->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-7 col-md-5 pl-0 text-right">
                                        @if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                            @if($item->hasOpened())
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
    </div>
</div>


<div class="site-section bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.recent-listings') }}</h2>
            </div>
        </div>
        <div class="row mt-5">

            @if($latest_items->count() > 0)
                @foreach($latest_items as $latest_items_key => $item)
                    <div class="col-lg-6">
                        <div class="d-block d-md-flex listing">
                            <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})"></a>
                            <div class="lh-content">

                                @php
                                    $latest_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                @endphp
                                @foreach($latest_item_getAllCategories as $item_all_categories_key => $category)
                                    <a href="{{ route('page.category', $category->category_slug) }}">
                                        <span class="category">
                                             @if(!empty($category->category_icon))
                                                <i class="{{ $category->category_icon }}"></i>
                                             @else
                                                <i class="fa-solid fa-heart"></i>
                                            @endif
                                            {{ $category->category_name }}
                                        </span>
                                    </a>
                                @endforeach

                                @php
                                    $latest_item_allCategories_count = $item->allCategories()->count();
                                @endphp
                                @if($latest_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                    <span class="category">{{ __('categories.and') . " " . strval($latest_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . " " . __('categories.more') }}</span>
                                @endif

                                <h3 class="pt-2" style="word-break: break-all;"><a href="{{ route('page.item', $item->item_slug) }}">{{ $item->item_title }}</a></h3>

                                @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                <address>
                                    <a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                    <a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                </address>
                                @endif

                                @php
                                    $latest_item_getCountRating = $item->getCountRating();
                                @endphp
                                @if($latest_item_getCountRating > 0)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}" data-id="rating_stars_{{ $item->item_slug }}" data-rating="{{ $item->item_average_rating }}"></div>
                                            <address class="mt-1">
                                                @if($latest_item_getCountRating == 1)
                                                    {{ '(' . $latest_item_getCountRating . ' ' . __('review.frontend.review') . ')' }}
                                                @else
                                                    {{ '(' . $latest_item_getCountRating . ' ' . __('review.frontend.reviews') . ')' }}
                                                @endif
                                            </address>
                                        </div>
                                    </div>
                                @endif

                                <hr class="item-box-hr">

                                <div class="row align-items-center">

                                    <div class="col-5 col-md-7 pr-0">
                                        <div class="row align-items-center item-box-user-div">
                                            <div class="col-3 item-box-user-img-div">
                                                @if(empty($item->user->user_image))
                                                    <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                                @else
                                                    <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                                @endif
                                            </div>
                                            <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                <div class="row pb-1">
                                                    <div class="col-12">
                                                        <a class="decoration-none" href="{{ route('page.profile', encrypt($item->user->id)) }}"><span class="font-size-13">{{ str_limit($item->user->name, 14, '.') }}</span></a>
                                                    </div>
                                                </div>
                                                <div class="row line-height-1-0">
                                                    <div class="col-12">
                                                        <span class="review">{{ $item->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="col-7 col-md-5 pl-0 text-right">
                                        @if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                            @if($item->hasOpened())
                                                <span class="item-box-hour-span-opened">{{ __('item_hour.frontend-item-box-hour-opened') }}</span>
                                            @else
                                                <span class="item-box-hour-span-closed">{{ __('item_hour.frontend-item-box-hour-closed') }}</span>
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
        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="{{ route('page.categories') }}" class="btn btn-primary rounded text-white">
                    {{ __('all_latest_listings.view-all-latest') }}
                </a>
            </div>
        </div>
    </div>
</div>

@if($all_testimonials->count() > 0)
<div class="site-section bg-white">
    <div class="container">

        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.testimonials') }}</h2>
            </div>
        </div>

        <div class="slide-one-item home-slider owl-carousel">

                @foreach($all_testimonials as $key => $testimonial)
                    <div>
                        <div class="testimonial">
                            <figure class="mb-4">
                                @if(empty($testimonial->testimonial_image))
                                    <img src="{{ asset('frontend/images/placeholder/profile-'. intval($testimonial->id % 10) . '.webp') }}" alt="Image" class="img-fluid mb-3">
                                @else
                                    <img src="{{ Storage::disk('public')->url('testimonial/' . $testimonial->testimonial_image) }}" alt="Image" class="img-fluid mb-3">
                                @endif
                                <p>
                                    {{ $testimonial->testimonial_name }}
                                    @if($testimonial->testimonial_job_title)
                                        {{ '• ' . $testimonial->testimonial_job_title }}
                                    @endif
                                    @if($testimonial->testimonial_company)
                                        {{ '• ' . $testimonial->testimonial_company }}
                                    @endif
                                </p>
                            </figure>
                            <blockquote>
                                <p>{{ $testimonial->testimonial_description }}</p>
                            </blockquote>
                        </div>
                    </div>
                @endforeach

        </div>
    </div>
</div>
@endif


@if($recent_blog->count() > 0)
<div class="site-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.our-blog') }}</h2>
                <p class="color-black-opacity-5">{{ __('frontend.homepage.our-blog-decr') }}</p>
            </div>
        </div>
        <div class="row mb-3 align-items-stretch">

                @foreach($recent_blog as $recent_blog_key => $post)
                    <div class="col-md-6 col-lg-4 mb-4 mb-lg-4">
                        <div class="h-entry">
                            @if(empty($post->featured_image))
                                <div class="mb-3" style="min-height:300px;border-radius: 0.25rem;background-image:url({{ asset('frontend/images/placeholder/full_item_feature_image_medium.webp') }});background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
                            @else
                                <div class="mb-3" style="min-height:300px;border-radius: 0.25rem;background-image:url({{ url('laravel_project/public' . $post->featured_image) }});background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
                            @endif
                            <h2 class="font-size-regular"><a href="{{ route('page.blog.show', $post->slug) }}" class="text-black">{{ $post->title }}</a></h2>
                            <div class="meta mb-3">
                                 
                                {{-- {{ $post->user()->first()->name }} --}}
                                {{-- <span class="mx-1">&bullet;</span> --}}
                                 {{ $post->updated_at->diffForHumans() }} <span class="mx-1">&bullet;</span>
                                @if($post->topic()->count() != 0)
                                <a href="{{ route('page.blog.topic', $post->topic()->first()->slug) }}">{{ $post->topic()->first()->name }}</a>
                                @else
                                    {{ __('frontend.blog.uncategorized') }}
                                @endif

                            </div>
                            <p>{{ str_limit(preg_replace("/&#?[a-z0-9]{2,8};/i"," ", strip_tags($post->body)), 200) }}</p>
                        </div>
                    </div>
                @endforeach

            <div class="col-12 text-center mt-4">
                <a href="{{ route('page.blog') }}" class="btn btn-primary rounded py-2 px-4 text-white">{{ __('frontend.homepage.all-posts') }}</a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Modal -->
<div class="modal fade" id="reload_page_modal" tabindex="-1" role="dialog" aria-labelledby="reload_page_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reload_page_modal_label">{{ __('category_image_option.geolocation.modal-title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('category_image_option.geolocation.modal-description') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('category_image_option.geolocation.modal-button-no') }}</button>
                <a href="{{ route('page.home') }}" class="btn btn-primary text-white rounded">{{ __('category_image_option.geolocation.modal-button-reload') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="nda_modal" tabindex="-1" role="dialog" aria-labelledby="nda_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content" id="nda_modal_content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">BETA CONTENT CREATOR AGREEMENT</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="content">
                    <div class="col-md-12">
                        <p>This Content Creator Agreement ("Agreement") is entered into as of _______, 20__ (the "Effective Date") by Coaches HQ LLC., a Virginia corporation, ("Coaches HQ LLC"), and ________ ("Creator") and includes the following terms and conditions and Exhibit A attached hereto.</p>
                        <p><strong>1. <u>Services & Rights.</u></strong></p>
                        <p>1.1. <u>Services; Content.</u> Creator shall perform the services and provide the content as specified in the "Statement of Work" attached hereto as Exhibit A (such services and content, including all related (i) services provided hereunder and (ii) content and materials provided hereunder, are the "Services" and "Content", respectively).  Creator is solely responsible for all Content.</p>
                        <p>1.2. <u>Creator Biography and Likeness.</u> Creator hereby grants to Coaches HQ LLC a right and license, but no obligation, to use and disseminate Creator's name, likeness and biographical information, for promotional and other purposes.</p>
                        <p>1.3. <u>Ownership.</u> Creator retains all right, title, and interest in and to all Content provided under this Agreement.  However, by submitting Content pursuant to this Agreement, Creator grants to Coaches HQ LLC a worldwide, non, royalty free, license to make, display, perform, use, reproduce, distribute, license, sell, import, export, transmit, and to create derivatives, enhancements, extensions, improvements, and modifications of ("Derivatives"), provide user access to, and otherwise commercialize, in all manner and medium now or hereafter known, the Content, any Content IP and any Derivatives created pursuant to this Agreement.</p>
                        <p>1.4. <u>Content IP.</u> Creator will retain no rights of outcomes, IP obtained and generated in consequence of engaging in the content, IP obtained from utilizing content in association with data science to derive certain outcomes, measurements, analytics associated with engaging in Content, and any other Content IP similar rights.</p>
                        <p><strong>2. <u>Compensation.</u></strong> Subject to terms and conditions of this Agreement, Coaches HQ LLC will not provide monetary compensation directly or indirectly for the service provided through this agreement. Creator agrees to release Coaches HQ LLC to any and all obligation regarding compensation.</p>
                        <p><strong>3. <u>Term & Termination.</u></strong> This Agreement shall commence on the Effective Date and continue for a minimum of two (2) years, unless earlier terminated by either party for any or no reason upon sixty (60) days prior written notice to the other party.  Sections 1, 3, 4, 5 and 6 of this Agreement will survive termination of this Agreement.</p>
                        <p><strong>4. <u>Warranties and Indemnity.</u></strong> Creator represents, warrants and covenants that:  (i) Creator has all right, power and authority to enter into and properly perform under this Agreement; (ii) the Content is original to Creator; (iii) the Content shall not infringe upon the rights of any third party, including but not limited to trademark, copyright, trade secret, the rights of publicity and privacy and the right against defamation; and (iv) the Content complies with all applicable federal, state and local laws, rules and regulations.    Creator shall indemnify, defend and hold harmless Coaches HQ LLC against all damages, claims, liabilities, losses and other expenses that arise out of Creator's breach of any representation or warranty or other provision of this Agreement.</p>
                        <p><strong>5. <u>Limitation of Liability.</u></strong> IN NO EVENT SHALL Coaches HQ LLC BE LIABLE TO CREATOR UNDER ANY LEGAL OR EQUITABLE THEORY FOR: (I) ANY SPECIAL, INDIRECT OR CONSEQUENTIAL DAMAGES OR (II) ANY AMOUNT IN EXCESS OF $100.</p>
                        <p><strong>6. <u>Miscellaneous.</u></strong>  Notwithstanding any provision hereof, for all purposes of this Agreement each party shall be and act as an independent contractor and not as partner, joint venturer, employer, employee or agent of the other.  The Compensation payable to Creator is inclusive of, and Creator shall be solely responsible for, all tax obligations due to all taxing authorities arising from or in connection with amounts paid to Creator hereunder, including, without limitation, federal, state, and local withholding taxes, FICA, FUTA, Social Security, Medicare, SUI and other such taxes and deductions ("Taxes"). This Agreement and the rights, obligations and licenses herein, shall be binding upon, and inure to the benefit of, the parties hereto and their respective heirs, successors and assigns. Creator shall not assign or transfer this Agreement in whole or part without the prior written consent of Coaches HQ LLC.  Coaches HQ LLC may freely assign or transfer this Agreement in whole or part.  This Agreement contains the entire understanding of the parties regarding its subject matter and supersedes all other agreements and understandings, whether oral or written. No changes or modifications or waivers are to be made to this Agreement unless evidenced in writing and signed for and on behalf of both parties.  If any portion of this Agreement is held to be illegal or unenforceable, that portion shall be restated, eliminated or limited to the minimum extent necessary so that this Agreement shall reflect as nearly as possible the original intention of the parties and the remainder of this Agreement shall remain in full force and effect.  This Agreement shall be governed by and construed in accordance with the laws of the State of Virginia without regard to the conflicts of laws provisions thereof.</p>
                    </div>
                    <div class="col-md-12">
                        <p>Coaches HQ LLC TECHNOLOGIES, INC.</p>
                        <p>Name: Title: </p>
                    </div>
                    <div class="col-md-12">
                        <p class="text-center"><strong>EXHIBIT A</strong></p>
                        <p class="text-center"><strong>Statement of Work</strong></p>
                        <p>1. Description of Services:</p>
                        <p>Creator will submit the following to complete their Coaching Profile:</p>
                        <p>a. Introduction Information</p>
                        <p>b. Bio in written form subject to be edited for length and applicability</p>
                        <p>c. Headshot</p>
                        <p>d. Contact information including email address, website, Instagram handle, Linkedin Profile, Twitter Handle, etc.</p>
                        <p>e. Minimum of 10 Coaching Content pieces (blogs, videos, white papers, etc.)</p>
                        <p>f. Feedback directly to Coaches HQ Development team through Telegram channel</p>
                        <p>g. Participation in a minimum of 2 virtual zoom meetings within a 90 day period</p>
                        <p>2. Compensation:</p>
                        <p>In exchange for content, Coaches HQ LLC agrees to release all revenue associated with business generated from the exposure of being on the platform where employees as well as employers will have direct access to contact you as a coach.</p>
                    </div>
                    <div class="col-md-12">
                        <p class="text-center"><strong>MUTUAL NONDISCLOSURE AGREEMENT</strong></p>
                        <p>Each undersigned party (the "<u>Receiving Party</u>") understands that the other party  (the "<u>Disclosing Party</u>") has disclosed or may disclose information relating to the Disclosing  Party's business (including, without limitation, computer programs, technical drawings,  algorithms, know-how, formulas, processes, ideas, inventions (whether patentable or not),  schematics and other technical, business, financial, customer and product development plans,  forecasts, strategies and information), which to the extent previously, presently or subsequently  disclosed to the Receiving Party is hereinafter referred to as "Proprietary Information" of the  Disclosing Party.</p>
                        <p>In consideration of the parties discussions and any access of the Receiving Party  to Proprietary Information of the Disclosing Party, the Receiving Party hereby agrees as follows:</p>
                        <p>1. The Receiving Party agrees (i) to hold the Disclosing Party's Proprietary Information in confidence and to take reasonable precautions to protect such Proprietary  Information (including, without limitation, all precautions the Receiving Party employs with  respect to its own confidential materials), (ii) not to divulge any such Proprietary Information or  any information derived therefrom to any third person, (iii) not to make any use whatsoever at any  time of such Proprietary Information except to evaluate internally its relationship with the  Disclosing Party, (iv) not to copy or reverse engineer any such Proprietary Information and (v) not  to export or reexport (within the meaning of U.S. or other export control laws or regulations) any  such Proprietary Information or product thereof. If the receiving party is an organization, then the  Receiving Party also agrees that, even within Receiving Party, Proprietary Information will be  disseminated only to those employees, officers and directors with a clear and well-defined "need to know" for purposes of the business relationship between the parties. Without granting any right  or license, the Disclosing Party agrees that the foregoing shall not apply with respect to any  information after five years following the disclosure thereof or any information that the Receiving  Party can document (i) is or becomes (through no improper action or inaction by the Receiving  Party or any affiliate, agent, consultant or employee of the Receiving Party) generally available to  the public, or (ii) was in its possession or known by it without restriction prior to receipt from the  Disclosing Party, or (iii) was rightfully disclosed to it by a third party without restriction, or  (iv) was independently developed without use of any Proprietary Information of the Disclosing Party. The Receiving Party may make disclosures required by law or court order provided the Receiving Party uses diligent reasonable efforts to limit disclosure and to obtain confidential treatment or a protective order and allows the Disclosing Party to participate in the proceeding</p>
                        <p>2. Immediately upon a request by the Disclosing Party at any time, the Receiving Party will turn over to the Disclosing Party all Proprietary Information of the Disclosing  Party and all documents or media containing any such Proprietary Information and any and all  copies or extracts thereof. The Receiving Party understands that nothing herein (i) requires the  disclosure of any Proprietary Information of the Disclosing Party or (ii) requires the Disclosing  Party to proceed with any transaction or relationship.</p>
                        <p>3. This Agreement applies only to disclosures made before the second anniversary of this Agreement. The Receiving Party acknowledges and agrees that due to the unique nature of the Disclosing Party's Proprietary Information, there can be no adequate remedy  at law for any breach of its obligations hereunder, which breach may result in irreparable harm to  the Disclosing Party, and therefore, that upon any such breach or any threat thereof, the Disclosing  Party shall be entitled to appropriate equitable relief, without the requirement of posting a bond,  in addition to whatever remedies it might have at law. In the event that any of the provisions of  this Agreement shall be held by a court or other tribunal of competent jurisdiction to be illegal,  invalid or unenforceable, such provisions shall be limited or eliminated to the minimum extent  necessary so that this Agreement shall otherwise remain in full force and effect. This Agreement  shall be governed by the laws of the State of California without regard to the conflicts of law  provisions thereof. This Agreement supersedes all prior discussions and writings and constitutes  the entire agreement between the parties with respect to the subject matter hereof. The prevailing  party in any action to enforce this Agreement shall be entitled to costs and attorneys' fees. No  waiver or modification of this Agreement will be binding upon a party unless made in writing and  signed by a duly authorized representative of such party and no failure or delay in enforcing any  right will be deemed a waiver.</p>
                    </div>
                    <div class="col-md-12">
                        <p>Date: </p>
                        <p>Coaches HQ LLC Technologies, Inc</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary agree_class" disabled data-dismiss="modal" id="i_agree">I Agree</button>
                <!-- <button type="button" class="btn btn-secondary agree_class" disabled data-dismiss="modal">Close</button> -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

    @if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
    <!-- Youtube Background for Header -->
    <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"
    integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function(){

            "use strict";

            /**
             * Start get user lat & lng location
             */
            function success(position) {
                const latitude  = position.coords.latitude;
                const longitude = position.coords.longitude;

                console.log("Latitude: " + latitude + ", Longitude: " + longitude);

                var ajax_url = '/ajax/location/save/' + latitude + '/' + longitude;

                console.log(ajax_url);

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

                        if(result.reload)
                        {
                            $('#reload_page_modal').modal('show');
                        }
                    }});
            }

            function error() {
                console.log("Unable to retrieve your geolocation");
            }

            if(!navigator.geolocation) {

                console.log("Geolocation is not supported by your browser");
            } else {

                console.log("Locating geolocation ...");
                navigator.geolocation.getCurrentPosition(success, error);
            }
            /**
             * End get user lat & lng location
             */

            @if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            @endif

        });
        var is_terms_read = {{$is_terms_read}};
        var is_login = {{$is_login}};
        if(is_login && is_terms_read != 1){
            //$(window).on("scroll", function() {
                const expDate = new Date();
                expDate.setTime(expDate.getTime() + (5*60*1000));
                const cookieValue  = $.cookie('modal_cookie');
                if(cookieValue  !== "MODALPOPUPED"){
                    $.cookie("modal_cookie", "MODALPOPUPED",{ expires: expDate });
                    setTimeout (function () {
                        $('#nda_modal').modal({backdrop: 'static', keyboard: false}, 'show');
                    }, 10000)
                }
            //});
        }

        $('#nda_modal_content').scroll(function () {
            if ($(this).scrollTop() == $(this)[0].scrollHeight - $(this).height()) {
                $('.agree_class').removeAttr('disabled');
            }
        });

        $('#i_agree').click(function () {
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            jQuery.ajax({
                url: '/ajax/terms/save',
                method: 'post',
                data: {
                },
                success: function(result){
                }
            });
        });

    </script>

@endsection
