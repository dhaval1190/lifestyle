@extends('frontend.layouts.app')

@section('styles')
    @if ($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif
    <link href="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
    <script src="https://www.google.com/recaptcha/api.js"></script>
@endsection

<style>
    .disabled-login-error{
        color:red;
    }
    .error{
        color:red;
    }
    .upper_logo_padding_25 {
    white-space: pre-wrap;
    }
        .listing .lh-content {
            text-align: left !important;

        }

        .box-video {
            position: relative;
            width: 100%;
            margin: 0 auto 20px auto;
            cursor: pointer;
            overflow: hidden;
        }

        /* Set Cover aka Background-Image */
        .box-video .bg-video {
            position: absolute;
            background-color: #000f24;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            z-index: 2;
            opacity: 1;
        }

        /* Add light shade to make play button visible*/
        .bg-video::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.1);
        }


        /* The Play-Button using CSS-Only */
        .bt-play {
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -30px 0 0 -30px;
            display: inline-block;
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-indent: -999em;
            cursor: pointer;
            z-index: 2;
            -webkit-transition: all .3s ease-out;
            transition: all .3s ease-out;
        }

        /* The Play-Triangle */
        .bt-play:after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            height: 0;
            width: 0;
            margin: -12px 0 0 -6px;
            border: solid transparent;
            border-left-color: #000;
            border-width: 12px 20px;
            -webkit-transition: all .3s ease-out;
            transition: all .3s ease-out;
        }

        .c-video__image-container:hover .bt-play {
            transform: scale(1.1);
        }

        /* When Class added the Cover gets hidden... */
        .box-video.open .bg-video {
            visibility: hidden;
            opacity: 0;
            -webkit-transition: all .6s .8s;
            transition: all .6s .8s;
        }

        /* and iframe shows up */
        .box-video.open .video-container {
            opacity: 1;
            -webkit-transition: all .6s .8s;
            transition: all .6s .8s;
        }

        /* Giving the div ratio of 16:9 with padding */
        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            margin: 0;
            z-index: 1;
            padding-bottom: 56.27198%;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }


        .c-header-in {
            position: absolute;
            left: 42vw;
            right: 0;
            top: 50px;
            color: white;
        }

        .vid-fit-user {
            height: 380;
            background-color: #000f24;
            position: relative;
        }

        .vid-fit-reveal {
            height: 215;
            background-color: #000f24;
            position: relative;
        }

        .vid-fit-reveal.red {
            background: red;
        }

        .vid-fit-reveal.reveal-video {
            background: none;
        }

        .vid-fit-reveal.reveal-video .c-header-in {
            display: none;
        }

        .vid-fit-reveal.reveal-video #vid-reveal {
            display: block;
            visibility: visible;
        }

        .vid-fit-reveal.reveal-video .c-video__play-btn {
            visibility: hidden;
        }

        .vid-fit-reveal #vid-reveal {
            visibility: hidden;
        }

        .c-video__play-btn {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
        }

        .table> :not(:last-child)> :last-child>* {
            border-bottom-color: inherit;
            border-right: 1px solid #fff;
            border-right-width: thin;
        }

        table.dataTable>thead>tr>td:not(.sorting_disabled),
        table.dataTable>thead>tr>th:not(.sorting_disabled) {
            border-bottom-color: inherit;
            border-right: 1px solid #fff;
            border-right-width: thin;
        }

        .w-30 {
            width: 30px !important;
        }

        .address_wrap {
            word-break: break-all;
        }

        .form-section {
            display: none;
        }

        .form-section.current {
            display: inline;
        }

        .parsley-errors-list {
            color: red;
        }
