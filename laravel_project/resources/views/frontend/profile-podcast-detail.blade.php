@extends('frontend.layouts.app')

@section('styles')
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('frontend/css/custom_style.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/custom_media.css') }}">
    <link href="{{ asset('frontend/css/mp3style.css') }}" rel="stylesheet" />
    <link href="{{ asset('frontend/css/demo.css') }}" rel="stylesheet" />
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>

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
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('Podcast') }}</h1>
                            <!-- <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ __('Profile Detail') }}</p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <section class="below">
            <div class="container">
                <div class="below_bg">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info">
                                <h3>Our Podcast</h3>
                            </div>
                        </div>
                        <div class="col-md-12 plr-45">
                            <div class="post-slide">
                                <div class="row audio-players">
                                    @foreach($podcast_media_array as $podcast_key => $podcast)
                                        <div class="col-md-3 col-6">
                                            <div class="audio-player js-audio-player">
                                                <button class="audio-player__control js-control">
                                                    <div class="audio-player__control-icon"></div>
                                                </button>
                                                <h4 class="audio-player__title">{{ $podcast->media_name }}</h4>
                                                <audio preload="auto">
                                                    <source src="{{ Storage::disk('public')->url('media_files/'. $podcast->media_image) }}"/>
                                                </audio>
                                                <!-- <img class="audio-player__cover" src="https://unsplash.it/g/300?image=29"/> -->
                                                <img class="audio-player__cover" src="{{ Storage::disk('public')->url('media_files/'. $podcast->media_cover) }}">
                                                <video preload="auto" loop="loop">
                                                    <source src="" type="video/mp4"/>
                                                </video>
                                            </div>
                                        </div>
                                        @if($podcast_key==3)
                                            </div></div><div class="post-slide"><div class="row audio-players">
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-12 plr-45">
                            <div class="row">
                                <div class="simple-audio-player" id="simp" data-config='{"shide_top":false,"shide_btm":false,"auto_load":false}'>
                                    <div class="simp-playlist">
                                        <ul>
                                            @foreach($podcast_media_array as $podcast_key => $podcast)
                                                <div class="post-slide">
                                                    <div class="post-img">
                                                        <li class="simp-active"><span class="simp-source" data-src="{{ Storage::disk('public')->url('media_files/'. $podcast->media_image) }}">{{ $podcast->media_name }}</span></li>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div> -->
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
    <script src="{{ asset('frontend/js/script.js') }}"></script>
    @include('frontend.partials.bootstrap-select-locale')
    <script>
        var $player = $('.js-audio-player'), $playbackClass = 'is-playing', $fadeDuration = 500

        $player.each(function(index) {
            var $this = $(this), id = 'audio-player-' + index

            $this.attr('id', id)

            $this.find('.js-control')[0].addEventListener('click', function() {
                resetPlayback(id)
                playback($this, $this.find('audio'), $this.find('video'))
            })
            
            // Reset state once audio has finished playing
            $this.find('audio')[0].addEventListener('ended', function() {
                resetPlayback()
            })
        })

        function playback($player, $audio, $video) {
            if ($audio[0].paused) {
                $audio[0].play()
                $video[0].play()
                $audio.animate({ volume: 1 }, $fadeDuration)
                $player.addClass($playbackClass)
            } else {
                $audio.animate({ volume: 0 }, $fadeDuration, function() {
                    $audio[0].pause()
                    $video[0].pause()
                })
                $player.removeClass($playbackClass)
            }
        }

        function resetPlayback(id) {
            $player.each(function() {
                var $this = $(this)

                if ($this.attr('id') !== id) {
                    $this.find('audio').animate({ volume: 0 }, $fadeDuration, function() {
                        $(this)[0].pause()
                        $this.find('video')[0].pause()
                    })
                    $this.removeClass($playbackClass)
                }
            })
        }

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
