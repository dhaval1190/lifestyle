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
                        <div class="col-md-9 text-center">
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('Profile Completeness Detail') }}</h1>
                            <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ __('How it works?') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="site-section"> -->
        <div class="container">
            <section class="mobile_section">
                <div class="row">
                    <div class="col-md-12">
                        <div class="step_section">
                            <div class="row ptb-20">
                                <div class="col-lg-4 col-md-6 offset-0 offset-lg-5 order_first">
                                    <div class="step_img">
                                        <img src="{{ asset('frontend/images/flow_chart/one.svg') }}" alt="">
                                        <!-- <p class="step_first_number">01</p> -->
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 order_second">
                                    <div class="step_info">
                                        <p class="content">
                                            <span class="bold">Basic</span>
                                            <i id="basic" class="icon-info-sign fa fa-info-circle" aria-hidden="true" title="info" data-toggle="modal" data-target="#earn_points_detail"></i>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row ptb-20">
                                <div class="col-lg-3 col-md-6 order_second">
                                    <div class="step_info">
                                        <p class="content">
                                            <span class="bold">Social</span>
                                            <i id="social" class="icon-info-sign fa fa-info-circle" aria-hidden="true" title="info" data-toggle="modal" data-target="#earn_points_detail"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 order_first pl-xl-0 pl-lg-0 ">
                                    <div class="step_img">
                                        <img src="{{ asset('frontend/images/flow_chart/two.svg') }}" alt="">
                                        <!-- <p class="step_second_number">02</p> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row ptb-20">
                                <div class="col-lg-4 col-md-6 offset-0 offset-lg-5 order_first">
                                    <div class="step_img">
                                        <img src="{{ asset('frontend/images/flow_chart/three.svg') }}" alt="">
                                        <!-- <p class="step_third_number">03</p> -->
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="step_info">
                                        <p class="content">
                                            <span class="bold">Bronze</span>
                                            <i id="bronze" class="icon-info-sign fa fa-info-circle" aria-hidden="true" title="info" data-toggle="modal" data-target="#earn_points_detail"></i>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row ptb-20">
                                <div class="col-lg-3 col-md-6  order_second">
                                    <div class="step_info">
                                        <p class="content">
                                            <span class="bold">Silver</span>
                                            <i id="silver" class="icon-info-sign fa fa-info-circle" aria-hidden="true" title="info" data-toggle="modal" data-target="#earn_points_detail"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 order_first pl-xl-0 pl-lg-0">
                                    <div class="step_img">
                                        <img src="{{ asset('frontend/images/flow_chart/four.svg') }}" alt="" class="">
                                        <!-- <p class="step_four_number">04</p> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row ptb-20">
                                <div class="col-lg-4 col-md-6 offset-0 offset-lg-5 order_first">
                                    <div class="step_img">
                                        <img src="{{ asset('frontend/images/flow_chart/five.svg') }}" alt="" class="">
                                        <!-- <p class="step_five_number">05</p> -->
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 order_second">
                                    <div class="step_info">
                                        <p class="content">
                                            <span class="bold">Gold</span>
                                            <i id="gold" class="icon-info-sign fa fa-info-circle" aria-hidden="true" title="info" data-toggle="modal" data-target="#earn_points_detail"></i>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row ptb-20">
                                <div class="col-lg-3 col-md-6 order_second">
                                    <div class="step_info">
                                        <p class="content">
                                            <span class="bold">Platinum</span>
                                            <i id="platinum" class="icon-info-sign fa fa-info-circle" aria-hidden="true" title="info" data-toggle="modal" data-target="#earn_points_detail"></i>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 order_first pl-xl-0 pl-lg-0">
                                    <div class="step_img">
                                        <img src="{{ asset('frontend/images/flow_chart/six.svg') }}" alt="" class="">
                                        <!-- <p class="step_six_number">06</p> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row ptb-20">
                                <div class="col-lg-4 col-md-6 offset-0 offset-lg-5 order_first ">
                                    <div class="step_img">
                                        <img src="{{ asset('frontend/images/flow_chart/seven.svg') }}" alt="">
                                        <!-- <p class="step_first_number">01</p> -->
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 order_second">
                                    <div class="step_info">
                                        <p class="content">
                                            <span class="bold">Rhodium</span>
                                            <i id="rhodium" class="icon-info-sign fa fa-info-circle" aria-hidden="true" title="info" data-toggle="modal" data-target="#earn_points_detail"></i>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    <!-- </div> -->


    <div class="modal fade" id="earn_points_detail" tabindex="-1" role="dialog" aria-labelledby="earn_points_detail" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">How it works?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" id="basic_content">
                        <div class="col-md-12">
                            <span class="bold">Basic Profile complete includes</span>
                        </div>
                        <div class="col-md-6">
                            <ul class="list_design_ptb-30">
                                <li>Picture</li>
                                <li>Category</li>
                                <li>Name</li>
                                <li>Company Name</li>
                                <li>Phone</li>
                                <li>Email</li>
                                <li>Working Method</li>
                                <li>Pronouns</li>
                                <li>Rate</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list_design_ptb-30">
                                <li>Years Exp.</li>
                                {{-- <li>Certifications</li>
                                <li>Awards</li> --}}
                                <li>Address</li>
                                <li>Country</li>
                                <li>State</li>
                                <li>City</li>
                                <li>Zip Code</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row" id="social_content">
                        <div class="col-md-12">
                            <span class="bold">Basic + Social Profile complete includes</span>
                            <ul class="list_design_ptb-30">
                                <li>Website</li>
                                <li>IG Handle*</li>
                                <li>Facebook*</li>
                                <li>LinkedIn*</li>
                                <li>YouTube*</li>
                            </ul>
                            <p class="content">*Must use /have a min of 3 platforms</p>
                        </div>
                    </div>
                    <div class="row" id="bronze_content">
                        <div class="col-md-12">
                            <span class="bold">Basic + Social + Bronze Level complete includes</span>
                            <ul class="list_design_ptb-30">
                                <li>10 pieces of content uploaded</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row" id="silver_content">
                        <div class="col-md-12">
                            <span class="bold">Basic + Social + Bronze + Silver Level complete includes</span>
                            <ul class="list_design_ptb-30">
                                <li>20 Pieces of content AND must include at least one from each type (Video, Podcast, Blog, Ebook)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row" id="gold_content">
                        <div class="col-md-12">
                            <span class="bold">Basic + Social + Bronze + Silver + Gold Level complete</span>
                            <ul class="list_design_ptb-30">
                                <li>3 Client Reviews</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row" id="platinum_content">
                        <div class="col-md-12">
                            <span class="bold">Basic + Social + Bronze + Silver + Gold + Platinum Level complete</span>
                            <ul class="list_design_ptb-30">
                                <li>30 Pieces of content</li>
                                <li>7 Client Reviews</li>
                                <li>1 Client Referral</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row" id="rhodium_content">
                        <div class="col-md-12">
                            <span class="bold">Basic + Social + Bronze + Silver + Gold + Platinum + Rhodium Level complete</span>
                            <ul class="list_design_ptb-30">
                                <li>5 Client referrals</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $("#basic").click(function(){
            $("#basic_content").show();
            $("#social_content").hide();
            $("#bronze_content").hide();
            $("#silver_content").hide();
            $("#gold_content").hide();
            $("#platinum_content").hide();
            $("#rhodium_content").hide();
        });
        $("#social").click(function(){
            $("#basic_content").show();
            $("#social_content").show();
            $("#bronze_content").hide();
            $("#silver_content").hide();
            $("#gold_content").hide();
            $("#platinum_content").hide();
            $("#rhodium_content").hide();
        });
        $("#bronze").click(function(){
            $("#basic_content").show();
            $("#social_content").show();
            $("#bronze_content").show();
            $("#silver_content").hide();
            $("#gold_content").hide();
            $("#platinum_content").hide();
            $("#rhodium_content").hide();
        });
        $("#silver").click(function(){
            $("#basic_content").show();
            $("#social_content").show();
            $("#bronze_content").show();
            $("#silver_content").show();
            $("#gold_content").hide();
            $("#platinum_content").hide();
            $("#rhodium_content").hide();
        });
        $("#gold").click(function(){
            $("#basic_content").show();
            $("#social_content").show();
            $("#bronze_content").show();
            $("#silver_content").show();
            $("#gold_content").show();
            $("#platinum_content").hide();
            $("#rhodium_content").hide();
        });
        $("#platinum").click(function(){
            $("#basic_content").show();
            $("#social_content").show();
            $("#bronze_content").show();
            $("#silver_content").show();
            $("#gold_content").show();
            $("#platinum_content").show();
            $("#rhodium_content").hide();
        });
        $("#rhodium").click(function(){
            $("#basic_content").show();
            $("#social_content").show();
            $("#bronze_content").show();
            $("#silver_content").show();
            $("#gold_content").show();
            $("#platinum_content").show();
            $("#rhodium_content").show();
        });
    </script>

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
