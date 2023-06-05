@extends('backend.user.layouts.app')

@section('styles')
<style>
    .invisible {
        display: none;
    }

    .canvasjs-chart-credit {
        display: none;
    }

    @import url(https://fonts.googleapis.com/css?family=Muli);

    * {
        transition: all 0.5s;
        -webkit-transition: all 0.5s;
    }



    .toggle {
        cursor: pointer;
    }

    .toggle_two {
        cursor: pointer;
        float: right;
        margin-top: 81px;
        margin-left: 10px;
    }

    .sidebar_two {
        position: fixed;
        width: 500px;
        float: right;
        box-shadow: 10px 10px 0px 3px #fff;
        border-left: 0px solid black;
        height: 100vh;
        top: 180px;
        right: -500px;
        z-index: 9999;
        background: #fff;
        opacity: 1.2;
        background-size: cover;
        border-radius: 30px;
    }

    .sidebar_two h2 {
        color: white;
        text-align: left;
        margin-top: 5rem;
        padding-left: 15px;
        font-family: 'Muli', sans-serif;
    }

    .sidebar_two.active {
        right: 0px;
    }

    .notibox {
        color: white;
        font-family: 'Muli', sans-serif;
        background-color: #5a5c69;
        width: calc(100% - 60px);
        padding: 15px;
        margin: 15px;
        border-radius: 4px;
        position: relative;
    }

    .cancel {
        position: absolute;
        right: 0px;
        top: 10px;
        cursor: pointer;
        padding: 3px;
        border-radius: 20px;
    }

    .cancel:hover {
        color: black;
        background-color: white;
    }

    .gone {
        display: none;
    }

    .none {
        opacity: 0;
    }

    .cancel_two {
        color: #fff;
    }

    .listing .lh-content {
        text-align: left !important;

    }

    .close {
        float: right;
        font-size: 30px;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: 0.8;
    }
</style>
@endsection

@section('content')

@if($paid_subscription_days_left == 1)
<div class="alert alert-warning" role="alert">
    {{ __('backend.subscription.subscription-end-soon-day') }}
</div>
@elseif($paid_subscription_days_left > 1 && $paid_subscription_days_left <= \App\Subscription::PAID_SUBSCRIPTION_LEFT_DAYS) <div class="alert alert-warning" role="alert">
    {{ __('backend.subscription.subscription-end-soon-days', ['days_left' => $paid_subscription_days_left]) }}
    </div>
    @endif

    <!-- Page Heading -->
    <div class="d-flex align-items-center justify-content-between mb-4 firstBlur">
        <h1 class="h3 mb-0 text-gray-800">{{ __('backend.homepage.dashboard') }}</h1>
        <!-- <a href="#"><img src="{{ asset('frontend/images/Svg/notification_zero.svg') }}" alt=""
                                class="notification_icon" /></a> -->
        <!-- <a href="#" id="action" class="toggle"><img src="{{ asset('frontend/images/Svg/notification_alert.svg') }}"
                alt="" class="notification_alert" /></a> -->
        @if(Auth::user()->isCoach())
            <div class="toggle">
                <div class="ico"><img src="{{ asset('frontend/images/Svg/notification_alert.svg') }}" alt="" class="notification_alert" data-toggle="modal" data-target="#exampleModalLong" /></div>
            </div>
        @endif
        {{-- <a href="{{ route('user.items.create') }}" class="btn btn-info btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-plus"></i>
        </span>
        <span class="text">{{ __('backend.homepage.post-a-listing') }}</span>
        </a> --}}
    </div>
    <div class="sidebar_two">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="notification_set_height" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="true" data-keyboard="true">
                        <div class="modal-header">
                            <h5 class="modal-title notification_msg" id="exampleModalLongTitle">Notification
                            </h5>
                            <button type="button" class="close toggle_two" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body notification_body ">
                            <div class="notification_messages">
                                @if($notifications->count() > 0)
                                @foreach($notifications as $key_notification=>$notification)
                                <div class="notification_message_details profile_img_{{$notification->id}}" data-id="{{$notification->id}}">
                                    @if(empty($notification->user_image))
                                    <img class="profile_img_{{$notification->id}}" data-id="{{$notification->id}}" src="{{ asset('backend/images/placeholder/profile-' . intval($login_user['id'] % 10) . '.webp') }}" style="border-radius:50%; width:50px; height:50px;">
                                    @else
                                    <img class="profile_img_{{$notification->id}}"data-id="{{$notification->id}}" src="{{ Storage::disk('public')->url('user/'. $notification->user_image) }}" style="border-radius:50%; width:50px; height:50px;">
                                    @endif
                                    <!-- <img src="{{ Storage::disk('public')->url('user/'. $notification->user_image) }}" alt="" /> -->
                                    <div class="message profile_img_{{$notification->id}}"data-id="{{$notification->id}}">
                                        <h4 class="m-one">{{$notification->name}}</h4>
                                        <p class="m-details">
                                            {{$notification->notification}}
                                        </p>
                                          <p class="m-date">{{$notification->created_at->diffForHumans()}}</p>  
                                        <div class="cancel notificationReadBtn" id="{{ $notification->id }}">✕
                                        </div>
                                        
                                    </div>
                                </div>
                                @if($key_notification==4)
                                    @php
                                    break; @endphp
                                    @endif
                                @endforeach
                                @else
                                <div class="notification_message_details">
                                    <p style="text-align: center">No records found</p>
                                </div>
                                @endif
                            </div>
                            
                        </div>
                        @if(isset($notifications) && !empty($notifications) && $notifications->count() >= 5)
                            <div class="notification_footer">
                                <div class="notification_message_info">
                                    <a href="{{ route('page.user.notification', encrypt($login_user['id'])) }}">View
                                        all</a>
                                </div>
                            </div>
                            @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="btn btn-primary rounded text-white" onclick="startFCM()" style="display:none" id="click">click</div>
    <!-- <section class="bg_effect">
                <div class="container"> -->
    <!-- <div class="row">
                <div class="col-md-12">
                    <div class="dashboard">
                        <h1>{{ __('backend.homepage.dashboard') }}</h1>
                        <a href="#"><img src="./Images/Svg/notification_zero.svg" alt=""
                                class="notification_icon" /></a>
                        <a href="#"><img src="./Images/Svg/notification_alert.svg" alt=""
                                class="notification_alert" /></a>
                    </div>
                </div>
            </div> -->
    <div class="row firstBlur">
        @if($login_user->isCoach())
        @php $main_div_class = 'col-lg-12 col-xl-9 order-xl-0 order-1'; @endphp
        @else
        @php $main_div_class = 'col-lg-12 col-xl-12 order-xl-0 order-1'; @endphp
        @endif
        <div class="{{ $main_div_class }}">
            <div class="row">
                @if($login_user->isCoach())
                    <div class="col-md-6">
                        <div class="first_coach coach">
                            <div class="coaches">
                                <img src="{{ asset('frontend/images/Svg/client.svg') }}" alt="" />
                                <div class="coaches_detail">
                                    <h3>{{ __('backend.homepage.subscription') }}</h3>
                                    <p class="c-one">{{ $plan_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-6">
                    <a href="{{ route('user.messages.index') }}" class="decoration-none">
                        <div class="second_coach coach">
                            <div class="coaches">
                                <img src="{{ asset('frontend/images/Svg/msg.svg') }}" alt="" />
                                <div class="coaches_detail">
                                    <h3>{{ __('backend.homepage.all-messages') }}</h3>
                                    <p class="c-two">{{ number_format($message_count) }}</p>
                                </div>
                            </div>
                        </div>  
                    </a>
                </div>
                @if($login_user->isCoach())
                <div class="col-md-6">
                    <a href="{{ route('user.comments.index') }}" class="decoration-none">
                        <div class="third_coach coach">
                            <div class="coaches">
                                <img src="{{ asset('frontend/images/Svg/meeting.svg') }}" alt="" />
                                <div class="coaches_detail">
                                    <h3>{{ __('backend.homepage.all-comments') }}</h3>
                                    <p class="c-three">{{ number_format($comment_count) }}</p>
                                </div>
                            </div>
                        </div>
                    </a>                    
                </div>

                <div class="col-md-6">
                    <a class="decoration-none" href="{{ route('user.articles.index') }}">
                        <div class="fourth_coach coach">
                            <div class="coaches">
                                <img src="{{ asset('frontend/images/Svg/group.svg') }}" alt="" />
                                <div class="coaches_detail">
                                    <h3>{{ __('backend.homepage.all-articles') }}</h3>
                                    <p class="c-four">{{ number_format($item_count) }}</p>
                                </div>
                            </div>
                        </div>
                    </a>                    
                </div>

                <div class="col-md-6">
                    <div class="fourth_coach coach">
                        <div class="coaches">
                            <img src="{{ asset('frontend/images/Svg/group.svg') }}" alt="" />
                            <div class="coaches_detail">
                                <h3>{{ __('Total Refferal') }}</h3>
                                <p class="c-four">{{ count(Auth::user()->referrals)  ?? '0' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if(isset($All_visit_count) && !empty($All_visit_count))
                <div class="col-md-6">
                    <a class="decoration-none" href="{{route('page.profile', encrypt($login_user['id'])) }}">
                        <div class="first_coach coach">
                            <div class="coaches">
                                <img src="{{ asset('frontend/images/Svg/client.svg') }}" alt="" />
                                <div class="coaches_detail">
                                    <h3>{{ __('Profile Visitors') }}</h3>    
                                    <div class="view_eye_post">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <p class="views">All</p>
                                        <span class="article_section_number">:
                                            {{ $All_visit_count }} </span>
                                    </div>
                                    <div class="view_calander_post">
                                        <i class="fa fa-calendar"></i>
                                        <p class="calander">Today</p>
                                        <span class="article_section_number">: {{ $Today_Visits_count }}
                                        </span>
                                    </div>
                                    <!-- <a href="{{ route('user.comments.index') }}">{{ __('backend.homepage.view-all-comment') }}</a> -->
                                </div>
                            </div>
                        </div>
                    </a>                    
                </div>
                @endif
                @endif
                <div class="col-lg-12">
                    <div class="coach_messages">
                        <div class="coach_message_info">
                            <h2>{{ __('backend.homepage.all-messages') }}</h2>
                            <a href="{{ route('user.messages.index') }}">{{ __('backend.homepage.view-all-message') }}</a>
                        </div>

                        @if($recent_threads->count() > 0)
                        @foreach($recent_threads as $recent_threads_key => $thread)
                        <div class="coach_message_details">
                            @if(empty($thread->creator()->user_image))
                            <img src="{{ asset('backend/images/placeholder/profile-' . intval($login_user['id'] % 10) . '.webp') }}" style="border-radius:50%; width:100px; height:100px;">
                            @else
                            <img src="{{ Storage::disk('public')->url('user/'. $thread->creator()->user_image) }}" style="border-radius:50%; width:100px; height:100px;">
                            @endif
                            <div class="message">
                                <h4 class="m-one">{{ $thread->subject }}</h4>
                                <p class="m-details">
                                    {{ str_limit(preg_replace("/&#?[a-z0-9]{2,8};/i"," ", strip_tags($thread->latestMessage->body)), 200) }}
                                </p>
                                <p class="m-created">
                                    @php $info_class = ($recent_threads_key%2 == 0) ? 'm-info-one' : 'm-info-two';
                                    @endphp
                                    Created by <span class="{{$info_class}}">{{ $thread->creator()->name }}</span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                        @else

                            <p style="text-align: center">No messages</p>
                        @endif
                    </div>
                </div>
                @if(isset($Articledetail) && !empty($Articledetail) || isset($Ebooks) && !empty($Ebooks) ||
                isset($PodcastImage) && !empty($PodcastImage)|| isset($media) && !empty($media) || isset($Youtube) &&
                !empty($Youtube))
                <div class="col-lg-12 mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{{ __('backend.homepage.traffic') }}</h1>
                </div>
                @endif
            </div>
            <div>
                @if(isset($Articledetail) && !empty($Articledetail))
                <section class="article_section col-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info padding-tb-30-lr-45">
                                <h3>{{ __('backend.homepage.article-details') }}</h3>
                                @if(isset($Articledetail) && !empty($Articledetail) && count($Articledetail) >= 5)
                                <a class="decoration-none" href="{{ route('page.profile.allarticle',encrypt($login_user['id'])) }}">View
                                    all</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 plr-45">
                            <div class="post-slide">
                                <div class="row">
                                    @php $i=1; @endphp
                                    @foreach($Articledetail as $article_key => $Article)
                                    <div class="col-md-3 col-12">
                                        <div class="post-img">
                                            <a href="{{ route('page.item', $Article['monthly']['item_slug']) }}"> <img src="{{ !empty($Article['monthly']['item_image']) ? Storage::disk('public')->url('item/' . $Article['monthly']['item_image']): asset('frontend/images/placeholder/full_item_feature_image_medium.webp')}}" alt="" class="w-100" /></a>
                                        </div>
                                        <div class="post-information">
                                            <h4 class="content"><a class="decoration-none" href="{{ route('page.item', $Article['monthly']['item_slug']) }}">{{$Article['monthly']['item_title']}}</a>
                                            </h4>
                                        </div>
                                        <div class="view_eye_post">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <p class="views">All</p>
                                            <span class="article_section_number">:
                                                {{$Article['monthly']['totalcount'] }} </span>
                                        </div>
                                        <div class="view_calander_post">
                                            <i class="fa fa-calendar"></i>
                                            <p class="calander">Today</p>
                                            <span class="article_section_number">: {{$Article['daily']['totalcount'] }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($i==4)
                                    @php
                                    break; @endphp
                                    @endif
                                    @php $i++; @endphp
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @endif
            </div>
            <div>
                @if(isset($AllPodcast) && !empty($AllPodcast))
                <section class="podcast_section col-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info padding-tb-30-lr-45">
                                <h3>{{ __('backend.homepage.podcast-details') }}</h3>
                                @if(isset($AllPodcast) && !empty($AllPodcast) && count($AllPodcast) >= 5)
                                <a href="{{ route('page.profile.allpodcast',encrypt($login_user['id'])) }}">View all</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 plr-45">
                            <div class="post-slide">
                                <div class="row audio-players">
                                    {{-- @php $i=1; @endphp
                                    @foreach($AllPodcast as $podcast_key => $image)
                                    <div class="col-md-3 col-12">
                                        <div class="audio-player js-audio-player">
                                            <button class="audio-player__control js-control">
                                                <div class="audio-player__control-icon"></div>
                                            </button>
                                            <h4 class="audio-player__title">{{ $image['monthly']['media_name'] }}</h4>
                                            <audio preload="auto">
                                                <source src="{{ Storage::disk('public')->url('media_files/'. $image['monthly']['media_image']) }}" />
                                            </audio>
                                            <!-- <img class="audio-player__cover" src="https://unsplash.it/g/300?image=29"/> -->
                                            <img class="audio-player__cover" src="{{ Storage::disk('public')->url('media_files/'. $image['monthly']['media_cover']) }}">
                                            <video preload="auto" loop="loop">
                                                <source src="" type="video/mp4" />
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
                                    @if($i==4)
                                    @php
                                    break; @endphp
                                    @endif
                                    @php $i++; @endphp
                                    @endforeach --}}

                                    @php $i=1; @endphp
                                    @foreach($AllPodcast as $podcast_key => $image)
                                    <div class="col-md-3">
                                        <div class="">
                                            <h4 class="audio-player__title">{{ $image['monthly']['media_name'] }}</h4>
                                            <div class="post-information">
                                                <h4 class="content">{{$image['monthly']['media_name']}}</h4>
                                            </div>

                                            <div class="box-video vid-fit-reveal" data-vid="box-video_{{ $image['monthly']['id'] }}">
                                                <a href="" target="_blank" class="bg-video" id="podcast_id_{{ $image['monthly']['id'] }}" data-toggle="modal" data-target="#podcastModal"
                                                    style="background-image: @if(str_contains($image['monthly']['media_image'],'spotify')) url('{{ asset('frontend/images/spotify_logo.png') }}') @elseif(str_contains($image['monthly']['media_image'],'apple')) url('{{ asset('frontend/images/apple_logo.png') }}') @elseif(str_contains($image['monthly']['media_image'],'stitcher')) url('{{ asset('frontend/images/stitcher_logo.png') }}') @elseif(str_contains($image['monthly']['media_image'],'redcircle')) url('{{ asset('frontend/images/redcircle_logo.png') }}') @endif; opacity: 1;" data-src="{{ $image['monthly']['media_image'] }}">
                                                    <div class="bt-play" id="bt-play_{{ $image['monthly']['id'] }}"></div>
                                                </a>
                                                <div class="video-container">
                                                    <iframe width="590" height="100%"
                                                    src="{{ $image['monthly']['media_image'] }}" frameborder="0"
                                                    allowfullscreen="allowfullscreen"></iframe>
                                                </div>
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


                                          {{-- <div class="box-video vid-fit-reveal" data-id="{{ $podcast->id }}" data-testid="play-pause-button" data-vid="box-video_{{ $podcast['id'] }}" id="#js-fitvideo_{{ $podcast->id }}">
                                                <a href="" target="_blank" class="bg-video" id="podcast_id_{{ $podcast['id'] }}" data-toggle="modal" data-target="#podcastModal"
                                                style="background-image: @if(str_contains($podcast['media_image'],'spotify')) url('{{ asset('frontend/images/spotify_logo.png') }}') @elseif(str_contains($podcast['media_image'],'apple')) url('{{ asset('frontend/images/apple_logo.png') }}') @elseif(str_contains($podcast['media_image'],'stitcher')) url('{{ asset('frontend/images/stitcher_logo.png') }}') @elseif(str_contains($podcast['media_image'],'redcircle')) url('{{ asset('frontend/images/redcircle_logo.png') }}') @endif; opacity: 1;" data-src="{{ $podcast['media_image'] }}">
                                                <div class="bt-play" id="bt-play_{{ $podcast->id }}"></div>
                                                </a>
                                                <div class="video-container">
                                                <iframe width="590" height="100%"
                                                    src="{{ $podcast['media_image'] }}" id="vid-reveal_{{ $podcast->id }}" frameborder="0"
                                                    allowfullscreen="allowfullscreen"></iframe>
                                                </div>
                                          </div> --}}
                                        </div>
                                        {{-- <p>{{ $podcast['media_name'] }}</p> --}}
                                      </div>
                                    @if($i==4)
                                    @php
                                    break; @endphp
                                    @endif
                                    @php $i++; @endphp
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @endif
            </div>

            <div>
                @if(isset($AllEbooks) && !empty($AllEbooks))
                <section class="ebook_section col-12">
                    <div class="row ">
                        <div class="col-md-12">
                            <div class="below_info padding-tb-30-lr-45">
                                <h3>{{ __('backend.homepage.ebook-details') }}</h3>
                                @if(isset($AllEbooks) && !empty($AllEbooks)&& count($AllEbooks) >= 5 )
                                <a href="{{ route('page.profile.allebook',encrypt($login_user['id'])) }}">View all</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 plr-45">
                         <div class="post-slide">
                         <div class="row">
                                @php $i=1; @endphp
                                @foreach($AllEbooks as $ebook_key => $Ebook)
                                <div class="col-md-3 col-12 pb-3">
                                    <div class="post-img">
                                        <a href="{{ Storage::disk('public')->url('media_files/'. $Ebook['monthly']['media_image']) }}" target="_blank">
                                            <img src="{{ Storage::disk('public')->url('media_files/'. $Ebook['monthly']['media_cover']) }}" alt="" class="w-100" /></a>
                                    </div>
                                    <div class="post-information">
                                        <h4 class="content">{{$Ebook['monthly']['media_name']}}</h4>
                                    </div>
                                    <div class="view_eye_post">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <p class="views">All</p>
                                        <span class="ebook_section_number">: {{$Ebook['monthly']['totalcount'] }}
                                        </span>
                                    </div>
                                    <div class="view_calander_post">
                                        <i class="fa fa-calendar"></i>
                                        <p class="calander">Today</p>
                                        <span class="ebook_section_number">: {{$Ebook['daily']['totalcount'] }} </span>
                                    </div>
                                </div>
                                @if($i==4)
                                @php
                                break; @endphp
                                @endif
                                @php $i++; @endphp
                                @endforeach
                            </div>
                         </div>
                        </div>
                    </div>
                </section>
                @endif
            </div>
            <div>
                @if(isset($AllMedia) && !empty($AllMedia) || isset($AllYoutube) && !empty($AllYoutube))
                <section class="youtube_section col-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info padding-tb-30-lr-45">
                                <h3>{{ __('backend.homepage.youtube-details') }}</h3>
                                @if(isset($AllMedia) && !empty($AllMedia) && (count($AllMedia) + count($AllYoutube)) >= 4)
                                <a href="{{ route('page.profile.allyoutube',encrypt($login_user['id'])) }}">View all</a>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="col-md-12 plr-45">
                            <div class="row">
                                @php $i=1; @endphp
                                @foreach($AllMedia as $video_key => $video)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="post-slide">
                                        <div class="post-img">
                                            <iframe width="560" height="215" src="{{ $video['monthly']['media_url']}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
                                @if($i==3)
                                @php
                                break; @endphp
                                @endif
                                @php $i++; @endphp
                                @endforeach
                                @if(isset($AllYoutube) && !empty($AllYoutube))
                                @php $i=1; @endphp
                                @foreach($AllYoutube as $youtube_key => $youtubevideo)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="post-slide">
                                        <div class="post-img">
                                            <a href="{{ $youtubevideo['monthly']['media_url'] }}">
                                                <iframe width="560" height="215" src="{{ $youtubevideo['monthly']['media_url']}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                            encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></a>
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
                                @if($i==1)
                                @php
                                break; @endphp
                                @endif
                                @php $i++; @endphp
                                @endforeach
                                @endif
                            </div>
                        </div> --}}

                        <div class="col-md-12 plr-45">
                            <div class="row">
                                @php $i=1; @endphp
                                @foreach($AllMedia as $video_key => $video)
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="post-slide">
                                            <div class="post-img">
                                                <div class='c-video__image-container vid-fit-reveal box-video-youtube'
                                                    data-id="{{ $video['monthly']['id'] }}" id="#js-fitvideo_{{ $video['monthly']['id'] }}" data-vid="box-video-youtube_{{ $video['monthly']['id'] }}">
                                                    <a href="" target="_blank" class="bg-video" id="youtube_id_{{ $video['monthly']['id'] }}" data-toggle="modal" data-target="#youtubeModal"
                                                        data-src="{{ $video['monthly']['media_url'] }}">
                                                        <div class="bt-play" id="bt-play_{{ $video['monthly']['id'] }}"></div>
                                                    </a>
                                                    <iframe width="560" height="215" src="{{ $video['monthly']['media_url']}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                    encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
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
                                    @if($i==3)
                                    @php
                                    break; @endphp
                                    @endif
                                    @php $i++; @endphp
                                @endforeach
                                @if(isset($AllYoutube) && !empty($AllYoutube))
                                @php $i=1; @endphp
                                    {{-- @foreach($AllYoutube as $youtube_key => $youtubevideo)
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="post-slide">
                                                <div class="post-img">
                                                    <a href="{{ $youtubevideo['monthly']['media_url'] }}">
                                                        <iframe width="560" height="215" src="{{ $youtubevideo['monthly']['media_url']}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                    encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></a>
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
                                        @if($i==1)
                                        @php
                                        break; @endphp
                                        @endif
                                        @php $i++; @endphp
                                    @endforeach --}}

                                    @foreach($AllYoutube as $youtube_key => $youtubevideo)
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="post-slide">
                                                <div class="post-img">
                                                    <div class='c-video__image-container vid-fit-reveal box-video-youtube'
                                                    data-id="" id="#js-fitvideo_" data-vid="box-video-youtube_">
                                                    <a href="" target="_blank" class="bg-video" id="youtube_id_" data-toggle="modal" data-target="#youtubeModal"
                                                        data-src="{{ $youtubevideo['monthly']['media_url'] }}">
                                                        <div class="bt-play" id="bt-play_"></div>
                                                    </a>
                                                    <iframe width="560" height="215" src="{{ $youtubevideo['monthly']['media_url']}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                    encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                </div>
                                                </div>
                                                <div class="post-information">
                                                    <!-- <a href="{{ $youtubevideo['monthly']['media_url']}}" class="content">{{ $youtubevideo['monthly']['media_url']}}</a> -->
                                                </div>
                                                <div class="view_eye_post">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                    <p class="views">All</p>
                                                    <span class="youtube_section_number">: {{$youtubevideo['monthly']['totalcount'] }}
                                                    </span>
                                                </div>
                                                <div class="view_calander_post">
                                                    <i class="fa fa-calendar"></i>
                                                    <p class="calander">Today</p>
                                                    <span class="youtube_section_number">: {{$youtubevideo['daily']['totalcount'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @if($i==1)
                                        @php
                                        break; @endphp
                                        @endif
                                        @php $i++; @endphp
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
                @endif
            </div>
        </div>
        @if($login_user->isCoach())
        <div class="col-lg-12 col-xl-3 order-xl-1 order-0">
            <div class="coach_sidebar">
                <div class="setting_icon">
                    <a href="{{ route('user.profile.edit') }}"><img src="{{ asset('frontend/images/Svg/setting.svg') }}" alt="" /></a>
                </div>
                <div class="sidebar_info">
                    <div class="sidebar_flex">
                        <div class="sidebar_align">
                            @if(empty($login_user['user_image']))
                            <img src="{{ asset('backend/images/placeholder/profile-' . intval($login_user['id'] % 10) . '.webp') }}">
                            @else
                            <img src="{{ Storage::disk('public')->url('user/'. $login_user['user_image']) }}">
                            @endif
                            <div>
                                <h5>{{ $login_user['name'] }}</h5>
                                <p>{{ !empty($login_user['working_type']) ? \App\User::WORKING_TYPES[$login_user['working_type']] : '' }}
                                    {{ !empty($login_user['experience_year']) ? ' | '.\App\User::EXPERIENCE_YEARS[$login_user['experience_year']].' Years' : '' }}
                                    {{ !empty($login_user['hourly_rate_type']) ? ' | '.\App\User::HOURLY_RATES[$login_user['hourly_rate_type']] : '' }}
                                </p>
                            </div>
                        </div>
                        <div class="sidebar_details">
                            <div class="detail">
                                <div class="one">
                                    <img src="{{ asset('frontend/images/bag-one.svg') }}" alt="" />
                                    <p>{{ $login_user['company_name'] }}</p>
                                </div>
                            </div>
                            <div class="detail">
                                <div class="one">
                                    <img src="{{ asset('frontend/images/bag-one.svg') }}" alt="" />
                                    <p>{{ !empty($login_user['preferred_pronouns']) ? \App\User::PREFERRED_PRONOUNS[$login_user['preferred_pronouns']] : '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="level">
                        <h5>{{ $progress_data['profile'] }}</h5>
                        <h6>{{ $progress_data['percentage'] }}% Completed</h6>
                        <div id="chartContainer" style="height: 200px; width: 100%;"></div>
                    </div>
                    <div class="main_progress_section">
                        <div class="prggress_level">
                            <p>Basic</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                    20%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Social</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                                    30%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Bronze</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                    50%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Silver</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                    60%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Gold</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                    70%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Platinum</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                    90%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Rhodium</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                    100%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="progress_info">
                        <p class="mb-4">{{ __('How it works? ') }}<a href="{{ route('page.earn.points') }}" target="_blank">{{ __('Learn Here') }}</a></p>
                        <p>
                            {{  str_limit($login_user['user_about'],800,'...')}} 
                            @php 
                            $length = strlen($login_user['user_about']);
                            @endphp
                           @if($length >= 800)
                            <a href="{{ route('page.profile',encrypt(Auth::user()->id)) }}">{{ __('Read More') }}</a>
                           @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>


    <!-- </div>
    </section> -->



    <!-- <div class="row">

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('backend.homepage.pending-listings') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($pending_item_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4 ">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('backend.homepage.all-listings') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($item_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4 ">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('backend.homepage.all-messages') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($message_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('backend.homepage.all-comments') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($comment_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('backend.homepage.message') }}</h6>
                </div>
                <div class="card-body">

                    @foreach($recent_threads as $recent_threads_key => $thread)
                        <div class="row pt-2 pb-2 {{ $recent_threads_key%2 == 0 ? 'bg-light' : '' }}">
                            <div class="col-9">
                                <span>{{ $thread->latestMessage->body }}</span>
                            </div>
                            <div class="col-3 text-right">
                                <span>{{ $thread->latestMessage->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="row text-center mt-3">
                        <div class="col-12">
                            <a href="{{ route('user.messages.index') }}">{{ __('backend.homepage.view-all-message') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('backend.homepage.comment') }}</h6>
                </div>
                <div class="card-body">

                    @foreach($recent_comments as $recent_comments_key => $comment)
                        <div class="row pt-2 pb-2 {{ $recent_comments_key%2 == 0 ? 'bg-light' : '' }}">
                            <div class="col-9">
                                <span>{{ $comment->comment }}</span>
                            </div>
                            <div class="col-3 text-right">
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="row text-center mt-3">
                        <div class="col-12">
                            <a href="{{ route('user.comments.index') }}">{{ __('backend.homepage.view-all-comment') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

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

    <!-- For youtube modal-->
    <div class="modal-4">
        <div class="modal fade" id="youtubeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal_dialog" role="document">
              <div class="modal-content content_design3">
                <div class="modal-header border-bottom-0">
                  <h5 class="modal-title" id="exampleModalLabel_youtube"></h5>
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
    <script src="https://www.gstatic.com/firebasejs/4.1.3/firebase.js"></script>

    <script>
        (function() {
            //Show Modal
            $("#exampleModalLong").on("show.bs.modal", function(e) {
                console.log("show");
                $(".firstBlur").addClass("modalBlur");
            });

            //Remove modal
            $("#exampleModalLong").on("hide.bs.modal", function(e) {
                console.log("hide");
                $(".firstBlur").removeClass("modalBlur");
            });
        })();


        $(document).ready(function() {
            $('.notificationReadBtn').on('click', function() {
                let id = $(this).attr('id');
                console.log(id)

                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                $.ajax({
                    url: "{{ route('read-notification') }}",
                    type: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                    }
                });
            })
        });

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
        $(".toggle").click(function() {
            console.log("toggling sidebar");
            $(".sidebar_two").toggleClass('active');

        });
        $(".toggle_two").click(function() {
            console.log("toggling sidebar");
            $(".sidebar_two").toggleClass('active');

        });
        $(".cancel").click(function() {
            let id = $(this).attr('id');
            console.log("toggling visibility");
            $('.profile_img_'+id).toggleClass('gone');

        });

        $(function() {
            $('#click').trigger('click');
        });
        var firebaseConfig = {
            aapiKey: "AIzaSyA31EsSr68dVVQ-cVZwfbLmeDK8_PUT2fM",
            authDomain: "coachhq-c1b3d.firebaseapp.com",
            projectId: "coachhq-c1b3d",
            storageBucket: "coachhq-c1b3d.appspot.com",
            messagingSenderId: "668525619724",
            appId: "1:668525619724:web:e4282225654d0467655c29",
            measurementId: "G-VFGKZNYRVM"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function startFCM() {
            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken()
                })
                .then(function(response) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route("save-token") }}',
                        type: 'POST',
                        data: {
                            token: response
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            //alert('Token stored.');
                        },
                        error: function(error) {
                            // alert(error);
                            console.log(error);
                        },
                    });
                }).catch(function(error) {
                    // alert(error);
                    console.log(error);
                });
        }


        window.onload = function() {
            var chartData = {
                "Profile Progress": [{
                    cursor: "pointer",
                    explodeOnClick: false,
                    innerRadius: "75%",
                    legendMarkerType: "square",
                    radius: "70%",
                    startAngle: 280,
                    type: "doughnut",
                    indexLabelLineThickness: 4,
                    dataPoints: <?php echo json_encode($data_points, JSON_NUMERIC_CHECK); ?>
                }],
            };

            var dataOptions = {
                backgroundColor: "#FFEEF9",
                animationEnabled: true,
                theme: "light2",
            };

            var chart = new CanvasJS.Chart("chartContainer", dataOptions);
            chart.options.data = chartData["Profile Progress"];
            chart.render();


        }
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

            //for youtube video

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
        })
    
    </script>

    @endsection