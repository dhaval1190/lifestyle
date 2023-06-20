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
                    <a class="decoration-none" href="{{ route('admin.users.index',['user_email_verified'=>1,'user_suspended'=>0,'order_by'=>1,'count_per_page'=>10]) }}">
                        <div class="first_coach coach">
                            <div class="coaches">
                                <img src="{{ asset('frontend/images/Svg/client.svg') }}" alt="" />
                                <div class="coaches_detail">
                                    <h3>{{ __('backend.homepage.pending-listings') }}</h3>
                                    <p class="c-one">{{ $pending_users_count }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                    
                </div>
                <div class="col-md-6">
                    <a class="decoration-none" href="{{ route('admin.messages.index') }}">
                        <div class="second_coach coach">
                            <div class="coaches">
                                <img src="{{ asset('frontend/images/Svg/msg.svg') }}" alt="" />
                                <div class="coaches_detail">
                                    <h3>{{ __('backend.homepage.all-messages') }}</h3>
                                    <p class="c-two">{{ $all_messages_count }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a class="decoration-none" href="{{ route('admin.comments.index') }}">
                        <div class="third_coach coach">
                            <div class="coaches">
                                <img src="{{ asset('frontend/images/Svg/meeting.svg') }}" alt="" />
                                <div class="coaches_detail">                                    
                                    <h3>{{ __('backend.homepage.all-comments') }}</h3>
                                    <p class="c-three">{{ $comments_count }}</p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-6">
                    <a class="decoration-none" href="{{ route('admin.items.index') }}">
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
                    @foreach($recent_threads as $recent_threads_key => $thread) 
                        <div class="coach_message_details">
                            @if(empty($thread->creator()->user_image) && isset($login_user['id']))
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
    </div>

@endsection

@section('scripts')
@endsection
