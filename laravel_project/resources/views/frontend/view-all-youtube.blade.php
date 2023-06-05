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
                                            <div class='c-video__image-container vid-fit-reveal box-video-youtube'
                                                data-id="{{ $video['monthly']['id'] }}" id="#js-fitvideo_{{ $video['monthly']['id'] }}" data-vid="box-video-youtube_{{ $video['monthly']['id'] }}">
                                                <a href="" target="_blank" class="bg-video" id="youtube_id_{{ $video['monthly']['id'] }}" data-toggle="modal" data-target="#youtubeModal"
                                                    data-src="{{ $video['monthly']['media_url'] }}">
                                                    <div class="bt-play" id="bt-play_{{ $video['monthly']['id'] }}"></div>
                                                </a>
                                                <iframe width="100%" height="215" src="{{ $video['monthly']['media_url']}}"
                                                    title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                        encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            </div>
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
                                        <div class='c-video__image-container vid-fit-reveal box-video-youtube'
                                                    data-id="" id="#js-fitvideo_" data-vid="box-video-youtube_">
                                                    <a href="" target="_blank" class="bg-video" id="youtube_id_" data-toggle="modal" data-target="#youtubeModal"
                                                        data-src="{{ $youtubevideo['monthly']['media_url'] }}">
                                                        <div class="bt-play" id="bt-play_"></div>
                                                    </a>
                                                    <iframe width="100%" height="215" src="{{ $youtubevideo['monthly']['media_url']}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                    encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
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
    $(document).ready(function(){
    $(".box-video-youtube").click(function () {
        $('#youtubeiframesrcURL').attr('src', '');
        // $('#youtubeiframesrcURL').removeAttr('class');
        $('#exampleModalLabel_podcast').text('');                


        var youtube_div_Id = $(this).data('vid');
        var split_youtube_div_id = youtube_div_Id.split('_')[1]; 
        // console.log(split_youtube_div_id)               
        var youtubeUrl = $('#youtube_id_'+split_youtube_div_id).data('src');
        console.log(youtubeUrl)
        
        $('#youtubeiframesrcURL').attr('src', youtubeUrl);
    });

    $("#youtubeModal").on("hidden.bs.modal", function() {
        $('#youtubeiframesrcURL').attr('src', '');            
    });
});
</script>
@endsection