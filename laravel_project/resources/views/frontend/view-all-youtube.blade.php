@extends('backend.user.layouts.app')

@section('styles')
@endsection

@section('content')
    <!-- Page Heading -->
  <div class="container">
    <div class="row">
        <div class="col-lg-12 order-lg-0">          
        @if(isset($media) && !empty($media) || isset($Youtube) && !empty($Youtube))
                <section class="youtube_section col-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info padding-tb-30-lr-45">
                                <h3>Our Youtube</h3>
                            </div>
                        </div>
                        <div class="col-md-12 plr-45">
                            <div class="row">
                                @foreach($media as $video_key => $video)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="post-slide">
                                        <div class="post-img">
                                            <iframe width="560" height="215" src="{{ $video['monthly']['media_url']}}"
                                                title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>
                                        </div>
                                        <div class="post-information">
                                            <h4 class="content">Lorem Ipsum doller</h4>
                                        </div>
                                        <div class="view_eye_post">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <p class="views">All</p>
                                            <span class="number">: {{$video['monthly']['totalcount'] }} </span>
                                        </div>
                                        <div class="view_calander_post">
                                            <i class="fa fa-calendar"></i>
                                            <p class="calander">Today</p>
                                            <span class="number">: {{$video['daily']['totalcount'] }} </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @if(isset($Youtube) && !empty($Youtube))
                                @foreach($Youtube as $youtube_key => $youtubevideo)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="post-slide">
                                        <div class="post-img">
                                            <iframe width="560" height="215"
                                                src="{{ $youtubevideo['monthly']['media_url']}}"
                                                title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>
                                        </div>
                                        <div class="post-information">
                                            <h4 class="content">Lorem Ipsum doller</h4>
                                        </div>
                                        <div class="view_eye_post">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <p class="views">All</p>
                                            <span class="number">: {{$youtubevideo['monthly']['totalcount'] }} </span>
                                        </div>
                                        <div class="view_calander_post">
                                            <i class="fa fa-calendar"></i>
                                            <p class="calander">Today</p>
                                            <span class="number">: {{$youtubevideo['daily']['totalcount'] }} </span>
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