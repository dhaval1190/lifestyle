@extends('frontend.layouts.app')

@section('styles')

    @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR && $site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <link rel="stylesheet" href="{{ asset('frontend/vendor/justified-gallery/justifiedGallery.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('frontend/vendor/colorbox/colorbox.css') }}" type="text/css">

    <!-- Start Google reCAPTCHA version 2 -->
    @if($site_global_settings->setting_site_recaptcha_item_lead_enable == \App\Setting::SITE_RECAPTCHA_ITEM_LEAD_ENABLE)
        {!! htmlScriptTagJsApi(['lang' => empty($site_global_settings->setting_site_language) ? 'en' : $site_global_settings->setting_site_language]) !!}
    @endif
    <!-- End Google reCAPTCHA version 2 -->
@endsection

@section('content')

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
                            {{ $item->city->city_name }}, {{ $item->state->state_name }} 
                            @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                             {{ $item->item_postal_code }}
                            @endif
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

                    @if(!empty($item->item_phone))
                    <a class="btn btn-primary rounded text-white" href="tel:{{ $item->item_phone }}"><i class="fas fa-phone-alt"></i> {{ __('frontend.item.call') }}</a>
                    @endif
                    <!-- <a class="btn btn-primary rounded text-white" href="#" data-toggle="modal" data-target="#qrcodeModal"><i class="fas fa-qrcode"></i></a> -->
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
                            {{ $item->city->city_name }}, {{ $item->state->state_name }} 
                            @if($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE)
                             {{ $item->item_postal_code }}
                            @endif
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
                    <!-- <a class="btn btn-primary btn-sm rounded text-white" href="#" data-toggle="modal" data-target="#qrcodeModal"><i class="fas fa-qrcode"></i></a> -->
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            Statics
        </div>
    </div>

@endsection
