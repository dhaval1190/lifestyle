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
        .view a{
            color: red;
        }
        .toggle {
            cursor: pointer;
        }
        .toggle_two{
            cursor: pointer;
            float: right;
            margin-top: 81px;
            margin-left: 10px;
            

        }

        .sidebar_two {
            position: fixed;
            width: 500px;
            float: right;
            box-shadow: 0px 0px 10px 3px black;   
            border-left: 1px solid black;
            height: 100%;
            top: 0px;
            right: -500px;
            z-index: 99;

            background-color: #5a5c69;
            opacity: 1.2;
            /* background-image: linear-gradient(180deg,#4e73df 10%,#224abe 100%); */
            background-size: cover;
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
            right: 7px;
            top: 10px;
            cursor: pointer;
            padding: 3px;
            padding-left: 8px;
            padding-right: 8px;
            border-radius: 20px;
        }


        /* .cancel_two {
            position: absolute;
            right: 7px;
            top: 10px;
            cursor: pointer;
            padding: 3px;
            padding-left: 8px;
            padding-right: 8px;
            border-radius: 20px;
        } */

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
</style>
@endsection

@section('content')

@if($paid_subscription_days_left == 1)
<div class="alert alert-warning" role="alert">
    {{ __('backend.subscription.subscription-end-soon-day') }}
