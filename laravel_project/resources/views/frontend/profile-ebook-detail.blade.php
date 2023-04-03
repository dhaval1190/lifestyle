@extends('frontend.layouts.app')

@section('styles')
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('frontend/css/custom_style.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/custom_media.css') }}">
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
                            <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('Ebook') }}</h1>
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
                                <h3>Our E-books</h3>
                            </div>
                        </div>
                        <div class="col-md-12 plr-45">
                            <div class="post-slide">
                                <div class="row">
                                    @foreach($ebook_media_array as $ebook_key => $ebook)
                                        <div class="col-md-3 col-6">
                                            <div class="post-img">
                                                <a href="{{ Storage::disk('public')->url('media_files/'. $ebook->media_image) }}" target="_blank"><img src="{{ Storage::disk('public')->url('media_files/'. $ebook->media_cover) }}" alt="" class="ebook_btn" data-id="{{$ebook->id}}" id="#ebook_{{$ebook->id}}"></a>
                                            </div>
                                            
                                            <div class="post-information">
                                                <h4 class="content">{{ $ebook->media_name }}</h4>
                                            </div>
                                        </div>
                                        @if($ebook_key==3)
                                            </div></div><div class="post-slide"><div class="row">
                                        @endif
                                    @endforeach
                                </div>
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
            $(".ebook_btn").on('click', function() {
                    var id = $(this).attr('data-id');                                      
                    $.ajax({
                    type:'POST',
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                    url:'<?php echo url("/media-visitor"); ?>',
                    data: {'id':id},
                    success:function(data){
                        console.log(data)
                    },
                });
                });
            
         });
        </script>
@endsection
