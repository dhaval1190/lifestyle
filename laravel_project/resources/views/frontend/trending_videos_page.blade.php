@extends('frontend.layouts.app')

@section('styles')
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" /> -->
    <link rel="stylesheet" href="{{ asset('frontend/css/custom_media.css') }}">
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>

    @if ($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <link href="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection
<style>
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
</style>

@section('content')
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
                        <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('Trending Videos') }}</h1>
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
                
            <div class="row mt-5 mb-3 align-items-center">
                <div class="col-md-7 text-left border-primary">
                    <h2 class="font-weight-light text-primary mt-5">All Trending Videos</h2>
                </div>
            </div>
            
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="below_info">
                                <h3></h3>
                            </div>
                        </div> 
                    </div>


                    <div class="row">
                        @if(isset($show_all_video) && !empty($show_all_video))
                        @foreach($show_all_video as $video_key => $video)

                        @if( $video['total_view']>0 )
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="post-slide">

                                <div class="post-img">
                                    <div class='c-video__image-container vid-fit-reveal box-video-youtube'
                                        data-id="{{ $video['id'] }}" id="#js-fitvideo_{{ $video['id'] }}" data-vid="box-video-youtube_{{ $video['id'] }}">
                                        <a href="" target="_blank" class="bg-video" id="youtube_id_{{ $video['id'] }}" data-toggle="modal" data-target="#youtubeModal"
                                            data-src="{{ $video['media_url'] }}">
                                            <div class="bt-play" id="bt-play_{{ $video['id'] }}"></div>
                                        </a>
                                        <iframe width="560" height="215" src="{{ $video['media_url']}}" id="vid-reveal_{{ $video['id'] }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                        encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    </div>
                                </div>
                                
                                <div class="view_eye_post">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    <p class="views">View</p>
                                    <span class="youtube_section_number">: {{$video['total_view'] }}
                                    </span>
                                </div>
                                <br><br>
                            </div>
                        </div>
                        @endif

                        @endforeach 
                        @endif
                    </div>
                
            </div>
        </section>
    </div>


    <!-- For youtube modal-->
    <div class="modal-4">
        <div class="modal fade" id="youtubeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal_dialog" role="document">
              <div class="modal-content content_design3">
                <div class="modal-header border-bottom-0">
                  <h5 class="modal-title" id="exampleModalLabel_youtube">Youtube</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                 <iframe src="" id="youtubeiframesrcURL" frameborder="0"  class="min_h_300" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                 allowfullscreen></iframe>
              </div>
            </div>
          </div>
    </div>
@endsection

@section('scripts')
<script>
    $("#youtubeModal").on("hidden.bs.modal", function() {
        $('#youtubeiframesrcURL').attr('src', '');            
    });
</script>
    @if ($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="{{ asset('frontend/vendor/leaflet/leaflet.js') }}"></script>
    @endif

    @if ($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <!-- Youtube Background for Header -->
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif

    <script src="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
    @include('frontend.partials.bootstrap-select-locale')
    
    
    <!-- <script>
        $(document).ready(function() {
            $("#news-slider").owlCarousel({
                items: 4,
                itemsDesktop: [1199, 4],
                itemsDesktopSmall: [980, 2],
                itemsMobile: [600, 1],
                navigation: true,
                navigationText: ["", ""],
                pagination: true,
                autoPlay: true
            });

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

        });
    </script> -->




    <script>

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

        //for youtube video
        
        $(document).ready(function(){  
            
            $(".box-video-youtube").click(function () {

                $('#youtubeiframesrcURL').attr('src', '');
                // $('#youtubeiframesrcURL').removeAttr('class');
                // $('#exampleModalLabel_podcast').text('');                


                var youtube_div_Id = $(this).data('vid');
                var split_youtube_div_id = youtube_div_Id.split('_')[1];                
                var youtubeUrl = $('#youtube_id_'+split_youtube_div_id).data('src');
                
                $('#youtubeiframesrcURL').attr('src', youtubeUrl);
            });
        });
    </script>
@endsection

