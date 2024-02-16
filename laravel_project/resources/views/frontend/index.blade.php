@extends('frontend.layouts.app')

@section('styles')
    <link rel="preload" href="{{ asset('frontend/images/placeholder/header-1.webp') }}" as="image">
    <style>
        #nda_modal_content {
            height: 800px;
            overflow-y: scroll;
        }
        .box-video {
            position: relative;
            width: 100%;
            margin: 0 auto 20px auto;
            cursor: pointer;
            overflow: hidden;
        }

        /* Set Cover aka Background-Image */
        .box-video .bg-video {
            position: absolute;
            background-color: #000f24;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            z-index: 2;
            opacity: 1;
        }

        /* Add light shade to make play button visible*/
        .bg-video::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
        }


        /* The Play-Button using CSS-Only */
        .bt-play {
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -31px 0 0 -31px;
            display: inline-block;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-indent: -999em;
            cursor: pointer;
            z-index: 2;
            -webkit-transition: all .3s ease-out;
            transition: all .3s ease-out;
        }

        .bt-play_user {
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -31px 0 0 -31px;
            display: inline-block;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-indent: -999em;
            cursor: pointer;
            z-index: 2;
            -webkit-transition: all .3s ease-out;
            transition: all .3s ease-out;
        }

        .bt-play_user:after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            height: 0;
            width: 0;
            margin: -12px 0 0 -6px;
            border: solid transparent;
            border-left-color: #000;
            border-width: 12px 20px;
            -webkit-transition: all .3s ease-out;
            transition: all .3s ease-out;
        }

        /* The Play-Triangle */
        .bt-play:after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            height: 0;
            width: 0;
            margin: -12px 0 0 -6px;
            border: solid transparent;
            border-left-color: #000;
            border-width: 12px 20px;
            -webkit-transition: all .3s ease-out;
            transition: all .3s ease-out;
        }

        .c-video__image-container:hover .bt-play {
            transform: scale(1.1);
        }

        /* When Class added the Cover gets hidden... */
        .box-video.open .bg-video {
            visibility: hidden;
            opacity: 0;
            -webkit-transition: all .6s .8s;
            transition: all .6s .8s;
        }

        /* and iframe shows up */
        .box-video.open .video-container {
            opacity: 1;
            -webkit-transition: all .6s .8s;
            transition: all .6s .8s;
        }

        /* Giving the div ratio of 16:9 with padding */
        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            margin: 0;
            z-index: 1;
            padding-bottom: 56.27198%;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }


        .c-header-in {
            position: absolute;
            left: 42vw;
            right: 0;
            top: 50px;
            color: white;
        }

        .vid-fit-user {
            height: 280px;
            background-color: #000f24;
            position: relative;
            border-radius: 20px;
        }

        .vid-fit-reveal {
            height: 215;
            background-color: #000f24;
            position: relative;
        }

        .vid-fit-reveal.red {
            background: red;
        }

        .vid-fit-reveal.reveal-video {
            background: none;
        }

        .vid-fit-reveal.reveal-video .c-header-in {
            display: none;
        }

        .vid-fit-reveal.reveal-video #vid-reveal {
            display: block;
            visibility: visible;
        }

        .vid-fit-reveal.reveal-video .c-video__play-btn {
            visibility: hidden;
        }

        .vid-fit-reveal #vid-reveal {
            visibility: hidden;
        }

        .c-video__play-btn {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
        }

        .table> :not(:last-child)> :last-child>* {
            border-bottom-color: inherit;
            border-right: 1px solid #fff;
            border-right-width: thin;
        }

        table.dataTable>thead>tr>td:not(.sorting_disabled),
        table.dataTable>thead>tr>th:not(.sorting_disabled) {
            border-bottom-color: inherit;
            border-right: 1px solid #fff;
            border-right-width: thin;
        }

        .w-30 {
            width: 30px !important;
        }
        .border-10{
            border-radius: 10px;
        }

        .padding-top-bottom-20{
            padding: 20px 0px;
        }
        .border-primary2 {
            border-color: #f05127 !important;
            position: relative;
            font-size: 28px;
        }

        .border-primary2::after {
            background: #f05127 !important;

        }
        .border-primary2::after {
            position: absolute;
            content: "";
            bottom: -3px;
            width: 80px;
            height: 3px;
            left: 0px;
        }
        .image-category-img2{
            margin: 20px 0px;
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@endsection

@section('content')

    @if ($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        {{-- <div class="site-blocks-cover overlay" style="background-image: url( {{ asset('frontend/images/placeholder/health-wellness-coach-with-client.jpg') }});"> --}}
        <div class="site-blocks-cover overlay"
            style="background-image: url( {{ asset('frontend/images/placeholder/health-wellness-coach-with-client.webp') }});">
        @elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_COLOR)
            <div class="site-blocks-cover overlay" style="background-color: {{ $site_homepage_header_background_color }};">
            @elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
                <div class="site-blocks-cover overlay"
                    style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_homepage_header_background_image) }});">
                @elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
                    <div class="site-blocks-cover overlay" style="background-color: #333333;">
    @endif

    @if ($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div data-youtube="{{ $site_homepage_header_background_youtube_video }}"></div>
    @endif

    @php
        $user_detail = '';
        $userId = '';
        $user_detail = Auth::user();
        if (isset($user_detail)) {
            $userId = $user_detail->id;
        }
    @endphp

    <div class="container">
        <div class="row align-items-center justify-content-center">
            <!-- <div class="col-md-6 border-10 form-search-wrap padding-top-bottom-20 d-small-none" data-aos="fade-right">
                   <div class="looking-heading">
                       <h2 class="font-weight-light text-white text-center f-md-24">I'm looking for...</h2>
                   </div>
                   <div class="row">
                        <div class="col-md-6">
                            <a href="#Feature-coaches"><h2 class="font-weight-light text-white border-primary2">Featured Coaches</h2>
                            <img src="{{ asset('frontend/images/Featured Coaches.webp') }}"
                                                        alt="Image" class="img-fluid rounded image-category-img image-category-img2  "> 
                           </a>
                        </div>
                        <div class="col-md-6">
                            <a href="#Trending-Videos"><h2 class="font-weight-light text-white border-primary2">Trending Videos</h2>
                            <img src="{{ asset('frontend/images/Trending Videos.webp') }}"
                                                        alt="Image" class="img-fluid rounded image-category-img image-category-img2"> </a>
                        </div>                       
                        <div class="col-md-6">
                            <a href="{{ route('page.allcoaches')}}"><h2 class="font-weight-light text-white border-primary2">All Coaches</h2>
                            <img src="{{ asset('frontend/images/Featured Coaches.webp') }}"
                                                        alt="Image" class="img-fluid rounded image-category-img image-category-img2"> 
                           </a>
                        </div>
                        <div class="col-md-6">
                            <a href="#Trending-topics"><h2 class="font-weight-light text-white border-primary2">Trending Topics</h2>
                            <img src="{{ asset('frontend/images/Trending Topics.webp') }}"
                                                        alt="Image" class="img-fluid rounded image-category-img image-category-img2"> </a>
                        </div>
                       
                   </div>
                </div> -->
                <div class="col-md-5 m-auto  padding-md-5-set"   data-aos="fade-left">
                     <div class="row justify-content-center mb-1">
                        <div class="col-md-12 text-center">
                            <h1 class="heading-one" data-aos="fade-up" style="color: {{ $site_homepage_header_title_font_color }};">
                                {{ __('frontend.homepage.title') }}</h1>
                            <p data-aos="fade-up" data-aos-delay="100"
                                style="color: {{ $site_homepage_header_paragraph_font_color }};word-break: initial;" class="description-para">
                                {{ __('frontend.homepage.description') }}</p>
                        </div>
                    </div> 
                    {{-- <div class="form-search-wrap" data-aos="fade-up" data-aos-delay="200">
                        @include('frontend.partials.search.head')
                    </div> --}}

                 {{------------------------------------------------new-two-btn-add-------------- --}}
                      <div class="form-search-wrap p-4">
                        <div class="main_banner_btn">
                            <a href="{{ route('page.coaches') }}"
                            class="btn btn-primary rounded py-2 px-4 text-white">Find A Coach</a>
                            <a href="{{ route('page.register') }}"
                            class="btn btn-primary rounded py-2 px-4 text-white">Become A Coach</a>
                        </div>
                    </div> 



                    <div class="row justify-content-center mt-5 mb-5 mb-lg-0">
                        <div class="col-md-12 col-lg-10 col-xl-8 text-center">
                            <!-- <a href="{{ route('page.coaches') }}"
                                class="btn btn-primary btn-lg btn-block rounded text-white f-16" style="font-size:16px">
                                {{ __('I want to find a great fit coach!') }}
                            </a> -->
                            
                        </div>
                    </div>
                </div>
            </div>

        <div class="row align-items-center justify-content-center text-center d-none mt-5">
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
    </div>
    <div class="site-section bg-light">
        <div class="container">
            <!-- Start categories section desktop view-->
            <div class="overlap-category pb-5">
                <div class="text-center">
                    <h2 class="font-weight-light text-primary">I'm looking for information that will help me with...</h2>
                </div>
                <div class="row align-items-stretch no-gutters justify-content-center">
                    @php
                        $categories_count = $categories->count();
                        $is_login = isset(Auth::user()->id) && !empty(Auth::user()->id) && Auth::user()->role_id == 2 ? 1 : 0;
                        $is_terms_read = isset(Auth::user()->is_terms_read) && !empty(Auth::user()->is_terms_read) ? 1 : 0;
                    @endphp

                    @if ($categories_count > 0)
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
                    @else
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                            <p>{{ __('frontend.homepage.no-categories') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <!-- End categories section desktop view-->

            <!-- Start categories section mobile view-->
            <div class="overlap-category-sm mb-4 d-none">
                <div class="row align-items-stretch no-gutters justify-content-center">
                    @if ($categories_count > 0)
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
                    @else
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                            <p>{{ __('frontend.homepage.no-categories') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <!-- End categories section mobile view-->
            <div class="row mt-5 mb-5" id="Feature-coaches">
                <div class="col-12 text-center">
                    <a href="{{ route('page.categories') }}" class="btn btn-primary rounded text-white">
                        <i class="fas fa-th"></i>
                        {{ __('frontend.homepage.all-categories') }}
                    </a>
                </div>
            </div>
            @if ($all_coaches->count() > 0)
                <div class="row mb-4">
                    <div class="col-md-7 text-left border-primary">
                        <h2 class="font-weight-light font-weight-300 text-primary p-0 mb-2">{{ __('Featured Coaches') }}
                        </h2>
                    </div>                    
                </div>
                <div class="row mb-4">
                    <div class="col-md-6 text-left">
                    </div>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('page.allcoaches') }}">
                            <button class="btn btn-primary btn-sm">
                                View All
                            </button>
                        </a>                                
                    </div>
                </div>
            @endif
            <div class="row" >
                <div class="col-lg-12 padding-0-set-sm">
                    @if ($all_coaches->count() >= 5)
                        <section class="card_texting container ptb-100-500">
                            <div class="swiper-container-coaches ">
                                <div class="swiper-wrapper">
                                    @php $count = 1; @endphp
                                    @foreach ($all_coaches as $all_coaches_key => $coach)
                                        @php
                                            if ($count == 8) {
                                                break;
                                            }
                                        @endphp
                                        <div class="swiper-slide">
                                            <div class="profile-card js-profile-card">
                                                <div class="profile-card__img">
                                                    <a href="{{ route('page.profile', encrypt($coach->id)) }}">
                                                        @if (empty($coach->user_image))
                                                            {{-- <img src="{{ asset('frontend/images/placeholder/profile-' . intval($coach->id % 10) . '.webp') }}"
                                                                alt="Image" /> --}}
                                                            <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}"
                                                                alt="Image" />
                                                        @else
                                                            <img src="{{ Storage::disk('public')->url('user/' . $coach->user_image) }}"
                                                                alt="{{ $coach->name }}" />
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="profile-card__cnt js-profile-cnt">
                                                    <div class="profile-card__name">
                                                        <a
                                                            href="{{ route('page.profile', encrypt($coach->id)) }}">{{ $coach->name }}</a>
                                                    </div>
                                                    <div class="profile-card__txt">
                                                        <div class="d-block d-md-flex listing vertical parent_set_listing"
                                                            style="min-height:0px;">
                                                            @if (isset($coach->category_parent_name) && !empty($coach->category_parent_name))
                                                                @foreach ($coach->category_parent_name as $item_all_categories_key => $category)
                                                                    <span class="category">
                                                                        @if (!empty($category->category_icon))
                                                                            <i class="{{ $category->category_icon }}"></i>
                                                                        @else
                                                                            <i class="fa-solid fa-heart"></i>
                                                                        @endif
                                                                        {{ $category->category_name }}
                                                                    </span>
                                                                @endforeach
                                                            @endif
                                                            @if (!empty($coach->category_icon_one))
                                                                <p>
                                                                    @foreach ($coach->category_icon_one as $icon_key => $icon)
                                                                        <a
                                                                            href="{{ route('page.category', $coach->category_slug_one[$icon_key]) }}">
                                                                            <span class="category_child">
                                                                                <i class="{{ $icon }}"></i>
                                                                                {{ $coach->category_name_one[$icon_key] }}
                                                                            </span>
                                                                        </a>
                                                                    @endforeach
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="profile-card-loc">
                                                        <span
                                                            class="profile-card-loc__txt">{{ str_limit($coach->company_name, 45, '...') }}</span>
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
                                                            @if ($coach->facebook)
                                                                <a href="{{ $coach->facebook }}" target="_blank">
                                                                    <i class="fa fa-facebook-f social_icon_design"></i></a>
                                                            @endif
                                                            @if ($coach->youtube)
                                                                <a href="{{ $coach->youtube }}" target="_blank">
                                                                    <i class="fa fa-youtube social_icon_design"></i></a>
                                                            @endif
                                                            @if ($coach->instagram)
                                                                <a href="https://instagram.com/_u/{{ $coach->instagram }}"
                                                                    target="_blank">
                                                                    <i class="fa fa-instagram social_icon_design"></i></a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @php $count++; @endphp
                                    @endforeach
                                </div>
                            </div>
                            <div class="overlay_effect">
                                <div class="handswipe">
                                    <img src="{{ asset('frontend/images/hand-move.gif') }}" />
                                </div>
                            </div>
                        </section>
                        <div class="swiper-pagination"></div>
                        <div class="swiper-button-next id-one"></div>
                        <div class="swiper-button-prev id-two"></div>
                    @endif

                    @if ($all_coaches->count() <= 4)
                        <div class="row">
                            @foreach ($all_coaches as $all_coaches_key => $coach)
                                <div class="col-md-6 col-lg-4 mb-5 mt-5">
                                    <div class="profile-card js-profile-card profile-card-design">
                                        <div class="profile-card__img">
                                            <a href="{{ route('page.profile', encrypt($coach->id)) }}">
                                                @if (empty($coach->user_image))
                                                    {{-- <img src="{{ asset('frontend/images/placeholder/profile-' . intval($coach->id % 10) . '.webp') }}"
                                                        alt="Image" /> --}}
                                                    <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}"
                                                        alt="Image" />
                                                @else
                                                    <img src="{{ Storage::disk('public')->url('user/' . $coach->user_image) }}"
                                                        alt="{{ $coach->name }}" />
                                                @endif
                                            </a>
                                        </div>

                                        <div class="profile-card__cnt js-profile-cnt">
                                            <div class="profile-card__name">
                                                <a
                                                    href="{{ route('page.profile', encrypt($coach->id)) }}">{{ $coach->name }}</a>
                                            </div>
                                            <div class="profile-card__txt">
                                                <div class="d-block d-md-flex listing vertical parent_set_listing"
                                                    style="min-height:0px;">
                                                    @if (isset($coach->category_parent_name) && !empty($coach->category_parent_name))
                                                        @foreach ($coach->category_parent_name as $item_all_categories_key => $category)
                                                            <span class="category">
                                                                @if (!empty($category->category_icon))
                                                                    <i class="{{ $category->category_icon }}"></i>
                                                                @else
                                                                    <i class="fa-solid fa-heart"></i>
                                                                @endif
                                                                {{ $category->category_name }}
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                    @if (!empty($coach->category_icon_one))
                                                        <p>
                                                            @foreach ($coach->category_icon_one as $icon_key => $icon)
                                                                <a
                                                                    href="{{ route('page.category', $coach->category_slug_one[$icon_key]) }}">
                                                                    <span class="category_child">
                                                                        <i class="{{ $icon }}"></i>
                                                                        {{ $coach->category_name_one[$icon_key] }}
                                                                    </span>
                                                                </a>
                                                            @endforeach
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="profile-card-loc">
                                                <span
                                                    class="profile-card-loc__txt">{{ str_limit($coach->company_name, 45, '...') }}</span>
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
                                                    @if ($coach->facebook)
                                                        <a href="{{ $coach->facebook }}" target="_blank">
                                                            <i class="fa fa-facebook-f social_icon_design"></i></a>
                                                    @endif
                                                    @if ($coach->youtube)
                                                        <a href="{{ $coach->youtube }}" target="_blank">
                                                            <i class="fa fa-youtube social_icon_design"></i></a>
                                                    @endif
                                                    @if ($coach->instagram)
                                                        <a href="https://instagram.com/_u/{{ $coach->instagram }}"
                                                            target="_blank">
                                                            <i class="fa fa-instagram social_icon_design"></i></a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="second_carousel_home">
        <div class="container position-relative">
            @if ($all_trainding_items->count() > 0)
                <div class="row mt-5 mb-3 align-items-center" id="Trending-topics">
                    <div class="col-md-7 text-left border-primary">
                        <h2 class="font-weight-light text-primary">{{ __('Trending Topics') }}</h2>
                    </div>
                    @if ($all_trainding_items->count() >= 8)
                        <!-- <div class="col-md-5 text-right">
                                    <a href="{{ route('page.all.recenttopics') }}" class="btn btn-primary rounded text-white">
                                        {{ __('all_latest_listings.view-all-latest') }}
                                    </a>
                                </div> -->
                    @endif
                </div>
            @endif
            @if ($all_trainding_items->count() >= 5)
                <section class="card_texting container  ptb-100-500 ptb-sm-50-50">
                    <div class="swiper-container-trainding-items">
                        <div class="swiper-wrapper">
                            @if ($all_trainding_items->count() > 0)
                                @php $count = 1; @endphp
                                @foreach ($all_trainding_items as $trainding_items_key => $trainding_item)
                                    @php
                                        if ($count == 8) {
                                            break;
                                        }
                                    @endphp
                                    <div class="swiper-slide">
                                        <div class="listing list_test">
                                            <a href="{{ route('page.item', $trainding_item->item_slug) }}"
                                                class="img d-block"
                                                style="background-image: url({{ !empty($trainding_item->item_image_medium) ? Storage::disk('public')->url('item/' . $trainding_item->item_image_medium) : (!empty($trainding_item->item_image) ? Storage::disk('public')->url('item/' . $trainding_item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})"></a>
                                            <div class="lh-content">

                                                @php
                                                    $latest_item_getAllCategories = $trainding_item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                                @endphp
                                                @foreach ($latest_item_getAllCategories as $item_all_categories_key => $category)
                                                    <a href="{{ route('page.category', $category->category_slug) }}">
                                                        <span class="category">
                                                            @if (!empty($category->category_icon))
                                                                <i class="{{ $category->category_icon }}"></i>
                                                            @else
                                                                <i class="fa-solid fa-heart"></i>
                                                            @endif
                                                            {{ $category->category_name }}
                                                        </span>
                                                    </a>
                                                @endforeach

                                                @php
                                                    $latest_item_allCategories_count = $trainding_item->allCategories()->count();
                                                @endphp
                                                @if ($latest_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                                    <span
                                                        class="category">{{ __('categories.and') . ' ' . strval($latest_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . ' ' . __('categories.more') }}</span>
                                                @endif

                                                <h3 class="pt-2" style="word-break: break-all;"><a
                                                        href="{{ route('page.item', $trainding_item->item_slug) }}">{{ $trainding_item->item_title }}</a>
                                                </h3>

                                                @if ($trainding_item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                                    @if (isset($user_detail->id))
                                                        @if ($user_detail->id == $trainding_item->user_id)
                                                            <address>
                                                                <a
                                                                    href="{{ route('page.city', ['state_slug' => $trainding_item->state->state_slug, 'city_slug' => $trainding_item->city->city_slug]) }}">{{ $trainding_item->city->city_name }}</a>,
                                                                <a
                                                                    href="{{ route('page.state', ['state_slug' => $trainding_item->state->state_slug]) }}">{{ $trainding_item->state->state_name }}</a>
                                                            </address>
                                                        @endif
                                                    @endif
                                                @endif

                                                @php
                                                    $latest_item_getCountRating = $trainding_item->getCountRating();
                                                @endphp
                                                @if ($latest_item_getCountRating > 0)
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="pl-0 rating_stars rating_stars_{{ $trainding_item->item_slug }}"
                                                                data-id="rating_stars_{{ $trainding_item->item_slug }}"
                                                                data-rating="{{ $trainding_item->item_average_rating }}">
                                                            </div>
                                                            <address class="mt-1">
                                                                @if ($latest_item_getCountRating == 1)
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
                                                    <div class="col-12 col-md-7 pr-0">
                                                        <div class="row align-items-center item-box-user-div">
                                                            <div class="col-3 item-box-user-img-div">
                                                                <a class="decoration-none"
                                                                    href="{{ route('page.profile', encrypt($trainding_item->user->id)) }}">
                                                                    @if (empty($trainding_item->user->user_image))
                                                                    
                                                                        {{-- <img src="{{ asset('frontend/images/placeholder/profile-' . intval($trainding_item->user->id % 10) . '.webp') }}"
                                                                            alt="Image"
                                                                            class="img-fluid rounded-circle"> --}}
                                                                        <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}"
                                                                            alt="Image"
                                                                            class="img-fluid rounded-circle">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('user/' . $trainding_item->user->user_image) }}"
                                                                            alt="{{ $trainding_item->user->name }}"
                                                                            class="img-fluid rounded-circle">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                                <div class="row pb-1">
                                                                    <div class="col-12">
                                                                        {{-- <a class="decoration-none" href="{{ route('page.profile', encrypt($trainding_item->user->id)) }}"><span class="font-size-13">{{ str_limit($item->user->name, 14, '.') }}</span></a> --}}
                                                                        <a class="decoration-none"
                                                                            href="{{ route('page.profile', encrypt($trainding_item->user->id)) }}"><span
                                                                                class="font-size-13">{{ $trainding_item->user->name }}</span></a>
                                                                    </div>
                                                                </div>
                                                                <div class="row line-height-1-0">
                                                                    <div class="col-12">
                                                                        <span
                                                                            class="review">{{ $trainding_item->created_at->diffForHumans() }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- <div class="col-7 col-md-5 pl-0 text-right">
                                                    @if ($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                                        @if ($trainding_item->hasOpened())
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
                                    @php $count++; @endphp
                                @endforeach
                            @endif
                        </div>
                    </div>
                    {{-- <div class="overlay_effect">
                        <div class="handswipe">
                            <img src="{{ asset('frontend/images/hand-move.gif') }}" />
                        </div>
                    </div> --}}
                </section>
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next id-three"></div>
                <div class="swiper-button-prev id-four"></div>
            @endif

            @if ($all_trainding_items->count() <= 4)
                <div class="container">
                    <div class="row">
                        @foreach ($all_trainding_items as $trainding_items_key => $trainding_item)
                            <div class="col-md-3">
                                <div class="listing list_test">
                                    <a href="{{ route('page.item', $trainding_item->item_slug) }}" class="img d-block"
                                        style="background-image: url({{ !empty($trainding_item->item_image_medium) ? Storage::disk('public')->url('item/' . $trainding_item->item_image_medium) : (!empty($trainding_item->item_image) ? Storage::disk('public')->url('item/' . $trainding_item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})"></a>
                                    <div class="lh-content">

                                        @php
                                            $latest_item_getAllCategories = $trainding_item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                        @endphp
                                        @foreach ($latest_item_getAllCategories as $item_all_categories_key => $category)
                                            <a href="{{ route('page.category', $category->category_slug) }}">
                                                <span class="category">
                                                    @if (!empty($category->category_icon))
                                                        <i class="{{ $category->category_icon }}"></i>
                                                    @else
                                                        <i class="fa-solid fa-heart"></i>
                                                    @endif
                                                    {{ $category->category_name }}
                                                </span>
                                            </a>
                                        @endforeach

                                        @php
                                            $latest_item_allCategories_count = $trainding_item->allCategories()->count();
                                        @endphp
                                        @if ($latest_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                            <span
                                                class="category">{{ __('categories.and') . ' ' . strval($latest_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . ' ' . __('categories.more') }}</span>
                                        @endif

                                        <h3 class="pt-2" style="word-break: break-all;"><a
                                                href="{{ route('page.item', $trainding_item->item_slug) }}">{{ $trainding_item->item_title }}</a>
                                        </h3>

                                        @if ($trainding_item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                            @if (isset($user_detail->id))
                                                @if ($user_detail->id == $trainding_item->user_id)
                                                    <address>
                                                        <a
                                                            href="{{ route('page.city', ['state_slug' => $trainding_item->state->state_slug, 'city_slug' => $trainding_item->city->city_slug]) }}">{{ $trainding_item->city->city_name }}</a>,
                                                        <a
                                                            href="{{ route('page.state', ['state_slug' => $trainding_item->state->state_slug]) }}">{{ $trainding_item->state->state_name }}</a>
                                                    </address>
                                                @endif
                                            @endif
                                        @endif

                                        @php
                                            $latest_item_getCountRating = $trainding_item->getCountRating();
                                        @endphp
                                        @if ($latest_item_getCountRating > 0)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="pl-0 rating_stars rating_stars_{{ $trainding_item->item_slug }}"
                                                        data-id="rating_stars_{{ $trainding_item->item_slug }}"
                                                        data-rating="{{ $trainding_item->item_average_rating }}">
                                                    </div>
                                                    <address class="mt-1">
                                                        @if ($latest_item_getCountRating == 1)
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
                                            <div class="col-12 col-md-7 pr-0">
                                                <div class="row align-items-center item-box-user-div">
                                                    <div class="col-3 item-box-user-img-div">
                                                        <a class="decoration-none"
                                                            href="{{ route('page.profile', encrypt($trainding_item->user->id)) }}">
                                                            @if (empty($trainding_item->user->user_image))
                                                                <img src="{{ asset('frontend/images/placeholder/profile-' . intval($trainding_item->user->id % 10) . '.webp') }}"
                                                                    alt="Image" class="img-fluid rounded-circle">
                                                            @else
                                                                <img src="{{ Storage::disk('public')->url('user/' . $trainding_item->user->user_image) }}"
                                                                    alt="{{ $trainding_item->user->name }}"
                                                                    class="img-fluid rounded-circle">
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                        <div class="row pb-1">
                                                            <div class="col-12">
                                                                {{-- <a class="decoration-none" href="{{ route('page.profile', encrypt($trainding_item->user->id)) }}"><span class="font-size-13">{{ str_limit($item->user->name, 14, '.') }}</span></a> --}}
                                                                <a class="decoration-none"
                                                                    href="{{ route('page.profile', encrypt($trainding_item->user->id)) }}"><span
                                                                        class="font-size-13">{{ $trainding_item->user->name }}</span></a>
                                                            </div>
                                                        </div>
                                                        <div class="row line-height-1-0">
                                                            <div class="col-12">
                                                                <span
                                                                    class="review">{{ $trainding_item->created_at->diffForHumans() }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($paid_items->count() > 0)
        <div class="site-section bg-light">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-md-7 text-left border-primary">
                        <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.featured-ads') }}</h2>
                    </div>
                </div>
            </div>
            @if ($paid_items->count() >= 5)
                <section class="card_texting container ptb-100-500 ptb-sm-50-50">
                    <div class="swiper-container-paid">
                        <div class="swiper-wrapper">
                            @php $count = 1; @endphp
                            @foreach ($paid_items as $paid_items_key => $item)
                                @php
                                    if ($count == 8) {
                                        break;
                                    }
                                @endphp
                                <div class="swiper-slide">
                                    <div class="listing">
                                        <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block"
                                            style="background-image: url({{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})"></a>
                                        <div class="lh-content">

                                            @php
                                                $paid_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                            @endphp
                                            @foreach ($paid_item_getAllCategories as $item_all_categories_key => $category)
                                                <a href="{{ route('page.category', $category->category_slug) }}">
                                                    <span class="category">
                                                        @if (!empty($category->category_icon))
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
                                            @if ($paid_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                                <span
                                                    class="category">{{ __('categories.and') . ' ' . strval($paid_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . ' ' . __('categories.more') }}</span>
                                            @endif

                                            <h3 class="pt-2"><a
                                                    href="{{ route('page.item', $item->item_slug) }}">{{ str_limit($item->item_title, 44, '...') }}</a>
                                            </h3>

                                            @if ($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                                @if (isset($user_detail->id))
                                                    @if ($user_detail->id == $item->user_id)
                                                        <address>
                                                            <a
                                                                href="{{ route('page.city', ['state_slug' => $item->state->state_slug, 'city_slug' => $item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                                            <a
                                                                href="{{ route('page.state', ['state_slug' => $item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                                        </address>
                                                    @endif
                                                @endif
                                            @endif

                                            @php
                                                $paid_item_getCountRating = $item->getCountRating();
                                            @endphp
                                            @if ($paid_item_getCountRating > 0)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}"
                                                            data-id="rating_stars_{{ $item->item_slug }}"
                                                            data-rating="{{ $item->item_average_rating }}"></div>
                                                        <address class="mt-1">
                                                            @if ($paid_item_getCountRating == 1)
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

                                                <div class="col-12 col-md-7 pr-0">
                                                    <div class="row align-items-center item-box-user-div">
                                                        <div class="col-3 item-box-user-img-div">
                                                            <a class="decoration-none"
                                                                href="{{ route('page.profile', encrypt($item->user->id)) }}">
                                                                @if (empty($item->user->user_image))
                                                                    {{-- <img src="{{ asset('frontend/images/placeholder/profile-' . intval($item->user->id % 10) . '.webp') }}"
                                                                        alt="Image" class="img-fluid rounded-circle"> --}}
                                                                    <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}"
                                                                        alt="Image" class="img-fluid rounded-circle">
                                                                @else
                                                                    <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                                                        alt="{{ $item->user->name }}"
                                                                        class="img-fluid rounded-circle">
                                                                @endif
                                                            </a>
                                                        </div>
                                                        <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                            <div class="row pb-1">
                                                                <div class="col-12">
                                                                    {{-- <a class="decoration-none" href="{{ route('page.profile', encrypt($item->user->id)) }}"><span class="font-size-13">{{ str_limit($item->user->name, 12, '.') }}</span></a> --}}
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
                                @php $count++; @endphp
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

            @if ($paid_items->count() <= 4)
                <div class="container">
                    <div class="row">
                        @foreach ($paid_items as $paid_items_key => $item)
                            <div class="col-md-3">
                                <div class="listing">
                                    <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block"
                                        style="background-image: url({{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})"></a>
                                    <div class="lh-content">

                                        @php
                                            $paid_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                        @endphp
                                        @foreach ($paid_item_getAllCategories as $item_all_categories_key => $category)
                                            <a href="{{ route('page.category', $category->category_slug) }}">
                                                <span class="category">
                                                    @if (!empty($category->category_icon))
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
                                        @if ($paid_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                            <span
                                                class="category">{{ __('categories.and') . ' ' . strval($paid_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . ' ' . __('categories.more') }}</span>
                                        @endif

                                        <h3 class="pt-2"><a
                                                href="{{ route('page.item', $item->item_slug) }}">{{ str_limit($item->item_title, 44, '...') }}</a>
                                        </h3>

                                        @if ($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                            @if (isset($user_detail->id))
                                                @if ($user_detail->id == $item->user_id)
                                                    <address>
                                                        <a
                                                            href="{{ route('page.city', ['state_slug' => $item->state->state_slug, 'city_slug' => $item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                                        <a
                                                            href="{{ route('page.state', ['state_slug' => $item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                                    </address>
                                                @endif
                                            @endif
                                        @endif

                                        @php
                                            $paid_item_getCountRating = $item->getCountRating();
                                        @endphp
                                        @if ($paid_item_getCountRating > 0)
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}"
                                                        data-id="rating_stars_{{ $item->item_slug }}"
                                                        data-rating="{{ $item->item_average_rating }}"></div>
                                                    <address class="mt-1">
                                                        @if ($paid_item_getCountRating == 1)
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

                                            <div class="col-12 col-md-7 pr-0">
                                                <div class="row align-items-center item-box-user-div">
                                                    <div class="col-3 item-box-user-img-div">
                                                        <a class="decoration-none"
                                                            href="{{ route('page.profile', encrypt($item->user->id)) }}">
                                                            @if (empty($item->user->user_image))
                                                                {{-- <img src="{{ asset('frontend/images/placeholder/profile-' . intval($item->user->id % 10) . '.webp') }}"
                                                                    alt="Image" class="img-fluid rounded-circle"> --}}
                                                                <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}"
                                                                    alt="Image" class="img-fluid rounded-circle">
                                                            @else
                                                                <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                                                    alt="{{ $item->user->name }}"
                                                                    class="img-fluid rounded-circle">
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                        <div class="row pb-1">
                                                            <div class="col-12">
                                                                {{-- <a class="decoration-none" href="{{ route('page.profile', encrypt($item->user->id)) }}"><span class="font-size-13">{{ str_limit($item->user->name, 12, '.') }}</span></a> --}}
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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if ($latest_items->count() > 0)
        <div class="bg-light">
            <div class="container">
                <div class="row mt-5 mb-3 align-items-center" id="Recent-topics">
                    <div class="col-md-7 text-left border-primary">
                        <h2 class="font-weight-light text-primary mt-5">{{ __('frontend.homepage.recent-listings') }}
                        </h2>
                    </div>
                    @if ($latest_items->count() >= 8)
                        <!-- <div class="col-md-5 text-right">
                                <a href="{{ route('page.all.recenttopics') }}" class="btn btn-primary rounded text-white">
                                    {{ __('all_latest_listings.view-all-latest') }}
                                </a>
                            </div> -->
                    @endif
                </div>
            </div>
    @endif

    @if ($latest_items->count() >= 5)
        <div class="container">
            <div class="row">
                <div class="col-md-12 padding-0-set-sm">
                    <section class="card_texting container ptb-100-500 ptb-sm-50-50">
                        <div class="swiper-container-latest swiper_topics_carousel_set">
                            <div class="swiper-wrapper">
                                @php $count = 1; @endphp
                                @foreach ($latest_items as $latest_items_key => $item)
                                    @php
                                        if ($count == 8) {
                                            break;
                                        }
                                    @endphp
                                    <div class="swiper-slide">
                                        <div class="listing">
                                            <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block"
                                                style="background-image: url({{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})"></a>
                                            <div class="lh-content">
                                                @php
                                                    $latest_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                                @endphp
                                                @foreach ($latest_item_getAllCategories as $item_all_categories_key => $category)
                                                    <a href="{{ route('page.category', $category->category_slug) }}">
                                                        <span class="category">
                                                            @if (!empty($category->category_icon))
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
                                                @if ($latest_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                                    <span
                                                        class="category">{{ __('categories.and') . ' ' . strval($latest_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . ' ' . __('categories.more') }}</span>
                                                @endif
                                                <h3 class="pt-2" style="word-break: break-all;"><a
                                                        href="{{ route('page.item', $item->item_slug) }}">{{ $item->item_title }}</a>
                                                </h3>

                                                @if ($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                                    @if (isset($user_detail->id))
                                                        @if ($user_detail->id == $item->user_id)
                                                            <address>
                                                                <a
                                                                    href="{{ route('page.city', ['state_slug' => $item->state->state_slug, 'city_slug' => $item->city->city_slug]) }}">{{ $item->city->city_name }}</a>
                                                                <a
                                                                    href="{{ route('page.state', ['state_slug' => $item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                                            </address>
                                                        @endif
                                                    @endif
                                                @endif

                                                @php
                                                    $latest_item_getCountRating = $item->getCountRating();
                                                @endphp
                                                @if ($latest_item_getCountRating > 0)
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}"
                                                                data-id="rating_stars_{{ $item->item_slug }}"
                                                                data-rating="{{ $item->item_average_rating }}"></div>
                                                            <address class="mt-1">
                                                                @if ($latest_item_getCountRating == 1)
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

                                                    <div class="col-12 col-md-7 pr-0">
                                                        <div class="row align-items-center item-box-user-div">
                                                            <div class="col-3 item-box-user-img-div">
                                                                <a class="decoration-none"
                                                                    href="{{ route('page.profile', encrypt($item->user->id)) }}">
                                                                    @if (empty($item->user->user_image))
                                                                        {{-- <img src="{{ asset('frontend/images/placeholder/profile-' . intval($item->user->id % 10) . '.webp') }}"
                                                                            alt="Image"
                                                                            class="img-fluid rounded-circle"> --}}
                                                                        <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}"
                                                                            alt="Image"
                                                                            class="img-fluid rounded-circle">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                                                            alt="{{ $item->user->name }}"
                                                                            class="img-fluid rounded-circle">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                                <div class="row pb-1">
                                                                    <div class="col-12">
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @php $count++; @endphp
                                @endforeach
                            </div>
                        </div>
                        {{-- <div class="overlay_effect">
                            <div class="handswipe">
                                <img src="{{ asset('frontend/images/hand-move.gif') }}" />
                            </div>
                        </div> --}}
                    </section>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next id-five"></div>
                    <div class="swiper-button-prev id-six"></div>
                </div>
            </div>
        </div>
    @endif

    @if ($latest_items->count() <= 4)
        <div class="container">
            <div class="row">
                @foreach ($latest_items as $latest_items_key => $item)
                    <div class="col-md-3">
                        <div class="listing">
                            <a href="{{ route('page.item', $item->item_slug) }}" class="img d-block"
                                style="background-image: url({{ !empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp')) }})"></a>
                            <div class="lh-content">
                                @php
                                    $latest_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                @endphp
                                @foreach ($latest_item_getAllCategories as $item_all_categories_key => $category)
                                    <a href="{{ route('page.category', $category->category_slug) }}">
                                        <span class="category">
                                            @if (!empty($category->category_icon))
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
                                @if ($latest_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE)
                                    <span
                                        class="category">{{ __('categories.and') . ' ' . strval($latest_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . ' ' . __('categories.more') }}</span>
                                @endif

                                <h3 class="pt-2" style="word-break: break-all;"><a
                                        href="{{ route('page.item', $item->item_slug) }}">{{ $item->item_title }}</a>
                                </h3>

                                @if ($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                    @if (isset($user_detail->id))
                                        @if ($user_detail->id == $item->user_id)
                                            <address>
                                                <a
                                                    href="{{ route('page.city', ['state_slug' => $item->state->state_slug, 'city_slug' => $item->city->city_slug]) }}">{{ $item->city->city_name }}</a>,
                                                <a
                                                    href="{{ route('page.state', ['state_slug' => $item->state->state_slug]) }}">{{ $item->state->state_name }}</a>
                                            </address>
                                        @endif
                                    @endif
                                @endif

                                @php
                                    $latest_item_getCountRating = $item->getCountRating();
                                @endphp
                                @if ($latest_item_getCountRating > 0)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pl-0 rating_stars rating_stars_{{ $item->item_slug }}"
                                                data-id="rating_stars_{{ $item->item_slug }}"
                                                data-rating="{{ $item->item_average_rating }}"></div>
                                            <address class="mt-1">
                                                @if ($latest_item_getCountRating == 1)
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
                                    <div class="col-12 col-md-7 pr-0">
                                        <div class="row align-items-center item-box-user-div">
                                            <div class="col-3 item-box-user-img-div">
                                                <a class="decoration-none"
                                                    href="{{ route('page.profile', encrypt($item->user->id)) }}">
                                                    @if (empty($item->user->user_image))
                                                        {{-- <img src="{{ asset('frontend/images/placeholder/profile-' . intval($item->user->id % 10) . '.webp') }}"
                                                            alt="Image" class="img-fluid rounded-circle"> --}}
                                                        <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}"
                                                            alt="Image" class="img-fluid rounded-circle">
                                                    @else
                                                        <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}"
                                                            alt="{{ $item->user->name }}"
                                                            class="img-fluid rounded-circle">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="col-9 line-height-1-2 item-box-user-name-div">
                                                <div class="row pb-1">
                                                    <div class="col-12">
                                                        {{-- <a class="decoration-none" href="{{ route('page.profile', encrypt($item->user->id)) }}"><span class="font-size-13">{{ str_limit($item->user->name, 14, '.') }}</span></a> --}}
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
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    </div>

    {{-- @if ($all_testimonials->count() > 0)
        <div class="site-section ">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 text-center border-primary">
                        <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.testimonials') }}</h2>
                    </div>
                </div>
                <div class="slide-one-item home-slider owl-carousel">
                    @foreach ($all_testimonials as $key => $testimonial)
                        <div>
                            <div class="testimonial">
                                <figure class="mb-4">
                                    @if (empty($testimonial->testimonial_image))
                                        <img src="{{ asset('frontend/images/placeholder/profile-' . intval($testimonial->id % 10) . '.webp') }}"
                                            alt="Image" class="img-fluid mb-3">
                                    @else
                                        <img src="{{ Storage::disk('public')->url('testimonial/' . $testimonial->testimonial_image) }}"
                                            alt="Image" class="img-fluid mb-3">
                                    @endif
                                    <p>
                                        {{ $testimonial->testimonial_name }}
                                        @if ($testimonial->testimonial_job_title)
                                            {{ '• ' . $testimonial->testimonial_job_title }}
                                        @endif
                                        @if ($testimonial->testimonial_company)
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
    @endif --}}

    @if ($recent_blog->count() > 0)
        <div class="site-section">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-md-7 text-center border-primary">
                        <h2 class="font-weight-light text-primary">{{ __('frontend.homepage.our-blog') }}</h2>
                        <p class="color-black-opacity-5">{{ __('frontend.homepage.our-blog-decr') }}</p>
                    </div>
                </div>
                <div class="row mb-3 align-items-stretch">
                    @foreach ($recent_blog as $recent_blog_key => $post)
                        <div class="col-md-6 col-lg-4 mb-4 mb-lg-4">
                            <div class="h-entry">
                                @if (empty($post->featured_image))
                                    <div class="mb-3"
                                        style="min-height:300px;border-radius: 0.25rem;background-image:url({{ asset('frontend/images/placeholder/full_item_feature_image_medium.webp') }});background-size:cover;background-repeat:no-repeat;background-position: center center;">
                                    </div>
                                @else
                                    <div class="mb-3"
                                        style="min-height:300px;border-radius: 0.25rem;background-image:url({{ url('laravel_project/public' . $post->featured_image) }});background-size:cover;background-repeat:no-repeat;background-position: center center;">
                                    </div>
                                @endif
                                <h2 class="font-size-regular"><a href="{{ route('page.blog.show', $post->slug) }}"
                                        class="text-black">{{ $post->title }}</a></h2>
                                <div class="meta mb-3">

                                    {{-- {{ $post->user()->first()->name }} --}}
                                    {{-- <span class="mx-1">&bullet;</span> --}}
                                    {{ $post->updated_at->diffForHumans() }} <span class="mx-1">&bullet;</span>
                                    @if ($post->topic()->count() != 0)
                                        <a
                                            href="{{ route('page.blog.topic', $post->topic()->first()->slug) }}">{{ $post->topic()->first()->name }}</a>
                                    @else
                                        {{ __('frontend.blog.uncategorized') }}
                                    @endif

                                </div>
                                <p>{{ str_limit(preg_replace('/&#?[a-z0-9]{2,8};/i', ' ', strip_tags($post->body)), 200) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-12 text-center mt-4">
                        <a href="{{ route('page.blog') }}"
                            class="btn btn-primary rounded py-2 px-4 text-white">{{ __('frontend.homepage.all-posts') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif





@if ($post_count > 0)
    @if ($post_count <= 4)
        <div class="@if($recent_blog->count() > 0) bg-light @else '' @endif">
            <div class="container">
                <div class="row mb-3 align-items-center" id="Trending-Videos">

                    <div class="col-md-7 text-left border-primary">
                        <h2 class="font-weight-light text-primary mt-5">Trending Videos
                        </h2>
                    </div>

                    <div class="col-md-12">
                        <div class="below_info justify-content-end">
                            <a href="{{ route('page.trending_videos') }}">
                                <button class="btn btn-primary btn-sm">
                                    View All
                                </button>
                            </a>
                        </div>
                    </div>

                @foreach ($AllMedia_view_arr as $video_key => $video)
                        <!--Begin: slid body  -->
                        <br><br><br>
                        <div class="col-lg-3">
                        <div class="post-slide">
                            <div class="post-img">
                                <div class='c-video__image-container vid-fit-reveal box-video-youtube'
                                    data-id="{{ $video['id'] }}" id="#js-fitvideo_{{ $video['id'] }}" data-vid="box-video-youtube_{{ $video['id'] }}">
                                    <a href="" target="_blank" class="bg-video" id="youtube_id_{{ $video['id'] }}" data-toggle="modal" data-target="#youtubeModal"
                                        data-src="{{ $video['media_url'] }}">
                                        <div class="bt-play" id="bt-play_{{ $video['id'] }}"></div>
                                    </a>
                                    <iframe width="560" height="215" src="{{ $video['media_url']}}"  id="vid-reveal_{{ $video['id'] }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                    encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </div>
                
                            <div class="view_eye_post">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                                <p class="views" style="color: black;">View</p>
                                <span class="youtube_section_number">: {{$video['totalcount'] }}</span>
                            </div>
                        </div>
                        </div>
                        <!--End: slider body  -->
                @endforeach
                <div>
            <div>
        <div>           
    @endif

    @if ($post_count > 4  && $post_count <= 6)
        <div class="@if($recent_blog->count() > 0) bg-light @else '' @endif">
            <div class="container">
                <div class="row mb-3 align-items-center" id="Trending-Videos">

                    <div class="col-md-7 text-left border-primary">
                        <h2 class="font-weight-light text-primary mt-5">Trending Videos
                        </h2>
                    </div>

                    <div class="col-md-12">
                        <div class="below_info justify-content-end">
                            <a href="{{ route('page.trending_videos') }}">
                                <button class="btn btn-primary btn-sm">
                                    View All
                                </button>
                            </a>
                        </div>
                    </div>

                    <!--Begin: trendding video slider -->
                    <div class="col-md-12 padding-0-set-sm">
                    <section class="card_texting container ptb-100-500 ptb-sm-50-50">
                        <div class="swiper-container-latest-2 swiper_topics_carousel_set">
                            <div class="swiper-wrapper">
                            @php $count = 1; @endphp
                                    @foreach ($AllMedia_view_arr as $video_key => $video)
                                        @php
                                            if ($count == 7) {
                                                break;
                                            }
                                        @endphp
                                    <div class="swiper-slide">
                                        <div class="listing">

                                            <!--Begin: slider body  -->
                                            <br><br><br>
                                                    <div class="post-slide">
                                                        <div class="post-img">
                                                            <div class='c-video__image-container vid-fit-reveal box-video-youtube'
                                                                data-id="{{ $video['id'] }}" id="#js-fitvideo_{{ $video['id'] }}" data-vid="box-video-youtube_{{ $video['id'] }}">
                                                                <a href="" target="_blank" class="bg-video" id="youtube_id_{{ $video['id'] }}" data-toggle="modal" data-target="#youtubeModal"
                                                                    data-src="{{ $video['media_url'] }}">
                                                                    <div class="bt-play" id="bt-play_{{ $video['id'] }}"></div>
                                                                </a>
                                                                <iframe width="560" height="215" src="{{ $video['media_url']}}"  id="vid-reveal_{{ $video['id'] }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                                encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                            </div>
                                                        </div>
                                        
                                                        <div class="view_eye_post">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                            <p class="views" style="color: black;">View</p>
                                                            <span class="youtube_section_number">: {{$video['totalcount'] }}</span>
                                                        </div>
                                                    </div>
                                                    <!--End: slider body  -->
                                                    
                                        </div>
                                    </div>
                                    @php $count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </section>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next id-five-22"></div>
                    <div class="swiper-button-prev id-six-22"></div>
                    </div>
                    <!--End: trendding video slider -->

                </div>
            </div>
        </div>
    @endif

    @if($post_count >= 7)
        <!-- Begin: Trending Video's -->
        <div class="@if($recent_blog->count() > 0) bg-light @else '' @endif">
            <div class="container">
                <div class="row mb-3 align-items-center" id="Trending-Videos">
                    <div class="col-md-7 text-left border-primary">
                        <h2 class="font-weight-light text-primary mt-5">Trending Videos
                        </h2>
                    </div>

                    <div class="col-md-12">
                        <div class="below_info justify-content-end">
                                <a href="{{ route('page.trending_videos') }}">
                                    <button class="btn btn-primary btn-sm">
                                        View All
                                    </button>
                                </a>
                            
                        </div>
                    </div>

                    <!--Begin: trendding video slider -->      
                            <div class="col-md-12 padding-0-set-sm">
                                <section class="card_texting container ptb-100-500 ptb-sm-50-50">
                                    <div class="swiper-container-latest-1 swiper_topics_carousel_set">
                                        <div class="swiper-wrapper">
                                        @php $count = 1; @endphp
                                        @foreach ($AllMedia_view_arr as $video_key => $video)
                                            @php
                                                if ($count == 8) {
                                                    break;
                                                }
                                            @endphp

                                                <div class="swiper-slide">
                                                    <div class="listing">
                                        
                                                        <!--Begin: slider body  -->
                                                        <br><br><br>
                                                        <div class="post-slide">
                                                            <div class="post-img">
                                                                <div class='c-video__image-container vid-fit-reveal box-video-youtube'
                                                                    data-id="{{ $video['id'] }}" id="#js-fitvideo_{{ $video['id'] }}" data-vid="box-video-youtube_{{ $video['id'] }}">
                                                                    <a href="" target="_blank" class="bg-video" id="youtube_id_{{ $video['id'] }}" data-toggle="modal" data-target="#youtubeModal"
                                                                        data-src="{{ $video['media_url'] }}">
                                                                        <div class="bt-play" id="bt-play_{{ $video['id'] }}"></div>
                                                                    </a>
                                                                    <iframe width="560" height="215" src="{{ $video['media_url']}}"  id="vid-reveal_{{ $video['id'] }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                                    encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                                </div>
                                                            </div>
                                            
                                                            <div class="view_eye_post">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                <p class="views" style="color: black;">View</p>
                                                                <span class="youtube_section_number">: {{$video['totalcount'] }}</span>
                                                            </div>
                                                        </div>
                                                        <!--End: slider body  -->
                                                    
                                                            </div>
                                                        </div>
                                            
                                            @php $count++; @endphp
                                            @endforeach
                                        </div>
                                    </div>
                                
                                </section>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-next id-five-1"></div>
                                <div class="swiper-button-prev id-six-1"></div>
                            </div>
                    <!--End: trendding video slider -->
                </div>
            </div>
        </div>
        <!-- End: Trending Video's -->
    @endif
@endif

<!-- For youtube modal-->
<div class="modal-4">
    <div class="modal fade" id="youtubeModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal_dialog" role="document">
            <div class="modal-content content_design3">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title" id="exampleModalLabel_youtube">Youtube</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <iframe src="" id="youtubeiframesrcURL" frameborder="0" class="min_h_300"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="reload_page_modal" tabindex="-1" role="dialog"
        aria-labelledby="reload_page_modal_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reload_page_modal_label">
                        {{ __('category_image_option.geolocation.modal-title') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('category_image_option.geolocation.modal-description') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded"
                        data-dismiss="modal">{{ __('category_image_option.geolocation.modal-button-no') }}</button>
                    <a href="{{ route('page.home') }}"
                        class="btn btn-primary text-white rounded">{{ __('category_image_option.geolocation.modal-button-reload') }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="nda_modal" tabindex="-1" role="dialog" aria-labelledby="nda_modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content" id="nda_modal_content">
                <div class="modal-header">
                    <h5 class="modal-title text-dark" id="exampleModalLongTitle">BETA CONTENT CREATOR AGREEMENT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="">
                        {!! $setting_agreement_data->setting_page_agreement_text !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary agree_class" data-dismiss="modal" id="i_agree">I
                        Agree</button>
                    <!-- <button type="button" class="btn btn-secondary agree_class" disabled data-dismiss="modal">Close</button> -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if ($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <!-- Youtube Background for Header -->
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"
        integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {

            var enabled = true;
            $('.swiper-slide').click(function() {
                if (enabled) {
                    $("swiper-slide a").fadeTo("fast", 0.5).removeAttr("href");
                } else {
                    $(".swiper-slide.swiper-slide-visible.swiper-slide-active a").fadeIn("fast").prop(
                        "href");
                }
                enabled = !enabled;
            })

            "use strict";

            /**
             * Start get user lat & lng location
             */
            function success(position) {
                const latitude = position.coords.latitude;
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
                    data: {},
                    success: function(result) {
                        console.log(result);

                        if (result.reload) {
                            $('#reload_page_modal').modal('show');
                        }
                    }
                });
            }

            function error() {
                console.log("Unable to retrieve your geolocation");
            }

            if (!navigator.geolocation) {

                console.log("Geolocation is not supported by your browser");
            } else {

                console.log("Locating geolocation ...");
                navigator.geolocation.getCurrentPosition(success, error);
            }
            /**
             * End get user lat & lng location
             */

            @if ($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
                /**
                 * Start Initial Youtube Background
                 */
                $("[data-youtube]").youtube_background();
                /**
                 * End Initial Youtube Background
                 */
            @endif

        });
        var is_terms_read = {{ $is_terms_read }};
        var is_login = {{ $is_login }};
        var user_accept_agreement_date = {{ $user_accept_agreement_date }};
        var agreement_updated_date = {{ $agreement_updated_date }};

        //console.log(user_accept_agreement_date, " ", agreement_updated_date);
        if (is_login && is_terms_read != 1) {
            //console.log("aaaaaaa")
            //$(window).on("scroll", function() {
            const expDate = new Date();
            expDate.setTime(expDate.getTime() + (5 * 60 * 1000));
            setTimeout(function() {
                $('#nda_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            }, 10000)
        }
        if (is_login && is_terms_read == 1 && agreement_updated_date > user_accept_agreement_date) {
            console.log("bbbbbbb")
            const expDate = new Date();
            expDate.setTime(expDate.getTime() + (5 * 60 * 1000));            
            setTimeout(function() {
                $('#nda_modal').modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            }, 10000)
        }

        $('#i_agree').click(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: '/ajax/terms/save',
                method: 'post',
                data: {},
                success: function(result) {}
            });
        });
    </script>
@endsection
@section('carousel_scripts')
    @if ($all_coaches->count() == 5 || $all_coaches->count() == 6)
        <script>
            // alert("dslkdlk");
            var swiper = new Swiper(".swiper-container-coaches", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 2,
                    slideShadows: true,
                },
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                // autoplay: 
                // {
                // delay: 1000,
                // },
                navigation: {
                    nextEl: '.swiper-button-next.id-one',
                    prevEl: '.swiper-button-prev.id-two',
                }
            });
        </script>
    @elseif($all_coaches->count() > 6)
        <script>
            var swiper = new Swiper(".swiper-container-coaches", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 3.8,
                    slideShadows: true,
                },
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },

                navigation: {
                    nextEl: '.swiper-button-next.id-one',
                    prevEl: '.swiper-button-prev.id-two',
                }
            });
        </script>
    @endif

    @if ($all_trainding_items->count() == 5 || $all_trainding_items->count() == 6)
        <script>
            // alert("dslkdlk");
            var swiper = new Swiper(".swiper-container-trainding-items", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 2,
                    slideShadows: true,
                },
                loop: true,

            });
        </script>
    @elseif($all_trainding_items->count() > 6)
        <script>
            var swiper = new Swiper(".swiper-container-trainding-items", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 3.8,
                    slideShadows: true,
                },
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next.id-three',
                    prevEl: '.swiper-button-prev.id-four',
                }
            });
        </script>
    @endif

    @if ($paid_items->count() == 5 || $paid_items->count() == 6)
        <script>
            // alert("dslkdlk");
            var swiper = new Swiper(".swiper-container-paid", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 2,
                    slideShadows: true,
                },
                loop: true,

            });
        </script>
    @elseif($paid_items->count() > 6)
        <script>
            var swiper = new Swiper(".swiper-container-paid", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 3.8,
                    slideShadows: true,
                },
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next.id-five',
                    prevEl: '.swiper-button-prev.id-six',
                }
            });
        </script>
    @endif

    @if ($latest_items->count() == 5 || $latest_items->count() == 6)
        <script>
            // alert("dslkdlk");
            var swiper = new Swiper(".swiper-container-latest", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 2,
                    slideShadows: true,
                },
                loop: true,
            });
        </script>
    @elseif($latest_items->count() > 6)
        <script>
            var swiper = new Swiper(".swiper-container-latest", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 3.8,
                    slideShadows: true,
                },
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next.id-five',
                    prevEl: '.swiper-button-prev.id-six',
                }
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            $(".overlay_effect").hide();
            $(window).on("scroll", function() {
                const expDate = new Date();
                expDate.setTime(expDate.getTime() + (24 * 60 * 60 * 1000));
                const cookieValue = $.cookie('index_modal_cookie');
                console.log(cookieValue);
                console.log(window.scrollY);
                if ($(window).scrollTop() > 1400) {
                    if (cookieValue !== "INDEXFEATUREDHANDMOVE") {
                        $(".overlay_effect").show();
                        $.cookie("index_modal_cookie", "INDEXFEATUREDHANDMOVE", {
                            expires: expDate
                        });
                        setTimeout(function() {
                            $(".overlay_effect").fadeOut()
                        }, 4000)
                    }
                }

            });
        });

    </script>
 
