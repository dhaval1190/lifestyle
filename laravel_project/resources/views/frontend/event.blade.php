@extends('frontend.layouts.app')
@section('event_active', 'active')


@section('styles')
    <!-- Start Google reCAPTCHA version 2 -->
    @if ($site_global_settings->setting_site_recaptcha_contact_enable == \App\Setting::SITE_RECAPTCHA_CONTACT_ENABLE)
        {!! htmlScriptTagJsApi([
            'lang' => empty($site_global_settings->setting_site_language) ? 'en' : $site_global_settings->setting_site_language,
        ]) !!}
    @endif
    <!-- End Google reCAPTCHA version 2 -->
@endsection
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

@section('content')

    @if ($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT)
        <div class="site-blocks-cover inner-page-cover overlay"
            style="background-image: url( {{ asset('frontend/images/placeholder/header-inner.webp') }});">
        @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR)
            <div class="site-blocks-cover inner-page-cover overlay"
                style="background-color: {{ $site_innerpage_header_background_color }};">
            @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE)
                <div class="site-blocks-cover inner-page-cover overlay"
                    style="background-image: url( {{ Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image) }});">
                @elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
                    <div class="site-blocks-cover inner-page-cover overlay" style="background-color: #333333;">
    @endif

    @if ($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <div data-youtube="{{ $site_innerpage_header_background_youtube_video }}"></div>
    @endif

    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">
                <div class="row justify-content-center mt-5">
                    <div class="col-md-9 text-center">
                        <h1 style="color: {{ $site_innerpage_header_title_font_color }};">{{ __('All Events') }}</h1>
                        <!-- <p class="mb-0" style="color: {{ $site_innerpage_header_paragraph_font_color }};">{{ __('frontend.contact.description') }}</p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
     <div class="site-section">
        <div class="container">
                @if ($all_events->count() > 0)
                <div class="row">
                    <div class="col-lg-12">
                        <span id="span"></span>                                              
                            @php $count=1; @endphp
                            @foreach ($all_events as $event)
                                <div class="tab_section">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="event_bg mt-5">
                                                    <div class="row">
                                                        <div class="col-md-3 col-xl-3 pr-0">
                                                            <div class="main_event_image">
                                                                @if (empty($event->event_image))
                                                                    <img src="{{ asset('frontend/images/placeholder/category-image.webp') }}"
                                                                        alt="" />
                                                                @else
                                                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url('event/' . $event->event_image) }}"
                                                                        alt="" />
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-xl-7 pl-0">
                                                            @if ($event->event_start_date < date('Y-m-d'))
                                                                <div class="event_information_set">
                                                                    <span>{{ $event->event_name }}</span>
                                                                </div>
                                                            @elseif(date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_start_hour &&
                                                                date('Y-m-d H:i:s') < $event->event_start_date . ' ' . $event->event_end_hour)
                                                                <a href="{{ $event->event_social_url }}" target="_blank"
                                                                    id="join_btn">
                                                                    <div class="event_information_set">
                                                                        <span>{{ $event->event_name }}</span>
                                                                    </div>
                                                                </a>
                                                            @elseif(date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_start_hour &&
                                                                date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_end_hour)
                                                                    <div class="event_information_set">
                                                                        <span>{{ $event->event_name }}</span>
                                                                    </div>
                                                            @else
                                                                <a href="{{ $event->event_social_url }}" target="_blank"
                                                                    id="join_btn">
                                                                    <div class="event_information_set">
                                                                        <span>{{ $event->event_name }}</span>
                                                                    </div>
                                                                </a>
                                                            @endif
                                                            
                                                            <div class="event_schedule_date">
                                                                <img src="{{ asset('frontend/images/time.png') }}"
                                                                    alt="" />
                                                                @if ($event->event_start_date < date('Y-m-d'))
                                                                    <p>{{ Str::substr(getUserEventTimezone($event->event_start_date.' '.$event->event_start_hour),10) }}
                                                                        To
                                                                        {{ Str::substr(getUserEventTimezone($event->event_start_date.' '.$event->event_end_hour),10) }}
                                                                    </p>
                                                                @elseif(date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_start_hour &&
                                                                    date('Y-m-d H:i:s') < $event->event_start_date . ' ' . $event->event_end_hour)
                                                                    <a href="{{ $event->event_social_url }}" target="_blank"
                                                                        id="join_btn">
                                                                        <p>{{ Str::substr(getUserEventTimezone($event->event_start_date.' '.$event->event_start_hour),10) }}
                                                                            To
                                                                            {{ Str::substr(getUserEventTimezone($event->event_start_date.' '.$event->event_end_hour),10) }}
                                                                        </p>
                                                                    </a>
                                                                @elseif(date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_start_hour &&
                                                                        date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_end_hour)
                                                                        <p>{{ Str::substr(getUserEventTimezone($event->event_start_date.' '.$event->event_start_hour),10) }}
                                                                            To
                                                                            {{ Str::substr(getUserEventTimezone($event->event_start_date.' '.$event->event_end_hour),10) }}
                                                                        </p>
                                                                @else
                                                                    <a href="{{ $event->event_social_url }}" target="_blank"
                                                                        id="join_btn">
                                                                        <p>{{ Str::substr(getUserEventTimezone($event->event_start_date.' '.$event->event_start_hour),10) }}
                                                                            To
                                                                            {{ Str::substr(getUserEventTimezone($event->event_start_date.' '.$event->event_end_hour),10) }}
                                                                        </p>
                                                                    </a>
                                                                @endif

                                                            </div>
                                                            <div class="event_schedule_date" id="join_event">
                                                                <img src="{{ asset('frontend/images/video.png') }}"
                                                                    alt="" />
                                                                @if ($event->event_start_date < date('Y-m-d'))
                                                                    <p class="info">Event Ended</p>
                                                                @elseif(date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_start_hour &&
                                                                        date('Y-m-d H:i:s') < $event->event_start_date . ' ' . $event->event_end_hour)
                                                                    <a href="{{ $event->event_social_url }}" target="_blank"
                                                                        id="join_btn">
                                                                        <p class="info">Join</p>
                                                                    </a>
                                                                @elseif(date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_start_hour &&
                                                                        date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_end_hour)
                                                                    <p class="info">Event Ended</p>
                                                                @else
                                                                    <a href="{{ $event->event_social_url }}" target="_blank"
                                                                        id="join_btn">
                                                                        <p class="info">Not Started</p>
                                                                    </a>
                                                                @endif
                                                            </div>
                                                            <div class="event_schedule_date_set">
                                                                <img src="{{ asset('frontend/images/event_description.png') }}"
                                                                    alt="" />
                                                                <div class="read_more">
                                                                    @if (strlen($event->event_description) > 140)
                                                                        <input type="checkbox" class="read-more-state"
                                                                            id="post-{{ $count }}" />
                                                                    @endif
                                                                    <p class="read-more-wrap">
                                                                        {!! Str::limit($event->event_description, $limit = 140, $end = '') !!}<span
                                                                            class="read-more-target">{{ substr($event->event_description, $limit) }}</span>
                                                                    </p>

                                                                    @if (strlen($event->event_description) > 140)
                                                                        <label for="post-{{ $count }}"
                                                                            class="read-more-trigger"></label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xl-2">
                                                            @php
                                                                $event_start_date = Str::substr(getUserEventTimezone($event->event_start_date.' '.$event->event_start_hour),0,-9);
                                                                $event_date_format = Str::limit(\Carbon\Carbon::parse($event_start_date)->format('m/d/Y'));
                                                            @endphp
                                                            @if ($event->event_start_date < date('Y-m-d'))
                                                                <div class="event_date_set">
                                                                    <div class="grid_set_event">
                                                                        <div class="event_schedule_time">
                                                                            <img src="{{ asset('frontend/images/time.png') }}"
                                                                                alt="" />
                                                                        </div>
                                                                        <p class="date" style="font-size:22px !important;">                                                                            
                                                                            {{ $event_date_format }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @elseif(date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_start_hour &&
                                                                    date('Y-m-d H:i:s') < $event->event_start_date . ' ' . $event->event_end_hour)
                                                                <a href="{{ $event->event_social_url }}" target="_blank" id="join_btn">
                                                                    <div class="event_date_set">
                                                                        <div class="grid_set_event">
                                                                            <div class="event_schedule_time">
                                                                                <img src="{{ asset('frontend/images/time.png') }}"
                                                                                    alt="" />
                                                                            </div>
                                                                            <p class="date" style="font-size:22px !important;">                                                                            
                                                                                {{ $event_date_format }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            @elseif(date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_start_hour &&
                                                                    date('Y-m-d H:i:s') > $event->event_start_date . ' ' . $event->event_end_hour)
                                                                <div class="event_date_set">
                                                                    <div class="grid_set_event">
                                                                        <div class="event_schedule_time">
                                                                            <img src="{{ asset('frontend/images/time.png') }}"
                                                                                alt="" />
                                                                        </div>
                                                                        <p class="date" style="font-size:22px !important;">                                                                            
                                                                            {{ $event_date_format }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <a href="{{ $event->event_social_url }}" target="_blank" id="join_btn">
                                                                    <div class="event_date_set">
                                                                        <div class="grid_set_event">
                                                                            <div class="event_schedule_time">
                                                                                <img src="{{ asset('frontend/images/time.png') }}"
                                                                                    alt="" />
                                                                            </div>
                                                                            <p class="date" style="font-size:22px !important;">                                                                            
                                                                                {{ $event_date_format }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- /container -->
                                </div>
                                @php $count++; @endphp
                            @endforeach                        
                    </div>
                </div>
                <div class="row justify-content-center mt-5">
                    {!! $all_events->links('pagination::bootstrap-4') !!}
                </div>
                @else
                <div class="row">
                    <div class="col-md-12">            
                        <p style="color:black;text-align:center;">{{ "No Event Found!" }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

@endsection

@section('scripts')

    @if ($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <!-- Youtube Background for Header -->
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif
    <script>
        $(document).ready(function() {

            "use strict";

            @if ($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
                /**
                 * Start Initial Youtube Background
                 */
                $("[data-youtube]").youtube_background();
                /**
                 * End Initial Youtube Background
                 */
            @endif

        });
    </script>  
@endsection
