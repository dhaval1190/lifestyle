@extends('backend.user.layouts.app')

@section('styles')
<style>
    .invisible {
        display: none;
    }
    .canvasjs-chart-credit{
        display: none;
    }
</style>
@endsection

@section('content')

    @if($paid_subscription_days_left == 1)
        <div class="alert alert-warning" role="alert">
            {{ __('backend.subscription.subscription-end-soon-day') }}
        </div>
    @elseif($paid_subscription_days_left > 1 && $paid_subscription_days_left <= \App\Subscription::PAID_SUBSCRIPTION_LEFT_DAYS)
        <div class="alert alert-warning" role="alert">
            {{ __('backend.subscription.subscription-end-soon-days', ['days_left' => $paid_subscription_days_left]) }}
        </div>
    @endif

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('backend.homepage.dashboard') }}</h1>
        <!-- <a href="#"><img src="{{ asset('frontend/images/Svg/notification_zero.svg') }}" alt=""
                                class="notification_icon" /></a> -->
        <a href="#"><img src="{{ asset('frontend/images/Svg/notification_alert.svg') }}" alt="" class="notification_alert" /></a>
        {{-- <a href="{{ route('user.items.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
            <span class="text">{{ __('backend.homepage.post-a-listing') }}</span>
        </a> --}}
    </div>

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
                                        <h3>{{ __('backend.homepage.all-messages') }}</h3>
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
                                        <h3>{{ __('backend.homepage.all-comments') }}</h3>
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
                                        <h3>{{ __('backend.homepage.all-articles') }}</h3>
                                        <p class="c-four">{{ number_format($item_count) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-12">
                            <div class="coach_messages">
                                <div class="coach_message_info">
                                    <h2>All Messages</h2>
                                    <a href="{{ route('user.messages.index') }}">{{ __('backend.homepage.view-all-message') }}</a>
                                </div>
                                
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
                                                @php $info_class = ($recent_threads_key%2 == 0) ? 'm-info-one' : 'm-info-two'; @endphp
                                                Created by <span class="{{$info_class}}">{{ $thread->creator()->name }}</span>
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @if($login_user->isCoach())
                    <div class="col-lg-4 order-lg-1 order-0">
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
                                            <p>{{ !empty($login_user['working_type']) ? \App\User::WORKING_TYPES[$login_user['working_type']] : '' }} {{ !empty($login_user['experience_year']) ? ' | '.\App\User::EXPERIENCE_YEARS[$login_user['experience_year']].' Years' : '' }} {{ !empty($login_user['hourly_rate_type']) ? ' | '.\App\User::HOURLY_RATES[$login_user['hourly_rate_type']] : '' }}</p>
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
                                                <p>{{ !empty($login_user['preferred_pronouns']) ? \App\User::PREFERRED_PRONOUNS[$login_user['preferred_pronouns']] : '' }}</p>
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
                                            <div class="progress-bar" role="progressbar" style="width: 20%"
                                                aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                20%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prggress_level">
                                        <p>Social</p>
                                        <div class="progress p-200">
                                            <div class="progress-bar" role="progressbar" style="width: 30%"
                                                aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                                                30%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prggress_level">
                                        <p>Bronze</p>
                                        <div class="progress p-200">
                                            <div class="progress-bar" role="progressbar" style="width: 50%"
                                                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                50%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prggress_level">
                                        <p>Silver</p>
                                        <div class="progress p-200">
                                            <div class="progress-bar" role="progressbar" style="width: 60%"
                                                aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                                60%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prggress_level">
                                        <p>Gold</p>
                                        <div class="progress p-200">
                                            <div class="progress-bar" role="progressbar" style="width: 70%"
                                                aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                                70%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prggress_level">
                                        <p>Platinum</p>
                                        <div class="progress p-200">
                                            <div class="progress-bar" role="progressbar" style="width: 90%"
                                                aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                                90%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="prggress_level">
                                        <p>Rhodium</p>
                                        <div class="progress p-200">
                                            <div class="progress-bar" role="progressbar" style="width: 100%"
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                100%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="progress_info">
                                    <p class="mb-4">{{ __('How it works? ') }}<a href="{{ route('page.earn.points') }}" target="_blank">{{ __('Learn Here') }}</a></p>
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
<script>
    window.onload = function () {
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