<!-- for trendding video's slider button-->
@if ($post_count > 4 && $post_count <= 6 )
        <script>
            // alert("dslkdlk");
            var swiper = new Swiper(".swiper-container-latest-2", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 2,
                    slideShadows: true,
                },
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next.id-five-22',
                    prevEl: '.swiper-button-prev.id-six-22',
                }
            });
        </script>
@endif

@if($post_count >= 7)
    <script>
            var swiper = new Swiper(".swiper-container-latest-1", {
                effect: "coverflow",
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: "auto",
                coverflowEffect: {
                    rotate: 0,
                    stretch: 0,
                    depth: 100,
                    modifier: 3.8,
                    slideShadows: true,
                },
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next.id-five-1',
                    prevEl: '.swiper-button-prev.id-six-1',
                }
            });
    </script>
@endif

<script>
// for count and create views
            $(document).ready(function() {
            $(".vid-fit-reveal").on('click', function() {
                var id = $(this).attr('data-id');
                var main_id = $(this).attr('id');

                $(main_id).addClass("reveal-video");
                $('#bt-play_' + id).hide();
                var myFrame = $(main_id).find('#vid-reveal_' + id);
                var url = $(myFrame).attr('src') + '?autoplay=1';
                $(myFrame).attr('src', url);

                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '<?php echo url('/media-visitor'); ?>',
                    data: {
                        'id': id
                    },
                    success: function(data) {},
                });
            });
        });


            //for youtube video playing

            $(".box-video-youtube").click(function () {
                $('#youtubeiframesrcURL').attr('src', '');
                // $('#youtubeiframesrcURL').removeAttr('class');
                $('#exampleModalLabel_podcast').text('');                


                var youtube_div_Id = $(this).data('vid');
                var split_youtube_div_id = youtube_div_Id.split('_')[1]; 
                // console.log(split_youtube_div_id)               
                var youtubeUrl = $('#youtube_id_'+split_youtube_div_id).data('src');
                // console.log(youtubeUrl)
                
                $('#youtubeiframesrcURL').attr('src', youtubeUrl);
            });

            $("#youtubeModal").on("hidden.bs.modal", function() {
                $('#youtubeiframesrcURL').attr('src', '');            
            });
</script>

<script>
    $(document).on('click', 'a[href^="#"]', function (event) {
    event.preventDefault();

    $('html, body').animate({
        scrollTop: $($.attr(this, 'href')).offset().top
    }, 1500);
});
</script>

@endsection
