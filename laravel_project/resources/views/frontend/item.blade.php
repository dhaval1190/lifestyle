@extends('frontend.layouts.app')

@section('styles')

    @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR && $site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <link rel="stylesheet" href="{{ asset('frontend/vendor/justified-gallery/justifiedGallery.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/colorbox/colorbox.css') }}" type="text/css">
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <!-- Start Google reCAPTCHA version 2 -->
    @if($site_global_settings->setting_site_recaptcha_item_lead_enable == \App\Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE)
        {!! htmlScriptTagJsApi(['lang' => empty($site_global_settings->setting_site_language) ? 'en' : $site_global_settings->setting_site_language]) !!}
    @endif
    <!-- End Google reCAPTCHA version 2 -->
@endsection
<style>
     .disabled-login-error{
        color:red;
    }
    .error{
        color:red;
    }
    .form-section{
    display: none;
    }
    .form-section.current{
        display: inline;
    }
    .parsley-errors-list{
        color:red;
    } 
</style>
@section('content')

@php 
    $user_detail = '';
    $userId = '';
    $user_detail = Auth::user();
    if(isset($user_detail)){
        $userId = $user_detail->id;

    }
@endphp

    <!-- Display on xl -->
    @if(!empty($item->item_image) && !empty($item->item_image_blur))
        <div class="site-blocks-cover inner-page-cover overlay d-none d-xl-flex" style="background-image: url({{ Storage::disk('public')->url('item/' . $item->item_image_blur) }});">
    @else
        <div class="site-blocks-cover inner-page-cover overlay d-none d-xl-flex" style="background-image: url({{ asset('frontend/images/placeholder/full_item_feature_image.webp') }});">
    @endif
        <div class="container">
            <div class="row align-items-center item-blocks-cover">

                <div class="col-lg-2 col-md-2" data-aos="fade-up" data-aos-delay="400">
                    @if(!empty($item->item_image_tiny))
                        <img src="{{ Storage::disk('public')->url('item/' . $item->item_image_tiny) }}" alt="Image" class="img-fluid rounded">
                    @elseif(!empty($item->item_image))
                        <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image" class="img-fluid rounded">
                    @else
                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                    @endif
                </div>
                <div class="col-lg-7 col-md-5" data-aos="fade-up" data-aos-delay="400">

                    <h1 class="item-cover-title-section" style="word-break: break-all;">{{ $item->item_title }}</h1>

                    @if($item_has_claimed)
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <span class="text-primary">
                                <i class="fas fa-check-circle"></i>
                                {{ __('item_claim.claimed') }}
                            </span>
                        </div>
                    </div>
                    @endif

                    @if($item_count_rating > 0)
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="rating_stars_header"></div>
                        </div>
                        <div class="col-md-9 pl-0">
                            <span class="item-cover-address-section">
                                @if($item_count_rating == 1)
                                    {{ '(' . $item_count_rating . ' ' . __('review.frontend.review') . ')' }}
                                @else
                                    {{ '(' . $item_count_rating . ' ' . __('review.frontend.reviews') . ')' }}
                                @endif
                            </span>
                        </div>
                    </div>
                    @endif

                    @foreach($item_display_categories as $item_display_categories_key => $item_category)
                    <a class="btn btn-sm btn-outline-primary rounded mb-2" href="{{ route('page.category', $item_category->category_slug) }}">
                        <span class="category">{{ $item_category->category_name }}</span>
                    </a>
                    @endforeach

                    @if($item_total_categories > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                        <a class="text-primary" href="#" data-toggle="modal" data-target="#showCategoriesModal">
                            {{ __('categories.and') . " " . strval($item_total_categories - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " ". __('categories.more') }}
                            <i class="far fa-window-restore text-primary"></i>
                        </a>
                    @endif

                    @if(isset($user_detail->id))
                        @if($user_detail->id == $item->user_id)
                            <p class="item-cover-address-section">
                                @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                    @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                                        {{ $item->item_address }} <br>
                                    @endif
                                    {{ $item->city->city_name }}, {{ $item->state->state_name }}
                                    @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                                    {{ $item->item_postal_code }}
                                    @endif
                                @endif
                            </p>
                        @endif
                    @endif

                    @guest
                        <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                    @else

                        @if($item->user_id != Auth::user()->id)

                            @if(Auth::user()->isAdmin())
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @else
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @endif

                        @endif

                    @endguest
                    <a class="btn btn-primary rounded text-white item-share-button"><i class="fas fa-share-alt"></i> {{ __('frontend.item.share') }}</a>
                    @guest
                        <a class="btn btn-primary rounded text-white" id="item-save-button-xl"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                        <form id="item-save-form-xl" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                            @csrf
                        </form>
                    @else
                        @if(Auth::user()->hasSavedItem($item->id))
                            <a class="btn btn-warning rounded text-white" id="item-saved-button-xl"><i class="fas fa-check"></i> {{ __('frontend.item.saved') }}</a>
                            <form id="item-unsave-form-xl" action="{{ route('page.item.unsave', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @else
                            <a class="btn btn-primary rounded text-white" id="item-save-button-xl"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                            <form id="item-save-form-xl" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @endif
                    @endguest
                            <?php
                            $userId = '';
                              if(isset(Auth::user()->id)){
                                  $userId = Auth::user()->id ? Auth::user()->id : '';
                                }
                            ?>

                    {{-- @if(isset(Auth::user()->id)) --}}
                        @if($item->user_id != $userId)
                        {{-- <a class="btn btn-primary rounded text-white" href="tel:{{ $item->item_phone }}"><i class="fas fa-phone-alt"></i> {{ __('frontend.item.call') }}</a> --}}
                        <a class="btn btn-primary rounded text-white item-contact-button"><i class="fas fa-phone-alt"></i> {{ __('Contact This Coach') }}</a>
                        {{-- @endif --}}
                    <!-- <a class="btn btn-primary rounded text-white" href="#" data-toggle="modal" data-target="#qrcodeModal"><i class="fas fa-qrcode"></i> {{ __('theme_directory_hub.listing.qr-code') }}</a> -->
                    @endif
                </div>
                <div class="col-lg-3 col-md-5 pl-0 pr-0 item-cover-contact-section" data-aos="fade-up" data-aos-delay="400">
                    @if(!empty($item->item_phone))
                        <!-- <h3><i class="fas fa-phone-alt"></i> {{ $item->item_phone }}</h3> -->
                    @endif
                    <p>
                        @if(!empty($item->item_website))
                            <a class="mr-1" href="{{ $item->item_website }}" target="_blank" rel="nofollow"><i class="fa-solid fa-globe"></i></a>
                        @endif
                        @if(!empty($item->item_social_facebook))
                            <a class="mr-1" href="{{ $item->item_social_facebook }}" target="_blank" rel="nofollow"><i class="fa-brands fa-facebook-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_twitter))
                            <a class="mr-1" href="{{ $item->item_social_twitter }}" target="_blank" rel="nofollow"><i class="fa-brands fa-twitter-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_linkedin))
                            <a class="mr-1" href="{{ $item->item_social_linkedin }}" target="_blank" rel="nofollow"><i class="fa-brands fa-linkedin"></i></a>
                        @endif
                        @if(!empty($item->item_social_instagram))
                            <a class="mr-1" href="https://instagram.com/_u/{{ $item->item_social_instagram }}/" target="_blank" rel="nofollow"><i class="fa-brands fa-instagram-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_whatsapp))
                            <a class="mr-1" href="https://wa.me/{{ $item->item_social_whatsapp }}" target="_blank" rel="nofollow"><i class="fa-brands fa-whatsapp-square"></i></a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Display on lg, md -->
    @if(!empty($item->item_image) && !empty($item->item_image_blur))
        <div class="site-blocks-cover inner-page-cover overlay d-none d-md-flex d-lg-flex d-xl-none" style="background-image: url({{ Storage::disk('public')->url('item/' . $item->item_image_blur) }});">
    @else
        <div class="site-blocks-cover inner-page-cover overlay d-none d-md-flex d-lg-flex d-xl-none" style="background-image: url({{ asset('frontend/images/placeholder/full_item_feature_image.webp') }});">
    @endif
        <div class="container">
            <div class="row align-items-center item-blocks-cover">
                <div class="col-lg-2 col-md-3" data-aos="fade-up" data-aos-delay="400">
                    @if(!empty($item->item_image_tiny))
                        <img src="{{ Storage::disk('public')->url('item/' . $item->item_image_tiny) }}" alt="Image" class="img-fluid rounded">
                    @elseif(!empty($item->item_image))
                        <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image" class="img-fluid rounded">
                    @else
                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                    @endif
                </div>
                <div class="col-lg-7 col-md-9" data-aos="fade-up" data-aos-delay="400">

                    <h1 class="item-cover-title-section">{{ $item->item_title }}</h1>

                    @if($item_has_claimed)
                        <div class="row mb-2">
                            <div class="col-md-12">
                            <span class="text-primary">
                                <i class="fas fa-check-circle"></i>
                                {{ __('item_claim.claimed') }}
                            </span>
                            </div>
                        </div>
                    @endif

                    @if($item_count_rating > 0)
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="rating_stars_header"></div>
                            </div>
                            <div class="col-md-8 pl-0">
                                <span class="item-cover-address-section">
                                    @if($item_count_rating == 1)
                                        {{ '(' . $item_count_rating . ' ' . __('review.frontend.review') . ')' }}
                                    @else
                                        {{ '(' . $item_count_rating . ' ' . __('review.frontend.reviews') . ')' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif

                    @foreach($item_display_categories as $key => $item_category)
                        <a class="btn btn-sm btn-outline-primary rounded mb-2" href="{{ route('page.category', $item_category->category_slug) }}">
                            <span class="category">{{ $item_category->category_name }}</span>
                        </a>
                    @endforeach

                    @if($item_total_categories > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                        <a class="text-primary" href="#" data-toggle="modal" data-target="#showCategoriesModal">
                            {{ __('categories.and') . " " . strval($item_total_categories - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " ". __('categories.more') }}
                            <i class="far fa-window-restore text-primary"></i>
                        </a>
                    @endif

                    <p class="item-cover-address-section">
                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                                {{ $item->item_address }} <br>
                            @endif
                            {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                        @endif
                    </p>

                    @if(!empty($item->item_phone))
                        <!-- <p class="item-cover-address-section"><i class="fas fa-phone-alt"></i> {{ $item->item_phone }}</p> -->
                    @endif
                    <p class="item-cover-address-section">
                        @if(!empty($item->item_website))
                            <a class="mr-1" href="{{ $item->item_website }}" target="_blank" rel="nofollow"><i class="fa-solid fa-globe"></i></a>
                        @endif
                        @if(!empty($item->item_social_facebook))
                            <a class="mr-1" href="{{ $item->item_social_facebook }}" target="_blank" rel="nofollow"><i class="fa-brands fa-facebook-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_twitter))
                            <a class="mr-1" href="{{ $item->item_social_twitter }}" target="_blank" rel="nofollow"><i class="fa-brands fa-twitter-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_linkedin))
                            <a class="mr-1" href="{{ $item->item_social_linkedin }}" target="_blank" rel="nofollow"><i class="fa-brands fa-linkedin"></i></a>
                        @endif
                        @if(!empty($item->item_social_instagram))
                            <a class="mr-1" href="https://instagram.com/_u/{{ $item->item_social_instagram }}/" target="_blank" rel="nofollow"><i class="fa-brands fa-instagram-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_whatsapp))
                            <a class="mr-1" href="https://wa.me/{{ $item->item_social_whatsapp }}" target="_blank" rel="nofollow"><i class="fa-brands fa-whatsapp-square"></i></a>
                        @endif
                    </p>

                    @guest
                        <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                    @else

                        @if($item->user_id != Auth::user()->id)

                            @if(Auth::user()->isAdmin())
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @else
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @endif

                        @endif

                    @endguest
                    <a class="btn btn-primary rounded text-white item-share-button"><i class="fas fa-share-alt"></i> {{ __('frontend.item.share') }}</a>
                    @guest
                        <a class="btn btn-primary rounded text-white" id="item-save-button-md"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                        <form id="item-save-form-md" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                            @csrf
                        </form>
                    @else
                        @if(Auth::user()->hasSavedItem($item->id))
                            <a class="btn btn-warning rounded text-white" id="item-saved-button-md"><i class="fas fa-check"></i> {{ __('frontend.item.saved') }}</a>
                            <form id="item-unsave-form-md" action="{{ route('page.item.unsave', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @else
                            <a class="btn btn-primary rounded text-white" id="item-save-button-md"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                            <form id="item-save-form-md" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @endif
                    @endguest

                    @if($item->user_id != $userId)
                        <a class="btn btn-primary rounded text-white item-contact-button contact_btn_set_sm_lg mt-2 mt-lg-0 "><i class="fas fa-phone-alt"></i> {{ __('Contact This Coach') }}</a>
                        <!-- <a class="btn btn-primary rounded text-white" href="#" data-toggle="modal" data-target="#qrcodeModal"><i class="fas fa-qrcode"></i></a> -->
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Display on sm and xs -->
    @if(!empty($item->item_image) && !empty($item->item_image_blur))
        <div class="site-blocks-cover site-blocks-cover-sm inner-page-cover overlay d-md-none" style="background-image: url({{ Storage::disk('public')->url('item/' . $item->item_image_blur) }});">
    @else
        <div class="site-blocks-cover site-blocks-cover-sm inner-page-cover overlay d-md-none" style="background-image: url({{ asset('frontend/images/placeholder/full_item_feature_image.webp') }});">
    @endif
        <div class="container">
            <div class="row align-items-center item-blocks-cover-sm">
                <div class="col-12" data-aos="fade-up" data-aos-delay="400">

                    <h1 class="item-cover-title-section item-cover-title-section-sm-xs">{{ $item->item_title }}</h1>

                    @if($item_has_claimed)
                        <div class="row mb-2">
                            <div class="col-md-12">
                            <span class="text-primary">
                                <i class="fas fa-check-circle"></i>
                                {{ __('item_claim.claimed') }}
                            </span>
                            </div>
                        </div>
                    @endif

                    @if($item_count_rating > 0)
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="rating_stars_header"></div>
                            </div>
                            <div class="col-6 pl-0">
                                <span class="item-cover-address-section item-cover-address-section-sm-xs">
                                    @if($item_count_rating == 1)
                                        {{ '(' . $item_count_rating . ' ' . __('review.frontend.review') . ')' }}
                                    @else
                                        {{ '(' . $item_count_rating . ' ' . __('review.frontend.reviews') . ')' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif

                    @foreach($item_display_categories as $key => $item_category)
                        <a class="btn btn-sm btn-outline-primary rounded mb-2" href="{{ route('page.category', $item_category->category_slug) }}">
                            <span class="category">{{ $item_category->category_name }}</span>
                        </a>
                    @endforeach

                    @if($item_total_categories > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                        <a class="text-primary" href="#" data-toggle="modal" data-target="#showCategoriesModal">
                            {{ __('categories.and') . " " . strval($item_total_categories - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " ". __('categories.more') }}
                            <i class="far fa-window-restore text-primary"></i>
                        </a>
                    @endif

                    <p class="item-cover-address-section item-cover-address-section-sm-xs">
                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                                {{ $item->item_address }} <br>
                            @endif
                            {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                        @endif
                    </p>

                    @if(!empty($item->item_phone))
                        <!-- <p class="item-cover-address-section item-cover-address-section-sm-xs">
                            <i class="fas fa-phone-alt"></i> {{ $item->item_phone }}
                            <a class="btn btn-outline-primary btn-sm rounded" href="tel:{{ $item->item_phone }}">{{ __('frontend.item.call') }}</a>
                        </p> -->
                    @endif
                    <p class="item-cover-address-section item-cover-address-section-sm-xs">
                        @if(!empty($item->item_website))
                            <a class="mr-1" href="{{ $item->item_website }}" target="_blank" rel="nofollow"><i class="fa-solid fa-globe"></i></a>
                        @endif
                        @if(!empty($item->item_social_facebook))
                            <a class="mr-1" href="{{ $item->item_social_facebook }}" target="_blank" rel="nofollow"><i class="fa-brands fa-facebook-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_twitter))
                            <a class="mr-1" href="{{ $item->item_social_twitter }}" target="_blank" rel="nofollow"><i class="fa-brands fa-twitter-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_linkedin))
                            <a class="mr-1" href="{{ $item->item_social_linkedin }}" target="_blank" rel="nofollow"><i class="fa-brands fa-linkedin"></i></a>
                        @endif
                        @if(!empty($item->item_social_instagram))
                            <a class="mr-1" href="https://instagram.com/_u/{{ $item->item_social_instagram }}/" target="_blank" rel="nofollow"><i class="fa-brands fa-instagram-square"></i></a>
                        @endif
                        @if(!empty($item->item_social_whatsapp))
                            <a class="mr-1" href="https://wa.me/{{ $item->item_social_whatsapp }}" target="_blank" rel="nofollow"><i class="fa-brands fa-whatsapp-square"></i></a>
                        @endif
                    </p>

                    @guest
                        <a class="btn btn-primary btn-sm rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                    @else

                        @if($item->user_id != Auth::user()->id)

                            @if(Auth::user()->isAdmin())
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary btn-sm rounded text-white" href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary btn-sm rounded text-white" href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @else
                                @if($item->reviewedByUser(Auth::user()->id))
                                    <a class="btn btn-primary btn-sm rounded text-white" href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                @else
                                    <a class="btn btn-primary btn-sm rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                @endif

                            @endif

                        @endif

                    @endguest
                    <a class="btn btn-primary btn-sm rounded text-white item-share-button"><i class="fas fa-share-alt"></i> {{ __('frontend.item.share') }}</a>
                    @guest
                        <a class="btn btn-primary btn-sm rounded text-white" id="item-save-button-sm"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                        <form id="item-save-form-sm" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                            @csrf
                        </form>
                    @else
                        @if(Auth::user()->hasSavedItem($item->id))
                            <a class="btn btn-warning btn-sm rounded text-white" id="item-saved-button-sm"><i class="fas fa-check"></i> {{ __('frontend.item.saved') }}</a>
                            <form id="item-unsave-form-sm" action="{{ route('page.item.unsave', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @else
                            <a class="btn btn-primary btn-sm rounded text-white" id="item-save-button-sm"><i class="far fa-bookmark"></i> {{ __('frontend.item.save') }}</a>
                            <form id="item-save-form-sm" action="{{ route('page.item.save', ['item_slug' => $item->item_slug]) }}" method="POST" hidden="true">
                                @csrf
                            </form>
                        @endif
                    @endguest
                    @if($item->user_id != $userId)
                        <a class="btn btn-primary rounded text-white item-contact-button contact_btn_set_sm_lg mt-2 mt-lg-0 btn-sm"><i class="fas fa-phone-alt"></i> {{ __('Contact This Coach') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">

            @include('frontend.partials.alert')

            @if($ads_before_breadcrumb->count() > 0)
                @foreach($ads_before_breadcrumb as $ads_before_breadcrumb_key => $ad_before_breadcrumb)
                    <div class="row mb-3">
                        @if($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
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

            <div class="row mb-3">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('page.home') }}">
                                    <i class="fas fa-bars"></i>
                                    {{ __('frontend.header.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('page.categories') }}">{{ __('frontend.item.all-categories') }}</a></li>

                            @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            <li class="breadcrumb-item"><a href="{{ route('page.state', ['state_slug'=>$item->state->state_slug]) }}">{{ $item->state->state_name }}</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug]) }}">{{ $item->city->city_name }}</a></li>
                            @endif

                            <li class="breadcrumb-item active" aria-current="page">{{ $item->item_title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            @if($ads_after_breadcrumb->count() > 0)
                @foreach($ads_after_breadcrumb as $ads_after_breadcrumb_key => $ad_after_breadcrumb)
                    <div class="row mb-3">
                        @if($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
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

            <div class="row">
                <div class="col-lg-8">

                    @if(Auth::check() && Auth::user()->id == $item->user_id)
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    {{ __('products.alert.this-is-your-item') }}
                                    @if(Auth::user()->isAdmin())
                                        <a class="pl-1" target="_blank" href="{{ route('admin.items.edit', $item->id) }}">
                                            <i class="fas fa-external-link-alt"></i>
                                            {{ __('products.edit-item-link') }}
                                        </a>
                                    @else
                                        @if(Auth::user()->isCoach())
                                            <a class="pl-1" target="_blank" href="{{ route('user.articles.edit', $item->id) }}">
                                                <i class="fas fa-external-link-alt"></i>
                                                {{ __('products.edit-item-link') }}
                                            </a>
                                        @else
                                            <a class="pl-1" target="_blank" href="{{ route('user.items.edit', $item->id) }}">
                                                <i class="fas fa-external-link-alt"></i>
                                                {{ __('products.edit-item-link') }}
                                            </a>
                                        @endif
                                    @endif
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- start item section after breadcrumb -->
                    @if($item_sections_after_breadcrumb->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_breadcrumb as $item_sections_after_breadcrumb_key => $after_breadcrumb_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_breadcrumb_section->item_section_title }}</h4>

                                    @php
                                        $after_breadcrumb_section_collections = $after_breadcrumb_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_breadcrumb_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_breadcrumb_section_collections as $after_breadcrumb_section_collections_key => $after_breadcrumb_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_breadcrumb_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_breadcrumb = \App\Product::find($after_breadcrumb_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_breadcrumb->product_slug]) }}">
                                                                    @if(empty($find_product_after_breadcrumb->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_breadcrumb->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_breadcrumb->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_breadcrumb->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_breadcrumb->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_breadcrumb->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_breadcrumb->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after breadcrumb -->

                    @if($ads_before_gallery->count() > 0)
                        @foreach($ads_before_gallery as $ads_before_gallery_key => $ad_before_gallery)
                            <div class="row mb-3">
                                @if($ad_before_gallery->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_gallery->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_gallery->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_gallery->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_gallery->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_gallery->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="card-deck mt-3">
                                <div class="card shadow border-0">
                                    <div class="card-body p-3">
                                        <p class="lead border-bottom">Lifetime Summary</p> 
                                        <div class="d-flex">
                                            <div class="mr-5">
                                                <p class="mb-0 small text-muted text-uppercase font-weight-bold"><b>Total Views</b></p> 
                                                <h3 class="mt-1">{{ $All_visit_count }}</h3>
                                            </div> 
                                        </div>
                                    </div>
                                </div> 
                                <div class="card shadow border-0">
                                    <div class="card-body p-3">
                                        <p class="lead border-bottom">Monthly Summary</p> 
                                        <div class="d-flex">
                                            <div class="mr-5">
                                                <p class="mb-0 small text-muted text-uppercase font-weight-bold">
                                                    <b>View(s)</b> <i class="fa-solid fa-circle-question" title="A view is counted when a visitor loads or reloads a page."></i>
                                                </p> 
                                                <h3 class="mt-1 mb-2">{{ $view_count }}</h3> 
                                                @php
                                                $view_arrow = ($view_month_over_month['direction']=='up') ? 'fa fa-arrow-up' : 'fa fa-arrow-down';
                                                $visit_arrow = ($visit_month_over_month['direction']=='up') ? 'fa fa-arrow-up' : 'fa fa-arrow-down';
                                                @endphp
                                                <p class="small text-muted"><i class="{{ $view_arrow }}"></i> {{ $view_month_over_month['percentage'] }}% from last month</p>
                                            </div> 
                                            <div>
                                                <p class="mb-0 small text-muted text-uppercase font-weight-bold">
                                                    <b>Visitor(s)</b> <i class="fa-solid fa-circle-question" title="A visitor is counted when we see a user or browser for the first time in a given 24-hour period."></i>
                                                </p> 
                                                <h3 class="mt-1 mb-2">{{ $visit_count }}</h3> 
                                                <p class="small text-muted"><i class="{{ $visit_arrow }}"></i> {{ $visit_month_over_month['percentage'] }}% from last month</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="row mb-3 <?php echo $item->galleries()->count() > 0 ? 'bg_determine' : '';?>">
                        <div class="col-12">
                            <div class="row mt-2">
                                @if($item->galleries()->count() > 0)
                                        @php
                                        $item_galleries = $item->galleries()->get();
                                        @endphp
                                        @foreach($item_galleries as $galleries_key => $gallery)
                                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
                                            <div id="item-image-gallery">
                                                <a href="{{ Storage::disk('public')->url('item/gallery/' . $gallery->item_image_gallery_name) }}" rel="item-image-gallery-thumb">
                                                    <img alt="Image" src="{{ empty($gallery->item_image_gallery_thumb_name) ? Storage::disk('public')->url('item/gallery/' . $gallery->item_image_gallery_name) : Storage::disk('public')->url('item/gallery/' . $gallery->item_image_gallery_thumb_name) }}"/>
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="col-12">                    
                                            @if(empty($item->item_image))
                                                <img src="{{ asset('frontend/images/placeholder/full_item_feature_image.webp') }}" alt="Image" class="img-fluid rounded" style= "width:100%;">
                                            @else
                                                <img src="{{ Storage::disk('public')->url('item/' . $item->item_image) }}" alt="Image" class="img-fluid rounded" style= "width:100%;">
                                            @endif       
                                        </div>
                                @endif
                            </div>
                            <hr>
                        </div>
                    </div>

                    <!-- start item section after gallery -->
                    @if($item_sections_after_gallery->count() > 0)
                        <div class="row mb-3 padding-set-sm">
                            <div class="col-12">
                                @foreach($item_sections_after_gallery as $item_sections_after_gallery_key => $after_gallery_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_gallery_section->item_section_title }}</h4>

                                    @php
                                        $after_gallery_section_collections = $after_gallery_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_gallery_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_gallery_section_collections as $after_gallery_section_collections_key => $after_gallery_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_gallery_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_gallery = \App\Product::find($after_gallery_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_gallery->product_slug]) }}">
                                                                    @if(empty($find_product_after_gallery->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_gallery->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_gallery->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_gallery->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_gallery->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_gallery->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_gallery->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after gallery -->

                    {{-- @if(!empty($item->item_youtube_id))

                        <div class="row mb-3">
                            <div class="col-12">
                                <h4 class="h5 mb-4 text-black">{{ __('customization.item.video') }}</h4>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="https://www.youtube-nocookie.com/embed/{{ $item->item_youtube_id }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <hr>
                            </div>
                        </div>

                    @endif --}}

                    @php
                    $item_video_url = '';
                    if($item->medias && count($item->medias) > 0) {
                        foreach($item->medias()->orderBy('item_media.id','desc')->get() as $media) {
                            if($media->media_type == \App\ItemMedia::MEDIA_TYPE_VIDEO && !empty($media->media_url)) {
                                $item_video_url = $media->media_url;
                                break;
                            }
                        }
                    }
                    @endphp

                    @if(!empty($item_video_url))
                        <div class="row mb-3">
                            <div class="col-12">
                                <h4 class="h5 mb-4 text-black">{{ __('customization.item.video') }}</h4>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="{{ $item_video_url }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <!-- <div style="max-width:854px"><div style="position:relative;height:0;padding-bottom:56.25%"><iframe src="https://embed.ted.com/talks/bunny_young_what_you_don_t_understand_about_the_power_of_purpose" width="854" height="480" style="position:absolute;left:0;top:0;width:100%;height:100%" frameborder="0" scrolling="no" allowfullscreen></iframe></div></div>
                                <div style="max-width:854px"><div style="position:relative;height:0;padding-bottom:56.25%"><iframe src="https://embed.ted.com/talks/taryn_simon_the_stories_behind_the_bloodlines" width="854" height="480" style="position:absolute;left:0;top:0;width:100%;height:100%" frameborder="0" scrolling="no" allowfullscreen></iframe></div></div>
                                <hr> -->
                            </div>
                        </div>
                    @endif

                    {{-- @if(!empty($item->item_youtube_id))
                        <div class="row mb-3">
                            <div class="col-12">
                                <h4 class="h5 mb-4 text-black">{{ __('customization.item.video') }}</h4>
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="https://www.youtube-nocookie.com/embed/{{ $item->item_youtube_id }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <hr>
                            </div>
                        </div>
                    @endif --}}

                    @if($ads_before_description->count() > 0)
                        @foreach($ads_before_description as $ads_before_description_key => $ad_before_description)
                            <div class="row mb-3">
                                @if($ad_before_description->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_description->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_description->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_description->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_description->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_description->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    @if(!empty($item->item_description))
                    <div class="row mb-3">
                        <div class="col-12">
                            <h4 class="h5 mb-4 text-black" style="word-break: break-all;">{{ $item->item_title }}</h4>
                            {{-- <p>{!! clean(nl2br($item->item_description), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}</p> --}}
                            <p>{!! html_entity_decode($item->item_description) !!}</p>
                            <hr>
                        </div>
                    </div>
                    @endif

                    <!-- start item section after description -->
                    @if($item_sections_after_description->count() > 0)
                        <div class="row mb-3 padding-set-sm">
                            <div class="col-12">
                                @foreach($item_sections_after_description as $item_sections_after_description_key => $after_description_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_description_section->item_section_title }}</h4>

                                    @php
                                        $after_description_section_collections = $after_description_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_description_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_description_section_collections as $after_description_section_collections_key => $after_description_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_description_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_description = \App\Product::find($after_description_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_description->product_slug]) }}">
                                                                    @if(empty($find_product_after_description->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_description->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_description->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_description->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_description->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_description->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_description->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after description -->

                    @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)

                        @if($ads_before_location->count() > 0)
                            @foreach($ads_before_location as $ads_before_location_key => $ad_before_location)
                                <div class="row mb-3">
                                    @if($ad_before_location->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_before_location->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_location->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_before_location->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_location->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_before_location->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            @endforeach
                        @endif

                        {{-- <div class="row mb-3">
                            <div class="col-12">
                                <h4 class="h5 mb-4 text-black">{{ __('frontend.item.location') }}</h4>
                                <div class="row pt-2 pb-2">
                                    <div class="col-12">
                                        <div id="mapid-item"></div>

                                        <div class="row align-items-center pt-2" style="word-break: break-all;">
                                            <div class="col-7">
                                                <small>
                                                    @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                                                        {{ $item->item_address }}
                                                    @endif
                                                        {{ $item->city->city_name }}, {{ $item->state->state_name }} {{ $item->item_postal_code }}
                                                </small>
                                            </div>
                                            <div class="col-5 text-right">
                                                <a class="btn btn-primary btn-sm rounded text-white" href="{{ 'https://www.google.com/maps/dir/?api=1&destination=' . $item->item_lat . ',' . $item->item_lng }}" target="_blank">
                                                    <i class="fas fa-directions"></i>
                                                    {{ __('google_map.get-directions') }}
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div> --}}

                        <!-- start item section after location map -->
                        @if($item_sections_after_location_map->count() > 0)
                            <div class="row mb-3">
                                <div class="col-12">
                                    @foreach($item_sections_after_location_map as $item_sections_after_location_map_key => $after_location_map_section)
                                        <h4 class="h5 mb-4 text-black">{{ $after_location_map_section->item_section_title }}</h4>

                                        @php
                                            $after_location_map_section_collections = $after_location_map_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                        @endphp

                                        @if($after_location_map_section_collections->count() > 0)
                                            <div class="row">
                                                @foreach($after_location_map_section_collections as $after_location_map_section_collections_key => $after_location_map_section_collection)
                                                    <div class="col-md-6 col-sm-12 mb-3">

                                                        @if($after_location_map_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                            @php
                                                                $find_product_after_location_map = \App\Product::find($after_location_map_section_collection->item_section_collection_collectible_id);
                                                            @endphp
                                                            <div class="row align-items-center border-right">
                                                                <div class="col-md-5 col-4">
                                                                    <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_location_map->product_slug]) }}">
                                                                        @if(empty($find_product_after_location_map->product_image_small))
                                                                            <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                        @else
                                                                            <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_location_map->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                                <div class="pl-0 col-md-7 col-8">

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <span class="text-black">{{ str_limit($find_product_after_location_map->product_name, 20) }}</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <span>{{ str_limit($find_product_after_location_map->product_description, 40) }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            @if(!empty($find_product_after_location_map->product_price))
                                                                                <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_location_map->product_price, 2) }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-1">
                                                                        <div class="col-12">
                                                                            <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_location_map->product_slug]) }}">
                                                                                {{ __('item_section.read-more') }}
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <!-- end item section after location map -->

                    @endif

                    @if($ads_before_features->count() > 0)
                        @foreach($ads_before_features as $ads_before_features_key => $ad_before_features)
                            <div class="row mb-3">
                                @if($ad_before_features->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_features->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_features->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_features->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_features->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_features->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    @if($item_features->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                <h4 class="h5 mb-4 text-black">{{ __('frontend.item.features') }}</h4>

                                @foreach($item_features as $item_features_key => $feature)

                                    <div class="row pt-2 pb-2 mt-2 mb-2 border-left {{ $item_features_key%2 == 0 ? 'bg-light' : '' }}">
                                            <div class="col-3">
                                                {{ $feature->customField->custom_field_name }}
                                            </div>

                                            <div class="col-9">
                                                @if($feature->item_feature_value)
                                                    @if($feature->customField->custom_field_type == \App\CustomField::TYPE_LINK)
                                                        @php
                                                            $parsed_url = parse_url($feature->item_feature_value);
                                                        @endphp

                                                        @if(is_array($parsed_url) && array_key_exists('host', $parsed_url))
                                                            <a target="_blank" rel=”nofollow” href="{{ $feature->item_feature_value }}">
                                                                {{ $parsed_url['host'] }}
                                                            </a>
                                                        @else
                                                            {!! clean(nl2br($feature->item_feature_value), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}
                                                        @endif

                                                    @elseif($feature->customField->custom_field_type == \App\CustomField::TYPE_MULTI_SELECT)
                                                        @if(count(explode(',', $feature->item_feature_value)))

                                                            @foreach(explode(',', $feature->item_feature_value) as $item_feature_value_multiple_select_key => $value)
                                                                <span class="review">{{ $value }}</span>
                                                            @endforeach

                                                        @else
                                                            {{ $feature->item_feature_value }}
                                                        @endif

                                                    @elseif($feature->customField->custom_field_type == \App\CustomField::TYPE_SELECT)
                                                        {{ $feature->item_feature_value }}

                                                    @elseif($feature->customField->custom_field_type == \App\CustomField::TYPE_TEXT)
                                                        {!! clean(nl2br($feature->item_feature_value), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                @endforeach
                                <hr>
                            </div>
                        </div>
                    @endif

                    <!-- start item section after features -->
                    @if($item_sections_after_features->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_features as $item_sections_after_features_key => $after_features_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_features_section->item_section_title }}</h4>

                                    @php
                                        $after_features_section_collections = $after_features_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_features_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_features_section_collections as $after_features_section_collections_key => $after_features_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_features_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_features = \App\Product::find($after_features_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_features->product_slug]) }}">
                                                                    @if(empty($find_product_after_features->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_features->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_features->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_features->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_features->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_features->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_features->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after features -->

                    @if($ads_before_reviews->count() > 0)
                        @foreach($ads_before_reviews as $ads_before_reviews_key => $ad_before_reviews)
                            <div class="row mb-3">
                                @if($ad_before_reviews->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_reviews->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_reviews->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_reviews->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_reviews->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_reviews->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <div class="row mb-3">
                        <div class="col-12">
                            <h4 id="review-section" class="h5 mb-4 text-black">{{ __('review.frontend.reviews-cap') }}</h4>
                            @if($reviews->count() == 0)

                                @guest
                                    <div class="row mb-3 pt-3 pb-3 bg-light">
                                        <div class="col-md-12 text-center">
                                            <p class="mb-0">
                                                <span class="icon-star text-warning"></span>
                                                <span class="icon-star text-warning"></span>
                                                <span class="icon-star text-warning"></span>
                                                <span class="icon-star text-warning"></span>
                                                <span class="icon-star text-warning"></span>
                                            </p>
                                            <span>{{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}</span>

                                            <div class="row mt-2">
                                                <div class="col-md-12 text-center">
                                                    <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @else
                                    @if($item->user_id != Auth::user()->id)

                                        @if($item->reviewedByUser(Auth::user()->id))
                                            <div class="row mb-3 pt-3 pb-3 bg-light">
                                                <div class="col-md-9">
                                                    {{ __('review.frontend.posted-a-review', ['item_name' => $item->item_title]) }}
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    @if(Auth::user()->isAdmin())
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                                    @else
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                                    @endif
                                                </div>
                                            </div>

                                        @else
                                            <div class="row mb-3 pt-3 pb-3 bg-light">
                                                <div class="col-md-12 text-center">
                                                    <p class="mb-0">
                                                        <span class="icon-star text-warning"></span>
                                                        <span class="icon-star text-warning"></span>
                                                        <span class="icon-star text-warning"></span>
                                                        <span class="icon-star text-warning"></span>
                                                        <span class="icon-star text-warning"></span>
                                                    </p>
                                                    <span>{{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}</span>

                                                    <div class="row mt-2">
                                                        <div class="col-md-12 text-center">
                                                            @if(Auth::user()->isAdmin())
                                                                <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                                            @else
                                                                <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endif

                                    @else
                                        <div class="row mb-3 pt-3 pb-3 bg-light">
                                            <div class="col-md-12 text-center">
                                                <span style="word-break: break-all;">{{ __('review.frontend.no-review', ['item_name' => $item->item_title]) }}</span>
                                            </div>
                                        </div>
                                    @endif
                                @endguest

                            @else

                                <div class="row mb-3 pt-3 pb-3 bg-light">
                                    <div class="col-md-9">
                                        @guest
                                            {{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}
                                        @else
                                            @if($item->user_id != Auth::user()->id)

                                                @if(Auth::user()->isAdmin())
                                                    @if($item->reviewedByUser(Auth::user()->id))
                                                        {{ __('review.frontend.posted-a-review', ['item_name' => $item->item_title]) }}
                                                    @else
                                                        {{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}
                                                    @endif

                                                @else
                                                    @if($item->reviewedByUser(Auth::user()->id))
                                                        {{ __('review.frontend.posted-a-review', ['item_name' => $item->item_title]) }}
                                                    @else
                                                        {{ __('review.frontend.start-a-review', ['item_name' => $item->item_title]) }}
                                                    @endif

                                                @endif

                                            @else
                                                {{ __('review.frontend.my-reviews') }}
                                            @endif
                                        @endguest
                                    </div>
                                    <div class="col-md-3 text-right">
                                        @guest
                                            <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                        @else

                                            @if($item->user_id != Auth::user()->id)

                                                @if(Auth::user()->isAdmin())
                                                    @if($item->reviewedByUser(Auth::user()->id))
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                                    @else
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('admin.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                                    @endif

                                                @else
                                                    @if($item->reviewedByUser(Auth::user()->id))
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.edit', ['item_slug' => $item->item_slug, 'review' => $item->getReviewByUser(Auth::user()->id)->id]) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.edit-a-review') }}</a>
                                                    @else
                                                        <a class="btn btn-primary rounded text-white" href="{{ route('user.items.reviews.create', $item->item_slug) }}" target="_blank"><i class="fas fa-star"></i> {{ __('review.backend.write-a-review') }}</a>
                                                    @endif

                                                @endif

                                            @endif

                                        @endguest
                                    </div>
                                </div>

                                <!-- Start review summary -->
                                @if($item_count_rating > 0)

                                    <div class="row mt-4 mb-3">
                                        <div class="col-12 text-right">
                                            <form action="{{ route('page.item', ['item_slug' => $item->item_slug]) }}#review-section" method="GET" class="form-inline" id="item-rating-sort-by-form">
                                                <div class="form-group">
                                                    <label for="rating_sort_by">{{ __('rating_summary.sort-by') }}</label>
                                                    <select class="custom-select ml-2 @error('rating_sort_by') is-invalid @enderror" name="rating_sort_by" id="rating_sort_by">
                                                        <option value="{{ \App\Item::ITEM_RATING_SORT_BY_NEWEST }}" {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_NEWEST ? 'selected' : '' }}>{{ __('rating_summary.sort-by-newest') }}</option>
                                                        <option value="{{ \App\Item::ITEM_RATING_SORT_BY_OLDEST }}" {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_OLDEST ? 'selected' : '' }}>{{ __('rating_summary.sort-by-oldest') }}</option>
                                                        <option value="{{ \App\Item::ITEM_RATING_SORT_BY_HIGHEST }}" {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_HIGHEST ? 'selected' : '' }}>{{ __('rating_summary.sort-by-highest') }}</option>
                                                        <option value="{{ \App\Item::ITEM_RATING_SORT_BY_LOWEST }}" {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_LOWEST ? 'selected' : '' }}>{{ __('rating_summary.sort-by-lowest') }}</option>
                                                    </select>
                                                    @error('rating_sort_by')
                                                    <span class="invalid-tooltip">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-lg-3 bg-primary text-white mtb-10-set">
                                            <div id="review_summary">
                                                <strong>{{ number_format($item_average_rating, 1) }}</strong>
                                                @if($item_count_rating > 1)
                                                    <small>{{ __('rating_summary.based-on-reviews', ['item_rating_count' => $item_count_rating]) }}</small>
                                                @else
                                                    <small>{{ __('rating_summary.based-on-review', ['item_rating_count' => $item_count_rating]) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <!-- Rating Progeress Bar -->
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $item_one_star_percentage }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3"><small><strong>{{ __('rating_summary.1-stars') }}</strong></small></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $item_two_star_percentage }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3"><small><strong>{{ __('rating_summary.2-stars') }}</strong></small></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $item_three_star_percentage }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3"><small><strong>{{ __('rating_summary.3-stars') }}</strong></small></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $item_four_star_percentage }}%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3"><small><strong>{{ __('rating_summary.4-stars') }}</strong></small></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: {{ $item_five_star_percentage }}%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3"><small><strong>{{ __('rating_summary.5-stars') }}</strong></small></div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endif
                                <!-- End review summary -->

                                @foreach($reviews as $reviews_key => $review)
                                    <div class="row mb-3">
                                        <div class="col-md-4">

                                            <div class="row align-items-center mb-3">
                                                <div class="col-4">
                                                    @if(empty(\App\User::find($review->author_id)->user_image))
                                                        {{-- <img src="{{ asset('frontend/images/placeholder/profile-'. intval($review->author_id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle"> --}}
                                                        <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                                    @else
                                                        <img src="{{ Storage::disk('public')->url('user/' . \App\User::find($review->author_id)->user_image) }}" alt="{{ \App\User::find($review->author_id)->name }}" class="img-fluid rounded-circle">
                                                    @endif
                                                </div>
                                                <div class="col-8 pl-0">
                                                    <span>{{ \App\User::find($review->author_id)->name }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <span>{{ __('review.backend.overall-rating') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_{{ $review->id }}" data-id="rating_stars_{{ $review->id }}" data-rating="{{ $review->rating }}"></div>
                                                </div>
                                            </div>

                                            @if($review->recommend == \App\Item::ITEM_REVIEW_RECOMMEND_YES)
                                                <div class="row mb-2">
                                                    <div class="col-md-12">
                                                <span class="bg-success text-white pl-2 pr-2 pt-2 pb-2 rounded">
                                                    <i class="fas fa-check"></i>
                                                    {{ __('review.backend.recommend') }}
                                                </span>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <!-- <div class="col-md-8">

                                            <div class="row mb-0">
                                                <div class="col-md-6">
                                                    <span class="font-size-13">{{ __('review.backend.customer-service') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_customer_service_{{ $review->id }}" data-id="rating_stars_customer_service_{{ $review->id }}" data-rating="{{ $review->customer_service_rating }}"></div>
                                                </div>

                                                <div class="col-md-6">
                                                    <span class="font-size-13">{{ __('review.backend.quality') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_quality_{{ $review->id }}" data-id="rating_stars_quality_{{ $review->id }}" data-rating="{{ $review->quality_rating }}"></div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <span class="font-size-13">{{ __('review.backend.friendly') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_friendly_{{ $review->id }}" data-id="rating_stars_friendly_{{ $review->id }}" data-rating="{{ $review->friendly_rating }}"></div>
                                                </div>

                                                <div class="col-md-6">
                                                    <span class="font-size-13">{{ __('review.backend.pricing') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_pricing_{{ $review->id }}" data-id="rating_stars_pricing_{{ $review->id }}" data-rating="{{ $review->pricing_rating }}"></div>
                                                </div>
                                            </div>

                                            @if(!empty($review->title))
                                                <div class="row mb-2">
                                                    <div class="col-md-12">
                                                        <span class="text-black">{{ $review->title }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row mb-1">
                                                <div class="col-md-12">
                                                    <p>{!! clean(nl2br($review->body), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}</p>
                                                </div>
                                            </div>

                                            @if($item->reviewGalleryCountByReviewId($review->id))
                                                <div class="row mb-1">
                                                    <div class="col-md-12" id="review-image-gallery-{{ $review->id }}">
                                                        @foreach($item->getReviewGalleriesByReviewId($review->id) as $key_1 => $review_image_gallery)
                                                            <a href="{{ Storage::disk('public')->url('item/review/' . $review_image_gallery->review_image_gallery_name) }}" rel="review-image-gallery-thumb{{ $review->id }}">
                                                                <img alt="Image" src="{{ Storage::disk('public')->url('item/review/' . $review_image_gallery->review_image_gallery_thumb_name) }}"/>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <span class="review font-size-13">{{ __('review.backend.posted-at') . ' ' . \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                                                    @if($review->created_at != $review->updated_at)
                                                        <span class="review font-size-13">{{ __('review.backend.updated-at') . ' ' . \Carbon\Carbon::parse($review->updated_at)->diffForHumans() }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                        </div> -->
                                    </div>
                                    <hr>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- start item section after reviews -->
                    @if($item_sections_after_reviews->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_reviews as $item_sections_after_reviews_key => $after_reviews_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_reviews_section->item_section_title }}</h4>

                                    @php
                                        $after_reviews_section_collections = $after_reviews_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_reviews_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_reviews_section_collections as $after_reviews_section_collections_key => $after_reviews_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_reviews_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_reviews = \App\Product::find($after_reviews_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_reviews->product_slug]) }}">
                                                                    @if(empty($find_product_after_reviews->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_reviews->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_reviews->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_reviews->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_reviews->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_reviews->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_reviews->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>


                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after reviews -->

                    @if($ads_before_comments->count() > 0)
                        @foreach($ads_before_comments as $ads_before_comments_key => $ad_before_comments)
                            <div class="row mb-3">
                                @if($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_comments->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_comments->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_comments->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_comments->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <div class="row mb-3">
                        <div class="col-12">
                                @if(isset(Auth::user()->id) && $item->user_id != Auth::user()->id)
                                    <a class="btn btn-primary rounded text-white item-contact-button"><i class="fas fa-phone-alt"></i> {{ __('Contact This Coach') }}</a>
                                @endif
                            <h4 class="h5 mb-4 text-black mt-2">{{ __('frontend.item.comments') }}</h4>

                            @comments([
                            'model' => $item,
                            'approved' => true,
                            'perPage' => 200
                            ])

                            <hr>
                        </div>
                    </div>

                    <!-- start item section after comments -->
                    @if($item_sections_after_comments->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_comments as $item_sections_after_comments_key => $after_comments_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_comments_section->item_section_title }}</h4>

                                    @php
                                        $after_comments_section_collections = $after_comments_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_comments_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_comments_section_collections as $after_comments_section_collections_key => $after_comments_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_comments_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_comments = \App\Product::find($after_comments_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_comments->product_slug]) }}">
                                                                    @if(empty($find_product_after_comments->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_comments->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_comments->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_comments->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_comments->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_comments->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_comments->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after comments -->

                    @if($ads_before_share->count() > 0)
                        @foreach($ads_before_share as $ads_before_share_key => $ad_before_share)
                            <div class="row mb-3">
                                @if($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_before_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_before_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_before_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_before_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <div class="row mb-3">
                        <div class="col-12">
                            <h4 class="h5 mb-4 text-black">{{ __('frontend.item.share') }}</h4>
                            <div class="row">
                                <div class="col-12">

                                    <!-- Create link with share to Facebook -->
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-facebook" href="" data-social="facebook">
                                        <i class="fab fa-facebook-f"></i>
                                        {{ __('social_share.facebook') }}
                                    </a>

                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-twitter" href="" data-social="twitter">
                                        <i class="fab fa-twitter"></i>
                                        {{ __('social_share.twitter') }}
                                    </a>

                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-linkedin" href="" data-social="linkedin">
                                        <i class="fab fa-linkedin-in"></i>
                                        {{ __('social_share.linkedin') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-blogger" href="" data-social="blogger">
                                        <i class="fab fa-blogger-b"></i>
                                        {{ __('social_share.blogger') }}
                                    </a>

                                    {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-pinterest" href="" data-social="pinterest">
                                        <i class="fab fa-pinterest-p"></i>
                                        {{ __('social_share.pinterest') }}
                                    </a> --}}
                                    {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-evernote" href="" data-social="evernote">
                                        <i class="fab fa-evernote"></i>
                                        {{ __('social_share.evernote') }}
                                    </a> --}}
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-reddit" href="" data-social="reddit">
                                        <i class="fab fa-reddit-alien"></i>
                                        {{ __('social_share.reddit') }}
                                    </a>
                                    {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-buffer" href="" data-social="buffer">
                                        <i class="fab fa-buffer"></i>
                                        {{ __('social_share.buffer') }}
                                    </a> --}}
                                    {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wordpress" href="" data-social="wordpress">
                                        <i class="fab fa-wordpress-simple"></i>
                                        {{ __('social_share.wordpress') }}
                                    </a> --}}
                                    {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-weibo" href="" data-social="weibo">
                                        <i class="fab fa-weibo"></i>
                                        {{ __('social_share.weibo') }}
                                    </a> --}}
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-skype" href="" data-social="skype">
                                        <i class="fab fa-skype"></i>
                                        {{ __('social_share.skype') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-telegram" href="" data-social="telegram">
                                        <i class="fab fa-telegram-plane"></i>
                                        {{ __('social_share.telegram') }}
                                    </a>
                                    {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-viber" href="" data-social="viber">
                                        <i class="fab fa-viber"></i>
                                        {{ __('social_share.viber') }}
                                    </a> --}}
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-whatsapp" href="" data-social="whatsapp">
                                        <i class="fab fa-whatsapp"></i>
                                        {{ __('social_share.whatsapp') }}
                                    </a>
                                    <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wechat" href="" data-social="wechat">
                                        <i class="fab fa-weixin"></i>
                                        {{ __('social_share.wechat') }}
                                    </a>
                                    {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-line" href="" data-social="line">
                                        <i class="fab fa-line"></i>
                                        {{ __('social_share.line') }}
                                    </a> --}}

                                </div>
                            </div>
                            
                            <hr>
                        </div>
                    </div>

                    @if($ads_after_share->count() > 0)
                        @foreach($ads_after_share as $ads_after_share_key => $ad_after_share)
                            <div class="row mb-3">
                                @if($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                    <div class="col-12 text-left">
                                        <div>
                                            {!! $ad_after_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                    <div class="col-12 text-center">
                                        <div>
                                            {!! $ad_after_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @elseif($ad_after_share->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                    <div class="col-12 text-right">
                                        <div>
                                            {!! $ad_after_share->advertisement_code !!}
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    @endif

                    <!-- start item section after share -->
                    @if($item_sections_after_share->count() > 0)
                        <div class="row mb-3">
                            <div class="col-12">
                                @foreach($item_sections_after_share as $item_sections_after_share_key => $after_share_section)
                                    <h4 class="h5 mb-4 text-black">{{ $after_share_section->item_section_title }}</h4>

                                    @php
                                        $after_share_section_collections = $after_share_section->itemSectionCollections()->orderBy('item_section_collection_order')->get();
                                    @endphp

                                    @if($after_share_section_collections->count() > 0)
                                        <div class="row">
                                            @foreach($after_share_section_collections as $after_share_section_collections_key => $after_share_section_collection)
                                                <div class="col-md-6 col-sm-12 mb-3">

                                                    @if($after_share_section_collection->item_section_collection_collectible_type == \App\ItemSectionCollection::COLLECTIBLE_TYPE_PRODUCT)
                                                        @php
                                                            $find_product_after_share = \App\Product::find($after_share_section_collection->item_section_collection_collectible_id);
                                                        @endphp
                                                        <div class="row align-items-center border-right">
                                                            <div class="col-md-5 col-4">
                                                                <a href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_share->product_slug]) }}">
                                                                    @if(empty($find_product_after_share->product_image_small))
                                                                        <img src="{{ asset('frontend/images/placeholder/full_item_feature_image_tiny.webp') }}" alt="Image" class="img-fluid rounded">
                                                                    @else
                                                                        <img src="{{ Storage::disk('public')->url('product/' . $find_product_after_share->product_image_small) }}" alt="Image" class="img-fluid rounded">
                                                                    @endif
                                                                </a>
                                                            </div>
                                                            <div class="pl-0 col-md-7 col-8">

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span class="text-black">{{ str_limit($find_product_after_share->product_name, 20) }}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <span>{{ str_limit($find_product_after_share->product_description, 40) }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        @if(!empty($find_product_after_share->product_price))
                                                                            <span class="text-black">{{ $site_global_settings->setting_product_currency_symbol . number_format($find_product_after_share->product_price, 2) }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="row mt-1">
                                                                    <div class="col-12">
                                                                        <a class="btn btn-sm btn-outline-primary btn-block rounded" href="{{ route('page.product', ['item_slug' => $item->item_slug, 'product_slug' => $find_product_after_share->product_slug]) }}">
                                                                            {{ __('item_section.read-more') }}
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <!-- end item section after share -->

                    @if(!$item_has_claimed)
                    <!-- <div class="row pt-3 pb-3 pl-2 pr-2 border border-dark rounded bg-light align-items-center">
                        <div class="col-sm-12 col-md-9 pr-0">
                            <h4 class="h5 text-black">{{ __('item_claim.claim-business') }}</h4>
                            <span>{{ __('item_claim.unclaimed-desc') }}</span>
                        </div>
                        <div class="col-sm-12 col-md-3 text-right">
                            @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->isAdmin())
                                <a class="btn btn-primary rounded text-white" href="{{ route('admin.item-claims.create', ['item_slug' => $item->item_slug]) }}" target="_blank">{{ __('item_claim.claim-business-button') }}</a>
                            @else
                                <a class="btn btn-primary rounded text-white" href="{{ route('user.item-claims.create', ['item_slug' => $item->item_slug]) }}" target="_blank">{{ __('item_claim.claim-business-button') }}</a>
                            @endif
                        </div>
                    </div> -->
                    @endif

                </div>

                <div class="col-lg-4">

                    <div class="pt-3">

                        {{-- @if($ads_before_sidebar_content->count() > 0)
                            @foreach($ads_before_sidebar_content as $ads_before_sidebar_content_key => $ad_before_sidebar_content)
                                <div class="row mb-5">
                                    @if($ad_before_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_before_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_before_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_before_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_before_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif

                        @if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                        <div class="row mb-2 align-items-center">
                            <div class="col-12">
                                <h3 class="h5 text-black">{{ __('item_hour.item-hours') }}</h3>
                            </div>
                        </div>

                        @if($item_hours->count() > 0)
                        <div class="row">
                            <div class="col-12 pl-0 pr-0">
                                @if($current_open_range)
                                    <div class="alert alert-success" role="alert">
                                        <i class="fas fa-door-open"></i>
                                        {{ __('item_hour.item-open-since') . ' ' . $current_open_range->start() . '.' }}
                                        {{ __('item_hour.item-will-close') . ' ' . $current_open_range->end() . '.' }}
                                    </div>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fas fa-exclamation-circle"></i>

                                        @php
                                            $previous_close_datetime = $opening_hours_obj->previousClose($datetime_now);
                                            $next_open_datetime = $opening_hours_obj->nextOpen($datetime_now);

                                            $previous_close_day_of_week = intval($previous_close_datetime->format('N'));
                                            $next_open_day_of_week = intval($next_open_datetime->format('N'));
                                        @endphp

                                        {{ __('item_hour.item-closed-since') }}

                                        @if($previous_close_day_of_week == 1)
                                            {{ __('item_hour.monday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 2)
                                            {{ __('item_hour.tuesday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 3)
                                            {{ __('item_hour.wednesday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 4)
                                            {{ __('item_hour.thursday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 5)
                                            {{ __('item_hour.friday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 6)
                                            {{ __('item_hour.saturday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @elseif($previous_close_day_of_week == 7)
                                            {{ __('item_hour.sunday') . ' ' . $previous_close_datetime->format('H:i') . '.' }}
                                        @endif

                                        {{ __('item_hour.item-will-re-open') }}

                                        @if($next_open_day_of_week == 1)
                                            {{ __('item_hour.monday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 2)
                                            {{ __('item_hour.tuesday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 3)
                                            {{ __('item_hour.wednesday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 4)
                                            {{ __('item_hour.thursday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 5)
                                            {{ __('item_hour.friday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 6)
                                            {{ __('item_hour.saturday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @elseif($next_open_day_of_week == 7)
                                            {{ __('item_hour.sunday') . ' ' . $next_open_datetime->format('H:i') . '.' }}
                                        @endif

                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-12">

                                <div class="row bg-light border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.monday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_monday) > 0)
                                            @foreach($item_hours_monday as $item_hours_monday_key => $an_item_hours_monday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_monday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.tuesday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_tuesday) > 0)
                                            @foreach($item_hours_tuesday as $item_hours_tuesday_key => $an_item_hours_tuesday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_tuesday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row bg-light border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.wednesday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_wednesday) > 0)
                                            @foreach($item_hours_wednesday as $item_hours_wednesday_key => $an_item_hours_wednesday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_wednesday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.thursday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_thursday) > 0)
                                            @foreach($item_hours_thursday as $item_hours_thursday_key => $an_item_hours_thursday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_thursday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row bg-light border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.friday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_friday) > 0)
                                            @foreach($item_hours_friday as $item_hours_friday_key => $an_item_hours_friday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_friday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.saturday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_saturday) > 0)
                                            @foreach($item_hours_saturday as $item_hours_saturday_key => $an_item_hours_saturday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_saturday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row bg-light border-left pt-1 pb-1">
                                    <div class="col-3">
                                        <span class="">{{ __('item_hour.sunday') }}</span>
                                    </div>
                                    <div class="col-9 text-right">
                                        @if(count($item_hours_sunday) > 0)
                                            @foreach($item_hours_sunday as $item_hours_sunday_key => $an_item_hours_sunday)
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span>{{ $an_item_hours_sunday }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row">
                                                <div class="col-12">
                                                    <span>{{ __('item_hour.item-closed') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if(count($item_hour_exceptions_obj) > 0)
                                <div class="row pt-1 pb-1">
                                    <div class="col-12">
                                        <a class="text-secondary" href="#" data-toggle="modal" data-target="#itemHourExceptionsModal">
                                            <i class="far fa-window-restore"></i>
                                            {{ __('item_hour.item-hour-exceptions-link') }}
                                        </a>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                        @endif --}}

                        @if(\Illuminate\Support\Facades\Auth::check())
                            @if(\Illuminate\Support\Facades\Auth::user()->id != $item->user_id)
                                <div class="row mb-2 align-items-center">
                                    <div class="col-12">
                                        <h3 class="h5 text-black">{{ __('backend.message.message-txt') }}</h3>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                    @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
                                        <!-- message item owner contact form -->
                                        <form method="POST" action="{{ route('admin.messages.store') }}" class="">
                                            @csrf

                                            <input type="hidden" name="recipient" value="{{ $item->user_id }}">
                                            <input type="hidden" name="item" value="{{ $item->id }}">
                                            <div class="form-group">
                                                <input id="subject" type="text" class="form-control rounded @error('subject') is-invalid @enderror" name="subject" value="{{ old('subject') }}" placeholder="{{ __('backend.message.subject') }}">
                                                @error('subject')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <textarea rows="6" id="message" type="text" class="form-control rounded @error('message') is-invalid @enderror" name="message" placeholder="{{ __('backend.message.message-txt') }}">{{ old('message') }}</textarea>
                                                @error('message')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-outline-primary btn-block rounded">
                                                    {{ __('frontend.item.send-message') }}
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <!-- message item owner contact form -->
                                        <form method="POST" action="{{ route('user.messages.store') }}" class="">
                                            @csrf

                                            <input type="hidden" name="recipient" value="{{ $item->user_id }}">
                                            <input type="hidden" name="item" value="{{ $item->id }}">
                                            <div class="form-group">
                                                <input id="subject" type="text" class="form-control rounded" name="subject" value="{{ old('subject') }}" placeholder="{{ __('backend.message.subject') }}">
                                                @error('subject')
                                                <p class="error_color">
                                                    <strong>{{ $message }}</strong>
                                                </p>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <textarea rows="6" id="message" type="text" class="form-control rounded" name="simple_message" placeholder="{{ __('backend.message.message-txt') }}">{{ old('simple_message') }}</textarea>
                                                @error('simple_message')
                                                <p class="error_color">
                                                    <strong>{{ $message }}</strong>
                                                </p>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-outline-primary btn-block rounded">
                                                    {{ __('frontend.item.send-message') }}
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                    </div>
                                </div>

                                <div class="row">
                                    <?php 
                                        // $user_details = Item::where('item_slug',$item->id)->first();
                                        // print_r($user_details);
                                    ?>
                                    <div class="card" style="width: 100%;">
                                        <div class="card-body" style="background: #dbdbdb">
                                          <h5 class="card-title">Author Details</h5>
                                        </div>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Author name: {{ $item_user->name }}</li>
                                            <li class="list-group-item">Bio: {!! Str::limit($item_user->user_about, $limit = 70, $end = '...') !!}<a href="{{ route('page.profile', encrypt($item->user->id)) }}">Read more</a></li>
                                            {{-- <li class="list-group-item">Vestibulum at eros</li> --}}
                                        </ul>
                                        @if($item->user_id != $userId)
                                            <div class="card-body" style="margin-left: 26px;">
                                                <a class="btn btn-primary rounded text-white item-contact-button"><i class="fas fa-phone-alt"></i> {{ __('Contact This Coach') }}</a>
                                            </div>
                                        @endif
                                      </div>
                                </div>
                            @endif
                        @endif

                       {{--  <div class="row mb-2 align-items-center">
                            <div class="col-12">
                                <h3 class="h5 text-black">{{ __('rating_summary.managed-by') }}</h3>
                            </div>
                        </div>

                        <div class="row align-items-center mb-4">
                            <div class="col-4">
                                @if(empty($item->user->user_image))
                                    <img src="{{ asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                @else

                                    <img src="{{ Storage::disk('public')->url('user/' . $item->user->user_image) }}" alt="{{ $item->user->name }}" class="img-fluid rounded-circle">
                                @endif
                            </div>
                            <div class="col-8 pl-0">
                                <span class="font-size-13">{{ $item->user->name }}</span><br/>
                                <span class="font-size-13">{{ __('frontend.item.posted') . ' ' . $item->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        @if(!\Illuminate\Support\Facades\Auth::check())
                            <div class="row mb-4 align-items-center">
                                <div class="col-12">
                                    <a class="btn btn-primary btn-block rounded text-white" href="#" data-toggle="modal" data-target="#itemLeadModal">{{ __('rating_summary.contact') }}</a>
                                </div>
                            </div>
                        @endif

                        <hr>

                        @include('frontend.partials.search.side')

                        @if($ads_after_sidebar_content->count() > 0)
                            @foreach($ads_after_sidebar_content as $ads_after_sidebar_content_key => $ad_after_sidebar_content)
                                <div class="row mt-5">
                                    @if($ad_after_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT)
                                        <div class="col-12 text-left">
                                            <div>
                                                {!! $ad_after_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_after_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER)
                                        <div class="col-12 text-center">
                                            <div>
                                                {!! $ad_after_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @elseif($ad_after_sidebar_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT)
                                        <div class="col-12 text-right">
                                            <div>
                                                {!! $ad_after_sidebar_content->advertisement_code !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif --}}

                    </div>

                </div>

            </div>
        </div>
    </div>

    @if($similar_items->count() > 0 || $nearby_items->count() > 0 || $similar_items_of_coaches->count() > 0)
    <div class="site-section bg-light">
        <div class="container">

        @if($similar_items_of_coaches->count() > 0)
        <div class="row mb-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.item.similar-listings-coach') }}</h2>
            </div>
        </div>
        <div class="row">

            @foreach($similar_items_of_coaches as $similar_coaches_items_key => $similar_coach_item)
                <div class="col-lg-6 mb-5">
                    <div class="d-block d-md-flex listing">
                        <a href="{{ route('page.item', $similar_coach_item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($similar_coach_item->item_image_small) ? Storage::disk('public')->url('item/' . $similar_coach_item->item_image_small) : (!empty($similar_coach_item->item_image) ? Storage::disk('public')->url('item/' . $similar_coach_item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')) }})"></a>
                        <div class="lh-content">

                            @foreach($similar_coach_item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY) as $similar_item_all_categories_key => $category)
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

                            @if($similar_coach_item->allCategories()->count() > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                                <span class="category">{{ __('categories.and') . " " . strval($similar_coach_item->allCategories()->count() - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " " . __('categories.more') }}</span>
                            @endif

                            <h3 class="pt-2"><a href="{{ route('page.item', $similar_coach_item->item_slug) }}">{{ $similar_coach_item->item_title }}</a></h3>

                            @if($similar_coach_item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            <address>
                                <a href="{{ route('page.city', ['state_slug'=>$similar_coach_item->state->state_slug, 'city_slug'=>$similar_coach_item->city->city_slug]) }}">{{ $similar_coach_item->city->city_name }}</a>,
                                <a href="{{ route('page.state', ['state_slug'=>$similar_coach_item->state->state_slug]) }}">{{ $similar_coach_item->state->state_name }}</a>
                            </address>
                            @endif

                            @if($similar_coach_item->getCountRating() > 0)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="pl-0 rating_stars rating_stars_{{ $similar_coach_item->item_slug }}" data-id="rating_stars_{{ $similar_coach_item->item_slug }}" data-rating="{{ $similar_coach_item->item_average_rating }}"></div>
                                        <address class="mt-1">
                                            @if($similar_coach_item->getCountRating() == 1)
                                                {{ '(' . $similar_coach_item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                                            @else
                                                {{ '(' . $similar_coach_item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                            @endif
                                        </address>
                                    </div>
                                </div>
                            @endif

                            <hr class="item-box-hr">

                            <div class="row align-items-center">

                                <div class="col-12 col-md-12 pr-0">
                                    <div class="row align-items-center item-box-user-div">
                                        <div class="col-2 col-md-2 col-lg-3 item-box-user-img-div">
                                            @if(empty($similar_coach_item->user->user_image))
                                                {{-- <img src="{{ asset('frontend/images/placeholder/profile-'. intval($similar_coach_item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle"> --}}
                                                <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                            @else
                                                <img src="{{ Storage::disk('public')->url('user/' . $similar_coach_item->user->user_image) }}" alt="{{ $similar_coach_item->user->name }}" class="img-fluid rounded-circle">
                                            @endif
                                        </div>
                                        <div class="col-10 col-md-10 col-lg-9 line-height-1-2 item-box-user-name-div">
                                            <div class="row pb-1">
                                                <div class="col-12">
                                                    <a class="decoration-none" href="{{ route('page.profile',encrypt($similar_coach_item->user->id)) }}"><span class="font-size-13">{{ str_limit($similar_coach_item->user->name, 14, '.') }}</span></a>
                                                </div>
                                            </div>
                                            <div class="row line-height-1-0">
                                                <div class="col-12">
                                                    <span class="review">{{ $similar_coach_item->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-7 col-md-5 pl-0 text-right">
                                    @if($similar_coach_item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                        @if($similar_coach_item->hasOpened())
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

        </div>
        @endif

        @if($similar_items->count() > 0)
        <div class="row mb-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.item.similar-listings') }}</h2>
            </div>
        </div>
        <div class="row">

            @foreach($similar_items as $similar_items_key => $similar_item)
                <div class="col-lg-6 mb-5">
                    <div class="d-block d-md-flex listing">
                        <a href="{{ route('page.item', $similar_item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($similar_item->item_image_small) ? Storage::disk('public')->url('item/' . $similar_item->item_image_small) : (!empty($similar_item->item_image) ? Storage::disk('public')->url('item/' . $similar_item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')) }})"></a>
                        <div class="lh-content">

                            @foreach($similar_item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY) as $similar_item_all_categories_key => $category)
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

                            @if($similar_item->allCategories()->count() > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                                <span class="category">{{ __('categories.and') . " " . strval($similar_item->allCategories()->count() - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " " . __('categories.more') }}</span>
                            @endif

                            <h3 class="pt-2"><a href="{{ route('page.item', $similar_item->item_slug) }}">{{ $similar_item->item_title }}</a></h3>

                            {{-- @if($similar_item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                                <address>
                                    <a href="{{ route('page.city', ['state_slug'=>$similar_item->state->state_slug, 'city_slug'=>$similar_item->city->city_slug]) }}">{{ $similar_item->city->city_name }}</a>,
                                    <a href="{{ route('page.state', ['state_slug'=>$similar_item->state->state_slug]) }}">{{ $similar_item->state->state_name }}</a>
                                </address>
                            @endif --}}

                            @if($similar_item->getCountRating() > 0)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="pl-0 rating_stars rating_stars_{{ $similar_item->item_slug }}" data-id="rating_stars_{{ $similar_item->item_slug }}" data-rating="{{ $similar_item->item_average_rating }}"></div>
                                        <address class="mt-1">
                                            @if($similar_item->getCountRating() == 1)
                                                {{ '(' . $similar_item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                                            @else
                                                {{ '(' . $similar_item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
                                            @endif
                                        </address>
                                    </div>
                                </div>
                            @endif

                            <hr class="item-box-hr">

                            <div class="row align-items-center">

                                <div class="col-12 col-md-12 pr-0">
                                    <div class="row align-items-center item-box-user-div">
                                        <div class="col-3 item-box-user-img-div">
                                            @if(empty($similar_item->user->user_image))
                                                {{-- <img src="{{ asset('frontend/images/placeholder/profile-'. intval($similar_item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle"> --}}
                                                <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                            @else
                                                <img src="{{ Storage::disk('public')->url('user/' . $similar_item->user->user_image) }}" alt="{{ $similar_item->user->name }}" class="img-fluid rounded-circle">
                                            @endif
                                        </div>
                                        <div class="col-9 line-height-1-2 item-box-user-name-div">
                                            <div class="row pb-1">
                                                <div class="col-12">
                                                    {{-- <a class="decoration-none" href="{{ route('page.profile', encrypt($similar_item->user->id)) }}"><span class="font-size-13">{{ str_limit($similar_item->user->name, 14, '.') }}</span></a> --}}
                                                    <a class="decoration-none" href="{{ route('page.profile', encrypt($similar_item->user->id)) }}"><span class="font-size-13">{{ $similar_item->user->name }}</span></a>
                                                </div>
                                            </div>
                                            <div class="row line-height-1-0">
                                                <div class="col-12">
                                                    <span class="review">{{ $similar_item->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-7 col-md-5 pl-0 text-right">
                                    @if($similar_item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                        @if($similar_item->hasOpened())
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

        </div>
        @endif

        @if($nearby_items->count() > 0)
        <!-- <div class="row mb-4 mt-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary">{{ __('frontend.item.nearby-listings') }}</h2>
            </div>
        </div>
        <div class="row">

            @foreach($nearby_items as $nearby_items_key => $nearby_item)
                <div class="col-lg-6">
                    <div class="d-block d-md-flex listing">
                        <a href="{{ route('page.item', $nearby_item->item_slug) }}" class="img d-block" style="background-image: url({{ !empty($nearby_item->item_image_small) ? Storage::disk('public')->url('item/' . $nearby_item->item_image_small) : (!empty($nearby_item->item_image) ? Storage::disk('public')->url('item/' . $nearby_item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')) }})"></a>
                        <div class="lh-content">

                            @foreach($nearby_item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY) as $nearby_item_all_categories_key => $category)
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

                            @if($nearby_item->allCategories()->count() > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
                                <span class="category">{{ __('categories.and') . " " . strval($nearby_item->allCategories()->count() - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " " . __('categories.more') }}</span>
                            @endif

                            <h3 class="pt-2"><a href="{{ route('page.item', $nearby_item->item_slug) }}">{{ $nearby_item->item_title }}</a></h3>

                            @if($nearby_item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                            <address>
                                <a href="{{ route('page.city', ['state_slug'=>$nearby_item->state->state_slug, 'city_slug'=>$nearby_item->city->city_slug]) }}">{{ $nearby_item->city->city_name }}</a>,
                                <a href="{{ route('page.state', ['state_slug'=>$nearby_item->state->state_slug]) }}">{{ $nearby_item->state->state_name }}</a>
                            </address>
                            @endif

                            @if($nearby_item->getCountRating() > 0)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="pl-0 rating_stars rating_stars_{{ $nearby_item->item_slug }}" data-id="rating_stars_{{ $nearby_item->item_slug }}" data-rating="{{ $nearby_item->item_average_rating }}"></div>
                                        <address class="mt-1">
                                            @if($nearby_item->getCountRating() == 1)
                                                {{ '(' . $nearby_item->getCountRating() . ' ' . __('review.frontend.review') . ')' }}
                                            @else
                                                {{ '(' . $nearby_item->getCountRating() . ' ' . __('review.frontend.reviews') . ')' }}
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
                                            @if(empty($nearby_item->user->user_image))
                                                <img src="{{ asset('frontend/images/placeholder/profile-'. intval($nearby_item->user->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                                            @else
                                                <img src="{{ Storage::disk('public')->url('user/' . $nearby_item->user->user_image) }}" alt="{{ $nearby_item->user->name }}" class="img-fluid rounded-circle">
                                            @endif
                                        </div>
                                        <div class="col-9 line-height-1-2 item-box-user-name-div">
                                            <div class="row pb-1">
                                                <div class="col-12">
                                                    <a class="decoration-none" href="{{ route('page.profile', $nearby_item->user->id) }}"><span class="font-size-13">{{ str_limit($nearby_item->user->name, 14, '.') }}</span></a>
                                                </div>
                                            </div>
                                            <div class="row line-height-1-0">
                                                <div class="col-12">
                                                    <span class="review">{{ $nearby_item->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-7 col-md-5 pl-0 text-right">
                                    @if($nearby_item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
                                        @if($nearby_item->hasOpened())
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

        </div> -->
        @endif

    </div>
    </div>
    @endif

<!-- Modal - share -->
<div class="modal fade" id="share-modal" tabindex="-1" role="dialog" aria-labelledby="share-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="exampleModalLongTitle">{{ __('frontend.item.share-listing') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">

                        <p>{{ __('frontend.item.share-listing-social-media') }}</p>

                        <!-- Create link with share to Facebook -->
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-facebook" href="" data-social="facebook">
                            <i class="fab fa-facebook-f"></i>
                            {{ __('social_share.facebook') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-twitter" href="" data-social="twitter">
                            <i class="fab fa-twitter"></i>
                            {{ __('social_share.twitter') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-linkedin" href="" data-social="linkedin">
                            <i class="fab fa-linkedin-in"></i>
                            {{ __('social_share.linkedin') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-blogger" href="" data-social="blogger">
                            <i class="fab fa-blogger-b"></i>
                            {{ __('social_share.blogger') }}
                        </a>
                        {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-pinterest" href="" data-social="pinterest">
                            <i class="fab fa-pinterest-p"></i>
                            {{ __('social_share.pinterest') }}
                        </a> --}}
                        {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-evernote" href="" data-social="evernote">
                            <i class="fab fa-evernote"></i>
                            {{ __('social_share.evernote') }}
                        </a> --}}
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-reddit" href="" data-social="reddit">
                            <i class="fab fa-reddit-alien"></i>
                            {{ __('social_share.reddit') }}
                        </a>
                        {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-buffer" href="" data-social="buffer">
                            <i class="fab fa-buffer"></i>
                            {{ __('social_share.buffer') }}
                        </a> --}}
                        {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wordpress" href="" data-social="wordpress">
                            <i class="fab fa-wordpress-simple"></i>
                            {{ __('social_share.wordpress') }}
                        </a> --}}
                        {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-weibo" href="" data-social="weibo">
                            <i class="fab fa-weibo"></i>
                            {{ __('social_share.weibo') }}
                        </a> --}}
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-skype" href="" data-social="skype">
                            <i class="fab fa-skype"></i>
                            {{ __('social_share.skype') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-telegram" href="" data-social="telegram">
                            <i class="fab fa-telegram-plane"></i>
                            {{ __('social_share.telegram') }}
                        </a>
                        {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-viber" href="" data-social="viber">
                            <i class="fab fa-viber"></i>
                            {{ __('social_share.viber') }}
                        </a> --}}
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-whatsapp" href="" data-social="whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            {{ __('social_share.whatsapp') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wechat" href="" data-social="wechat">
                            <i class="fab fa-weixin"></i>
                            {{ __('social_share.wechat') }}
                        </a>
                        {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-line" href="" data-social="line">
                            <i class="fab fa-line"></i>
                            {{ __('social_share.line') }}
                        </a> --}}

                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <p>{{ __('frontend.item.share-listing-email') }}</p>
                        {{-- @if(!Auth::check())
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ __('frontend.item.login-require') }}
                                </div>
                            </div>
                        </div>
                        @endif --}}
                        <form action="{{ route('page.item.email', ['item_slug' => $item->item_slug]) }}" method="POST" name="shareProfileModal" id="shareProfileModal">
                            @csrf
                            <div class="form-row mb-3">
                                <div class="col-md-4">
                                    <label for="item_share_email_name" class="text-black">{{ __('frontend.item.name') }}<span class="text-danger">*</span></label>
                                    <input id="item_share_email_name" type="text" class="form-control @error('item_share_email_name') is-invalid @enderror" name="item_share_email_name" value="{{ isset(auth()->user()->name) ? auth()->user()->name : old('item_share_email_name') }}">
                                    <p class="profile_name_error error_color" role="alert"></p>
                                    @error('item_share_email_name')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="item_share_email_from_email" class="text-black">{{ __('frontend.item.email') }}<span class="text-danger">*</span></label>
                                    <input id="item_share_email_from_email" type="email" class="form-control @error('item_share_email_from_email') is-invalid @enderror" name="item_share_email_from_email" value="{{ isset(auth()->user()->email) ? auth()->user()->email : old('item_share_email_from_email') }}">
                                    <p class="profile_from_email_error error_color" role="alert"></p>
                                    @error('item_share_email_from_email')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="item_share_email_to_email" class="text-black">{{ __('frontend.item.email-to') }}<span class="text-danger">*</span></label>
                                    <input id="item_share_email_to_email" type="email" class="form-control @error('item_share_email_to_email') is-invalid @enderror" name="item_share_email_to_email" value="{{ old('item_share_email_to_email') }}">
                                    <p class="profile_to_error error_color" role="alert"></p>
                                    @error('item_share_email_to_email')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col-md-12">
                                    <label for="item_share_email_note" class="text-black">{{ __('frontend.item.add-note') }}<span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('item_share_email_note') is-invalid @enderror" id="item_share_email_note" rows="3" name="item_share_email_note">{{ old('item_share_email_note') }}</textarea>
                                    <p class="profile_note_error error_color" role="alert"></p>
                                    @error('item_share_email_note')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col-md-12">
                                    <div class="g-recaptcha" data-sitekey="6LeXpRIpAAAAANR5q7jXCepgrSKbM91QWgLumZXc"></div>
                                    @error('g-recaptcha-response')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div id="re_captcha_error"></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <button type="submit" id="submit" class="btn btn-primary py-2 px-4 text-white rounded">
                                        {{ __('frontend.item.send-email') }}
                                    </button>
                                    <span class="please_wait">Please Wait..</span>
                                        {{-- @if(!Auth::user())
                                            <a href="{{ route('login') }}"class="btn btn-primary px-4 text-white rounded float-right">
                                                {{ __('Login') }}                                            
                                            </a>
                                        @endif --}}
                                </div>
                            </div>
                            <input type="hidden" name="user_bio" id="user_bio">
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-bs-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
            </div>
        </div>
    </div>
</div>


<!--Start call modal -->
<div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="contact-modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-dark" id="exampleModalLongTitle">{{ __('Contact This Coach') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                
                <div class="row">
                    <div class="col-md-12">
                        <h5>{{ __('frontend.item.share-listing-email') }}</h5>
                        <p>{{ __('Please answer the following question so this coach can determine if they are uniquely qualified to best help you and to better prepare them for a potential initial conversation.') }}</p>
                        {{-- @if(!Auth::check())
                            <div class="row mb-2">
                                <div class="col-12">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ __('frontend.item.login-require') }}
                                    </div>
                                </div>
                            </div>
                        @endif --}}
                        <div class="nav nav-fill my-3">
                            <label class="nav-link shadow-sm step0 steps  border ml-2 ">Step One</label>
                            <label class="nav-link shadow-sm step1 steps  border ml-2 " >Step Two</label>
                            <label class="nav-link shadow-sm step2 steps  border ml-2 " >Step Three</label>
                            <label class="nav-link shadow-sm step3 steps  border ml-2 " >Step Four</label>
                            <label class="nav-link shadow-sm step4 steps  border ml-2 " >Step Five</label>
                            <label class="nav-link shadow-sm step5 steps  border ml-2 " >Step Six</label>
                        </div>
                        <form action="{{ route('page.item.contact', ['item_slug' => $item->item_slug]) }}" method="POST" name="contactFormModal" id="contactFormModal" class="contact-coach-form">
                            @csrf
                            <input type="hidden" name="userId" value = "{{ $item->user_id }}">
                            <input type="hidden" name="articleTitle" value = "{{ $item->item_title }}">
                            <input type="hidden" name="authUserId" value="{{ isset(auth()->user()->id) ? auth()->user()->id : '' }}">
                            <input type="hidden" id="new_user" name="new_user" value="">
                            <div class="step-01 step_class">
                                <div class="form-section">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="item_conntact_email_name" class="text-black">{{ __('frontend.item.name') }}<span class="text-danger">*</span></label>
                                                <input id="item_conntact_email_name" type="text" class="form-control @error('item_conntact_email_name') is-invalid @enderror" name="item_conntact_email_name" value="{{ isset(auth()->user()->name) ? auth()->user()->name : old('item_conntact_email_name') }}" required>
                                                <p class="name_error error_color" role="alert"></p>
                                                @error('item_conntact_email_name')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="item_contact_email_from_email" class="text-black">{{ __('frontend.item.email') }}<span class="text-danger">*</span></label>
                                                <input id="item_contact_email_from_email" type="email" class="form-control @error('item_contact_email_from_email') is-invalid @enderror" name="item_contact_email_from_email" value="{{ isset(auth()->user()->email) ? auth()->user()->email : old('item_contact_email_from_email') }}" required>
                                                <p class="email_error error_color" role="alert"></p>
                                                @error('item_contact_email_from_email')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label for="Question 1"
                                                class="text-black">{{ __('1. What are the top 2 challenges you feel this coach can help you navigate?') }}<span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('question1') is-invalid @enderror" id="question1_txt"
                                                rows="3" name="question1" required>{{ old('question1') }}</textarea>
                                                <p class="question1_error error_color_modal" role="alert"></p>
                                                <p class="question1_desc_char_count count_error"></p>
                                            @error('question1')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>                                    
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-section col-md-12">
                                        <label for="Question 2">{{ __('2.What type of personality traits would be helpful for a person to have when coaching you?') }}<span class="text-danger">*</span>
                                            <p class="pl-3">Select all that apply from the following choices:</p>
                                        </label>
                                        {{-- <textarea name="question2" id="question2_txt" class="form-control mb-3" cols="30" rows="5" required></textarea> --}}
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="question2[]" value="Patient" id="question2_checkbox1" required>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                a) Patient
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="question2[]" value="Diplomatic" id="question2_checkbox2" required>
                                            <label class="form-check-label" for="flexCheckChecked">
                                                b) Diplomatic
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="question2[]" value="Direct" id="question2_checkbox3" required>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                c) Direct
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="question2[]" value="Sensitive" id="question2_checkbox4" required>
                                            <label class="form-check-label" for="flexCheckChecked">
                                                d) Sensitive
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="question2[]" value="High Energy" id="question2_checkbox5" required>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                e) High Energy
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="question2[]" value="Calm" id="question2_checkbox6" required>
                                            <label class="form-check-label" for="flexCheckChecked">
                                                f) Calm
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="question_2">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                g) Other
                                            </label>
                                            <textarea name="question2[]" class="form-control mb-3" cols="30" rows="5" id="question2_txt" style="display:none"></textarea>
                                        </div>
                                        <p class="question2_error error_color_modal" role="alert"></p>
                                        <p class="question2_desc_char_count count_error"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-section col-md-12 ">                                            
                                        <label for="Question 3">{{ __('3.What specific training, expertise and industry knowledge is important for this coach to possess?') }}<span class="text-danger">*</span></label>
                                        <textarea name="question3" id="question3_txt" class="form-control mb-3" cols="30" rows="5" required></textarea>                                            
                                        <p class="question3_error error_color_modal" role="alert"></p>
                                        <p class="question3_desc_char_count count_error"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-section col-md-12 ">                                            
                                        <label for="Question 4">{{ __('4.On a scale of 1-10 how structured do you want your coaching experience?') }}<span class="text-danger">*</span></label><br>
                                        <input type="radio" id="question4" name="question4" value="1" required><label for="question4"><span class="pl-2"> 1</span></label><br>                                            
                                        <input type="radio" id="question4" name="question4" value="2" required><label for="question4"><span class="pl-2"> 2</span></label><br>                                            
                                        <input type="radio" id="question4" name="question4" value="3" required><label for="question4"><span class="pl-2"> 3</span></label><br>                                            
                                        <input type="radio" id="question4" name="question4" value="4" required><label for="question4"><span class="pl-2"> 4</span></label><br>                                            
                                        <input type="radio" id="question4" name="question4" value="5" required><label for="question4"><span class="pl-2"> 5</span></label><br>                                            
                                        <input type="radio" id="question4" name="question4" value="6" required><label for="question4"><span class="pl-2"> 6</span></label><br>                                            
                                        <input type="radio" id="question4" name="question4" value="7" required><label for="question4"><span class="pl-2"> 7</span></label><br>                                            
                                        <input type="radio" id="question4" name="question4" value="8" required><label for="question4"><span class="pl-2"> 8</span></label><br>                                            
                                        <input type="radio" id="question4" name="question4" value="9" required><label for="question4"><span class="pl-2"> 9</span></label><br>                                            
                                        <input type="radio" id="question4" name="question4" value="10" required><label for="question4"><span class="pl-2"> 1</span>0</label><br>                                            
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-section col-md-12 ">                                            
                                        <label for="Question 5">{{ __('5.If you invest your time and money with this coach, what is the single biggest change you hope to achieve?') }}<span class="text-danger">*</span></label>
                                        <textarea name="question5" id="question5_txt" class="form-control mb-3" cols="30" rows="5" required></textarea>                                            
                                        <p class="question5_error error_color_modal" role="alert"></p>
                                        <p class="question5_desc_char_count count_error"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-section col-md-12 ">                                            
                                        <label for="Question 6">{{ __('6.Was there a particular Blog post, Podcast, Video, e-Book, etc that helped you select this coach? If so please share the name of it.') }}<span class="text-danger">*</span></label>
                                        <textarea name="question6" id="question6_txt" class="form-control mb-3" cols="30" rows="5" required></textarea>                                            
                                        <p class="question6_error error_color_modal" role="alert"></p>
                                        <p class="question6_desc_char_count count_error"></p>
                                        <div class="g-recaptcha" data-sitekey="6LeXpRIpAAAAANR5q7jXCepgrSKbM91QWgLumZXc"></div>
                                        <div id="re_captcha_error1"></div>

                                    </div>
                                </div>                            
                                <div class="form-row">
                                    <div class="col-md-12 form-navigation">
                                        {{-- @if(Auth::check()) --}}
                                            <button type="button" class="previous btn btn-primary float-left mt-2">&lt; Previous</button>                                        
                                            <button type="button" class="next btn btn-primary float-right mt-2">Next &gt;</button>

                                            <button type="submit" id="kt_login_step_01" class="btn btn-primary py-2 px-4 text-white rounded float-right mt-2">
                                                {{ __('frontend.item.send-email') }}
                                            </button>
                                        {{-- @endif --}}
                                        <span class="please_wait">Please Wait..</span>
                                            {{-- @if(!Auth::user())
                                                <a href="{{ route('login') }}"class="btn btn-primary px-4 text-white rounded float-right mt-2">
                                                    {{ __('Login') }}                                            
                                                </a>
                                            @endif  --}}
                                    </div>
                                </div>
                            </div>    
                            <div class="step-02 step_class" style="display:none">
                                    <!-- <div class="fv-row mb-10">
                                        <label class="form-label fw-bold6er text-dark fs-6">Enter OTP sent to your email address</label>
                                        <input class="form-control otp" id="otp" type="text" placeholder="" name="otp" autocomplete="off" required />
                                        <div id="otp_val" class="error"></div>
                                    </div> -->
                                    <div class="text-center mb-10">
                                        <h1 class="text-dark mb-3 verification-text">
                                            Two-Factor Verification
                                        </h1>
                                        <div class="fw-semibold fs-5 mb-5">Enter the verification code we sent to your email</div>
                                    </div>
                                    <div class="fv-row mb-10">
                                        <!-- <div class="fw-bold text-start text-dark fs-6 mb-1 ms-1">Type your 6 digit otp</div> -->
                                        {{-- <div class="d-flex flex-wrap flex-stack">
                                            <input type="text" name="code_1" id="code_1" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                            <input type="text" name="code_2" id="code_2" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                            <input type="text" name="code_3" id="code_3" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                            <input type="text" name="code_4" id="code_4" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                            <input type="text" name="code_5" id="code_5" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                            <input type="text" name="code_6" id="code_6" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                        </div>--}}
                                        <div class="d-flex ap-otp-inputs justify-content-between" data-channel="email" data-length="6" data-form="login">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="0" id="code_1">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="1" id="code_2">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="2" id="code_3">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="3" id="code_4">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="4" id="code_5">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="5" id="code_6">
                                        </div>
                                        <div id="otp_val" class="error"></div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="fw-semibold fs-5 d-flex w-100 justify-content-center fw-semibold fs-5 align-items-center">
                                        <span class="text-muted me-1" id="resend_login_otp_title">Didn't get the otp ?</span>
                                            <a href="#" id="resend_login_otp" class="link-primary fs-5 me-1 pl-1">
                                            <span class="indicator-label" style="margin-left: 10px;">{{ __('Resend') }}</span>
                                            </a>
                                            <span class="indicator-progress pl-1" >Please wait...
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                        
                                            <div class="login_otp_countdown fw-semibold fs-5 pl-1" style="margin-left:10px;"></div>
                                        </div>
                                    </div>
                                    <div class="pb-lg-0 pb-8 mt-3">
                                        <button type="button" id="kt_login_step_02" class="btn btn-lg btn-info w-100 mb-5">
                                        {{ __('frontend.item.send-email') }}
                                        </button>
                                        </div>
                                    <a href="#" id="kt_login_prev_step_01" class="d-flex w-100 justify-content-center link-primary fs-5 me-1">
                                        <span class="indicator-label">{{ __('Back') }}</span>
                                            <!-- <span class="indicator-progress">Please wait...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span> -->
                                    </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-bs-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- End call modal -->

@if($item_total_categories > \App\Item::ITEM_TOTAL_SHOW_CATEGORY)
<!-- Modal show categories -->
<div class="modal fade" id="showCategoriesModal" tabindex="-1" role="dialog" aria-labelledby="showCategoriesModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('categories.all-cat') . " - " . $item->item_title }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        @foreach($item_all_categories as $item_all_categories_key => $a_category)

                            <a class="btn btn-sm btn-outline-primary rounded mb-2" href="{{ route('page.category', $a_category->category_slug) }}">
                                <span class="category">{{ $a_category->category_name }}</span>
                            </a>

                        @endforeach
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-bs-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
@endif

@if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW)
@if(count($item_hour_exceptions_obj) > 0)
<div class="modal fade" id="itemHourExceptionsModal" tabindex="-1" role="dialog" aria-labelledby="itemHourExceptionsModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('item_hour.modal-item-hour-exceptions-title')  }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <p>{{ $item->item_title . ' ' . __('item_hour.modal-item-hour-exceptions-description') }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">

                        @foreach($item_hour_exceptions_obj as $item_hour_exceptions_obj_key => $item_hour_exception)
                        <div class="row pt-1 pb-1 bg-light mb-1 align-items-center">
                            <div class="col-4">
                                <i class="far fa-calendar-alt"></i>
                                {{ $item_hour_exceptions_obj_key }}
                            </div>
                            <div class="col-8 text-right">
                                @php
                                    $item_hour_exception_iterator = $item_hour_exception->getIterator();
                                @endphp

                                @if(count($item_hour_exception_iterator) > 0)
                                    @foreach($item_hour_exception_iterator as $item_hour_exception_iterator_key => $an_item_hour_exception_iterator)
                                        <i class="far fa-clock"></i>
                                        @if(count($item_hour_exception_iterator) - 1 == $item_hour_exception_iterator_key)
                                            {{ $an_item_hour_exception_iterator }}
                                        @else
                                            {{ $an_item_hour_exception_iterator . ', ' }}
                                        @endif
                                    @endforeach
                                @else
                                    {{ __('item_hour.item-closed') }}
                                @endif
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-bs-dismiss="modal">{{ __('importer_csv.error-notify-modal-close') }}</button>
            </div>
        </div>
    </div>
</div>
@endif
@endif

<!-- QR Code modal -->
<div class="modal fade" id="qrcodeModal" tabindex="-1" role="dialog" aria-labelledby="qrcodeModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('theme_directory_hub.listing.qr-code')  }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <div id="item-qrcode"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-bs-dismiss="modal">{{ __('importer_csv.error-notify-modal-close') }}</button>
            </div>
        </div>
    </div>
</div>

@if(!\Illuminate\Support\Facades\Auth::check())
<div class="modal fade" id="itemLeadModal" tabindex="-1" role="dialog" aria-labelledby="itemLeadModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('rating_summary.contact') . ' ' . $item->item_title }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('page.item.lead.store', ['item_slug' => $item->item_slug]) }}" method="POST">
                            @csrf
                            <div class="form-row mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="item_lead_name" class="text-black">{{ __('role_permission.item-leads.item-lead-name') }}</label>
                                    <input id="item_lead_name" type="text" class="form-control @error('item_lead_name') is-invalid @enderror" name="item_lead_name" value="{{ old('item_lead_name') }}">
                                    @error('item_lead_name')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="item_lead_email" class="text-black">{{ __('role_permission.item-leads.item-lead-email') }}</label>
                                    <input id="item_lead_email" type="text" class="form-control @error('item_lead_email') is-invalid @enderror" name="item_lead_email" value="{{ old('item_lead_email') }}">
                                    @error('item_lead_email')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="item_lead_phone" class="text-black">{{ __('role_permission.item-leads.item-lead-phone') }}</label>
                                    <input id="item_lead_phone" type="text" class="form-control @error('item_lead_phone') is-invalid @enderror" name="item_lead_phone" value="{{ old('item_lead_phone') }}" onkeypress="validatePostalCode(event)"
                                    >
                                    @error('item_lead_phone')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="item_lead_subject" class="text-black">{{ __('role_permission.item-leads.item-lead-subject') }}</label>
                                    <input id="item_lead_subject" type="text" class="form-control @error('item_lead_subject') is-invalid @enderror" name="item_lead_subject" value="{{ old('item_lead_subject') }}">
                                    @error('item_lead_subject')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <div class="col-md-12">
                                    <label for="item_lead_message" class="text-black">{{ __('role_permission.item-leads.item-lead-message') }}</label>
                                    <textarea class="form-control @error('item_lead_message') is-invalid @enderror" id="item_lead_message" rows="3" name="item_lead_message">{{ old('item_lead_message') }}</textarea>
                                    @error('item_lead_message')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Start Google reCAPTCHA version 2 -->
                            @if($site_global_settings->setting_site_recaptcha_item_lead_enable == \App\Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE)
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

                            <div class="form-row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary py-2 px-4 text-white rounded">
                                        {{ __('rating_summary.contact') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-bs-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')

    @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR && $site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="{{ asset('frontend/vendor/leaflet/leaflet.js') }}"></script>
    @endif

    <script src="{{ asset('frontend/vendor/justified-gallery/jquery.justifiedGallery.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/colorbox/jquery.colorbox-min.js') }}"></script>

    <script src="{{ asset('frontend/vendor/goodshare/goodshare.min.js') }}"></script>

    <script src="{{ asset('frontend/vendor/jquery-qrcode/jquery-qrcode-0.18.0.min.js') }}"></script>
    <script src="https://www.gstatic.com/firebasejs/4.1.3/firebase.js"></script>
    <!-- start for contact us stepper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js" integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- end for contact us stepper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
  <script>
    $(document).ready(function() {
            $('.name_error').text('')
            $('.item_contact_email_from_email').text('')
            $('.note_error').text('');
            $('.please_wait').text('');

            $('#item_conntact_email_name').on('input', function(e) {
                $('.name_error').text('');
            });
            $('#item_contact_email_from_email').on('input', function(e) {
                $('.email_error').text('');
            });
            $('#item_contact_email_note').on('input', function(e) {
                $('.note_error').text('');
            });
            $("#contact-modal").on("hidden.bs.modal", function(){
                $('.name_error').text('');
                $('.email_error').text('');
                $(".note_error").text("");
            });
            $('#contactFormModal').on('submit', function(e) {
                e.preventDefault();
                $('.please_wait').text('Please Wait..');
                var item_slug = <?php echo json_encode($item->item_slug)  ?>;
                // alert(item_slug);
                
                var formData = new FormData(this);

                jQuery.ajax({
                    type: 'POST',
                    url: '/items/'+item_slug+'/contact',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                    },

                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            // console.log(response)
                            $(".error_color").text("");
                            $('.please_wait').text('');
                            // location.reload(); 
                            // window.location.href = "{{ route('login') }}";

                            var firebaseConfig = {
                            aapiKey: "AIzaSyA31EsSr68dVVQ-cVZwfbLmeDK8_PUT2fM",
                            authDomain: "coachhq-c1b3d.firebaseapp.com",
                            projectId: "coachhq-c1b3d",
                            storageBucket: "coachhq-c1b3d.appspot.com",
                            messagingSenderId: "668525619724",
                            appId: "1:668525619724:web:e4282225654d0467655c29",
                            measurementId: "G-VFGKZNYRVM"
                            };
                            firebase.initializeApp(firebaseConfig);
                            const messaging = firebase.messaging();
                            messaging.onMessage(function(payload) {
                                const title = payload.notification.title;
                                const options = {
                                    body: payload.notification.body,
                                    icon: payload.notification.icon,
                                };
                                new Notification(title, options);
                            });
                            location.reload();
                            toastr.success('Conatct This Coach SuccessFull');


                        }
                        if (response.status == 'error') {
                            // console.log(response.msg.item_contact_email_note)
                            $('.please_wait').text('');
                            $.each(response.msg, function(key, val) {
                                if (response.msg.item_conntact_email_name) {
                                    $('.name_error').text(response.msg.item_conntact_email_name)
                                }
                                if (response.msg.item_contact_email_from_email) {
                                    $('.email_error').text(response.msg.item_contact_email_from_email)
                                }
                                if (response.msg.item_contact_email_note) {
                                    $('.note_error').text(response.msg.item_contact_email_note)
                                }
                                $(':input[type="submit"]').prop('disabled', false);

                            });

                        }
                    }
                });

            });

        });

    // $('#submit').on('click', function(){
    //     var firebaseConfig = {
    //         aapiKey: "AIzaSyA31EsSr68dVVQ-cVZwfbLmeDK8_PUT2fM",
    //         authDomain: "coachhq-c1b3d.firebaseapp.com",
    //         projectId: "coachhq-c1b3d",
    //         storageBucket: "coachhq-c1b3d.appspot.com",
    //         messagingSenderId: "668525619724",
    //         appId: "1:668525619724:web:e4282225654d0467655c29",
    //         measurementId: "G-VFGKZNYRVM"
    //     };
    //     firebase.initializeApp(firebaseConfig);
    //     const messaging = firebase.messaging();
    //     messaging.onMessage(function (payload) {
    //         const title = payload.notification.title;
    //         const options = {
    //             body: payload.notification.body,
    //             icon: payload.notification.icon,
    //         };
    //         new Notification(title, options);
    //     });
    // });
    $(document).ready(function() {
            $('.profile_name_error').text('')
            $('.profile_from_email_error').text('')
            $('.profile_to_error').text('')
            $('.profile_note_error').text('');
            $('.please_wait').text('');

            $('#item_share_email_name').on('input', function(e) {
                $('.profile_name_error').text('');
            });
            $('#item_share_email_from_email').on('input', function(e) {
                $('.profile_from_email_error').text('');
            });
            $('#item_share_email_to_email').on('input', function(e) {
                $('.profile_to_error').text('');
            });
            $('#item_share_email_note').on('input', function(e) {
                $('.profile_note_error').text('');
            });
            $("#share-modal").on("hidden.bs.modal", function(){
                $('.profile_name_error').text('');
                $('.profile_from_email_error').text('');
                $('.profile_to_error').text('');
                $(".profile_note_error").text("");
            });
            $('#shareProfileModal').on('submit', function(e) {
                e.preventDefault();
                $('.please_wait').text('Please Wait..');
                var item_slug = <?php echo json_encode($item->item_slug)  ?>;
                // alert(item_slug);
                
                var formData = new FormData(this);

                jQuery.ajax({
                    type: 'POST',
                    url: '/items/'+item_slug+'/email',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                    },

                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            // console.log(response)
                            $(".error_color").text("");
                            $('.please_wait').text('');
                            // location.reload(); 
                            // window.location.href = "{{ route('login') }}";

                            var firebaseConfig = {
                            aapiKey: "AIzaSyA31EsSr68dVVQ-cVZwfbLmeDK8_PUT2fM",
                            authDomain: "coachhq-c1b3d.firebaseapp.com",
                            projectId: "coachhq-c1b3d",
                            storageBucket: "coachhq-c1b3d.appspot.com",
                            messagingSenderId: "668525619724",
                            appId: "1:668525619724:web:e4282225654d0467655c29",
                            measurementId: "G-VFGKZNYRVM"
                            };
                            firebase.initializeApp(firebaseConfig);
                            const messaging = firebase.messaging();
                            messaging.onMessage(function(payload) {
                                const title = payload.notification.title;
                                const options = {
                                    body: payload.notification.body,
                                    icon: payload.notification.icon,
                                };
                                new Notification(title, options);
                            });
                            location.reload();
                            //toastr.success('Shared SuccessFull');


                        }
                        if (response.status == 'error') {
                            // console.log(response.msg.item_contact_email_note)
                            $('.please_wait').text('');
                            $.each(response.msg, function(key, val) {
                                if (response.msg.item_share_email_name) {
                                    $('.profile_name_error').text(response.msg.item_share_email_name)
                                }
                                if (response.msg.item_share_email_from_email) {
                                    $('.profile_from_email_error').text(response.msg.item_share_email_from_email)
                                }
                                if (response.msg.item_share_email_to_email) {
                                    $('.profile_to_error').text(response.msg.item_share_email_to_email)
                                }
                                if (response.msg.item_share_email_note) {
                                    $('.profile_note_error').text(response.msg.item_share_email_note)
                                }
                                if (key == 'g-recaptcha-response') {
                                    $('#re_captcha_error').append('<p class="profile_note_error error_color" role="alert">'+val[0]+'</p>')
                                }
                                $(':input[type="submit"]').prop('disabled', false);

                            });

                        }
                    }
                });

            });

        });

        $(document).ready(function(){

            "use strict";

            /**
             * Start initial map
             */
            @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP && $item->item_type == \App\Item::ITEM_TYPE_REGULAR)
            // var map = L.map('mapid-item', {
            //     center: [{{ $item->item_lat }}, {{ $item->item_lng }}],
            //     zoom: 13,
            //     scrollWheelZoom: false,
            // });

            // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            // }).addTo(map);

            // L.marker([{{ $item->item_lat }}, {{ $item->item_lng }}]).addTo(map);
            @endif
            /**
             * End initial map
             */


            /**
             * Start initial image gallery justify gallery
             */
            @if($item->galleries()->count() > 0)
            // $("#item-image-gallery").justifiedGallery({
            //     rowHeight : 150,
            //     maxRowHeight: 180,
            //     lastRow : 'nojustify',
            //     margins : 3,
            //     captions: false,
            //     randomize: true,
            //     rel : 'item-image-gallery-thumb', //replace with 'gallery1' the rel attribute of each link
            // }).on('jg.complete', function () {
            //     $(this).find('a').colorbox({
            //         maxWidth : '95%',
            //         maxHeight : '95%',
            //         opacity : 0.8,
            //     });
            // });
            @endif
            /**
             * End initial image gallery justify gallery
             */

            /**
             * Start initial review image gallery justify gallery
             */
            @foreach($reviews as $reviews_key => $review)
            @if($item->reviewGalleryCountByReviewId($review->id))
            $("#review-image-gallery-{{ $review->id }}").justifiedGallery({
                rowHeight : 80,
                maxRowHeight: 100,
                lastRow : 'nojustify',
                margins : 3,
                captions: false,
                randomize: true,
                rel : 'review-image-gallery-thumb-{{ $review->id }}', //replace with 'gallery1' the rel attribute of each link
            }).on('jg.complete', function () {
                $(this).find('a').colorbox({
                    maxWidth : '95%',
                    maxHeight : '95%',
                    opacity : 0.8,
                });
            });
            @endif
            @endforeach
            /**
             * End initial review image gallery justify gallery
             */

            /**
             * Start initial share button and share modal
             */
            $('.item-share-button').on('click', function(){
                $('#share-modal').modal('show');
            });
            $('.item-contact-button').on('click', function(){
                $('#contact-modal').modal('show');
            });

            @error('item_share_email_name')
            $('#share-modal').modal('show');
            @enderror

            @error('item_share_email_from_email')
            $('#share-modal').modal('show');
            @enderror

            @error('item_share_email_to_email')
            $('#share-modal').modal('show');
            @enderror

            @error('item_share_email_note')
            $('#share-modal').modal('show');
            @enderror

            @error('item_conntact_email_name')
            $('#contact-modal').modal('show');
            @enderror

            @error('item_contact_email_from_email')
            $('#contact-modal').modal('show');
            @enderror

            @error('item_contact_email_note')
            $('#contact-modal').modal('show');
            @enderror

            
            /**
             * End initial share button and share modal
             */

            /**
             * Start initial listing lead modal
             */
            @error('item_lead_name')
            $('#itemLeadModal').modal('show');
            @enderror

            @error('item_lead_email')
            $('#itemLeadModal').modal('show');
            @enderror

            @error('item_lead_phone')
            $('#itemLeadModal').modal('show');
            @enderror

            @error('item_lead_subject')
            $('#itemLeadModal').modal('show');
            @enderror

            @error('item_lead_message')
            $('#itemLeadModal').modal('show');
            @enderror

            @error('g-recaptcha-response')
            $('#itemLeadModal').modal('show');
            @enderror
            /**
             * End initial listing lead modal
             */

            /**
             * Start initial save button
             */
            // xl view
            $('#item-save-button-xl').on('click', function(){
                $("#item-save-button-xl").addClass("disabled");
                $("#item-save-form-xl").submit();
            });

            $('#item-saved-button-xl').on('click', function(){
                $("#item-saved-button-xl").off("mouseenter");
                $("#item-saved-button-xl").off("mouseleave");
                $("#item-saved-button-xl").addClass("disabled");
                $("#item-unsave-form-xl").submit();
            });

            $("#item-saved-button-xl").on('mouseenter', function(){
                $("#item-saved-button-xl").attr("class", "btn btn-danger rounded text-white");
                $("#item-saved-button-xl").html("<i class=\"far fa-trash-alt\"></i> <?php echo __('frontend.item.unsave') ?>");
            });

            $("#item-saved-button-xl").on('mouseleave', function(){
                $("#item-saved-button-xl").attr("class", "btn btn-warning rounded text-white");
                $("#item-saved-button-xl").html("<i class=\"fas fa-check\"></i> <?php echo __('frontend.item.saved') ?>");
            });

            // md view
            $('#item-save-button-md').on('click', function(){
                $("#item-save-button-md").addClass("disabled");
                $("#item-save-form-md").submit();
            });

            $('#item-saved-button-md').on('click', function(){
                $("#item-saved-button-md").off("mouseenter");
                $("#item-saved-button-md").off("mouseleave");
                $("#item-saved-button-md").addClass("disabled");
                $("#item-unsave-form-md").submit();
            });

            $("#item-saved-button-md").on('mouseenter', function(){
                $("#item-saved-button-md").attr("class", "btn btn-danger rounded text-white");
                $("#item-saved-button-md").html("<i class=\"far fa-trash-alt\"></i> <?php echo __('frontend.item.unsave') ?>");
            });

            $("#item-saved-button-md").on('mouseleave', function(){
                $("#item-saved-button-md").attr("class", "btn btn-warning rounded text-white");
                $("#item-saved-button-md").html("<i class=\"fas fa-check\"></i> <?php echo __('frontend.item.saved') ?>");
            });

            // sm view
            $('#item-save-button-sm').on('click', function(){
                $("#item-save-button-sm").addClass("disabled");
                $("#item-save-form-sm").submit();
            });

            $('#item-saved-button-sm').on('click', function(){
                $("#item-saved-button-sm").off("mouseenter");
                $("#item-saved-button-sm").off("mouseleave");
                $("#item-saved-button-sm").addClass("disabled");
                $("#item-unsave-form-sm").submit();
            });

            $("#item-saved-button-sm").on('mouseenter', function(){
                $("#item-saved-button-sm").attr("class", "btn btn-sm btn-danger rounded text-white");
                $("#item-saved-button-sm").html("<i class=\"far fa-trash-alt\"></i> <?php echo __('frontend.item.unsave') ?>");
            });

            $("#item-saved-button-sm").on('mouseleave', function(){
                $("#item-saved-button-sm").attr("class", "btn btn-sm btn-warning rounded text-white");
                $("#item-saved-button-sm").html("<i class=\"fas fa-check\"></i> <?php echo __('frontend.item.saved') ?>");
            });
            /**
             * End initial save button
             */

            /**
             * Start rating star
             */
            @if($item_count_rating > 0)
            $(".rating_stars_header").rateYo({
                spacing: "5px",
                starWidth: "23px",
                readOnly: true,
                rating: {{ $item_average_rating }},
            });
            @endif
            /**
             * End rating star
             */

            /**
             * Start rating sort by
             */
            $('#rating_sort_by').on('change', function() {
                $( "#item-rating-sort-by-form" ).submit();
            });
            /**
             * End rating sort by
             */

            /**
             * Start initial QR code
             */
            var window_width = $(window).width();
            var qrcode_width = 0;
            if(window_width >= 400)
            {
                qrcode_width = 400;
            }
            else
            {
                qrcode_width = window_width - 50 > 0 ? window_width - 50 : window_width;
            }

            $("#item-qrcode").qrcode({
                // render method: 'canvas', 'image' or 'div'
                render: 'canvas',

                // version range somewhere in 1 .. 40
                minVersion: 10,
                //maxVersion: 40,

                // error correction level: 'L', 'M', 'Q' or 'H'
                ecLevel: 'H',

                // offset in pixel if drawn onto existing canvas
                left: 0,
                top: 0,

                // size in pixel
                size: qrcode_width,

                // code color or image element
                fill: '{{ $customization_site_primary_color }}',

                // background color or image element, null for transparent background
                background: '#f8f9fa',

                // content
                text: '{{ route('page.item', ['item_slug' => $item->item_slug]) }}',

                // corner radius relative to module width: 0.0 .. 0.5
                radius: 0.5,

                // quiet zone in modules
                quiet: 4,

                // modes
                // 0: normal
                // 1: label strip
                // 2: label box
                // 3: image strip
                // 4: image box
                mode: 0,

                mSize: 0.1,
                mPosX: 0.5,
                mPosY: 0.5,

                label: 'no label',
                fontname: 'sans',
                fontcolor: '#000',

                image: null
            });
            /**
             * End initial QR code
             */
        });
    </script>

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_GOOGLE_MAP && $item->item_type == \App\Item::ITEM_TYPE_REGULAR)
    <script>
        // Initialize and add the map
        function initMap() {
            // The location of Uluru
            var uluru = {lat: <?php echo $item->item_lat; ?>, lng: <?php echo $item->item_lng; ?>};
            // The map, centered at Uluru
            var map = new google.maps.Map(document.getElementById('mapid-item'), {
                zoom: 14,
                center: uluru,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            // The marker, positioned at Uluru
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js??v=quarterly&key={{ $site_global_settings->setting_site_map_google_api_key }}&callback=initMap"></script>
    @endif
    <script>
        $(function(){
            var $sections=$('.form-section');

            function navigateTo(index){
                $sections.removeClass('current').eq(index).addClass('current');

                $('.form-navigation .previous').toggle(index>0);
                var atTheEnd = index >= $sections.length - 1;
                $('.form-navigation .next').toggle(!atTheEnd);
                $('.form-navigation [Type=submit]').toggle(atTheEnd);
        
                const step= document.querySelector('.step'+index);
                // console.log(step);
                step.style.backgroundColor="#17a2b8";
                step.style.color="white";
            }

            function curIndex(){
                return $sections.index($sections.filter('.current'));
            }

            $('.form-navigation .previous').click(function(){
                navigateTo(curIndex() - 1);
            });

            $('.form-navigation .next').click(function(){
                $('.contact-coach-form').parsley().whenValidate({
                    group:'block-'+curIndex()
                }).done(function(){
                    navigateTo(curIndex()+1);
                });

            });

            $sections.each(function(index,section){
                $(section).find(':input').attr('data-parsley-group','block-'+index);
            });

            navigateTo(0);

        });
    </script>
    <script>
        $('#question_2').on('change',function(){
                $('#question2_txt').slideToggle("slow");
                var val = this.checked ? this.value : '';
                if(val){
                    $("#question2_txt").prop('required',true);
                    $("#question2_checkbox1").prop('required',false);
                    $("#question2_checkbox2").prop('required',false);
                    $("#question2_checkbox3").prop('required',false);
                    $("#question2_checkbox4").prop('required',false);
                    $("#question2_checkbox5").prop('required',false);
                    $("#question2_checkbox6").prop('required',false);
                }else{
                    $("#question2_txt").prop('required',false);
                    $("#question2_checkbox1").prop('required',true);
                    $("#question2_checkbox2").prop('required',true);
                    $("#question2_checkbox3").prop('required',true);
                    $("#question2_checkbox4").prop('required',true);
                    $("#question2_checkbox5").prop('required',true);
                    $("#question2_checkbox6").prop('required',true);
                }
            });
    </script>
    <script>
        $("#contact-modal").on("hidden.bs.modal", function() {
            $("#contactFormModal").trigger("reset");
            $('.question1_desc_char_count,.question2_desc_char_count,.question3_desc_char_count,.question5_desc_char_count,.question6_desc_char_count').text("750/750");
            $('.question1_error,.question2_error,.question3_error,.question5_error,.question6_error').html('');
            $('.steps').removeAttr('style');
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/inputmask/inputmask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/inputmask/jquery.inputmask.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
    <script>
        Inputmask({
            "mask": "999999"
        }).mask(".otp");

        $('#resend_login_otp').on('click', function() {
            $("#resend_login_otp").attr('disabled', true);
            $("#resend_login_otp").attr('data-kt-indicator', 'on');
            $('#kt_login_step_01').trigger('click');
        });

        let interval_otp;

        $(".ap-otp-input").keyup(function(){
            $('#otp_val').text('');
            otp = $('#code_1').val() + $('#code_2').val()  + $('#code_3').val()  + $('#code_4').val()  + $('#code_5').val()  + $('#code_6').val() ;
            check_otp_length = otp.length;
            if(check_otp_length == 6){
                $('#kt_login_step_02').trigger('click');
            }
        });

        $("#kt_login_step_01").on('click', function(e) {
            e.preventDefault();
            $('#email_val').text('');
            if ($('#contactFormModal').valid()) {
                // var captcha = $('textarea#g-recaptcha-response').val();
                var captcha = "";
                $("form#contactFormModal textarea[name=g-recaptcha-response]").each(function(){
                    captcha = $(this).val();
                });
                if(captcha != ''){
                    $("#kt_login_step_01").attr('disabled', true);
                    $("#kt_login_step_01").attr('data-kt-indicator', 'on');
                    email = $('#item_contact_email_from_email').val();
                    name = $('#item_conntact_email_name').val();              
                    var email_from = email.substring(0, email.indexOf("@"));
                    var num = (email_from.match(/\./g)) ? email_from.match(/\./g).length : 0;
                    if(num <= 2)
                    {
                        $.ajax({
                            url: "/conact-coach",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            method: "POST",
                            data: {
                                'email': email,
                                'name':name,
                                
                            },
                            success: function(response) {
                                if (response.status==false) {
                                    $("#kt_login_step_01").removeAttr('disabled');
                                    $("#kt_login_step_01").removeAttr('data-kt-indicator');
                                    $("#resend_login_otp").removeAttr('disabled');
                                    $("#resend_login_otp").removeAttr('data-kt-indicator');
                                    if(response.message){
                                        $('.disabled-login-error').text(response.message);
                                        $('.disabled-login-error').show();
                                    }
                                    // if (response.errors.email) {
                                    //     $('#email_val').text(response.errors.email);
                                    // }
                                }else{
                                    $("#new_user").val(response.new_user)
                                    $("#resend_login_otp_title").text("OTP send in");
                                    setCountDownLoginOTP();
                                    $("#resend_login_otp").hide();
                                    $("#kt_login_step_01").removeAttr('disabled');
                                    $("#kt_login_step_01").removeAttr('data-kt-indicator');
                                    $("#resend_login_otp").removeAttr('disabled');
                                    $("#resend_login_otp").removeAttr('data-kt-indicator');
                                    $('.disabled-login-error').text('');
                                    $('.disabled-login-error').hide();
                                    $(".step-01").hide();
                                    $(".step-02").show();
                                    $("#code_1").focus();
                                    $("#indicator-progress").show();

                                    // pls_wit
                                }
                            },
                            error: function(err) {
                                $("#kt_login_step_01").removeAttr('disabled');
                                $("#kt_login_step_01").removeAttr('data-kt-indicator');
                                if(!err.responseJSON.status) {
                                    $('.disabled-login-error').text(err.responseJSON.message);
                                    $('.disabled-login-error').show();
                                    // if (err.responseJSON.errors.email) {
                                    //     $('#email_val').text(err.responseJSON.errors.email);
                                    // }
                                }
                            }
                        });
                    }
                    else {
                        $("#kt_login_step_01").removeAttr('disabled');
                        $("#kt_login_step_01").removeAttr('data-kt-indicator');
                        $('#email_val').text('Invalid email !');
                    }
                }else{
                    $('#re_captcha_error1').html('<ul class="parsley-errors-list" aria-hidden="false">Re-Captcha field is required</ul>') 
                }
            }
        });

        $("#kt_login_step_02").on('click', function() {
            if ($('#contactFormModal').valid()) {
                $("#kt_login_step_02").attr('disabled', true);
                $("#kt_login_step_02").attr('data-kt-indicator', 'on');
                otp = $('#code_1').val() + $('#code_2').val() + $('#code_3').val() + $('#code_4').val() + $('#code_5').val() + $('#code_6').val();
                $.ajax({
                    url: "/verify-conact-coach-email-otp",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    data: {
                        'otp': otp
                    },
                    success: function(res) {
                        if (res.status == true) {
                            console.log(res);
                            $('#contactFormModal').submit();
                        } else {
                            $("#kt_login_step_02").removeAttr('disabled');
                            $("#kt_login_step_02").removeAttr('data-kt-indicator');
                            $('#otp_val').text(res.message);
                        }
                    },
                    error: function(err) {
                        $("#kt_login_step_02").removeAttr('disabled');
                        $("#kt_login_step_02").removeAttr('data-kt-indicator');
                    }
                });
            }
        });

        $("#kt_login_prev_step_01").on('click', function() {
            $('#otp_val').text('');
            $("#code_1").val('');
            $("#code_2").val('');
            $("#code_3").val('');
            $("#code_4").val('');
            $("#code_5").val('');
            $("#code_6").val('');
            clearInterval(interval_otp);
            $(".step-02").hide();	
            $(".step-01").show();	

        });	

        function setCountDownLoginOTP() {
            $(".login_otp_countdown").html('');
            $(".login_otp_countdown").show();
            var otp_timer2 = "1:00";
            interval_otp = setInterval(function() {
                var timer_otp = otp_timer2.split(':');
                //by parsing integer, I avoid all extra string processing
                var minutes = parseInt(timer_otp[0], 10);
                var seconds = parseInt(timer_otp[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) clearInterval(interval_otp);
                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                //minutes = (minutes < 10) ?  minutes : minutes;
                $('.login_otp_countdown').html(minutes + ':' + seconds);
                otp_timer2 = minutes + ':' + seconds;
                if(minutes < 0) {
                    $(".login_otp_countdown").hide();
                    clearInterval(interval_otp);
                    $("#resend_login_otp").show();
                    $("#resend_login_otp_title").text("Didn't get the otp?");
                }
            }, 1000);
        }

        const $inp = $(".ap-otp-input");
        $inp.on({
            paste(ev) { // Handle Pasting
                const clip = ev.originalEvent.clipboardData.getData('text').trim();
                // Allow numbers only
                if (!/\d{6}/.test(clip)) return ev.preventDefault(); // Invalid. Exit here
                // Split string to Array or characters
                const s = [...clip];
                // Populate inputs. Focus last input.
                $inp.val(i => s[i]).eq(5).focus();
            },
            input(ev) { // Handle typing
                const i = $inp.index(this);
                if (this.value) $inp.eq(i + 1).focus();
            },
            keydown(ev) { // Handle Deleting
                const i = $inp.index(this);
                if (!this.value && ev.key === "Backspace" && i) $inp.eq(i - 1).focus();
            }
        });
    </script>

@endsection
