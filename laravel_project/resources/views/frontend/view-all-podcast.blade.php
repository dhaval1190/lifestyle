@extends('backend.user.layouts.app')
@section('content')
    <!-- Page Heading -->
    <div class="podcast_section">
        <div class="row">
            <div class="col-lg-12 order-lg-0">
                @if (isset($PodcastImage) && !empty($PodcastImage))
                    <section class="col-lg-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="below_info padding-tb-30-lr-45">
                                    <h3>{{ __('backend.homepage.podcast-details') }}</h3>
                                </div>
                            </div>
                            <div class="col-md-12 plr-45">
                                <div class="">
                                    <div class="row audio-players">
                                        {{-- @foreach ($PodcastImage as $podcast_key => $image)
                                            <div class="col-md-4 col-lg-3 col-xl-2 col-sm-6 col-12 mb-5">
                                                <div class="audio-player js-audio-player">
                                                    <button class="audio-player__control js-control">
                                                        <div class="audio-player__control-icon"></div>
                                                    </button>
                                                    <h4 class="audio-player__title">{{ $image['monthly']['media_name'] }}
                                                    </h4>
                                                    <audio preload="auto">
                                                        <source
                                                            src="{{ Storage::disk('public')->url('media_files/' . $image['monthly']['media_image']) }}" />
                                                    </audio>
                                                    <!-- <img class="audio-player__cover" src="https://unsplash.it/g/300?image=29"/> -->
                                                    <img class="audio-player__cover"
                                                        src="{{ Storage::disk('public')->url('media_files/' . $image['monthly']['media_cover']) }}">
                                                    <video preload="auto" loop="loop">
                                                        <source src="" type="video/mp4" />
                                                    </video>
                                                </div>
                                                <div class="post-information">
                                                    <h4 class="content">{{ $image['monthly']['media_name'] }}</h4>
                                                </div>
                                                <div class="view_eye_post">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                    <p class="views">All</p>
                                                    <span class="podcast_section_number">:
                                                        {{ $image['monthly']['totalcount'] }} </span>
                                                </div>
                                                <div class="view_calander_post">
                                                    <i class="fa fa-calendar"></i>
                                                    <p class="calander">Today</p>
                                                    <span class="podcast_section_number">:
                                                        {{ $image['daily']['totalcount'] }} </span>
                                                </div>
                                            </div>
                                        @endforeach --}}
                                        @foreach ($PodcastImage as $podcast_key => $image)
                                            <div class="col-md-3">
                                                <div class="">
                                                    <h4 class="audio-player__title">{{ $image['monthly']['media_name'] }}
                                                    </h4>
                                                    <div class="post-information">
                                                        <h4 class="content">{{ $image['monthly']['media_name'] }}</h4>
                                                    </div>

                                                    <div class="box-video vid-fit-reveal"
                                                        data-vid="box-video_{{ $image['monthly']['id'] }}">
                                                        {{-- <a href="" target="_blank" class="bg-video"
                                                            id="podcast_id_{{ $image['monthly']['id'] }}"
                                                            data-toggle="modal" data-target="#podcastModal"
                                                            style="background-image: @if (str_contains($image['monthly']['media_image'], 'spotify')) url('{{ asset('frontend/images/spotify_logo.png') }}') @elseif(str_contains($image['monthly']['media_image'], 'apple')) url('{{ asset('frontend/images/apple_logo.png') }}') @elseif(str_contains($image['monthly']['media_image'], 'stitcher')) url('{{ asset('frontend/images/stitcher_logo.png') }}') @elseif(str_contains($image['monthly']['media_image'], 'redcircle')) url('{{ asset('frontend/images/redcircle_logo.png') }}') @endif; opacity: 1;"
                                                            data-src="{{ $image['monthly']['media_image'] }}">
                                                            <div class="bt-play"
                                                                id="bt-play_{{ $image['monthly']['id'] }}"></div>
                                                        </a> --}}
                                                        <a href="" target="_blank" class="bg-video"
                                                            id="podcast_id_{{ $image['monthly']['id'] }}"
                                                            data-toggle="modal" data-target="#podcastModal"
                                                            style="background-image: url('{{ $image['monthly']['media_cover'] }}'); opacity: 1;"
                                                            data-src="{{ $image['monthly']['media_image'] }}">
                                                            <div class="bt-play"
                                                                id="bt-play_{{ $image['monthly']['id'] }}"></div>
                                                        </a>
                                                        <div class="video-container">
                                                            <iframe width="590" height="100%"
                                                                src="{{ $image['monthly']['media_image'] }}"
                                                                frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                                                        </div>
                                                    </div>

                                                    <div class="view_eye_post">
                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                        <p class="views">All</p>
                                                        <span class="podcast_section_number">:
                                                            {{ $image['monthly']['totalcount'] }} </span>
                                                    </div>
                                                    <div class="view_calander_post">
                                                        <i class="fa fa-calendar"></i>
                                                        <p class="calander">Today</p>
                                                        <span class="podcast_section_number">:
                                                            {{ $image['daily']['totalcount'] }} </span>
                                                    </div>
                                                </div>
                                            </div>                                            
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif

            </div>
        </div>
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
    <script>
        $(document).ready(function() {
            var $player = $('.js-audio-player'),
                $playbackClass = 'is-playing',
                $fadeDuration = 500

            $player.each(function(index) {
                var $this = $(this),
                    id = 'audio-player-' + index

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
                    $audio.animate({
                        volume: 1
                    }, $fadeDuration)
                    $player.addClass($playbackClass)
                } else {
                    $audio.animate({
                        volume: 0
                    }, $fadeDuration, function() {
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
                        $this.find('audio').animate({
                            volume: 0
                        }, $fadeDuration, function() {
                            $(this)[0].pause()
                            $this.find('video')[0].pause()
                        })
                        $this.removeClass($playbackClass)
                    }
                })
            }
        });
    </script>

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
            console.log(podcast_div_Id);
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
@endsection('scripts')
