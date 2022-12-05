@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    @if($warning_smtp)
        <div class="alert alert-warning" role="alert">
            <p>
                <i class="fas fa-exclamation-triangle"></i>
                {{ __('theme_directory_hub.setting.warning-smtp') }}
            </p>
            <p>
                <a href="{{ route('admin.settings.general.edit') }}" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-cog"></i>
                    {{ __('theme_directory_hub.setting.warning-smtp-action') }}
                </a>
            </p>
        </div>
    @endif

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('backend.homepage.dashboard') }}</h1>
        <a href="{{ route('admin.items.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
            <span class="text">{{ __('backend.homepage.post-a-listing') }}</span>
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12 order-lg-0 order-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="first_coach coach">
                        <div class="coaches">
                            <img src="{{ asset('frontend/images/Svg/client.svg') }}" alt="" />
                            <div class="coaches_detail">
                                <h3>{{ __('backend.homepage.pending-listings') }}</h3>
                                <p class="c-one">0</p>
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
                                <p class="c-two">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="third_coach coach">
                        <div class="coaches">
                            <img src="{{ asset('frontend/images/Svg/meeting.svg') }}" alt="" />
                            <div class="coaches_detail">
                                <!-- <a href="{{ route('user.comments.index') }}">{{ __('backend.homepage.view-all-comment') }}</a> -->
                                <h3>{{ __('backend.homepage.all-comments') }}</h3>
                                <p class="c-three">0</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="fourth_coach coach">
                        <div class="coaches">
                            <img src="{{ asset('frontend/images/Svg/group.svg') }}" alt="" />
                            <div class="coaches_detail">
                                <h3>{{ __('backend.homepage.all-listings') }}</h3>
                                <p class="c-four">{{ number_format($item_count) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="coach_messages">
                        <div class="coach_message_info">
                            <h2>All Messages</h2>
                            <a href="{{ route('admin.messages.index') }}">{{ __('backend.homepage.view-all-message') }}</a>
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
        <!-- <div class="col-lg-4 order-lg-1 order-0">
            <div class="coach_sidebar">
                <div class="setting_icon">
                    <a href="{{ route('user.profile.edit') }}"><img src="{{ asset('frontend/images/Svg/setting.svg') }}" alt="" /></a>
                </div>
                <div class="sidebar_info">
                    <div class="sidebar_flex">
                        <div class="sidebar_align">
                            @if(empty($admin_user['user_image']))
                                <img src="{{ asset('backend/images/placeholder/profile-' . intval($admin_user['id'] % 10) . '.webp') }}">
                            @else
                                <img src="{{ Storage::disk('public')->url('user/'. $admin_user['user_image']) }}">
                            @endif
                            <div>
                                <h5>{{ $admin_user['name'] }}</h5>
                                <p>{{ !empty($admin_user['working_type']) ? \App\User::WORKING_TYPES[$admin_user['working_type']] : '' }} {{ !empty($admin_user['experience_year']) ? ' | '.\App\User::EXPERIENCE_YEARS[$admin_user['experience_year']].' Years' : '' }} {{ !empty($admin_user['hourly_rate_type']) ? ' | '.\App\User::HOURLY_RATES[$admin_user['hourly_rate_type']] : '' }}</p>
                            </div>
                        </div>
                        <div class="sidebar_details">
                            <div class="detail">
                                <div class="one">
                                    <img src="{{ asset('frontend/images/bag.png') }}" alt="" />
                                    <p>{{ $admin_user['company_name'] }}</p>
                                </div>
                            </div>
                            <div class="detail">
                                <div class="one">
                                    <img src="{{ asset('frontend/images/bag-one.svg') }}" alt="" />
                                    <p>{{ !empty($admin_user['preferred_pronouns']) ? \App\User::PREFERRED_PRONOUNS[$admin_user['preferred_pronouns']] : '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="level">
                        <div class="percentage_card">
                            <h5>Level 9</h5>
                            <div class="percent">
                                <svg>
                                    <circle cx="105" cy="105" r="80"></circle>
                                    <circle cx="105" cy="105" r="80" style="--percent: 70"></circle>
                                </svg>
                                <div class="number">
                                    <h3>
                                        70<span>%</span>
                                        <span class="completed">Completed</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="main_progress_section">
                        <div class="prggress_level">
                            <p>Text 1</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 25%"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    25%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Text 2</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 25%"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    25%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Text 3</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 25%"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    25%
                                </div>
                            </div>
                        </div>
                        <div class="prggress_level">
                            <p>Text 4</p>
                            <div class="progress p-200">
                                <div class="progress-bar" role="progressbar" style="width: 25%"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    25%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="progress_info">
                        <p>
                            {{ $admin_user['user_about']}}
                        </p>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
    <!-- <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('backend.homepage.categories') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($category_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-th-large fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('backend.homepage.listings') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($item_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('backend.homepage.posts') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($post_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rss fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('backend.homepage.users') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($user_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-cog fa-2x text-gray-300"></i>
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
                            <a href="{{ route('admin.messages.index') }}">{{ __('backend.homepage.view-all-message') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 d-none">
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
                            <a href="{{ route('admin.comments.index') }}">{{ __('backend.homepage.view-all-comment') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

@endsection

@section('scripts')
@endsection