</div>
@elseif($paid_subscription_days_left > 1 && $paid_subscription_days_left <=
    \App\Subscription::PAID_SUBSCRIPTION_LEFT_DAYS) <div class="alert alert-warning" role="alert">
    {{ __('backend.subscription.subscription-end-soon-days', ['days_left' => $paid_subscription_days_left]) }}
    </div>
    @endif

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('backend.homepage.dashboard') }}</h1>
        <!-- <a href="#"><img src="{{ asset('frontend/images/Svg/notification_zero.svg') }}" alt=""
                                class="notification_icon" /></a> -->
        <!-- <a href="#" id="action" class="toggle"><img src="{{ asset('frontend/images/Svg/notification_alert.svg') }}"
                alt="" class="notification_alert" /></a> -->
                <div class="toggle"><div class="ico"><img src="{{ asset('frontend/images/Svg/notification_alert.svg') }}"
                alt="" class="notification_alert" /></div></div>
        {{-- <a href="{{ route('user.items.create') }}" class="btn btn-info btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-plus"></i>
        </span>
        <span class="text">{{ __('backend.homepage.post-a-listing') }}</span>
        </a> --}}
    </div>
    <div class="sidebar_two" id="main">
        <div class="d-flex">
        <h2>Notifications</h2>
        <div class="toggle_two" style="color:red;"><button type="button" class="btn btn-primary">Close</button></div>
        </div>

        <div class="notibox" >
            <div class="container">
                <div class="row">
                    @if(isset($notifications) && !empty($notifications) && $notifications->count() >= 10)
                    <div class="view"><a href="{{ route('page.user.notification', $login_user['id']) }}">View all</a></div>
                    @endif
                    @foreach($notifications as $notification)
                    <div class="col-md-12">
                        <p>
                        <h5>{{$notification->notification}}</h5>
                        <div class="cancel">✕</div>
                        </p>
                    </div>
                    @endforeach
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
    <div class="row">
        @if($login_user->isCoach())
        @php $main_div_class = 'col-lg-8 order-lg-0 order-1'; @endphp
        @else
        @php $main_div_class = 'col-lg-12 order-lg-0 order-1'; @endphp
        @endif
        <div class="{{ $main_div_class }}">
            <div class="row">
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
                <div class="col-md-6">
                    <div class="second_coach coach">
                        <div class="coaches">
                            <img src="{{ asset('frontend/images/Svg/msg.svg') }}" alt="" />
                            <div class="coaches_detail">
                                <h3><a class="decoration-none"
                                        href="{{ route('user.messages.index') }}">{{ __('backend.homepage.all-messages') }}</a>
                                </h3>
                                <p class="c-two">{{ number_format($message_count) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if($login_user->isCoach())
                <div class="col-md-6">
                    <div class="third_coach coach">
                        <div class="coaches">
                            <img src="{{ asset('frontend/images/Svg/meeting.svg') }}" alt="" />
                            <div class="coaches_detail">
                                <!-- <a href="{{ route('user.comments.index') }}">{{ __('backend.homepage.view-all-comment') }}</a> -->
                                <h3><a class="decoration-none"
                                        href="{{ route('user.comments.index') }}">{{ __('backend.homepage.all-comments') }}</a>
                                </h3>
                                <p class="c-three">{{ number_format($comment_count) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="fourth_coach coach">
                        <div class="coaches">
                            <img src="{{ asset('frontend/images/Svg/group.svg') }}" alt="" />
                            <div class="coaches_detail">
                                <h3><a class="decoration-none"
                                        href="{{ route('user.articles.index') }}">{{ __('backend.homepage.all-articles') }}</a>
                                </h3>
                                <p class="c-four">{{ number_format($item_count) }}</p>
                            </div>
                        </div>
                    </div>
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
                @endif
                <div class="col-lg-12">
                    <div class="coach_messages">
                        <div class="coach_message_info">
                            <h2>All Messages</h2>
                            <a
                                href="{{ route('user.messages.index') }}">{{ __('backend.homepage.view-all-message') }}</a>
                        </div>

                        @foreach($recent_threads as $recent_threads_key => $thread)
                        <div class="coach_message_details">
                            @if(empty($thread->creator()->user_image))
                            <img src="{{ asset('backend/images/placeholder/profile-' . intval($login_user['id'] % 10) . '.webp') }}"
                                style="border-radius:50%; width:100px; height:100px;">
                            @else
                            <img src="{{ Storage::disk('public')->url('user/'. $thread->creator()->user_image) }}"
                                style="border-radius:50%; width:100px; height:100px;">
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

                    </div>
                </div>
                @if(isset($All_visit_count) && !empty($All_visit_count))
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-3">
                            <b>All Profile Visitor(s)</b> : {{ $All_visit_count }}
                        </div>
                        <div class="col-lg-3">
                            <b>Today Profile Visitor(s)</b> : {{ $Today_Visits_count }}
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-lg-12 mt-2">
                    <div class="row">
                        @if(isset($Articledetail) && !empty($Articledetail))
                        <div class="col-lg-6 ">
                            <ul class="list-group" id="music-list">
                                <h4 class="m-one">Article Details</h4>
                                @foreach($Articledetail as $Article)
                                <li class="list-group-item list-group-item-action item"
                                    data-id="<?= $Article['monthly']['id'] ?>">
                                    <div class="d-flex w-100 align-items-center">
                                        <div class="col-auto pe-2">
                                            <img src="{{ !empty($Article['monthly']['item_image']) ? Storage::disk('public')->url('item/' . $Article['monthly']['item_image']): asset('frontend/images/placeholder/full_item_feature_image_medium.webp')}}"
                                                height="100px" width="100px" alt=""
                                                class="img-thumbnail bg-gradient bg-dark mini-display-img">

                                        </div>
                                        <div class="col-auto flex-grow-1 flex-shrink-1">
                                            <p class="m-0 text-truncate"
                                                title="<?= $Article['monthly']['item_slug'] ?>">
                                                <?= $Article['monthly']['item_slug'] ?></p>
                                        </div>
                                        <div class="col-auto px-2">
                                            <p>All:{{$Article['monthly']['totalcount'] }}</p>
                                        </div>
                                        <div class="col-auto px-2">
                                            <p>Today:{{$Article['daily']['totalcount'] }}</p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if(isset($Ebooks) && !empty($Ebooks))
                        <div class="col-lg-6 ">
                            <ul class="list-group" id="music-list">
                                <h4 class="m-one">Ebook Details</h4>
                                @foreach($Ebooks as $Ebook)
                                <li class="list-group-item list-group-item-action item"
                                    data-id="<?= $Ebook['monthly']['id'] ?>">
                                    <div class="d-flex w-100 align-items-center">
                                        <div class="col-auto pe-2">
                                            <img src="{{ !empty($Ebook['monthly']['media_cover']) ? Storage::disk('public')->url('media_files/' . $Ebook['monthly']['media_cover']): asset('frontend/images/placeholder/full_item_feature_image_medium.webp')}}"
                                                height="100px" width="100px" alt=""
                                                class="img-thumbnail bg-gradient bg-dark mini-display-img">

                                        </div>
                                        <div class="col-auto flex-grow-1 flex-shrink-1">
                                            <p class="m-0 text-truncate" title="<?= $Ebook['daily']['media_name'] ?>">{{$Ebook['daily']['media_name']}}</p>
                                        </div>
                                        <div class="col-auto px-2">
                                            <p>All:{{$Ebook['monthly']['totalcount'] }}</p>
                                        </div>
                                        <div class="col-auto px-2">
                                            <p>Today:{{$Ebook['daily']['totalcount'] }}</p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
               
                @if(isset($PodcastImage) && !empty($PodcastImage) || isset($media) && !empty($media) || isset($Youtube) && !empty($Youtube))
                <div class="col-lg-12">
                    <div class="row">
                        <div class="below_info">
                            <h3>Our Media View Details</h3>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-lg-12 mt-2">
                    <div class="row">
                        @if(isset($PodcastImage) && !empty($PodcastImage))
                        <div class="col-lg-6 ">
                            <ul class="list-group" id="music-list">
                                <h4 class="m-one">Podcast Details</h4>
                                @foreach($PodcastImage as $podcast_key => $image)
                                <li class="list-group-item list-group-item-action item"
                                    data-id="<?= $image['monthly']['id'] ?>">
                                    <div class="d-flex w-100 align-items-center">
                                        <div class="col-auto pe-2">
                                            <img src="{{ Storage::disk('public')->url('media_files/'. $image['monthly']['media_cover']) }}"
                                                height="100px" width="100px" alt=""
                                                class="img-thumbnail bg-gradient bg-dark mini-display-img">
                                        </div>
                                        <div class="col-auto flex-grow-1 flex-shrink-1">
                                            <p class="m-0 text-truncate" title="<?= $image['daily']['media_name'] ?>">
                                                <?= $image['monthly']['media_name'] ?></p>
                                        </div>
                                        <div class="col-auto px-2">
                                            <p>All:{{$image['monthly']['totalcount'] }}</p>
                                        </div>
                                        <div class="col-auto px-2">
                                            <p>Today:{{$image['daily']['totalcount'] }}</p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @if(isset($media) && !empty($media) || isset($Youtube) && !empty($Youtube))
                        <div class="col-lg-6">
                            <ul class="list-group" id="music-list">
                                <h4 class="m-one">Youtube Details</h4>
                                @foreach($media as $video_key => $video)
                                <li class="list-group-item list-group-item-action item" data-id="<?= $video['monthly']['id'] ?>">
                                    <div class="d-flex w-100 align-items-center">
                                        <div class="col-auto pe-2">
                                            <iframe width="100" height="100" src="{{ $video['monthly']['media_url']}}" title="YouTube video player" frameborder="0" id="vid-reveal"></iframe>
                                        </div>
                                        <div class="col-auto flex-grow-1 flex-shrink-1">
                                            <p class="m-0 text-truncate" title="<?= $video['monthly']['media_name']?>">
                                            </p>
                                        </div>
                                        <div class="col-auto px-2">
                                            <p>All:{{$video['monthly']['totalcount'] }}</p>
                                        </div>
                                        <div class="col-auto px-2">
                                            <p>Today:{{$video['daily']['totalcount'] }}</p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                                @if(isset($Youtube) && !empty($Youtube))
                       
                                @foreach($Youtube as $youtube_key => $youtubevideo)
                                <li class="list-group-item list-group-item-action item" data-id="<?= $youtubevideo['monthly']['id'] ?>">
                                    <div class="d-flex w-100 align-items-center">
                                        <div class="col-auto pe-2">
                                            <iframe width="100" height="100" src="{{ $youtubevideo['monthly']['media_url']}}" title="YouTube video player" frameborder="0" id="vid-reveal"></iframe>
                                        </div>
                                        <div class="col-auto flex-grow-1 flex-shrink-1">
                                            <p class="m-0 text-truncate"
                                                title="<?= $youtubevideo['monthly']['media_url']?>"></p>
                                        </div>
                                        <div class="col-auto px-2">
                                            <p>All:{{$youtubevideo['monthly']['totalcount'] }}</p>
                                        </div>
                                        <div class="col-auto px-2">
                                            <p>Today:{{$youtubevideo['daily']['totalcount'] }}</p>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            
                        @endif
                            </ul>
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>

        @if($login_user->isCoach())
        <div class="col-lg-4 order-lg-1 order-0">
            <div class="coach_sidebar">
                <div class="setting_icon">
                    <a href="{{ route('user.profile.edit') }}"><img src="{{ asset('frontend/images/Svg/setting.svg') }}"
                            alt="" /></a>
                </div>
                <div class="sidebar_info">
                    <div class="sidebar_flex">
                        <div class="sidebar_align">
                            @if(empty($login_user['user_image']))
                            <img
                                src="{{ asset('backend/images/placeholder/profile-' . intval($login_user['id'] % 10) . '.webp') }}">
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
                                    <img src="{{ asset('frontend/images/bag.png') }}" alt="" />
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
                                <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="20"
                                    aria-valuemin="0" aria-valuemax="100">
                                    20%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Social</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 30%" aria-valuenow="30"
                                    aria-valuemin="0" aria-valuemax="100">
                                    30%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Bronze</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50"
                                    aria-valuemin="0" aria-valuemax="100">
                                    50%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Silver</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 60%" aria-valuenow="60"
                                    aria-valuemin="0" aria-valuemax="100">
                                    60%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Gold</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 70%" aria-valuenow="70"
                                    aria-valuemin="0" aria-valuemax="100">
                                    70%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Platinum</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 90%" aria-valuenow="90"
                                    aria-valuemin="0" aria-valuemax="100">
                                    90%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Rhodium</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100"
                                    aria-valuemin="0" aria-valuemax="100">
                                    100%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="progress_info">
                        <p class="mb-4">{{ __('How it works? ') }}<a href="{{ route('page.earn.points') }}"
                                target="_blank">{{ __('Learn Here') }}</a></p>
                        <p>
                            {{ $login_user['user_about']}}
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

    @endsection

    @section('scripts')
    <script src="https://www.gstatic.com/firebasejs/4.1.3/firebase.js"></script>
    <script>
    $(".toggle").click(function() {
        console.log("toggling sidebar");
        $(".sidebar_two").toggleClass('active');

    });
    $(".toggle_two").click(function() {
        console.log("toggling sidebar");
        $(".sidebar_two").toggleClass('active');

    });
    $(".cancel").click(function() {
        console.log("toggling visibility");
        $(this).parent().toggleClass('gone');

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
                        alert(error);
                    },
                });
            }).catch(function(error) {
                alert(error);
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

    @endsection