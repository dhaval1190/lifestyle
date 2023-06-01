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
                        {{-- <div class="col-md-12 plr-45">
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
                        </div> --}}
                        
                        {{-- <div class="col-md-12 plr-45">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="card rounded-0 shadow mb-5">
                                        <div class="card-body card_scroll">
                                            <ul class="list-group" id="music-list">
                                                @foreach($podcast_media_array as $podcast_key => $podcast)
                                                    <li class="list-group-item list-group-item-action item" data-id="<?= $podcast->id ?>">
                                                        <div class="d-flex w-100 align-items-center">
                                                            <div class="col-3 col-md-3 pe-2">
                                                                <img src="{{ Storage::disk('public')->url('media_files/'. $podcast->media_cover) }}" height="100px" width="100px" alt="" class="img-thumbnail bg-gradient bg-dark mini-display-img">
                                                            </div>
                                                            <div class="col-7 col-md-7 flex-grow-1 flex-shrink-1">
                                                                <p class="m-0 text-truncate" title="<?= $podcast->media_name ?>"><?= $podcast->media_name ?></p>
                                                            </div>
                                                            <div class="col-2 col-md-2 px-2">
                                                                <button class="btn btn-outline-secondary btn-sm rounded-circle play" data-id="<?= $podcast->id ?>" data-type="pause"><i class="fa fa-play"></i></button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach

                                                @foreach($podcast_media_array as $podcast_key => $podcast)
                                                    <li class="list-group-item list-group-item-action item" data-id="<?= $podcast->id ?>">
                                                        <div class="col-md-5">
                                                            <div class="">
                                                              <div class="box-video play" data-id="{{ $podcast->id }}" data-testid="play-pause-button" data-vid="box-video_{{ $podcast['id'] }}" id="#js-fitvideo_{{ $podcast->id }}">
                                                                <a href="" target="_blank" class="bg-video" id="podcast_id_{{ $podcast['id'] }}" data-toggle="modal" data-target="#podcastModal"
                                                                  style="background-image: @if(str_contains($podcast['media_image'],'spotify')) url('{{ asset('frontend/images/spotify_logo.png') }}') @elseif(str_contains($podcast['media_image'],'apple')) url('{{ asset('frontend/images/apple_logo.png') }}') @elseif(str_contains($podcast['media_image'],'stitcher')) url('{{ asset('frontend/images/stitcher_logo.png') }}') @elseif(str_contains($podcast['media_image'],'redcircle')) url('{{ asset('frontend/images/redcircle_logo.png') }}') @endif; opacity: 1;" data-src="{{ $podcast['media_image'] }}">
                                                                  <div class="bt-play" id="bt-play_{{ $podcast->id }}"></div>
                                                                </a>
                                                                <div class="video-container">
                                                                  <iframe width="590" height="100%"
                                                                    src="{{ $podcast['media_image'] }}" id="vid-reveal_{{ $podcast->id }}" frameborder="0"
                                                                    allowfullscreen="allowfullscreen"></iframe>
                                                                </div>
                                                              </div>
                                                            </div>
                                                          </div>
                                                    </li>
                                                    
                                                @endforeach
                                            </ul>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="col-md-12 text-center">
                                        <img src="{{ Storage::disk('public')->url('media_files/logo2.jpg') }}" alt="" id="display-img" class="img-fluid border bg-gradient bg-dark" style="width:100% !important;height:50% !important;">
                                    </div>
                                    <h4 class="text-center-font-set"><b id="inplay-title">---</b></h4>
                                    <small class="text-muted d-flex-set-center" id="inplay-duration">--:--</small>
                                    <!-- <hr>
                                    <p id="inplay-description">---</p> -->
                                    <div class="d-flex w-100 justify-content-center">
                                        <div class="mx-1">
                                            <button class="btn btn-sm btn-light bg-gradient text-dark" id="prev-btn"><i class="fa fa-step-backward"></i></button>
                                        </div>
                                        <div class="mx-1">
                                            <button class="btn btn-sm btn-light bg-gradient text-dark" id="play-btn" data-value="play"><i class="fa fa-play"></i></button>
                                        </div>
                                        <div class="mx-1">
                                            <button class="btn btn-sm btn-light bg-gradient text-dark" id="stop-btn" title="Stop"><i class="fa fa-stop"></i></button>
                                        </div>
                                        <div class="mx-1">
                                            <button class="btn btn-sm btn-light bg-gradient text-dark" id="next-btn"><i class="fa fa-step-forward"></i></button>
                                        </div>
                                    </div>
                                    <div class="d-flex-margin-100">
                                        <div class="mx-1">
                                            <span id="currentTime">--:--</span>
                                        </div>
                                        <div id="range-holder" class="mx-1">
                                            <input type="range" id="playBackSlider" value="0">
                                        </div>
                                        <div class="mx-1">
                                            <span id="vol-icon"><i class="fa fa-volume-up"></i></span> <input type="range" value="100" id="volume">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}


                        <div class="col-md-12 plr-45 padding_set_left_right_15">                                
                            <div class="row">
                                @foreach ($podcast_media_array as $podcast_key => $podcast)
                                <div class="col-md-3">
                                    <div class="">
                                      <div class="box-video play" data-id="{{ $podcast->id }}" data-testid="play-pause-button" data-vid="box-video_{{ $podcast['id'] }}" id="#js-fitvideo_{{ $podcast->id }}">
                                        <a href="" target="_blank" class="bg-video" id="podcast_id_{{ $podcast['id'] }}" data-toggle="modal" data-target="#podcastModal"
                                          style="background-image: @if(str_contains($podcast['media_image'],'spotify')) url('{{ asset('frontend/images/spotify_logo.png') }}') @elseif(str_contains($podcast['media_image'],'apple')) url('{{ asset('frontend/images/apple_logo.png') }}') @elseif(str_contains($podcast['media_image'],'stitcher')) url('{{ asset('frontend/images/stitcher_logo.png') }}') @elseif(str_contains($podcast['media_image'],'redcircle')) url('{{ asset('frontend/images/redcircle_logo.png') }}') @endif; opacity: 1;" data-src="{{ $podcast['media_image'] }}">
                                          <div class="bt-play" id="bt-play_{{ $podcast->id }}"></div>
                                        </a>
                                        <div class="video-container">
                                          <iframe width="590" height="100%"
                                            src="{{ $podcast['media_image'] }}" id="vid-reveal_{{ $podcast->id }}" frameborder="0"
                                            allowfullscreen="allowfullscreen"></iframe>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                @endforeach
                            </div>                          
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- For podcast modal-->
    <div class="modal-4">
        <div class="modal fade" id="podcastModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal_dialog" role="document">
              <div class="modal-content content_design3">
                <div class="modal-header border-bottom-0">
                  <h5 class="modal-title" id="exampleModalLabel_podcast"></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                 <iframe src="" id="iframesrcURL" frameborder="0"  class=""></iframe>
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
    <script src="{{ asset('frontend/js/script.js') }}"></script>
    @include('frontend.partials.bootstrap-select-locale')
    <script>
        $(".play").on('click', function() {
                    var id = $(this).attr('data-id');                                      
                    $.ajax({
                    type:'POST',
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                    url:'<?php echo url("/media-visitor"); ?>',
                    data: {'id':id},
                    success:function(data){
                    },
                });
                });
        const loaderHTML = document.createElement('div');
        loaderHTML.setAttribute('id', 'pre-loader');
        loaderHTML.innerHTML = "<div id='loader-container'><div></div><div></div><div></div></div>";
        window.start_loader = function() {
            if (!document.getElementById('pre-loader') || (!!document.getElementById('pre-loader') && document.getElementById('pre-loader').length <= 0)) {
                document.querySelector('body').appendChild(loaderHTML);
            }
        }
        window.end_loader = function() {
            if (!!document.getElementById('pre-loader')) {
                document.getElementById('pre-loader').remove();
            }
        }

        var audio = new Audio();
        var slider;
        var currentPlayID;
        $(function() {
            setTimeout(() => {
                end_loader()
            }, 500)
            $('.play').click(function() {
                var _this = $(this)
                var id = $(this).attr('data-id')
                var type = $(this).attr('data-type')
                // var id = $(this).val();
            
            // alert(id);
            // url = 'http://localhost/coach_directory/ajax/profile/podcast/' + id;
            url = 'http://localhost/coach_directory/ajax/profile/podcast/' + id;
                if (type == 'pause') {
                    start_loader();
                    // var ajax_url = '/ajax/profile/podcast/' + id;
                    
                    jQuery.ajax({
                        url: url,
                        method: 'get',
                        error: err => {
                            console.error(err)
                            console.log("An error occurred");
                            end_loader()
                        },
                        success: function(resp) {
                            var _modal = $('#update_music_modal')
                            if (resp.status == 'success') {
                                var data = resp.data
                                currentPlayID = data.id
                                $('#display-img').attr('src', data.media_cover);
                                $('#inplay-title').text(data.media_name);
                                // $('#inplay-description').text(data.media_description);
                                audio.setAttribute('src', data.media_image)
                                audio.currentTime = 0;
                                audio.play();
                                $('.play').attr('data-type', 'pause')
                                _this.attr('data-type', 'play')
                                $('.play').removeClass('btn-outline-success btn-outline-secondary').addClass('btn-outline-secondary')
                                _this.removeClass('btn-outline-success btn-outline-secondary').addClass('btn-outline-success')
                                $('#play-btn').attr('data-value', 'pause')
                                $('#play-btn').html('<i class="fa fa-pause"></i>').focus()
                                $('#inplay-duration').text("--:--")
                                setTimeout(() => {
                                    var duration = String(Math.floor(audio.duration / 60)).padStart(2, '0') + ":" + String(Math.floor(60 * Math.abs((audio.duration / 60) - Math.floor(audio.duration / 60)))).padStart(2, '0')
                                    $('#inplay-duration').text(duration)
                                }, 500)

                                slider = setInterval(() => {
                                    var current_slide = (audio.currentTime / audio.duration) * 100;
                                    $('#playBackSlider').val(current_slide)
                                    $('#currentTime').text(String(Math.floor(audio.currentTime / 60)).padStart(2, '0') + ":" + String(Math.floor(60 * Math.abs((audio.currentTime / 60) - Math.floor(audio.currentTime / 60)))).padStart(2, '0'))
                                }, 500)
                            } else {
                                console.error(err)
                                consle.log("An error occurred");
                            }
                            setTimeout(() => {
                                end_loader()
                            }, 500)

                        }
                    });
                }
            })
            $('#play-btn').click(function() {
                if (audio.src != '') {
                    var action = $(this).attr('data-value')
                    if (action == "pause") {
                        $(this).attr('data-value', 'play')
                        $('#play-btn').html('<i class="fa fa-play"></i>')
                        audio.pause();
                    } else {
                        $(this).attr('data-value', 'pause')
                        $('#play-btn').html('<i class="fa fa-pause"></i>')
                        audio.play();
                    }
                }
            })
            $('#stop-btn').click(function() {
                if (audio.src != '') {
                    $('#play-btn').html('<i class="fa fa-play"></i>')
                    $('#play-btn').attr('data-value', 'play')
                    audio.pause();
                    audio.currentTime = 0;
                }
            })

            // Playback Slider Moved Function 
            $('#playBackSlider').on('mousedown', function() {
                if (audio.src != '') {
                    clearInterval(slider)
                    $(this).on('mousemove', function() {
                        audio.pause()
                        audio.currentTime = (audio.duration * ($(this).val() / 100));
                        $('#currentTime').text(String(Math.floor(audio.currentTime / 60)).padStart(2, '0') + ":" + String(Math.floor(60 * Math.abs((audio.currentTime / 60) - Math.floor(audio.currentTime / 60)))).padStart(2, '0'))
                    })
                }
            })
            $('#playBackSlider').on('mouseup', function() {
                if (audio.src != '') {
                    $(this).off('mousemove')
                    audio.currentTime = (audio.duration * ($(this).val() / 100));
                    $('#currentTime').text(String(Math.floor(audio.currentTime / 60)).padStart(2, '0') + ":" + String(Math.floor(60 * Math.abs((audio.currentTime / 60) - Math.floor(audio.currentTime / 60)))).padStart(2, '0'))
                    if ($('#play-btn').attr('data-value') == 'pause') {
                        audio.play()
                        slider = setInterval(() => {
                            var current_slide = (audio.currentTime / audio.duration) * 100;
                            $('#playBackSlider').val(current_slide)
                            $('#currentTime').text(String(Math.floor(audio.currentTime / 60)).padStart(2, '0') + ":" + String(Math.floor(60 * Math.abs((audio.currentTime / 60) - Math.floor(audio.currentTime / 60)))).padStart(2, '0'))
                        }, 500)
                    }
                }
            })

            // volume slider
            $('#volume').on('mousedown', function(e) {
                $(this).on('mousemove', function() {
                    var vol = $(this).val() / 100
                    audio.volume = vol
                    if (vol == 0) {
                        $('#vol-icon').html('<i class="fa fa-volume-off"></i>')
                    } else if (vol < .5) {
                        $('#vol-icon').html('<i class="fa fa-volume-down"></i>')
                    } else {
                        $('#vol-icon').html('<i class="fa fa-volume-up"></i>')
                    }
                })
            })
            $('#volume').on('mouseup', function(e) {
                $(this).off('mousemove')
            })

            // Next Btn
            $('#next-btn').click(function() {
                    if (currentPlayID > 0) {
                        var currentIndex = $('#music-list .item[data-id="' + currentPlayID + '"]').index();
                        if (!!$('#music-list .item').eq(currentIndex + 1)) {
                            $('#music-list .item').eq(currentIndex + 1).find('.play').trigger('click')
                        }
                    }
                })
                // Previous Btn
            $('#prev-btn').click(function() {
                if (currentPlayID > 0) {
                    var currentIndex = $('#music-list .item[data-id="' + currentPlayID + '"]').index();
                    if ((currentIndex - 1) == -1) {
                        audio.currentTime = 0
                    } else {
                        if (!!$('#music-list .item').eq(currentIndex - 1)) {
                            $('#music-list .item').eq(currentIndex - 1).find('.play').trigger('click')
                        }
                    }
                }
            })
            audio.onended = function() {
                console.log('Next Track')
                $('#next-btn').trigger('click')
                if (audio.src != '') {
                    $('#play-btn').html('<i class="fa fa-play"></i>')
                    $('#play-btn').attr('data-value', 'play')
                    audio.pause();
                    audio.currentTime = 0;
                }
            }
        })
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

    <script>
        $(document).ready(function(){
            $(".box-video").click(function () {
                $('#iframesrcURL').attr('src', '');
                $('#iframesrcURL').removeAttr('class');
                $('.modal_dialog').removeClass('modal-xl');
                $('.modal-content').removeClass('content_design3');
                $('.modal-content').removeClass('content_design2');
                $('.modal-content').removeClass('content_design1');
                $('#exampleModalLabel_podcast').text('');
                


                var podcast_div_Id = $(this).data('vid');
                var split_podcast_div_id = podcast_div_Id.split('_')[1];
                var podcastUrl = $('#podcast_id_'+split_podcast_div_id).data('src');
                if(podcastUrl.indexOf('redcircle') > -1) {
                    $('#iframesrcURL').addClass('min_h_800');
                    $('.modal_dialog').addClass('modal-xl');
                    $('.modal-content').addClass('content_design3');
                    $('#exampleModalLabel_podcast').text('RedCircle Podcast');


                }
                if(podcastUrl.indexOf('stitcher') > -1) {
                    $('#iframesrcURL').addClass('min_h_300');
                    $('.modal_dialog').addClass('modal-xl');
                    $('.modal-content').addClass('content_design3');
                    $('#exampleModalLabel_podcast').text('Stitcher Podcast');


                }
                if(podcastUrl.indexOf('apple') > -1) {
                    $('.modal-content').addClass('content_design2');
                    $('#exampleModalLabel_podcast').text('Apple Podcast');

                }

                if(podcastUrl.indexOf('spotify') > -1) {
                    $('.modal-content').addClass('content_design1');
                    $('#exampleModalLabel_podcast').text('Spotify Podcast');

                }
                $('#iframesrcURL').attr('src', podcastUrl);
                // $(this).addClass('open');
            });
        })
    
    </script>

@endsection