</style>
@section('content')
    <!-- <div class="site-section"> -->
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12 col-12 p-0">
                <div class="upper_white_bg">
                    <div class="upper_middle_img">                    
                        @if (empty($user_detail['user_cover_image']))
                            <div class="site-blocks-cover inner-page-cover main_logo"
                                style="background-image: url( {{ asset('frontend/images/main_upper_logo.png') }});">
                            </div>
                        @else
                            <div class="site-blocks-cover inner-page-cover main_logo"
                                style="background-image: url( {{ Storage::disk('public')->url('user/' . $user_detail['user_cover_image']) }});">
                                {{-- <div class="site-blocks-cover inner-page-cover overlay main_logo" style="background-image: url( {{ Storage::url('user/'. $user_detail['user_cover_image']) }});"> --}}
                            </div>
                        @endif                      

                        <div class="upper_main_section">
                            <div class="upper_logo">
                                <div class="logo_star">
                                    @if (empty($user_detail['user_image']))
                                        {{-- <img src="{{ asset('backend/images/placeholder/profile-' . intval($user_detail['id'] % 10) . '.webp') }}"
                                            class="logo"> --}}
                                        <img src="{{ asset('backend/images/placeholder/profile_default.webp') }}"
                                            class="logo">
                                    @else
                                        <img src="{{ Storage::disk('public')->url('user/' . $user_detail['user_image']) }}"
                                            class="logo">
                                    @endif
                                    <div class="rating_stars_header"></div>
                                    @if ($item_count_rating > 0)
                                        <span class="item-cover-address-section">
                                            @if ($item_count_rating == 1)
                                                {{ '(' . $item_count_rating . ' ' . __('review.frontend.review') . ')' }}
                                            @else
                                                {{ '(' . $item_count_rating . ' ' . __('review.frontend.reviews') . ')' }}
                                            @endif
                                        </span>
                                    @endif
                                </div>

                                <div class="space_between">
                                    <div class="upper_logo_details">
                                        <h1>{{ $user_detail['name'] }}</h1>
                                        <p>{{ !empty($user_detail['working_type']) ? \App\User::WORKING_TYPES[$user_detail['working_type']] : '' }}
                                            {{ !empty($user_detail['experience_year']) ? ' | ' . \App\User::EXPERIENCE_YEARS[$user_detail['experience_year']] . ' Years' : '' }}
                                            {{ !empty($user_detail['hourly_rate_type']) ? ' | ' . \App\User::HOURLY_RATES[$user_detail['hourly_rate_type']] : '' }}
                                        </p>
                                        <div class="details">
                                            <div class="detail one">
                                                <!-- <img src="{{ asset('frontend/images/bag-one.svg') }}" alt=""> -->
                                                <img src="{{ asset('frontend/images/bag.svg') }}" alt="">
                                                <p>{{ $user_detail['company_name'] }}</p>
                                            </div>
                                            <div class="detail two">
                                                <img src="{{ asset('frontend/images/blue-bag.svg') }}" alt="">
                                                <p>{{ !empty($user_detail['preferred_pronouns']) ? \App\User::PREFERRED_PRONOUNS[$user_detail['preferred_pronouns']] : '' }}
                                                </p>
                                            </div>
                                            @if (isset(Auth::user()->id) && !empty(Auth::user()->id) && $user_detail['id'] == Auth::user()->id)
                                                <div class="detail two">
                                                    <i
                                                        class="fas fa-share-alt item-share-refferal-button"style="cursor: pointer;"></i>
                                                </div>
                                            @endif
                                            <div class="detail one padding-0">
                                                <img src="{{ asset('frontend/images/eye.svg') }}" alt="">
                                                <p style="cursor: pointer;"
                                                    onclick="window.location='{{ url('visitor-view/' . encrypt($user_detail->id)) }}'"
                                                    id="eye">
                                                    <b>Total Visitor(s)</b> : {{ $All_visit_count }}
                                                </p>
                                            </div>
                                            <div class="both_btn_set_small_device">
                                                <?php
                                                $userId = '';
                                                if (isset(Auth::user()->id)) {
                                                    $userId = Auth::user()->id ? Auth::user()->id : '';
                                                }
                                                ?>
                                                @if ($user_detail->id == $userId)
                                                <a class="btn btn-primary rounded text-white item-share-refferal-button"><i
                                                    class="fas fa-share-alt"></i> {{ __('frontend.item.share') }}</a>
                                                @endif
                                                @if ($user_detail->id != $userId)
                                                    <a class="btn btn-primary rounded text-white item-contact-button"><i
                                                            class="fas fa-phone-alt"></i>
                                                        {{ __('Contact This Coach') }}</a>
                                                        <a class="btn btn-primary rounded text-white item-share-button"><i
                                                                class="fas fa-share-alt"></i> {{ __('frontend.item.share') }}</a>
                                                @endif
                                                @guest
                                                    <a class="btn btn-primary rounded text-white item-review-button"><i
                                                            class="fas fa-star"></i> Write a Review</a>
                                                @else
                                                    @if ($user_detail->id != Auth::user()->id)
                                                        @if (Auth::user()->isAdmin())
                                                            @if ($review_count->profilereviewedByUser(Auth::user()->id, $user_detail->id))
                                                                <a class="btn btn-primary rounded text-white"
                                                                    href="{{ route('admin.page.reviews.edit', $Auth_review->id) }}"
                                                                    target="_blank"><i class="fas fa-star"></i>
                                                                    {{ __('review.backend.edit-a-review') }}</a>
                                                            @else
                                                                <button
                                                                    class="btn btn-primary rounded text-white item-review-button"><i
                                                                        class="fas fa-star"></i> Write a Review</button>
                                                            @endif
                                                        @else
                                                            @if ($review_count->profilereviewedByUser(Auth::user()->id, $user_detail->id))
                                                                <a class="btn btn-primary rounded text-white"
                                                                    href="{{ route('user.page.reviews.edit', $Auth_review->id) }}"
                                                                    target="_blank"><i class="fas fa-star"></i>
                                                                    {{ __('review.backend.edit-a-review') }}</a>
                                                            @else
                                                                <button
                                                                    class="btn btn-primary rounded text-white item-review-button"><i
                                                                        class="fas fa-star"></i> Write a Review</button>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endguest

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @if ($user_detail->id == $userId)
                                    <div class="upper_address_info">
                                        <h3>Address</h3>
                                        <div class="upper_address_detail">
                                            <div class="upper_address">
                                                <img src="{{ asset('frontend/images/map_icon.svg') }}" alt=""
                                                    class="address_logo" />
                                                <p class="address_wrap">
                                                    {{-- @if ($user_detail->id == $userId)                                                                 --}}
                                                    {{ !empty($user_detail['address']) ? $user_detail['address'] . ',' : '' }}
                                                    {{ !empty($user_detail->city->city_name) ? $user_detail->city->city_name . ',' : '' }}
                                                    {{ !empty($user_detail->state->state_name) ? $user_detail->state->state_name . ',' : '' }}
                                                    {{ !empty($user_detail->country->country_name) ? $user_detail->country->country_name : '' }}
                                                    {{ $user_detail['post_code'] }}
                                                    {{-- @else
                                                                {{ !empty($user_detail->city->city_name) ? $user_detail->city->city_name . ',' : '' }}
                                                                {{ !empty($user_detail->state->state_name) ? $user_detail->state->state_name : '' }}                                                                --}}
                                                    {{-- @endif --}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                               
                                <!-- <div class="progress">
                                                                <div class="progress-bar" role="progressbar" style="width: {{ $progress_data['percentage'] }}%"
                                                                    aria-valuenow="{{ $progress_data['percentage'] }}" aria-valuemin="0" aria-valuemax="100" title="{{ $progress_data['profile'] }}">
                                                                    &nbsp;{{ $progress_data['percentage'] }}%
                                                                </div>
                                                            
                                                </div> -->
                            </div>
                        </div>
                        <div class="alert alert-success alert-dismissible fade show" id="register_success_error_div" role="alert" style="display:none;margin:4px;">
                            <span id="register_success">Review Added SuccessFull</span>
                        </div>
                        <div class="alert alert-success alert-dismissible fade show col-6" id="share_modal_success_error_div" role="alert" style="display:none;margin:4px;">
                            <span id="share_modal_success">Shared SuccessFull</span>
                        </div>
                        <div class="alert alert-success alert-dismissible fade show col-6" id="contactForm_success_error_div" role="alert" style="display:none;margin:4px;">
                            <span id="contactForm_success">Contact To This Coach SuccessFull</span>
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show" id="register_error_div" role="alert" style="display:none;margin:4px;">
                            <span id="register_s">Review Not Added</span>
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show col-6" id="share_modal_error_div" role="alert" style="display:none;margin:4px;">
                            <span id="share_modal_">Shared SuccessFull</span>
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show col-6" id="contactForm_error_div" role="alert" style="display:none;margin:4px;">
                            <span id="contactForm_">Contact To This Coach SuccessFull</span>
                        </div>
                        @if(!empty($user_detail['user_about']))
                        <div class="upper_logo_info">
                            <div class="upper_logo_padding_25">
                                <h3>About</h3>
                                <p>{{ $user_detail['user_about'] }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>

    <div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="contact-modal"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="exampleModalLongTitle" style="color: black;">{{ __('Contact This Coach') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>{{ __('frontend.item.share-listing-email') }}</h5>
                            <p>{{ __('Please answer the following question so this coach can determine if they are uniquely qualified to best help you and to better prepare them for a potential initial conversation.') }}
                            </p>
                            {{-- @if (!Auth::check())
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ __('frontend.item.login-require') }}
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
                            <div class="nav nav-fill my-3">
                                <label class="nav-link shadow-sm step0 steps  border  ">Step One</label>
                                <label class="nav-link shadow-sm step1 steps  border  ">Step Two</label>
                                <label class="nav-link shadow-sm step2 steps  border  ">Step Three</label>
                                <label class="nav-link shadow-sm step3 steps  border  ">Step Four</label>
                                <label class="nav-link shadow-sm step4 steps  border  ">Step Five</label>
                                <label class="nav-link shadow-sm step5 steps  border  ">Step Six</label>
                            </div>
                            <form action="{{ route('page.item.contact', ['item_slug' => $item->item_slug]) }}"
                                method="POST" name="contactFormModal" id="contactFormModal" class="contact-coach-form">
                                @csrf
                                <input type="hidden" name="hexId" value="{{ $hexId }}">
                                <input type="hidden" name="contact_profile" value="contact_profile">
                                <input type="hidden" name="userId" value="{{ $user_detail->id }}">
                                <input type="hidden" name="authUserId"
                                    value="{{ isset(auth()->user()->id) ? auth()->user()->id : '' }}">
                                <input type="hidden" id="new_user" name="new_user" value="">

                                <div class="step-01 step_class">
                                    <div class="form-section">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6 pl-0">
                                                    <label for="item_conntact_email_name"
                                                        class="text-black">{{ __('frontend.item.name') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input id="item_conntact_email_name" type="text"
                                                        class="form-control @error('item_conntact_email_name') is-invalid @enderror"
                                                        name="item_conntact_email_name"
                                                        value="{{ isset(auth()->user()->name) ? auth()->user()->name : old('item_conntact_email_name') }}" required>
                                                    <p class="name_error error_color" role="alert"></p>
                                                    @error('item_conntact_email_name')
                                                        <span class="invalid-tooltip">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6 pl-0">
                                                    <label for="item_contact_email_from_email"
                                                        class="text-black">{{ __('frontend.item.email') }}<span
                                                            class="text-danger">*</span></label>
                                                    <input id="item_contact_email_from_email" type="email"
                                                        class="form-control @error('item_contact_email_from_email') is-invalid @enderror"
                                                        name="item_contact_email_from_email"
                                                        value="{{ isset(auth()->user()->email) ? auth()->user()->email : old('item_contact_email_from_email') }}" required>
                                                    <p class="email_error error_color" role="alert"></p>
                                                    @error('item_contact_email_from_email')
                                                        <span class="invalid-tooltip">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label for="Question 1"
                                                    class="text-black">{{ __('1. What are the top 2 challenges you feel this coach can help you navigate?') }}<span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control @error('question1') is-invalid @enderror" id="question1_txt" rows="3"
                                                    name="question1" required>{{ old('question1') }}</textarea>
                                                <p class="question1_error error_color_modal" role="alert"></p>
                                                <p class="question1_desc_char_count count_error"></p>
                                                @error('question1')
                                                    <span class="invalid-tooltip">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-section col-md-12">
                                            <label
                                                for="Question 2">{{ __('2.What type of personality traits would be helpful for a person to have when coaching you?') }}<span
                                                    class="text-danger">*</span>
                                                <p class="pl-3">Check all that apply fields:</p>
                                            </label>
                                            {{-- <textarea name="question2" id="question2_txt" class="form-control mb-3" cols="30" rows="5" required></textarea> --}}
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="question2[]"
                                                    value="Patient" id="question2_checkbox1" required>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    a) Patient
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="question2[]"
                                                    value="Diplomatic" id="question2_checkbox2" required>
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    b) Diplomatic
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="question2[]"
                                                    value="Direct" id="question2_checkbox3" required>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    c) Direct
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="question2[]"
                                                    value="Sensitive" id="question2_checkbox4" required>
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    d) Sensitive
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="question2[]"
                                                    value="High Energy" id="question2_checkbox5" required>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    e) High Energy
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="question2[]"
                                                    value="Calm" id="question2_checkbox6" required>
                                                <label class="form-check-label" for="flexCheckChecked">
                                                    f) Calm
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="question_2">
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    g) Other
                                                </label>
                                                <textarea name="question2[]" class="form-control mb-3" cols="30" rows="5" id="question2_txt"
                                                    style="display:none"></textarea>
                                            </div>
                                            <p class="question2_error error_color_modal" role="alert"></p>
                                            <p class="question2_desc_char_count count_error"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-section col-md-12 ">
                                            <label
                                                for="Question 3">{{ __('3.What specific training, expertise and industry knowledge is important for this coach to possess?') }}<span
                                                    class="text-danger">*</span></label>
                                            <textarea name="question3" id="question3_txt" class="form-control mb-3" cols="30" rows="5" required></textarea>
                                            <p class="question3_error error_color_modal" role="alert"></p>
                                            <p class="question3_desc_char_count count_error"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-section col-md-12 ">
                                            <label
                                                for="Question 4">{{ __('4.On a scale of 1-10 how structured do you want your coaching experience?') }}<span
                                                    class="text-danger">*</span></label><br>
                                            <input type="radio" id="question4" name="question4" value="1"
                                                required><label for="question4"><span class="pl-2"> 1</span></label><br>
                                            <input type="radio" id="question4" name="question4" value="2"
                                                required><label for="question4"><span class="pl-2"> 2</span></label><br>
                                            <input type="radio" id="question4" name="question4" value="3"
                                                required><label for="question4"><span class="pl-2"> 3</span></label><br>
                                            <input type="radio" id="question4" name="question4" value="4"
                                                required><label for="question4"><span class="pl-2"> 4</span></label><br>
                                            <input type="radio" id="question4" name="question4" value="5"
                                                required><label for="question4"><span class="pl-2"> 5</span></label><br>
                                            <input type="radio" id="question4" name="question4" value="6"
                                                required><label for="question4"><span class="pl-2"> 6</span></label><br>
                                            <input type="radio" id="question4" name="question4" value="7"
                                                required><label for="question4"><span class="pl-2"> 7</span></label><br>
                                            <input type="radio" id="question4" name="question4" value="8"
                                                required><label for="question4"><span class="pl-2"> 8</span></label><br>
                                            <input type="radio" id="question4" name="question4" value="9"
                                                required><label for="question4"><span class="pl-2"> 9</span></label><br>
                                            <input type="radio" id="question4" name="question4" value="10"
                                                required><label for="question4"><span class="pl-2">10</span></label><br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-section col-md-12 ">
                                            <label
                                                for="Question 5">{{ __('5.If you invest your time and money with this coach, what is the single biggest change you hope to achieve?') }}<span
                                                    class="text-danger">*</span></label>
                                            <textarea name="question5" id="question5_txt" class="form-control mb-3" cols="30" rows="5" required></textarea>
                                            <p class="question5_error error_color_modal" role="alert"></p>
                                            <p class="question5_desc_char_count count_error"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-section col-md-12 ">
                                            <label
                                                for="Question 6">{{ __('6.Was there a particular Blog post, Podcast, Video, e-Book, etc that helped you select this coach? If so please share the name of it.') }}<span
                                                    class="text-danger">*</span></label>
                                            <textarea name="question6" id="question6_txt" class="form-control mb-3" cols="30" rows="5" required></textarea>
                                            <p class="question6_error error_color_modal" role="alert"></p>
                                            <p class="question6_desc_char_count count_error"></p>
                                            <div class="g-recaptcha" data-sitekey="6LeXpRIpAAAAANR5q7jXCepgrSKbM91QWgLumZXc"></div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 form-navigation">
                                            {{-- @if (Auth::check()) --}}
                                                <button type="button" class="previous btn btn-primary float-left mt-2">&lt;
                                                    Previous</button>
                                                <button type="button" class="next btn btn-primary float-right mt-2">Next
                                                    &gt;</button>
                                                <button type="submit" id="kt_login_step_01"
                                                    class="btn btn-primary py-2 px-4 mt-2 text-white rounded float-right">
                                                    {{ __('frontend.item.send-email') }}
                                                </button>
                                            {{-- @endif --}}
                                            <span class="please_wait">Please Wait..</span>
                                            {{-- @if (!Auth::user())
                                                <a
                                                    href="{{ route('login') }}"class="btn btn-primary px-4 text-white rounded float-right mt-2">
                                                    {{ __('Login') }}
                                                </a>
                                            @endif --}}
                                        </div>
                                    </div>
                                </div>    
                                <div class="step-02 step_class" style="display:none">
                                    <!-- <div class="fv-row mb-10">
                                        <label class="form-label fw-bold6er text-dark fs-6">Enter OTP sent to your email address</label>
                                        <input class="form-control otp" id="otp" type="text" placeholder="" name="otp" autocomplete="off" required />
                                        <div id="otp_val" class="error"></div>
                                    </div> -->
                                    <div class="text-center mb-10">
                                        <h1 class="text-dark mb-3 verification-text">
                                            Two-Factor Verification
                                        </h1>
                                        <div class="fw-semibold fs-5 mb-5">Enter the verification code we sent to your email</div>
                                    </div>
                                    <div class="fv-row mb-10">
                                        <!-- <div class="fw-bold text-start text-dark fs-6 mb-1 ms-1">Type your 6 digit otp</div> -->
                                        {{-- <div class="d-flex flex-wrap flex-stack">
                                            <input type="text" name="code_1" id="code_1" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                            <input type="text" name="code_2" id="code_2" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                            <input type="text" name="code_3" id="code_3" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                            <input type="text" name="code_4" id="code_4" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                            <input type="text" name="code_5" id="code_5" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                            <input type="text" name="code_6" id="code_6" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1" class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" autocomplete="off" value=""/>
                                        </div>--}}
                                        <div class="d-flex ap-otp-inputs justify-content-between" data-channel="email" data-length="6" data-form="login">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="0" id="code_1">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="1" id="code_2">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="2" id="code_3">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="3" id="code_4">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="4" id="code_5">
                                            <input class="ap-otp-input form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" type="tel" maxlength="1" data-index="5" id="code_6">
                                        </div>
                                        <div id="otp_val" class="error"></div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="fw-semibold fs-5 d-flex w-100 justify-content-center fw-semibold fs-5 align-items-center">
                                        <span class="text-muted me-1" id="resend_login_otp_title">Didn't get the otp ?</span>
                                            <a href="#" id="resend_login_otp" class="link-primary fs-5 me-1 pl-1">
                                            <span class="indicator-label" style="margin-left: 10px;">{{ __('Resend') }}</span>
                                            </a>
                                            <span class="indicator-progress pl-1">Please wait...
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                        
                                            <div class="login_otp_countdown fw-semibold fs-5 pl-1" style="margin-left:10px;"></div>
                                        </div>
                                    </div>
                                    <div class="pb-lg-0 pb-8 mt-3">
                                        <button type="button" id="kt_login_step_02" class="btn btn-lg btn-info w-100 mb-5">
                                        {{ __('frontend.item.send-email') }}
                                        </button>
                                        </div>
                                    <a href="#" id="kt_login_prev_step_01" class="d-flex w-100 justify-content-center link-primary fs-5 me-1">
                                        <span class="indicator-label">{{ __('Back') }}</span>
                                            <!-- <span class="indicator-progress">Please wait...
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span> -->
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded"
                        data-bs-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal - share -->
    <div class="modal fade" id="share-modal" tabindex="-1" role="dialog" aria-labelledby="share-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="exampleModalLongTitle" style="color: black;">{{ __('frontend.item.share-listing') }}
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-12">

                            <p>{{ __('frontend.item.share-listing-social-media') }}</p>

                            <!-- Create link with share to Facebook -->
                            <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-facebook" href=""
                                data-social="facebook">
                                <i class="fab fa-facebook-f"></i>
                                {{ __('social_share.facebook') }}
                            </a>
                            <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-twitter" href=""
                                data-social="twitter">
                                <i class="fab fa-twitter"></i>
                                {{ __('social_share.twitter') }}
                            </a>
                            <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-linkedin" href=""
                                data-social="linkedin">
                                <i class="fab fa-linkedin-in"></i>
                                {{ __('social_share.linkedin') }}
                            </a>
                            <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-blogger" href=""
                                data-social="blogger">
                                <i class="fab fa-blogger-b"></i>
                                {{ __('social_share.blogger') }}
                            </a>
                            {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-pinterest" href=""
                                data-social="pinterest">
                                <i class="fab fa-pinterest-p"></i>
                                {{ __('social_share.pinterest') }}
                            </a> --}}
                            {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-evernote" href="" data-social="evernote">
                            <i class="fab fa-evernote"></i>
                            {{ __('social_share.evernote') }}
                        </a> --}}
                            <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-reddit" href=""
                                data-social="reddit">
                                <i class="fab fa-reddit-alien"></i>
                                {{ __('social_share.reddit') }}
                            </a>
                            {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-buffer" href="" data-social="buffer">
                            <i class="fab fa-buffer"></i>
                            {{ __('social_share.buffer') }}
                        </a> --}}
                            {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wordpress" href="" data-social="wordpress">
                            <i class="fab fa-wordpress-simple"></i>
                            {{ __('social_share.wordpress') }}
                        </a> --}}
                            {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-weibo" href="" data-social="weibo">
                            <i class="fab fa-weibo"></i>
                            {{ __('social_share.weibo') }}
                        </a> --}}
                            <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-skype" href=""
                                data-social="skype">
                                <i class="fab fa-skype"></i>
                                {{ __('social_share.skype') }}
                            </a>
                            <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-telegram" href=""
                                data-social="telegram">
                                <i class="fab fa-telegram-plane"></i>
                                {{ __('social_share.telegram') }}
                            </a>
                            {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-viber" href="" data-social="viber">
                            <i class="fab fa-viber"></i>
                            {{ __('social_share.viber') }}
                        </a> --}}
                            <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-whatsapp" href=""
                                data-social="whatsapp">
                                <i class="fab fa-whatsapp"></i>
                                {{ __('social_share.whatsapp') }}
                            </a>
                            <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wechat" href=""
                                data-social="wechat">
                                <i class="fab fa-weixin"></i>
                                {{ __('social_share.wechat') }}
                            </a>
                            {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-line" href="" data-social="line">
                            <i class="fab fa-line"></i>
                            {{ __('social_share.line') }}
                        </a> --}}

                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <p>{{ __('frontend.item.share-listing-email') }}</p>
                            {{-- @if (!Auth::check())
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ __('frontend.item.login-require') }}
                                        </div>
                                    </div>
                                </div>
                            @endif --}}
                            <form action="{{ route('page.emailProfile.email', ['profile_slug' => $hexId]) }}"
                                method="POST" name="shareProfileModal" id="shareProfileModal">
                                <input type="hidden" name="userId" value="{{ $user_detail->id }}">
                                @csrf
                                <div class="form-row mb-3">
                                    <div class="col-md-4">
                                        <label for="profile_share_email_name"
                                            class="text-black">{{ __('frontend.item.name') }}<span
                                                class="text-danger">*</span></label>
                                        <input id="profile_share_email_name" type="text"
                                            class="form-control @error('profile_share_email_name') is-invalid @enderror"
                                            name="profile_share_email_name"
                                            value="{{ isset(auth()->user()->name) ? auth()->user()->name : old('item_conntact_email_name') }}">
                                        <p class="profile_name_error error_color" role="alert"></p>
                                        @error('profile_share_email_name')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="profile_share_email_from_email"
                                            class="text-black">{{ __('frontend.item.email') }}<span
                                                class="text-danger">*</span></label>
                                        <input id="profile_share_email_from_email" type="email"
                                            class="form-control @error('profile_share_email_from_email') is-invalid @enderror"
                                            name="profile_share_email_from_email"
                                            value="{{ isset(auth()->user()->email) ? auth()->user()->email : old('profile_share_email_from_email') }}">
                                        <p class="profile_from_email_error error_color" role="alert"></p>
                                        @error('profile_share_email_from_email')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="profile_share_email_to_email"
                                            class="text-black">{{ __('frontend.item.email-to') }}<span
                                                class="text-danger">*</span></label>
                                        <input id="profile_share_email_to_email" type="email"
                                            class="form-control @error('profile_share_email_to_email') is-invalid @enderror"
                                            name="profile_share_email_to_email"
                                            value="{{ old('profile_share_email_to_email') }}">
                                        <p class="profile_to_error error_color" role="alert"></p>
                                        @error('profile_share_email_to_email')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <label for="profile_share_email_note"
                                            class="text-black">{{ __('frontend.item.add-note') }}<span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('profile_share_email_note') is-invalid @enderror" id="profile_share_email_note"
                                            rows="3" name="profile_share_email_note">{{ old('profile_share_email_note') }}</textarea>
                                        <p class="profile_note_error error_color" role="alert"></p>
                                        @error('profile_share_email_note')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <button type="submit"class="btn btn-primary py-2 px-4 text-white rounded">
                                            {{ __('frontend.item.send-email') }}
                                        </button>
                                        <span class="please_wait">Please Wait..</span>
                                        {{-- @if (!Auth::user())
                                            <a
                                                href="{{ route('login') }}"class="btn btn-primary px-4 text-white rounded float-right">
                                                {{ __('Login') }}
                                            </a>
                                        @endif --}}
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded"
                        data-bs-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="review-modal" tabindex="-1" role="dialog"
        aria-labelledby="share-modal"aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="color: black;">Select Your Rating</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            @if (!Auth::check())
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ __('frontend.item.login-require') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (!Auth::check() || Auth::user()->isAdmin())
                                <form method="POST" action="{{ route('admin.page.reviews.store') }}"
                                    name="review_form" id="review_form">
                                @else
                            @endif
                            <form method="POST" action="{{ route('user.page.reviews.store') }}" name="review_form"
                                id="review_form">
                                @csrf
                                <input type="hidden" value="{{ $user_detail['id'] }}" name="id">
                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <span
                                            class="text-lg text-gray-800">{{ __('review.backend.select-rating') }}</span>
                                        <small class="form-text text-muted">
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <label for="rating"
                                            class="text-black">{{ __('review.backend.overall-rating') }}</label><br>
                                        <!-- <select class="rating_stars" name="rating"  {{ Auth::check() ? '' : 'disabled' }} readonly>
                                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">{{ __('rating_summary.1-stars') }}</option>
                                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">{{ __('rating_summary.2-stars') }}</option>
                                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">{{ __('rating_summary.3-stars') }}</option>
                                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">{{ __('rating_summary.4-stars') }}</option>
                                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">{{ __('rating_summary.5-stars') }}</option>
                                                </select> -->
                                        <select class="selectpicker form-control" name="rating" id="rating"
                                            tabindex="null" {{ Auth::check() ? '' : 'disabled' }}>
                                            <p class="profile_review_rating_error error_color" role="alert"></p>
                                            <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}">
                                                {{ __('rating_summary.1-stars') }}</option>
                                            <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}">
                                                {{ __('rating_summary.2-stars') }}</option>
                                            <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}">
                                                {{ __('rating_summary.3-stars') }}</option>
                                            <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}">
                                                {{ __('rating_summary.4-stars') }}</option>
                                            <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}">
                                                {{ __('rating_summary.5-stars') }}</option>
                                        </select>
                                        @error('rating')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <span
                                            class="text-lg text-gray-800">{{ __('review.backend.tell-experience') }}</span>
                                        <small class="form-text text-muted">
                                        </small>
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <label for="body"
                                            class="text-black">{{ __('review.backend.description') }}<span
                                                class="text-danger">*</span></label>
                                        <!-- <textarea class="form-control @error('body') is-invalid @enderror" id="body"rows="3" name="body"
                                            {{ Auth::check() ? '' : 'disabled' }} readonly>{{ old('body') }}</textarea> -->
                                        <textarea class="form-control @error('body') is-invalid @enderror" id="body"rows="3" name="body"
                                            {{ Auth::check() ? '' : 'disabled' }}>{{ old('body') }}</textarea>
                                        <p class="profile_review_body_error error_color" role="alert"></p>

                                        @error('body')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input {{ old('recommend') == 1 ? 'checked' : '' }} class="form-check-input"
                                                type="checkbox" id="recommend" name="recommend" value="1"
                                                {{ Auth::check() ? '' : 'disabled' }} readonly>
                                            <label class="form-check-label" for="recommend">
                                                {{ __('review.backend.recommend') }}
                                            </label>
                                        </div>
                                        @error('recommend')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <!-- <div class="form-row mb-3">
                                            <div class="col-md-12">
                                                <span class="text-lg text-gray-800">{{ __('review_galleries.upload-photos') }}</span>
                                                <small class="form-text text-muted">
                                                    {{ __('review_galleries.upload-photos-help') }}
                                                </small>
                                                @error('review_image_galleries')
                                            <span class="invalid-tooltip">
                                                                                            <strong>{{ $message }}</strong>
                                                                                        </span>
                                        @enderror
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <button id="upload_gallery" type="button" class="btn btn-primary mb-2"  {{ Auth::check() ? '' : 'disabled' }} readonly>{{ __('review_galleries.choose-photo') }}</button>
                                                        <div class="row" id="selected-images">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                <div class="form-row mb-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success py-2 px-4 text-white"
                                            {{ Auth::check() ? '' : 'disabled' }} readonly>
                                            {{ __('review.backend.post-review') }}
                                        </button>
                                        <span class="please_wait">Please Wait..</span>
                                        @if (!Auth::user())
                                            <a
                                                href="{{ route('login') }}"class="btn btn-primary px-4 text-white rounded float-right">
                                                {{ __('Login') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    </div>
    <section class="middle">
        <div class="container">
            <div class="row">
                @if (isset($user_detail['website']))
                    <div class="col-md-6">
                        <div class="middle_bg">
                            <div class="middle_info">
                                <h3>Contact Details</h3>
                            </div>
                            <div class="middle_detail">
                                {{-- <div class="middle_main"><img src="{{ asset('frontend/images/email.svg') }}" alt="" />
                                                <p>{{ $user_detail['email'] }}</p>
                                            </div>
                                            <div class="middle_main"><img src="{{ asset('frontend/images/call.svg') }}" alt="" />
                                                <p>{{ $user_detail['phone'] }}</p>
                                            </div> --}}
                                <div class="middle_main"><img src="{{ asset('frontend/images/web.svg') }}"
                                        alt="" />
                                    <a href="{{ $user_detail['website'] }}" target="_blank">
                                        <p>{{ $user_detail['website'] }}</p>
                                    </a>
                                </div>
                            </div>
                            <div>
                                @if ($user_detail->id != $userId)
                                    <a class="btn btn-primary rounded text-white item-contact-button ml-50-set"><i
                                            class="fas fa-phone-alt"></i>
                                        {{ __('Contact This Coach') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if (isset($user_detail['linkedin']) || isset($user_detail['instagram']) || isset($user_detail['facebook']))
                    <div class="col-md-6 mt-3 mt-md-0">
                        <div class="middle_bg_two">
                            <div class="middle_info">
                                <h3>Social Links</h3>
                            </div>
                            <div class="middle_detail">
                                @if (isset($user_detail['linkedin']))
                                    <div class="middle_main"><img src="{{ asset('frontend/images/linkedin.svg') }}"
                                            alt="" />
                                        <a href="{{ $user_detail['linkedin'] }}" target="_blank">
                                            <p>{{ $user_detail['linkedin'] }}</p>
                                        </a>
                                    </div>
                                @endif
                                @if (isset($user_detail['instagram']))
                                    <div class="middle_main"><img src="{{ asset('frontend/images/instagram.svg') }}"
                                            alt="" />
                                        
                                    @if(stripos($user_detail['instagram'],'.com') !== false || stripos($user_detail['instagram'],'http') !== false || stripos($user_detail['instagram'],'https') !== false || stripos($user_detail['instagram'],'www.') !== false || stripos($user_detail['instagram'],'//') !== false)
                                        <a href="{{ $user_detail->instagram }}/"
                                            target="_blank">
                                            <p>{{ $user_detail['instagram'] }}</p>
                                        </a>
                                    @else
                                        <a href="https://instagram.com/_u/{{ $user_detail->instagram }}/"
                                            target="_blank">
                                            <p>{{ $user_detail['instagram'] }}</p>
                                        </a>
                                    @endif
                                        
                                    </div>
                                    

                                @endif
                                @if (isset($user_detail['facebook']))
                                    <div class="middle_main"><img src="{{ asset('frontend/images/facebook.svg') }}"
                                            alt="" />
                                        <a href="{{ $user_detail['facebook'] }}" target="_blank">
                                            <p>{{ $user_detail['facebook'] }}</p>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="below">
        <div class="container">
            <div class="below_bg">
                @if (isset($user_detail['youtube']) && !empty($user_detail['youtube']))
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="below_info">
                                <h3>Youtube Channel</h3>
                            </div>
                        </div>
                        {{-- <div class="col-lg-12 plr-45 padding_set_left_right">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="video">
                                        <div class='c-video__image-container vid-fit-user box-video-youtube'
                                            data-id="{{ $user_detail->id }}"
                                            data-vid="box-video-youtube_{{ $user_detail->id }}"
                                            id="#js-fitvideouser_{{ $user_detail->id }}">
                                            <a href="" target="_blank" class="bg-video"
                                                id="youtube_id_{{ $user_detail->id }}" data-toggle="modal"
                                                data-target="#youtubeModal" data-src="{{ $user_detail->youtube }}">
                                                <div class="bt-play" id="bt-playuser_{{ $user_detail->id }}"></div>
                                            </a>
                                            <div class="bt-play" id="bt-playuser_{{ $user_detail->id }}"></div>
                                            <iframe width="500" height="380" src="{{ $user_detail['youtube'] }}"
                                                frameborder="0" allowfullscreen id="vid-reveal" class="c-video-reveal"
                                                frameborder="0" allow="autoplay">
                                            </iframe>
                                        </div>

                                        <!-- <iframe width="500" height="380" src="{{ $user_detail['youtube'] }}"
                                                    title="YouTube video player" frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe> -->

                                        <!-- <div class='c-video__image-container vid-fit-reveal' id="js-fitvideo">
                                                  <div class="bt-play"></div> -->
                                        <!-- <iframe width="500" height="380" src="{{ $user_detail['youtube'] }}" frameborder="0" allowfullscreen id="vid-reveal" class="c-video-reveal" frameborder="0" allow="autoplay"></iframe> -->
                                        <!-- </div> -->
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="video_info">
                                        <h3>{{ $user_detail['youtube_intro_title'] }}</h3>
                                        <p>
                                            {{ str_limit($user_detail['youtube_intro_description'], 800, '...') }}
                                        </p>
                                        @php
                                            $length = strlen($user_detail['youtube_intro_description']);
                                        @endphp
                                        @if ($length >= 800 && !empty($user_detail['youtube_intro_title']))
                                            <a href="{{ route('page.profile.youtube', encrypt($user_detail['id'])) }}">Read
                                                More</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div> --}}
                        <div class="col-lg-12 plr-45 padding_set_left_right mb-2">
                            <div class="row">
                                <div class="col-lg-6">
                                    <a href="{{ $user_detail['youtube'] }}" class="btn btn-primary rounded" target="_blank">
                                        {{ __('Click to visit the channel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($video_media_array) && !empty($video_media_array) && $video_media_array->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info">
                                <h3>Keep Learning</h3>
                                @if (isset($video_media_array) && !empty($video_media_array) && $video_media_array->count() >= 4)
                                    <a href="{{ route('page.profile.youtube', encrypt($user_detail['id'])) }}">View
                                        all</a>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="col-md-12 plr-45 padding_set_left_right_15">
                            <div id="news-slider" class="owl-carousel">
                                @foreach ($video_media_array as $video_key => $video)
                                    <div class="post-slide">
                                        <div class="post-img">
                                            <div class='c-video__image-container vid-fit-reveal'
                                                data-id="{{ $video->id }}" id="#js-fitvideo_{{ $video->id }}">
                                                <div class="bt-play" id="bt-play_{{ $video->id }}"></div>
                                                <iframe width="250" height="215" src="{{ $video['media_url'] }}"
                                                    title="YouTube video player" frameborder="0"
                                                    id="vid-reveal_{{ $video->id }}"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div> --}}
                        <div class="col-md-12 plr-45 padding_set_left_right_15">
                            <div class="row">
                                @php $count = 1; @endphp
                                @foreach ($video_media_array as $video_key => $video)
                                    @php
                                        if ($count == 4) {
                                            break;
                                        }
                                    @endphp
                                    <div class="post-slide">
                                        <div class="post-img">
                                            <div class='c-video__image-container vid-fit-reveal box-video-youtube'
                                                data-id="{{ $video->id }}" id="#js-fitvideo_{{ $video->id }}"
                                                data-vid="box-video-youtube_{{ $video->id }}">
                                                <a href="" target="_blank" class="bg-video"
                                                    id="youtube_id_{{ $video->id }}" data-toggle="modal"
                                                    data-target="#youtubeModal" data-src="{{ $video['media_url'] }}">
                                                    <div class="bt-play" id="bt-play_{{ $video->id }}"></div>
                                                </a>
                                                <iframe width="250" height="215" src="{{ $video['media_url'] }}"
                                                    title="YouTube video player" frameborder="0"
                                                    id="vid-reveal_{{ $video->id }}"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>
                                    @php $count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($ebook_media_array) && !empty($ebook_media_array) && $ebook_media_array->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info">
                                <h3>Our E-books</h3>
                                @if (isset($ebook_media_array) && !empty($ebook_media_array) && $ebook_media_array->count() >= 5)
                                    <a href="{{ route('page.profile.ebook', encrypt($user_detail['id'])) }}">View all</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 plr-45 padding_set_left_right_15">
                            <div class="post-slide">
                                <div class="row">
                                    @foreach ($ebook_media_array as $ebook_key => $ebook)
                                        <div class="col-md-3 col-6">
                                            <div class="post-img">
                                                <a href="{{ Storage::disk('public')->url('media_files/' . $ebook->media_image) }}"
                                                    target="_blank"><img
                                                        src="{{ Storage::disk('public')->url('media_files/' . $ebook->media_cover) }}"
                                                        alt="" class="ebook" data-id="{{ $ebook->id }}"
                                                        id="#ebook_{{ $ebook->id }}"></a>
                                            </div>
                                            <div class="post-information">
                                                <h4 class="content">{{ $ebook->media_name }}</h4>
                                            </div>
                                        </div>
                                        @if ($ebook_key == 3)
                                            @php break; @endphp
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (isset($podcast_media_array) && !empty($podcast_media_array) && $podcast_media_array->count() > 0)
                    {{-- <div class="row">
                        <div class="col-md-12">
                            <div class="below_info">
                                <h3>Our Podcast</h3>
                                @if (isset($podcast_media_array) && !empty($podcast_media_array) && $podcast_media_array->count() >= 5)
                                    <a href="{{ route('page.profile.podcast', encrypt($user_detail['id'])) }}">View
                                        all</a>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 plr-45 padding_set_left_right_15">
                            <div class="post-slide">
                                <div class="row audio-players">
                                    @foreach ($podcast_media_array as $podcast_key => $podcast)
                                        <div class="col-md-3 col-6">
                                            <div class="audio-player js-audio-player" data-id="{{ $podcast->id }}"
                                                id="#js-fitvideo_{{ $podcast->id }}">
                                                <button class="audio-player__control js-control">
                                                    <div class="audio-player__control-icon"></div>
                                                </button>
                                                <h4 class="audio-player__title">{{ $podcast->media_name }}</h4>
                                                <audio preload="auto">
                                                    <source
                                                        src="{{ Storage::disk('public')->url('media_files/' . $podcast->media_image) }}" />
                                                </audio>
                                                <!-- <img class="audio-player__cover" src="https://unsplash.it/g/300?image=29"/> -->
                                                <img class="audio-player__cover"
                                                    src="{{ Storage::disk('public')->url('media_files/' . $podcast->media_cover) }}">
                                                <video preload="auto" loop="loop">
                                                    <source src="" type="video/mp4" />
                                                </video>
                                            </div>
                                        </div>
                                        @if ($podcast_key == 3)
                                            @php break; @endphp
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info">
                                <h3>Our Podcast</h3>
                                @if (isset($podcast_media_array) && !empty($podcast_media_array) && $podcast_media_array->count() >= 4)
                                    <a href="{{ route('page.profile.podcast', encrypt($user_detail['id'])) }}">View
                                        all</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 plr-45 padding_set_left_right_15">
                            <div class="row">
                                @php $count = 1; @endphp
                                @foreach ($podcast_media_array as $podcast_key => $podcast)
                                    @php
                                        if ($count == 5) {
                                            break;
                                        }
                                    @endphp
                                    <div class="col-md-3">
                                        <div class="">
                                            <div class="box-video vid-fit-reveal" data-id="{{ $podcast->id }}"
                                                data-testid="play-pause-button"
                                                data-vid="box-video_{{ $podcast['id'] }}"
                                                id="#js-fitvideo_{{ $podcast->id }}">
                                                {{-- <a href="" target="_blank" class="bg-video" id="podcast_id_{{ $podcast['id'] }}" data-toggle="modal" data-target="#podcastModal"
                                            style="background-image: @if (str_contains($podcast['media_image'], 'spotify')) url('{{ asset('frontend/images/spotify_logo.png') }}') @elseif(str_contains($podcast['media_image'],'apple')) url('{{ asset('frontend/images/apple_logo.png') }}') @elseif(str_contains($podcast['media_image'],'stitcher')) url('{{ asset('frontend/images/stitcher_logo.png') }}') @elseif(str_contains($podcast['media_image'],'redcircle')) url('{{ asset('frontend/images/redcircle_logo.png') }}') @endif; opacity: 1;" data-src="{{ $podcast['media_image'] }}">
                                             --}}
                                             @if($podcast['podcast_web_type'] == 'stitcher_podcast')
                                                <a href="" target="_blank" class="bg-video"
                                                    id="podcast_id_{{ $podcast['id'] }}" data-toggle="modal"
                                                    data-target="#podcastModal"
                                                    style="background-image: url('{{ Storage::disk('public')->url('media_files/' . $podcast['media_cover']) }} '); opacity: 1;"
                                                    data-src="{{ $podcast['media_image'] }}">
                                                    <div class="bt-play" id="bt-play_{{ $podcast->id }}"></div>
                                                </a>
                                            @else
                                                <a href="" target="_blank" class="bg-video"
                                                    id="podcast_id_{{ $podcast['id'] }}" data-toggle="modal"
                                                    data-target="#podcastModal"
                                                    style="background-image: url('{{ $podcast['media_cover'] }} '); opacity: 1;"
                                                    data-src="{{ $podcast['media_image'] }}">
                                                    <div class="bt-play" id="bt-play_{{ $podcast->id }}"></div>
                                                </a>

                                            @endif
                                                <div class="video-container">
                                                    <iframe width="590" height="100%"
                                                        src="{{ $podcast['media_image'] }}"
                                                        id="vid-reveal_{{ $podcast->id }}" frameborder="0"
                                                        allowfullscreen="allowfullscreen"></iframe>
                                                </div>
                                            </div>
                                        </div>
                                        <p>{{ $podcast['media_name'] }}</p>
                                        {{-- <p>{{ $podcast['media_duration'] }}</p> --}}
                                    </div>
                                    @php $count++; @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                @if (isset($free_items) && !empty($free_items) && $free_items->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info">
                                <h3>Topics</h3>
                                @if (isset($free_items) && !empty($free_items) && $free_items->count() >= 4)
                                    <a href="{{ route('page.user.categories', encrypt($user_detail['id'])) }}">View
                                        all</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12 plr-45 padding_set_left_right">
                            <div class="row">
                                @if ($free_items->count() > 0)
                                    @foreach ($free_items as $free_items_key => $item)
                                        <div class="col-md-6 col-lg-4 col-xl-3 col-sm-6 col-12 mb-5 mb-sm-0">
                                            @include('frontend.partials.all-free-item-block')
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                @endif
                <div class="row mb-3">
                    <div class="col-12">
                        @if($reviews->count() > 0)
                            <div class="below_info">
                                <h3 id="review-section" class="h5 mb-4 text-black">{{ __('review.frontend.reviews-cap') }}
                                </h3>
                            </div>
                        @endif
                        @if ($reviews->count() == 0)
                            @guest

                                <div class="col-12 mb-3 pt-3 pb-3 bg-light">
                                    <div class="col-md-12 text-center">
                                        <p class="mb-0">
                                            <span class="icon-star text-warning"></span>
                                            <span class="icon-star text-warning"></span>
                                            <span class="icon-star text-warning"></span>
                                            <span class="icon-star text-warning"></span>
                                            <span class="icon-star text-warning"></span>
                                        </p>
                                        <div class="row mt-2">
                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-primary rounded text-white item-review-button"><i
                                                        class="fas fa-star"></i> {{ __('Write a Review') }}</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @else
                                @if ($user_detail->id != Auth::user()->id)
                                    @if ($review_count->profilereviewedByUser(Auth::user()->id, $user_detail->id))
                                        <div class="col-12 mb-3 pt-3 pb-3 bg-light">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    @if (Auth::user()->isAdmin())
                                                        {{ __('review.frontend.posted-a-review', ['item_name' => $user_detail->name]) }}
                                                    @else
                                                        {{ __('review.frontend.posted-a-review', ['item_name' => $user_detail->name]) }}
                                                    @endif
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    @if (Auth::user()->isAdmin())
                                                        <a class="btn btn-primary rounded text-white"
                                                            href="{{ route('admin.page.reviews.edit', $user_detail->id) }}"
                                                            target="_blank"><i class="fas fa-star"></i>
                                                            {{ __('review.backend.edit-a-review') }}</a>
                                                    @else
                                                        <a class="btn btn-primary rounded text-white"
                                                            href="{{ route('user.page.reviews.edit', $Auth_review->id) }}"
                                                            target="_blank"><i class="fas fa-star"></i>
                                                            {{ __('review.backend.edit-a-review') }}</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-12 mb-3 pt-3 pb-3 bg-light">
                                            <div class="col-12 text-center">
                                                <p class="mb-0">
                                                    <span class="icon-star text-warning"></span>
                                                    <span class="icon-star text-warning"></span>
                                                    <span class="icon-star text-warning"></span>
                                                    <span class="icon-star text-warning"></span>
                                                    <span class="icon-star text-warning"></span>
                                                </p>
                                                <span>Start your review</span>

                                                <div class="row mt-2">
                                                    <div class="col-md-12 text-center">
                                                        @if (Auth::user()->isAdmin())
                                                            <button
                                                                class="btn btn-primary rounded text-white item-review-button"><i
                                                                    class="fas fa-star"></i>
                                                                {{ __('Write a Review') }}</button>
                                                        @else
                                                            <button
                                                                class="btn btn-primary rounded text-white item-review-button"><i
                                                                    class="fas fa-star"></i>
                                                                {{ __('Write a Review') }}</button>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                @else
                                    {{-- <div class="col-12 row mb-3 pt-3 pb-3 bg-light">
                                            <div class="col-12 text-center">
                                                <span style="word-break: break-all;">{{ __('review.frontend.no-review-profile')}}{{ \App\User::find($user_detail->id)->name }}</span>
                                            </div>
                                        </div>                               --}}
                                @endif
                            @endguest
                        @else
                            <div class="col-12 mb-3 pt-3 pb-3 pb-3 bg-light">
                                <div class="row">
                                    <div class="col-md-9">
                                        @guest
                                            {{ __('review.frontend.start-a-review-profile') }}{{ \App\User::find($user_detail->id)->name }}
                                        @else
                                            @if ($user_detail->id != Auth::user()->id)
                                                @if (Auth::user()->isAdmin())
                                                    @if ($review_count->profilereviewedByUser(Auth::user()->id, $user_detail->id))
                                                        {{ __('review.frontend.posted-a-review-profile') }}{{ \App\User::find($user_detail->id)->name }}
                                                    @else
                                                        {{ __('review.frontend.start-a-review-profile') }}{{ \App\User::find($user_detail->id)->name }}
                                                    @endif
                                                @else
                                                    @if ($review_count->profilereviewedByUser(Auth::user()->id, $user_detail->id))
                                                        {{ __('review.frontend.my-reviews') }}
                                                    @else
                                                        {{ __('review.frontend.start-a-review-profile') }}{{ \App\User::find($user_detail->id)->name }}
                                                    @endif
                                                @endif
                                            @else
                                                {{ __('review.frontend.my-reviews') }}
                                            @endif
                                        @endguest
                                    </div>
                                    <div class="col-md-3 text-right">
                                        @guest
                                            <button class="btn btn-primary rounded text-white item-review-button"><i
                                                    class="fas fa-star"></i> {{ __('Write a Review') }}</button>
                                        @else
                                            @if ($user_detail->id != Auth::user()->id)
                                                @if (Auth::user()->isAdmin())
                                                    @if ($review_count->profilereviewedByUser(Auth::user()->id, $user_detail->id))
                                                        <a class="btn btn-primary rounded text-white"
                                                            href="{{ route('admin.page.reviews.edit', $Auth_review->id) }}"
                                                            target="_blank"><i class="fas fa-star"></i>
                                                            {{ __('review.backend.edit-a-review') }}</a>
                                                    @else
                                                        <button
                                                            class="btn btn-primary rounded text-white item-review-button"><i
                                                                class="fas fa-star"></i> {{ __('Write a Review') }}</button>
                                                    @endif
                                                @else
                                                    @if ($review_count->profilereviewedByUser(Auth::user()->id, $user_detail->id))
                                                        <a class="btn btn-primary rounded text-white"
                                                            href="{{ route('user.page.reviews.edit', $Auth_review->id) }}"
                                                            target="_blank"><i class="fas fa-star"></i>
                                                            {{ __('review.backend.edit-a-review') }}</a>
                                                    @else
                                                        <button
                                                            class="btn btn-primary rounded text-white item-review-button"><i
                                                                class="fas fa-star"></i> {{ __('Write a Review') }}</button>
                                                    @endif
                                                @endif
                                            @endif
                                        @endguest
                                    </div>
                                </div>
                            </div>

                            <!-- Start review summary -->
                            @if ($item_count_rating > 0)
                                <div class="row mt-4 mb-3">
                                    <div class="col-12 text-right">
                                        <form
                                            action="{{ route('page.profile', encrypt($user_detail->id)) }}#review-section"
                                            method="GET" class="form-inline" id="item-rating-sort-by-form">
                                            <div class="form-group">
                                                <label for="rating_sort_by">{{ __('rating_summary.sort-by') }}</label>
                                                <select
                                                    class="custom-select ml-2 @error('rating_sort_by') is-invalid @enderror"
                                                    name="rating_sort_by" id="rating_sort_by">
                                                    <option value="{{ \App\Item::ITEM_RATING_SORT_BY_NEWEST }}"
                                                        {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_NEWEST ? 'selected' : '' }}>
                                                        {{ __('rating_summary.sort-by-newest') }}</option>
                                                    <option value="{{ \App\Item::ITEM_RATING_SORT_BY_OLDEST }}"
                                                        {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_OLDEST ? 'selected' : '' }}>
                                                        {{ __('rating_summary.sort-by-oldest') }}</option>
                                                    <option value="{{ \App\Item::ITEM_RATING_SORT_BY_HIGHEST }}"
                                                        {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_HIGHEST ? 'selected' : '' }}>
                                                        {{ __('rating_summary.sort-by-highest') }}</option>
                                                    <option value="{{ \App\Item::ITEM_RATING_SORT_BY_LOWEST }}"
                                                        {{ $rating_sort_by == \App\Item::ITEM_RATING_SORT_BY_LOWEST ? 'selected' : '' }}>
                                                        {{ __('rating_summary.sort-by-lowest') }}</option>
                                                </select>
                                                @error('rating_sort_by')
                                                    <span class="invalid-tooltip">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="row">
                                        <div class="col-lg-3 bg-primary text-white">
                                            <div id="review_summary">
                                                <strong>{{ number_format($item_average_rating, 1) }}</strong>
                                                {{-- @if ($item_count_rating > 1)
                                                    <small>{{ __('rating_summary.based-on-reviews', ['item_rating_count' => $item_count_rating]) }}</small>
                                                @else
                                                    <small>{{ __('rating_summary.based-on-review', ['item_rating_count' => $item_count_rating]) }}</small>
                                                @endif --}}
                                                <small>Profile Reviews Rating</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-9">
                                            <!-- Rating Progeress Bar -->
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $profile_one_star_percentage }}%"
                                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3">
                                                    <small><strong>{{ __('rating_summary.1-stars') }}</strong></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $profile_two_star_percentage }}%"
                                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3">
                                                    <small><strong>{{ __('rating_summary.2-stars') }}</strong></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $profile_three_star_percentage }}%"
                                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3">
                                                    <small><strong>{{ __('rating_summary.3-stars') }}</strong></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $profile_four_star_percentage }}%"
                                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3">
                                                    <small><strong>{{ __('rating_summary.4-stars') }}</strong></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-10 col-9 mt-2">
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: {{ $profile_five_star_percentage }}%"
                                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-3">
                                                    <small><strong>{{ __('rating_summary.5-stars') }}</strong></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endif
                            <!-- End review summary -->

                            @foreach ($reviews as $reviews_key => $review)
                                <div class="col-12 mb-3">
                                    <div class="row">
                                        <div class="col-md-4">

                                            <div class="row align-items-center mb-3">
                                                <div class="col-4">
                                                    @if (empty(\App\User::find($review->author_id)->user_image))
                                                        {{-- <img src="{{ asset('frontend/images/placeholder/profile-' . intval($review->author_id % 10) . '.webp') }}"
                                                            alt="Image" class="img-fluid rounded-circle"> --}}
                                                        <img src="{{ asset('frontend/images/placeholder/profile_default.webp') }}"
                                                            alt="Image" class="img-fluid rounded-circle">
                                                    @else
                                                        <img src="{{ Storage::disk('public')->url('user/' . \App\User::find($review->author_id)->user_image) }}"
                                                            alt="{{ \App\User::find($review->author_id)->name }}"
                                                            class="img-fluid rounded-circle">
                                                    @endif
                                                </div>
                                                <div class="col-8 pl-0">
                                                    <span>{{ \App\User::find($review->author_id)->name }}</span>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <span>{{ __('review.backend.overall-rating') }}</span>

                                                    <div class="pl-0 rating_stars rating_stars_{{ $review->id }}"
                                                        data-id="rating_stars_{{ $review->id }}"
                                                        data-rating="{{ $review->rating }}"></div>
                                                </div>
                                            </div>

                                            @if ($review->recommend == \App\Item::ITEM_REVIEW_RECOMMEND_YES)
                                                <div class="row mb-2">
                                                    <div class="col-md-12">
                                                        <span class="bg-success text-white pl-2 pr-2 pt-2 pb-2 rounded">
                                                            <i class="fas fa-check"></i>
                                                            {{ __('review.backend.recommend') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <hr>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- </div> -->

    <div class="modal fade" id="share-refferal-modal" tabindex="-1" role="dialog"
        aria-labelledby="share-refferal-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="color: black;">{{ __('Share a Referral Link') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>{{ __('frontend.item.share-listing-email') }}</p>
                            @if (!Auth::check())
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ __('frontend.item.login-require') }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (isset(Auth::user()->id) && !empty(Auth::user()->id) && $user_detail['id'] == Auth::user()->id)
                                {{-- <form action="{{ route('page.referral.email', ['referral_link' => Auth::user()->id]) }}" --}}
                                <form action="#" method="POST" name="shareProfileReferalModal"
                                    id="shareProfileReferalModal">
                                    @csrf
                                    <div class="form-row mb-3">
                                        <div class="col-md-4">
                                            <label for="item_share_email_name"
                                                class="text-black">{{ __('frontend.item.name') }}<span
                                                    class="text-danger">*</span></label>
                                            <input id="item_share_email_name" type="text"
                                                class="form-control @error('item_share_email_name') is-invalid @enderror"
                                                name="item_share_email_name"
                                                value="{{ isset(auth()->user()->name) ? auth()->user()->name : old('item_share_email_name') }}"
                                                {{ Auth::check() ? '' : 'disabled' }} readonly>
                                            <p class="profile_referal_name_error error_color" role="alert"></p>
                                            @error('item_share_email_name')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <!-- <div class="col-md-4">
                                                    <label for="item_share_email_from_email" class="text-black">{{ __('frontend.item.email') }}</label>
                                                    <input id="item_share_email_from_email" type="email" class="form-control @error('item_share_email_from_email') is-invalid @enderror" name="item_share_email_from_email" value="{{ old('item_share_email_from_email') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                                    @error('item_share_email_from_email')
        <span class="invalid-tooltip">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
    @enderror
                                            </div> -->
                                        <div class="col-md-4">
                                            <label for="item_share_email_to_email"
                                                class="text-black">{{ __('frontend.item.email-to') }}<span
                                                    class="text-danger">*</span></label>
                                            <input id="item_share_email_to_email" type="email"
                                                class="form-control @error('item_share_email_to_email') is-invalid @enderror"
                                                name="item_share_email_to_email"
                                                value="{{ old('item_share_email_to_email') }}"
                                                {{ Auth::check() ? '' : 'disabled' }}>
                                            <p class="profile_referal_from_email_error error_color" role="alert"></p>
                                            @error('item_share_email_to_email')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row mb-3">
                                        <div class="col-md-12">
                                            <label for="item_share_email_note"
                                                class="text-black">{{ __('frontend.item.add-note') }}<span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control @error('item_share_email_note') is-invalid @enderror" id="item_share_email_note"
                                                rows="3" name="item_share_email_note" {{ Auth::check() ? '' : 'disabled' }}>{{ old('item_share_email_note') }}</textarea>
                                            <p class="profile_referal_note_error error_color" role="alert"></p>
                                            @error('item_share_email_note')
                                                <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary py-2 px-4 text-white rounded"
                                                {{ Auth::check() ? '' : 'disabled' }}>
                                                {{ __('frontend.item.send-email') }}
                                            </button>
                                            <span class="please_wait">Please Wait..</span>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded"
                        data-bs-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- For podcast modal-->
    <div class="modal-4">
        <div class="modal fade" id="podcastModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal_dialog" role="document">
                <div class="modal-content content_design3">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="exampleModalLabel_podcast"></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <iframe src="" id="iframesrcURL" frameborder="0" class=""></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- For youtube modal-->
    <div class="modal-4">
        <div class="modal fade" id="youtubeModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal_dialog" role="document">
                <div class="modal-content content_design3">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="exampleModalLabel_youtube">Youtube</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <iframe src="" id="youtubeiframesrcURL" frameborder="0" class="min_h_300"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script src="{{ asset('frontend/vendor/justified-gallery/jquery.justifiedGallery.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('frontend/vendor/colorbox/jquery.colorbox-min.js') }}"></script> --}}
    <script src="{{ asset('frontend/vendor/goodshare/goodshare.min.js') }}"></script>

    @if ($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="{{ asset('frontend/vendor/leaflet/leaflet.js') }}"></script>
    @endif

    @if ($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <!-- Youtube Background for Header -->
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif

    <script src="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.1.3/firebase.js"></script>
    <!-- start for contact us stepper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"
        integrity="sha512-eyHL1atYNycXNXZMDndxrDhNAegH2BDWt1TmkXJPoGf1WLlNYt08CSjkqF5lnCRmdm3IrkHid8s2jOUY4NIZVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- end for contact us stepper -->
    <script src="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">

    @include('frontend.partials.bootstrap-select-locale')
    <script>
        $(document).ready(function() {
            $('#rating_sort_by').on('change', function() {
                $("#item-rating-sort-by-form").submit();
            });
            "use strict";
            /**	
             * Start image gallery uplaod	
             */
            $('#upload_gallery').on('click', function() {
                $('#gallery_image_error_div').hide();
                $('#gallery_img_error').text('');
                window.selectedImages = [];
                $.FileDialog({
                    // accept: "image/jpeg",	
                    accept: ".jpeg,.jpg,.png",
                }).on("files.bs.filedialog", function(event) {
                    var html = "";
                    for (var a = 0; a < event.files.length; a++) {
                        if (a == 12) {
                            break;
                        }
                        selectedImages.push(event.files[a]);
                        html +=
                            "<div class='col-lg-3 col-md-4 col-sm-6 mb-2' id='review_image_gallery_" +
                            a + "'>" +
                            "<img style='max-width: 120px;' src='" + event.files[a].content + "'>" +
                            "<br/><button class='btn btn-danger btn-sm text-white mt-1' onclick='$(\"#review_image_gallery_" +
                            a + "\").remove();'>" + "{{ __('backend.shared.delete') }}" +
                            "</button>" +
                            "<input type='hidden' value='" + event.files[a].content +
                            "' name='review_image_galleries[]'>" +
                            "</div>";
                        var img_str = event.files[a].content;
                        var img_str_split = img_str.split(";base64")[0];
                        var img_ext = img_str_split.split("/")[1];
                        if (img_ext != 'jpeg' && img_ext != 'png' && img_ext != 'jpg') {
                            $('#gallery_img_error').text('Please choose only .jpg,.jpeg,.png file');
                            $('#gallery_image_error_div').show();
                            return false;
                        }
                    }
                    document.getElementById("selected-images").innerHTML += html;
                });
            });
            /**	
             * End image gallery uplaod	
             */
            @if ($item_count_rating > 0)
                $(".rating_stars_header").rateYo({
                    spacing: "5px",
                    starWidth: "23px",
                    readOnly: true,
                    rating: {{ $item_average_rating }},
                });
            @endif


        });
    </script>
    <script>
        $(document).ready(function() {
            $('#question_2').on('change', function() {
                $('#question2_txt').slideToggle("slow");
                var val = this.checked ? this.value : '';
                console.log(val);
                if (val) {
                    $("#question2_txt").prop('required', true);
                    $("#question2_checkbox1").prop('required', false);
                    $("#question2_checkbox2").prop('required', false);
                    $("#question2_checkbox3").prop('required', false);
                    $("#question2_checkbox4").prop('required', false);
                    $("#question2_checkbox5").prop('required', false);
                    $("#question2_checkbox6").prop('required', false);
                } else {
                    $("#question2_txt").prop('required', false);
                    $("#question2_checkbox1").prop('required', true);
                    $("#question2_checkbox2").prop('required', true);
                    $("#question2_checkbox3").prop('required', true);
                    $("#question2_checkbox4").prop('required', true);
                    $("#question2_checkbox5").prop('required', true);
                    $("#question2_checkbox6").prop('required', true);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.profile_review_rating_error').text('');
            $('.profile_review_body_error').text('');
            $('.please_wait').text('');
            $('#rating').on('input', function(e) {
                $('.profile_review_rating_error').text('');
            });
            $('#body').on('input', function(e) {
                $('.profile_review_body_error').text('');
            });
            $("#review-modal").on("hidden.bs.modal", function() {
                $('.profile_review_rating_error').text('');
                $('.profile_review_rating_error').text('');
            });
            $('#review_form').on('submit', function(e) {
                e.preventDefault();
                $('.please_wait').text('Please Wait..');
                var user_id = <?php echo json_encode($user_detail->id); ?>;
                var role_id = <?php if (isset(Auth::user()->role_id)) {
                    echo json_encode(Auth::user()->role_id);
                } else {
                    echo '5';
                } ?>;
                // alert(role_id);	
                if (role_id == 2 || role_id == 3) {
                    var url = "{{ route('user.page.reviews.store') }}";
                } else {
                    var url = "{{ route('admin.page.reviews.store') }}"
                }
                var formData = new FormData(this);
                jQuery.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            // console.log(response)	
                            $(".error_color").text("");
                            $('.please_wait').text('');
                            // location.reload(); 	
                            // window.location.href = "{{ route('login') }}";	
                            $('#review-modal').modal('hide');
                            // $("#review-modal").on("hidden.bs.modal", function() {
                            // $('.profile_review_rating_error').text('');
                            // $('.profile_review_body_error').text('');
                            $("#review_form").trigger("reset");
                            $('#rating').val(1);
                            $("#rating").selectpicker("refresh");
                            // $('#register_success_error_div').show();
                            toastr.success('profile review added');
                            $(':input[type="submit"]').prop('disabled', false);
                            location.reload();
                            // });
                        }
                        if (response.status == 'error') {
                            // console.log(response.msg.item_contact_email_note)	
                            $('.please_wait').text('');
                            $('#register_error_div').show();

                            $.each(response.msg, function(key, val) {
                                if (response.msg.rating) {
                                    $('.profile_review_rating_error').text(response.msg
                                        .rating)
                                }
                                if (response.msg.body) {
                                    console.log(response.msg.body);
                                    $('.profile_review_body_error').text(response.msg
                                        .body)
                                }
                                $(':input[type="submit"]').prop('disabled', false);
                            });
                        }
                    }
                });
            });
            $('.item-review-button').on('click', function() {
                $('#review-modal').modal('show');
            });

        });
        $(document).ready(function() {
            $('.name_error').text('');
            $('.item_contact_email_from_email').text('');
            $('.note_error').text('');
            $('.please_wait').text('');

            $('#item_conntact_email_name').on('input', function(e) {
                $('.name_error').text('');
            });
            $('#item_contact_email_from_email').on('input', function(e) {
                $('.email_error').text('');
            });
            $('#item_contact_email_note').on('input', function(e) {
                $('.note_error').text('');
            });
            $("#contact-modal").on("hidden.bs.modal", function() {
                $('.name_error').text('');
                $('.email_error').text('');
                $(".note_error").text("");
            });
            $('#contactFormModal').on('submit', function(e) {
                e.preventDefault();
                $('.please_wait').text('Please Wait..');
                var item_slug = <?php echo json_encode($item->item_slug); ?>;
                // alert(item_slug);
                var formData = new FormData(this);

                jQuery.ajax({
                    type: 'POST',
                    url: '/items/' + item_slug + '/contact',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                    },

                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            // console.log(response)
                            $(".error_color").text("");
                            $('.please_wait').text('');
                            // location.reload(); 
                            // window.location.href = "{{ route('login') }}";

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
                            messaging.onMessage(function(payload) {
                                const title = payload.notification.title;
                                const options = {
                                    body: payload.notification.body,
                                    icon: payload.notification.icon,
                                };
                                new Notification(title, options);
                            });
                            location.reload();
                           // $('#contactForm_success_error_div').show();
                            toastr.success('Conatct This Coach SuccessFull');



                        }
                        if (response.status == 'error') {
                            // console.log(response.msg.item_contact_email_note)
                            $('.please_wait').text('');
                            $('#contactForm_error_div').show();
                            $.each(response.msg, function(key, val) {
                                if (response.msg.item_conntact_email_name) {
                                    $('.name_error').text(response.msg
                                        .item_conntact_email_name)
                                }
                                if (response.msg.item_contact_email_from_email) {
                                    $('.email_error').text(response.msg
                                        .item_contact_email_from_email)
                                }
                                if (response.msg.item_contact_email_note) {
                                    $('.note_error').text(response.msg
                                        .item_contact_email_note)
                                }
                                $(':input[type="submit"]').prop('disabled', false);

                            });

                        }
                    }
                });
            });

            $('.profile_name_error').text('')
            $('.profile_from_email_error').text('')
            $('.profile_to_error').text('')
            $('.profile_note_error').text('');
            $('.please_wait').text('');

            $('#profile_share_email_name').on('input', function(e) {
                $('.profile_name_error').text('');
            });
            $('#profile_share_email_from_email').on('input', function(e) {
                $('.profile_from_email_error').text('');
            });
            $('#profile_share_email_to_email').on('input', function(e) {
                $('.profile_to_error').text('');
            });
            $('#profile_share_email_note').on('input', function(e) {
                $('.profile_note_error').text('');
            });
            $("#share-modal").on("hidden.bs.modal", function() {
                $('.profile_name_error').text('');
                $('.profile_from_email_error').text('');
                $('.profile_to_error').text('');
                $(".profile_note_error").text("");
            });
            $('#shareProfileModal').on('submit', function(e) {
                e.preventDefault();
                $('.please_wait').text('Please Wait..');
                var hexId = <?php echo json_encode($hexId); ?>;
                // alert(hexId);

                var formData = new FormData(this);

                jQuery.ajax({
                    type: 'POST',
                    url: '/profile/' + hexId + '/email',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                    },

                    success: function(response) {
                        console.log(response);
                        // return false;
                        if (response.status == 'success') {
                            // console.log(response)
                            $(".error_color").text("");
                            $('.please_wait').text('');
                            // location.reload(); 
                            // window.location.href = "{{ route('login') }}";

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
                            messaging.onMessage(function(payload) {
                                const title = payload.notification.title;
                                const options = {
                                    body: payload.notification.body,
                                    icon: payload.notification.icon,
                                };
                                new Notification(title, options);
                            });
                            location.reload();
                           // $('#share_modal_success_error_div').show();
                            toastr.success('Shared SuccessFull');



                        }
                        if (response.status == 'error') {
                            // console.log(response.msg.item_contact_email_note)
                            $('.please_wait').text('');
                            $('#share_modal_error_div').show();

                            $.each(response.msg, function(key, val) {
                                if (response.msg.profile_share_email_name) {
                                    $('.profile_name_error').text(response.msg
                                        .profile_share_email_name)
                                }
                                if (response.msg.profile_share_email_from_email) {
                                    $('.profile_from_email_error').text(response.msg
                                        .profile_share_email_from_email)
                                }
                                if (response.msg.profile_share_email_to_email) {
                                    $('.profile_to_error').text(response.msg
                                        .profile_share_email_to_email)
                                }
                                if (response.msg.profile_share_email_note) {
                                    $('.profile_note_error').text(response.msg
                                        .profile_share_email_note)
                                }
                                $(':input[type="submit"]').prop('disabled', false);

                            });

                        }
                    }
                });

            });

            $('.profile_referal_name_error').text('')
            $('.profile_referal_from_email_error').text('')
            $('.profile_referal_note_error').text('');
            $('.please_wait').text('');

            $('#item_share_email_name').on('input', function(e) {
                $('.profile_referal_name_error').text('');
            });
            $('#item_share_email_to_email').on('input', function(e) {
                $('.profile_referal_from_email_error').text('');
            });
            $('#item_share_email_note').on('input', function(e) {
                $('.profile_referal_note_error').text('');
            });
            $("#share-refferal-modal").on("hidden.bs.modal", function() {
                $('.profile_referal_name_error').text('');
                $('.profile_referal_from_email_error').text('');
                $(".profile_referal_note_error").text("");
            });
            $('#shareProfileReferalModal').on('submit', function(e) {
                e.preventDefault();
                $('.please_wait').text('Please Wait..');
                var user_id = <?php echo json_encode($id); ?>;
                // alert(user_id);

                var formData = new FormData(this);

                jQuery.ajax({
                    type: 'POST',
                    url: '/referral/' + user_id + '/email',
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                    },

                    success: function(response) {
                        // console.log(response);
                        if (response.status == 'success') {
                            // console.log(response)
                            $(".error_color").text("");
                            $('.please_wait').text('');
                            // location.reload(); 
                            // window.location.href = "{{ route('login') }}";

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
                            messaging.onMessage(function(payload) {
                                const title = payload.notification.title;
                                const options = {
                                    body: payload.notification.body,
                                    icon: payload.notification.icon,
                                };
                                new Notification(title, options);
                            });
                            location.reload();
                            toastr.success('Share a Referral Link SuccessFull');


                        }
                        if (response.status == 'error') {
                            // console.log(response.msg.item_contact_email_note)
                            $('.please_wait').text('');
                            $.each(response.msg, function(key, val) {
                                if (response.msg.item_share_email_name) {
                                    $('.profile_referal_name_error').text(response.msg
                                        .item_share_email_name)
                                }
                                if (response.msg.item_share_email_to_email) {
                                    $('.profile_referal_from_email_error').text(response
                                        .msg.item_share_email_to_email)
                                }
                                if (response.msg.item_share_email_note) {
                                    $('.profile_referal_note_error').text(response.msg
                                        .item_share_email_note)
                                }
                                $(':input[type="submit"]').prop('disabled', false);

                            });

                        }
                    }
                });

            });
        });



        $(".ebook").on('click', function() {
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '<?php echo url('/media-visitor'); ?>',
                data: {
                    'id': id
                },
                success: function(data) {
                    // console.log(data)
                },
            });
        });
        // $('#submit').on('click', function() {
        //     var firebaseConfig = {
        //         aapiKey: "AIzaSyA31EsSr68dVVQ-cVZwfbLmeDK8_PUT2fM",
        //         authDomain: "coachhq-c1b3d.firebaseapp.com",
        //         projectId: "coachhq-c1b3d",
        //         storageBucket: "coachhq-c1b3d.appspot.com",
        //         messagingSenderId: "668525619724",
        //         appId: "1:668525619724:web:e4282225654d0467655c29",
        //         measurementId: "G-VFGKZNYRVM"
        //     };
        //     firebase.initializeApp(firebaseConfig);
        //     const messaging = firebase.messaging();
        //     messaging.onMessage(function(payload) {
        //         const title = payload.notification.title;
        //         const options = {
        //             body: payload.notification.body,
        //             icon: payload.notification.icon,
        //         };
        //         new Notification(title, options);
        //     });
        // });
        $(document).ready(function() {
            $('.item-contact-button').on('click', function() {
                $('#contact-modal').modal('show');
            });
            @error('item_conntact_email_name')
                $('#contact-modal').modal('show');
            @enderror

            @error('item_contact_email_from_email')
                $('#contact-modal').modal('show');
            @enderror

            @error('item_contact_email_note')
                $('#contact-modal').modal('show');
            @enderror
        });
        $(document).ready(function() {
            $(".vid-fit-user").on('click', function() {
                var userid = $(this).attr('data-id');
                var main_id = $(this).attr('id');
                $(main_id).addClass("reveal-video");
                $('#bt-playuser_' + userid).hide();
                var myFrame = $(main_id).find('#vid-revealuser_' + userid);
                var url = $(myFrame).attr('src') + '?autoplay=1';
                $(myFrame).attr('src', url);

                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '<?php echo url('/youtube-visitor'); ?>',
                    data: {
                        'userid': userid
                    },
                    success: function(data) {},
                });
            });
        });
        $(document).ready(function() {
            $(".vid-fit-reveal").on('click', function() {
                var id = $(this).attr('data-id');
                var main_id = $(this).attr('id');

                $(main_id).addClass("reveal-video");
                $('#bt-play_' + id).hide();
                var myFrame = $(main_id).find('#vid-reveal_' + id);
                var url = $(myFrame).attr('src') + '?autoplay=1';
                $(myFrame).attr('src', url);

                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '<?php echo url('/media-visitor'); ?>',
                    data: {
                        'id': id
                    },
                    success: function(data) {},
                });
            });

            $(".js-audio-player").on('click', function() {
                var id = $(this).attr('data-id');
                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '<?php echo url('/media-visitor'); ?>',
                    data: {
                        'id': id
                    },
                    success: function(data) {},
                });
            });
        });
        //  $('#eye').on('click', function(){
        //     $('#share-refferal-modal2').modal('show');
        // });
        // $(function() {

        //     $('#eye').click(function() {

        //         if ($(this).hasClass('fa-eye')) {

        //             $(this).removeClass('fa-eye');

        //             $(this).addClass('fa-eye');




        //         } else {

        //             $(this).removeClass('fa-eye');

        //             $(this).addClass('fa-eye');


        //         }
        //     });
        // });
        $(document).ready(function() {
            $("#news-slider").owlCarousel({
                items: 4,
                itemsDesktop: [1199, 4],
                itemsDesktopSmall: [980, 2],
                itemsMobile: [600, 1],
                navigation: true,
                navigationText: ["", ""],
                pagination: true,
                autoPlay: true
            });

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

        $('.item-share-refferal-button').on('click', function() {
            $('#share-refferal-modal').modal('show');
        });
        @error('item_share_email_name')
            $('#share-refferal-modal').modal('show');
        @enderror

        @error('item_share_email_to_email')
            $('#share-refferal-modal').modal('show');
        @enderror

        @error('item_share_email_note')
            $('#share-refferal-modal').modal('show');
        @enderror

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

        // function playAnimation(id){
        //     var selectImg = document.getElementById(id);
        //     selectImg.src = '{{ asset('frontend/images/play.gif') }}';
        // }
        // function pauseAnimation(id){
        //     var selectImg = document.getElementById(id);
        //     selectImg.src = '{{ asset('frontend/images/Rectangle 6.png') }}';
        // }
    </script>


    @if ($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_GOOGLE_MAP)
        <script>
            // Initial the google map
            function initMap() {

                @if (count($free_items))

                    var window_height = $(window).height();
                    $('#mapid-box').css('height', window_height + 'px');

                    var locations = [];

                    @foreach ($free_items as $free_items_key => $free_item)
                        @if ($free_item->item_type == \App\Item::ITEM_TYPE_REGULAR)

                            var popup_item_title = '{{ $free_item->item_title }}';

                            @if ($free_item->item_address_hide)
                                var popup_item_address =
                                    '{{ $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code }}';
                            @else
                                var popup_item_address =
                                    '{{ $free_item->item_address . ', ' . $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code }}';
                            @endif
                            var popup_item_get_direction = '<a target="_blank" href="' +
                                '{{ 'https://www.google.com/maps/dir/?api=1&destination=' . $free_item->item_lat . ',' . $free_item->item_lng }}' +
                                '"><i class="fas fa-directions"></i> ' + '{{ __('google_map.get-directions') }}' + '</a>';

                            @if ($free_item->getCountRating() > 0)
                                var popup_item_rating = '{{ $free_item->item_average_rating }}' + '/5';
                                var popup_item_reviews = ' - {{ $free_item->getCountRating() }}' + ' ' +
                                    '{{ __('category_image_option.map.review') }}';
                            @else
                                var popup_item_rating = '';
                                var popup_item_reviews = '';
                            @endif

                            var popup_item_feature_image_link = '<img src="' +
                                '{{ !empty($free_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $free_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp') }}' +
                                '">';
                            var popup_item_link = '<a href="' + '{{ route('page.item', $free_item->item_slug) }}' +
                                '" target="_blank">' + popup_item_title + '</a>';

                            locations.push(["<div class='google_map_scrollFix'>" + popup_item_feature_image_link + "<br><br>" +
                                popup_item_link + "<br>" + popup_item_rating + popup_item_reviews + "<br>" +
                                popup_item_address + '<br>' + popup_item_get_direction + "</div>",
                                {{ $free_item->item_lat }}, {{ $free_item->item_lng }}
                            ]);
                        @endif
                    @endforeach

                    var infowindow = null;
                    var infowindow_hover = null;

                    if (locations.length === 0) {
                        // Destroy mapid-box DOM since no regular listings found
                        $("#mapid-box").remove();
                    } else {
                        var map = new google.maps.Map(document.getElementById('mapid-box'), {
                            zoom: 12,
                            //center: new google.maps.LatLng(-33.92, 151.25),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                        });

                        //create empty LatLngBounds object
                        var bounds = new google.maps.LatLngBounds();
                        var infowindow = new google.maps.InfoWindow();

                        var marker, i;

                        for (i = 0; i < locations.length; i++) {
                            marker = new google.maps.Marker({
                                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                                map: map
                            });

                            //extend the bounds to include each marker's position
                            bounds.extend(marker.position);

                            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                                return function() {

                                    if (infowindow_hover) {
                                        infowindow_hover.close();
                                    }

                                    infowindow.setContent(locations[i][0]);
                                    infowindow.open(map, marker);
                                }
                            })(marker, i));
                        }

                        //now fit the map to the newly inclusive bounds
                        map.fitBounds(bounds);

                        var listener = google.maps.event.addListener(map, "idle", function() {
                            if (map.getZoom() > 12) map.setZoom(12);
                            google.maps.event.removeListener(listener);
                        });

                        // Start google map hover event
                        $(".listing_for_map_hover").on('mouseover', function() {

                            var map_item_lat = this.getAttribute("data-map-lat");
                            var map_item_lng = this.getAttribute("data-map-lng");
                            var map_item_title = this.getAttribute("data-map-title");
                            var map_item_address = this.getAttribute("data-map-address");

                            var map_item_rating = '';
                            var map_item_reviews = parseInt(this.getAttribute("data-map-reviews"));

                            if (map_item_reviews > 0) {
                                map_item_rating = this.getAttribute("data-map-rating") + '/5';
                                map_item_reviews = ' - ' + this.getAttribute("data-map-reviews") + ' ' +
                                    '{{ __('category_image_option.map.review') }}';
                            } else {
                                map_item_rating = '';
                                map_item_reviews = '';
                            }

                            var map_item_link = '<a href="' + this.getAttribute("data-map-link") +
                                '" target="_blank">' + map_item_title + '</a>';
                            var map_item_feature_image_link = '<img src="' + this.getAttribute(
                                "data-map-feature-image-link") + '">';
                            var map_item_get_direction =
                                '<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=' +
                                map_item_lat + ',' + map_item_lng + '"><i class="fas fa-directions"></i> ' +
                                '{{ __('google_map.get-directions') }}' + '</a>';

                            if (map_item_lat !== '' && map_item_lng !== '') {
                                var center = new google.maps.LatLng(map_item_lat, map_item_lng);
                                var contentString = "<div class='google_map_scrollFix'>" + map_item_feature_image_link +
                                    "<br><br>" + map_item_link + "<br>" + map_item_rating + map_item_reviews + "<br>" +
                                    map_item_address + "<br>" + map_item_get_direction + "</div>";

                                if (infowindow_hover) {
                                    infowindow_hover.close();
                                }
                                if (infowindow) {
                                    infowindow.close();
                                }

                                infowindow_hover = new google.maps.InfoWindow({
                                    content: contentString,
                                    position: center,
                                    pixelOffset: new google.maps.Size(0, -45)
                                });

                                infowindow_hover.open({
                                    map,
                                    shouldFocus: true,
                                });
                            }
                        });
                        // End google map hover event
                    }
                @endif
            }
        </script>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js??v=quarterly&key={{ $site_global_settings->setting_site_map_google_api_key }}&callback=initMap">
        </script>
    @endif

    <script>
        $(document).ready(function() {

            $('.item-share-button').on('click', function() {
                $('#share-modal').modal('show');
            });
        });

        @error('profile_share_email_name')
            $('#share-modal').modal('show');
        @enderror

        @error('profile_share_email_from_email')
            $('#share-modal').modal('show');
        @enderror

        @error('profile_share_email_to_email')
            $('#share-modal').modal('show');
        @enderror

        @error('profile_share_email_note')
            $('#share-modal').modal('show');
        @enderror
    </script>
    <script>
        $(function() {
            var $sections = $('.form-section');

            function navigateTo(index) {
                $sections.removeClass('current').eq(index).addClass('current');

                $('.form-navigation .previous').toggle(index > 0);
                var atTheEnd = index >= $sections.length - 1;
                $('.form-navigation .next').toggle(!atTheEnd);
                $('.form-navigation [Type=submit]').toggle(atTheEnd);

                const step = document.querySelector('.step' + index);
                // console.log(step);
                step.style.backgroundColor = "#17a2b8";
                step.style.color = "white";
            }

            function curIndex() {
                return $sections.index($sections.filter('.current'));
            }

            $('.form-navigation .previous').click(function() {
                navigateTo(curIndex() - 1);
            });

            $('.form-navigation .next').click(function() {
                $('.contact-coach-form').parsley().whenValidate({
                    group: 'block-' + curIndex()
                }).done(function() {
                    navigateTo(curIndex() + 1);
                });

            });

            $sections.each(function(index, section) {
                $(section).find(':input').attr('data-parsley-group', 'block-' + index);
            });

            navigateTo(0);



        });
    </script>
    <script>
        $("#contact-modal").on("hidden.bs.modal", function() {
            $("#contactFormModal").trigger("reset");
            $('.question1_desc_char_count,.question2_desc_char_count,.question3_desc_char_count,.question5_desc_char_count,.question6_desc_char_count')
                .text("750/750");
            $('.question1_error,.question2_error,.question3_error,.question5_error,.question6_error').html('');
            $('.steps').removeAttr('style');
        })
    </script>
    <script>
        $(document).ready(function() {
            $(".box-video").click(function() {
                $('#iframesrcURL').attr('src', '');
                $('#iframesrcURL').removeAttr('class');
                $('.modal_dialog').removeClass('modal-xl');
                $('.modal-content').removeClass('content_design3');
                $('.modal-content').removeClass('content_design2');
                $('.modal-content').removeClass('content_design1');
                $('#exampleModalLabel_podcast').text('');



                var podcast_div_Id = $(this).data('vid');
                var split_podcast_div_id = podcast_div_Id.split('_')[1];
                var podcastUrl = $('#podcast_id_' + split_podcast_div_id).data('src');
                if (podcastUrl.indexOf('redcircle') > -1) {
                    $('#iframesrcURL').addClass('min_h_800');
                    $('.modal_dialog').addClass('modal-xl');
                    $('.modal-content').addClass('content_design3');
                    $('#exampleModalLabel_podcast').text('RedCircle Podcast');


                }
                if (podcastUrl.indexOf('stitcher') > -1) {
                    $('#iframesrcURL').addClass('min_h_300');
                    $('.modal_dialog').addClass('modal-xl');
                    $('.modal-content').addClass('content_design3');
                    $('#exampleModalLabel_podcast').text('Stitcher Podcast');


                }
                if (podcastUrl.indexOf('apple') > -1) {
                    $('.modal-content').addClass('content_design2');
                    $('#exampleModalLabel_podcast').text('Apple Podcast');

                }

                if (podcastUrl.indexOf('spotify') > -1) {
                    $('.modal-content').addClass('content_design1');
                    $('#exampleModalLabel_podcast').text('Spotify Podcast');

                }
                $('#iframesrcURL').attr('src', podcastUrl);
                // $(this).addClass('open');
            });

            $("#podcastModal").on("hidden.bs.modal", function() {
                $('#iframesrcURL').attr('src', '');
            });

            //for youtube video

            $(".box-video-youtube").click(function() {
                $('#youtubeiframesrcURL').attr('src', '');
                // $('#youtubeiframesrcURL').removeAttr('class');
                // $('#exampleModalLabel_podcast').text('');                


                var youtube_div_Id = $(this).data('vid');
                var split_youtube_div_id = youtube_div_Id.split('_')[1];
                // console.log(split_youtube_div_id)
                var youtubeUrl = $('#youtube_id_' + split_youtube_div_id).data('src');
                // console.log(youtubeUrl);

                $('#youtubeiframesrcURL').attr('src', youtubeUrl);
            });

            $("#youtubeModal").on("hidden.bs.modal", function() {
                $('#youtubeiframesrcURL').attr('src', '');
            });
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/inputmask/inputmask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/inputmask/jquery.inputmask.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>

    <script>
        Inputmask({
            "mask": "999999"
        }).mask(".otp");

        $('#resend_login_otp').on('click', function() {
            $("#resend_login_otp").attr('disabled', true);
            $("#resend_login_otp").attr('data-kt-indicator', 'on');
            $('#kt_login_step_01').trigger('click');
        });

        let interval_otp;

        $(".ap-otp-input").keyup(function(){
            $('#otp_val').text('');
            otp = $('#code_1').val() + $('#code_2').val()  + $('#code_3').val()  + $('#code_4').val()  + $('#code_5').val()  + $('#code_6').val() ;
            check_otp_length = otp.length;
            if(check_otp_length == 6){
                $('#kt_login_step_02').trigger('click');
            }
        });

        $("#kt_login_step_01").on('click', function() {
            $('#email_val').text('');
            if ($('#contactFormModal').valid()) {
                $("#kt_login_step_01").attr('disabled', true);
                $("#kt_login_step_01").attr('data-kt-indicator', 'on');
                email = $('#item_contact_email_from_email').val();
                name = $('#item_conntact_email_name').val();              
                var email_from = email.substring(0, email.indexOf("@"));
                var num = (email_from.match(/\./g)) ? email_from.match(/\./g).length : 0;
                if(num <= 2)
                {
                    $.ajax({
                        url: "/conact-coach",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: "POST",
                        data: {
                            'email': email,
                            'name':name,
                            
                        },
                        success: function(response) {
                            if (response.status==false) {
                                $("#kt_login_step_01").removeAttr('disabled');
                                $("#kt_login_step_01").removeAttr('data-kt-indicator');
                                $("#resend_login_otp").removeAttr('disabled');
                                $("#resend_login_otp").removeAttr('data-kt-indicator');
                                if(response.message){
                                    $('.disabled-login-error').text(response.message);
                                    $('.disabled-login-error').show();
                                }
                                // if (response.errors.email) {
                                //     $('#email_val').text(response.errors.email);
                                // }
                            }else{
                                $("#new_user").val(response.new_user)
                                $("#resend_login_otp_title").text("OTP send in");
                                setCountDownLoginOTP();
                                $("#resend_login_otp").hide();
                                $("#kt_login_step_01").removeAttr('disabled');
                                $("#kt_login_step_01").removeAttr('data-kt-indicator');
                                $("#resend_login_otp").removeAttr('disabled');
                                $("#resend_login_otp").removeAttr('data-kt-indicator');
                                $('.disabled-login-error').text('');
                                $('.disabled-login-error').hide();
                                $(".step-01").hide();
                                $(".step-02").show();
                                $("#code_1").focus();
                                $("#indicator-progress").show();

                                // pls_wit
                            }
                        },
                        error: function(err) {
                            $("#kt_login_step_01").removeAttr('disabled');
                            $("#kt_login_step_01").removeAttr('data-kt-indicator');
                            if(!err.responseJSON.status) {
                                $('.disabled-login-error').text(err.responseJSON.message);
                                $('.disabled-login-error').show();
                                // if (err.responseJSON.errors.email) {
                                //     $('#email_val').text(err.responseJSON.errors.email);
                                // }
                            }
                        }
                    });
                }
                else {
                    $("#kt_login_step_01").removeAttr('disabled');
                    $("#kt_login_step_01").removeAttr('data-kt-indicator');
                    $('#email_val').text('Invalid email !');
                }
            }
        });

        $("#kt_login_step_02").on('click', function() {
            if ($('#contactFormModal').valid()) {
                $("#kt_login_step_02").attr('disabled', true);
                $("#kt_login_step_02").attr('data-kt-indicator', 'on');
                otp = $('#code_1').val() + $('#code_2').val() + $('#code_3').val() + $('#code_4').val() + $('#code_5').val() + $('#code_6').val();
                $.ajax({
                    url: "/verify-conact-coach-email-otp",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    data: {
                        'otp': otp
                    },
                    success: function(res) {
                        if (res.status == true) {
                            console.log(res);
                            $('#contactFormModal').submit();
                        } else {
                            $("#kt_login_step_02").removeAttr('disabled');
                            $("#kt_login_step_02").removeAttr('data-kt-indicator');
                            $('#otp_val').text(res.message);
                        }
                    },
                    error: function(err) {
                        $("#kt_login_step_02").removeAttr('disabled');
                        $("#kt_login_step_02").removeAttr('data-kt-indicator');
                    }
                });
            }
        });

        $("#kt_login_prev_step_01").on('click', function() {
            $('#otp_val').text('');
            $("#code_1").val('');
            $("#code_2").val('');
            $("#code_3").val('');
            $("#code_4").val('');
            $("#code_5").val('');
            $("#code_6").val('');
            clearInterval(interval_otp);
            $(".step-02").hide();	
            $(".step-01").show();	

        });	

        function setCountDownLoginOTP() {
            $(".login_otp_countdown").html('');
            $(".login_otp_countdown").show();
            var otp_timer2 = "1:00";
            interval_otp = setInterval(function() {
                var timer_otp = otp_timer2.split(':');
                //by parsing integer, I avoid all extra string processing
                var minutes = parseInt(timer_otp[0], 10);
                var seconds = parseInt(timer_otp[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) clearInterval(interval_otp);
                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                //minutes = (minutes < 10) ?  minutes : minutes;
                $('.login_otp_countdown').html(minutes + ':' + seconds);
                otp_timer2 = minutes + ':' + seconds;
                if(minutes < 0) {
                    $(".login_otp_countdown").hide();
                    clearInterval(interval_otp);
                    $("#resend_login_otp").show();
                    $("#resend_login_otp_title").text("Didn't get the otp?");
                }
            }, 1000);
        }

        const $inp = $(".ap-otp-input");
        $inp.on({
            paste(ev) { // Handle Pasting
                const clip = ev.originalEvent.clipboardData.getData('text').trim();
                // Allow numbers only
                if (!/\d{6}/.test(clip)) return ev.preventDefault(); // Invalid. Exit here
                // Split string to Array or characters
                const s = [...clip];
                // Populate inputs. Focus last input.
                $inp.val(i => s[i]).eq(5).focus();
            },
            input(ev) { // Handle typing
                const i = $inp.index(this);
                if (this.value) $inp.eq(i + 1).focus();
            },
            keydown(ev) { // Handle Deleting
                const i = $inp.index(this);
                if (!this.value && ev.key === "Backspace" && i) $inp.eq(i - 1).focus();
            }
        });
    </script>
@endsection
