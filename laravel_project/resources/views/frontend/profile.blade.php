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
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image) }});">

    @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: #333333;">
    @endif

        @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            <div data-youtube="{{ $site_innerpage_header_background_youtube_video }}"></div>
        @endif

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">
                    <div class="row justify-content-center mt-5">
                        <div class="col-md-8 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('Profile') }}</h1>
                            <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ __('frontend.categories.description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="upper_white_bg">
                        <div class="upper_middle_img">
                            @if(empty($user_detail['user_cover_image']))
                                <img src="{{ asset('frontend/images/main_upper_logo.png') }}" alt="" class="main_logo"/>
                            @else
                                <img src="{{ Storage::disk('public')->url('user/'. $user_detail['user_cover_image']) }}" class="main_logo">
                            @endif
                            <!-- <img src="{{ asset('frontend/images/main_upper_logo.png') }}" alt="" class="main_logo" /> -->
                            <div class="upper_main_section">
                                <div class="upper_logo">
                                    @if(empty($user_detail['user_image']))
                                        <img src="{{ asset('backend/images/placeholder/profile-' . intval($user_detail['id'] % 10) . '.webp') }}" class="logo">
                                    @else
                                        <img src="{{ Storage::disk('public')->url('user/'. $user_detail['user_image']) }}" class="logo">
                                    @endif
                                    <div class="space_between">
                                        <div class="upper_logo_details">
                                            <h1>{{ $user_detail['name'] }}</h1>
                                            <p>{{ !empty($user_detail['working_type']) ? \App\User::WORKING_TYPES[$user_detail['working_type']] : '' }} {{ !empty($user_detail['experience_year']) ? ' | '.\App\User::EXPERIENCE_YEARS[$user_detail['experience_year']].' Years' : '' }} {{ !empty($user_detail['hourly_rate_type']) ? ' | '.\App\User::HOURLY_RATES[$user_detail['hourly_rate_type']] : '' }}</p>
                                            <div class="details">
                                                <div class="detail one">
                                                    <img src="{{ asset('frontend/images/bag.png') }}" alt="">
                                                    <p>{{ $user_detail['company_name']}}</p>
                                                </div>
                                                <div class="detail two">
                                                    <img src="{{ asset('frontend/images/bag-one.svg') }}" alt="">
                                                    <p>{{ !empty($user_detail['preferred_pronouns']) ? \App\User::PREFERRED_PRONOUNS[$user_detail['preferred_pronouns']] : '' }}</p>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 25%"
                                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                    25%
                                                </div>
                                            
                                            </div>
                                        </div>
                                        <div class="upper_address_info">
                                            <h3>Address</h3>
                                            <div class="upper_address_detail">
                                                <div class="upper_address">
                                                    <img src="{{ asset('frontend/images/seven.png') }}" alt="" class="address_logo"/>
                                                    <p>{{ !empty($user_detail['address']) ? $user_detail['address'].',' : '' }} {{ !empty($user_detail->city->city_name) ? $user_detail->city->city_name.',' : '' }} {{ !empty($user_detail->state->state_name) ? $user_detail->state->state_name.',' : '' }} {{ !empty($user_detail->country->country_name) ? $user_detail->country->country_name : '' }} {{ $user_detail['post_code'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="upper_logo_info">
                                <div class="upper_logo_padding_25">
                                    <h3>About</h3>
                                    <p>{{ $user_detail['user_about']}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="middle">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="middle_bg">
                            <div class="middle_info">
                                <h3>Contact Details</h3>
                            </div>
                            <div class="middle_detail">
                                <div class="middle_main"><img src="{{ asset('frontend/images/five.png') }}" alt="" />
                                    <p>{{ $user_detail['email'] }}</p>
                                </div>
                                <div class="middle_main"><img src="{{ asset('frontend/images/six.png') }}" alt="" />
                                    <p>{{ $user_detail['phone'] }}</p>
                                </div>
                                <div class="middle_main"><img src="{{ asset('frontend/images/eight.png') }}" alt="" />
                                    <p>{{ $user_detail['website'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <div class="middle_bg_two">
                            <div class="middle_info">
                                <h3>Social Links</h3>
                            </div>
                            <div class="middle_detail">
                                <div class="middle_main"><img src="{{ asset('frontend/images/two.png') }}" alt="" />
                                    <a href="{{ $user_detail['linkedin'] }}"><p>{{ $user_detail['linkedin'] }}</p></a>
                                </div>
                                <div class="middle_main"><img src="{{ asset('frontend/images/four.png') }}" alt="" />
                                    <a href="{{ $user_detail['instagram'] }}"><p>{{ $user_detail['instagram'] }}</p></a>
                                </div>
                                <div class="middle_main"><img src="{{ asset('frontend/images/one.png') }}" alt="" />
                                    <a href="{{ $user_detail['facebook'] }}"><p>{{ $user_detail['facebook'] }}</p></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="below">
            <div class="container">
                <div class="below_bg">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="below_info">
                                <h3>Youtube</h3>
                            </div>
                        </div>
                    <div class="col-lg-12 plr-45">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="video">
                                    <!-- <iframe src="{{ $user_detail['youtube'] }}" width="854" height="480" style="position:absolute;left:0;top:0;width:100%;height:100%" frameborder="0" scrolling="no" allowfullscreen></iframe> -->
                                    <iframe width="500" height="380" src="{{ $user_detail['youtube'] }}"
                                        title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                            <!-- <div class="col-lg-6">
                                <div class="video_info">
                                    <h3>Intro to Bunny Young, Business Therapist and BLOOPERS!</h3>
                                    <p>
                                        Who is Bunny Young? What does Bunny Young do? How can Bunny
                                        Young help you? What is a Business Therapist? All of these
                                        questions and more answered in this video about Bunny Young.
                                        Stuntwoman turned serial entrepreneur and melorist, Bunny Young
                                        doesn't hold any punches. This video is about who Bunny Young is
                                        and what this channel is about.
                                    </p>
                                    <a href="#">Read More</a>
                                </div>
                            </div>  -->
                        </div>
                    
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info">
                                <h3>Keep Learning</h3>
                                @if(isset($media_count) && $media_count > 5)
                                <a href="{{ route('page.profile.detail', $user_detail['id']) }}">View all</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 plr-45">
                            <div id="news-slider" class="owl-carousel">
                                @if(isset($video_media_array) && !empty($video_media_array))
                                    @foreach($video_media_array as $video_key => $video)
                                        <div class="post-slide">
                                            <div class="post-img">
                                                <iframe width="250" height="215" src="{{ $video['media_url']}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>                            
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
            $("#news-slider").owlCarousel({
                items : 4,
                itemsDesktop:[1199,4],
                itemsDesktopSmall:[980,2],
                itemsMobile : [600,1],
                navigation:true,
                navigationText:["",""],
                pagination:true,
                autoPlay:true
            });

            "use strict";

            @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            @endif
        });

    </script>

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_GOOGLE_MAP)
        <script>
            // Initial the google map
            function initMap() {

                @if(count($free_items))

                var window_height = $(window).height();
                $('#mapid-box').css('height', window_height + 'px');

                var locations = [];

                @foreach($free_items as $free_items_key => $free_item)
                    @if($free_item->item_type == \App\Item::ITEM_TYPE_REGULAR)

                        var popup_item_title = '{{ $free_item->item_title }}';

                        @if($free_item->item_address_hide)
                        var popup_item_address = '{{ $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code }}';
                        @else
                        var popup_item_address = '{{ $free_item->item_address . ', ' . $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code }}';
                        @endif
                        var popup_item_get_direction = '<a target="_blank" href="'+ '{{ 'https://www.google.com/maps/dir/?api=1&destination=' . $free_item->item_lat . ',' . $free_item->item_lng }}' +'"><i class="fas fa-directions"></i> '+ '{{ __('google_map.get-directions') }}' +'</a>';

                        @if($free_item->getCountRating() > 0)
                        var popup_item_rating = '{{ $free_item->item_average_rating }}' + '/5';
                        var popup_item_reviews = ' - {{ $free_item->getCountRating() }}' + ' ' + '{{ __('category_image_option.map.review') }}';
                        @else
                        var popup_item_rating = '';
                        var popup_item_reviews = '';
                        @endif

                        var popup_item_feature_image_link = '<img src="'+ '{{ !empty($free_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $free_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp') }}' +'">';
                        var popup_item_link = '<a href="' + '{{ route('page.item', $free_item->item_slug) }}' + '" target="_blank">' + popup_item_title + '</a>';

                        locations.push(["<div class='google_map_scrollFix'>" + popup_item_feature_image_link + "<br><br>" + popup_item_link + "<br>" + popup_item_rating + popup_item_reviews + "<br>" + popup_item_address + '<br>' + popup_item_get_direction + "</div>", {{ $free_item->item_lat }}, {{ $free_item->item_lng }} ]);

                     @endif
                @endforeach

                var infowindow = null;
                var infowindow_hover = null;

                if(locations.length === 0)
                {
                    // Destroy mapid-box DOM since no regular listings found
                    $("#mapid-box").remove();
                }
                else
                {
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

                                if(infowindow_hover)
                                {
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

                        if(map_item_reviews > 0)
                        {
                            map_item_rating = this.getAttribute("data-map-rating") + '/5';
                            map_item_reviews = ' - ' + this.getAttribute("data-map-reviews") + ' ' + '{{ __('category_image_option.map.review') }}';
                        }
                        else
                        {
                            map_item_rating = '';
                            map_item_reviews = '';
                        }

                        var map_item_link = '<a href="' + this.getAttribute("data-map-link") + '" target="_blank">' + map_item_title + '</a>';
                        var map_item_feature_image_link = '<img src="'+ this.getAttribute("data-map-feature-image-link") + '">';
                        var map_item_get_direction = '<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=' + map_item_lat + ',' + map_item_lng + '"><i class="fas fa-directions"></i> '+ '{{ __('google_map.get-directions') }}' +'</a>';

                        if(map_item_lat !== '' && map_item_lng !== '')
                        {
                            var center = new google.maps.LatLng(map_item_lat, map_item_lng);
                            var contentString = "<div class='google_map_scrollFix'>" + map_item_feature_image_link + "<br><br>" + map_item_link + "<br>" + map_item_rating + map_item_reviews + "<br>" + map_item_address + "<br>" + map_item_get_direction + "</div>";

                            if(infowindow_hover)
                            {
                                infowindow_hover.close();
                            }
                            if(infowindow)
                            {
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
        <script async defer src="https://maps.googleapis.com/maps/api/js??v=quarterly&key={{ $site_global_settings->setting_site_map_google_api_key }}&callback=initMap"></script>
    @endif

@endsection
