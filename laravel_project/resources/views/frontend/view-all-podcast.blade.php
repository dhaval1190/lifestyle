@extends('backend.user.layouts.app')

@section('styles')
@endsection
@section('content')
    <!-- Page Heading -->
 <div class="podcast_section">
    <div class="row">
        <div class="col-lg-12 order-lg-0">
        @if(isset($PodcastImage) && !empty($PodcastImage))
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
                                    @foreach($PodcastImage as $podcast_key => $image)
                                <div class="col-md-4 col-lg-3 col-xl-2 col-sm-6 col-12 mb-5">
                                    <div class="audio-player js-audio-player">
                                        <button class="audio-player__control js-control">
                                            <div class="audio-player__control-icon"></div>
                                        </button>
                                        <h4 class="audio-player__title">{{ $image['monthly']['media_name'] }}</h4>
                                        <audio preload="auto">
                                            <source src="{{ Storage::disk('public')->url('media_files/'. $image['monthly']['media_image']) }}"/>
                                        </audio>
                                        <!-- <img class="audio-player__cover" src="https://unsplash.it/g/300?image=29"/> -->
                                        <img class="audio-player__cover" src="{{ Storage::disk('public')->url('media_files/'. $image['monthly']['media_cover']) }}">
                                        <video preload="auto" loop="loop">
                                            <source src="" type="video/mp4"/>
                                        </video>
                                    </div>
                                    <div class="post-information">
                                    <h4 class="content">{{$image['monthly']['media_name']}}</h4>
                                    </div>
                                    <div class="view_eye_post">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    <p class="views">All</p>
                                    <span class="podcast_section_number">:
                                        {{$image['monthly']['totalcount'] }} </span>
                                    </div>
                                    <div class="view_calander_post">
                                        <i class="fa fa-calendar"></i>
                                        <p class="calander">Today</p>
                                        <span class="podcast_section_number">:
                                            {{$image['daily']['totalcount'] }} </span>
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
    @endsection
    @section('scripts')
    <script>
        $(document).ready(function(){
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
    @endsection('scripts')