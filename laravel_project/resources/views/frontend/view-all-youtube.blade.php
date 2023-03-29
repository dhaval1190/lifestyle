@extends('backend.user.layouts.app')
@section('content')
<!-- Page Heading -->
<div class="youtube_section">
    <div class="row">
        <div class="col-lg-12 order-lg-0">
            @if(isset($media) && !empty($media) || isset($Youtube) && !empty($Youtube))
            <section class="col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="below_info padding-tb-30-lr-45">
                            <h3>{{ __('backend.homepage.youtube-details') }}</h3>
                        </div>
                    </div>
                    <div class="col-md-12 plr-45">
                        <div class="row audio-playres">
                            @foreach($media as $video_key => $video)
                            <div class="col-md-4 col-lg-3 col-xl-2 col-sm-6 col-12 mb-5">
                                <div class="">
                                    <div class="post-img">
                                        <iframe width="100%" height="215" src="{{ $video['monthly']['media_url']}}"
                                            title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                    </div>
                                    <div class="post-information">
                                        <!-- <a href="{{ $video['monthly']['media_url']}}" class="content">{{ $video['monthly']['media_url']}}</a> -->
                                    </div>
                                    <div class="view_eye_post">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <p class="views">All</p>
                                        <span class="youtube_section_number">: {{$video['monthly']['totalcount'] }}
                                        </span>
                                    </div>
                                    <div class="view_calander_post">
                                        <i class="fa fa-calendar"></i>
                                        <p class="calander">Today</p>
                                        <span class="youtube_section_number">: {{$video['daily']['totalcount'] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            @if(isset($Youtube) && !empty($Youtube))
                            @foreach($Youtube as $youtube_key => $youtubevideo)
                            <div class="col-md-4 col-lg-3 col-xl-2 col-sm-6 col-12 mb-5">
                                <div class="">
                                    <div class="post-img">
                                        <a href="{{ $youtubevideo['monthly']['media_url'] }}">
                                            <iframe width="100%" height="215"
                                                src="{{ $youtubevideo['monthly']['media_url']}}"
                                                title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                            encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe></a>
                                    </div>
                                    <div class="post-information">
                                        <!-- <a href="{{ $youtubevideo['monthly']['media_url']}}" class="content">{{ $youtubevideo['monthly']['media_url']}}</a> -->
                                    </div>
                                    <div class="view_eye_post">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <p class="views">All</p>
                                        <span class="youtube_section_number">:
                                            {{$youtubevideo['monthly']['totalcount'] }} </span>
                                    </div>
                                    <div class="view_calander_post">
                                        <i class="fa fa-calendar"></i>
                                        <p class="calander">Today</p>
                                        <span class="youtube_section_number">:
                                            {{$youtubevideo['daily']['totalcount'] }} </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </section>
            @endif
        </div>
    </div>
</div>
@endsection