@extends('frontend.layouts.app')

@section('styles')   

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <link href="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection

<style>
    .listing .lh-content {
    text-align:left !important;

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

.table > :not(:last-child) > :last-child > * {
  border-bottom-color: inherit;
  border-right: 1px solid #fff;
  border-right-width: thin;
}

table.dataTable>thead>tr>td:not(.sorting_disabled), table.dataTable>thead>tr>th:not(.sorting_disabled) {
  border-bottom-color: inherit;
  border-right: 1px solid #fff;
  border-right-width: thin;
}

.w-30{
  width: 30px !important;
}

.address_wrap{
    word-break: break-all;
}

</style>
@section('content')
    <!-- <div class="site-section"> -->
        <div class="container-fluid">
            
            <div class="row">
                <div class="col-md-12 col-12 p-0">
                    <div class="upper_white_bg">
                        <div class="upper_middle_img">
                            @if(empty($user_detail['user_cover_image']))
                                <div class="site-blocks-cover inner-page-cover overlay main_logo" style="background-image: url( {{ asset('frontend/images/main_upper_logo.png') }});">
                                </div>
                            @else
                                <div class="site-blocks-cover inner-page-cover overlay main_logo" style="background-image: url( {{ Storage::disk('public')->url('user/'. $user_detail['user_cover_image']) }});">
                                    {{-- <div class="site-blocks-cover inner-page-cover overlay main_logo" style="background-image: url( {{ Storage::url('user/'. $user_detail['user_cover_image']) }});"> --}}
                                </div>
                            @endif

                            <div class="upper_main_section">
                                <div class="upper_logo">
                                    @if(empty($user_detail['user_image']))
                                        <img src="{{ asset('backend/images/placeholder/profile-' . intval($user_detail['id'] % 10) . '.webp') }}" class="logo">
                                    @else
                                        <img src="{{ Storage::disk('public')->url('user/'. $user_detail['user_image']) }}" class="logo">
                                    @endif
                                    <div class="space_between">
                                        <div class="upper_logo_details">
                                            <h1>{{ $user_detail['name'] }}</h1>
                                            <p>{{ !empty($user_detail['working_type']) ? \App\User::WORKING_TYPES[$user_detail['working_type']] : '' }} {{ !empty($user_detail['experience_year']) ? ' | '.\App\User::EXPERIENCE_YEARS[$user_detail['experience_year']].' Years' : '' }} {{ !empty($user_detail['hourly_rate_type']) ? ' | '.\App\User::HOURLY_RATES[$user_detail['hourly_rate_type']] : '' }}</p>
                                            <div class="details">
                                                <div class="detail one">
                                                    <img src="{{ asset('frontend/images/bag.png') }}" alt="">
                                                    <p>{{ $user_detail['company_name']}}</p>
                                                </div>
                                                <div class="detail two">
                                                    <img src="{{ asset('frontend/images/bag-one.svg') }}" alt="">
                                                    <p>{{ !empty($user_detail['preferred_pronouns']) ? \App\User::PREFERRED_PRONOUNS[$user_detail['preferred_pronouns']] : '' }}</p>
                                                </div>
                                                @if(isset(Auth::user()->id) && !empty(Auth::user()->id) && $user_detail['id'] == Auth::user()->id)
                                                    <div class="detail two">
                                                        <i class="fas fa-share-alt item-share-refferal-button"style="cursor: pointer;"></i>
                                                    </div>
                                                @endif
                                                <div class="detail one">
                                                <p> <i class="fas fa-eye" style="cursor: pointer;" onclick="window.location='{{ url("visitor-view/".encrypt($user_detail->id)) }}'" id="eye">                                               
                                                    <b>Total Visitor(s)</b> : {{ $All_visit_count }}</i> </p>                                                    
                                                </div> 
                                                <?php
                                                    $userId = '';
                                                    if(isset(Auth::user()->id)){
                                                        $userId = Auth::user()->id ? Auth::user()->id : '';
                                                        }
                                                    ?>    
                                                    @if($user_detail->id != $userId)                                           
                                                <a class="btn btn-primary rounded text-white item-contact-button"><i class="fas fa-phone-alt"></i> {{ __('Contact This Coach') }}</a>&nbsp;
                                                @endif
                                                <a class="btn btn-primary rounded text-white item-share-button"><i class="fas fa-share-alt"></i> {{ __('frontend.item.share') }}</a>
                                            </div>
                                    </div>
                                            <!-- <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $progress_data['percentage']}}%"
                                                    aria-valuenow="{{ $progress_data['percentage']}}" aria-valuemin="0" aria-valuemax="100" title="{{ $progress_data['profile'] }}">
                                                    &nbsp;{{ $progress_data['percentage']}}%
                                                </div>
                                            
                                            </div> -->
                                        </div>
                                        <div class="upper_address_info">
                                            <h3>Address</h3>
                                            <div class="upper_address_detail">
                                                <div class="upper_address">
                                                    <img src="{{ asset('frontend/images/map_icon.svg') }}" alt="" class="address_logo"/>
                                                    <p class="address_wrap">{{ !empty($user_detail['address']) ? $user_detail['address'].',' : '' }} {{ !empty($user_detail->city->city_name) ? $user_detail->city->city_name.',' : '' }} {{ !empty($user_detail->state->state_name) ? $user_detail->state->state_name.',' : '' }} {{ !empty($user_detail->country->country_name) ? $user_detail->country->country_name : '' }} {{ $user_detail['post_code'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                            <div class="upper_logo_info">
                                <div class="upper_logo_padding_25">
                                    <h3>About</h3>
                                    <p style="white-space:pre-line;">{{ $user_detail['user_about']}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="contact-modal" tabindex="-1" role="dialog" aria-labelledby="contact-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Contact This Coach') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>{{ __('frontend.item.share-listing-email') }}</p>
                                @if(!Auth::check())
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ __('frontend.item.login-require') }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <form action="{{ route('page.item.contact', ['item_slug' => $item->item_slug]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="hexId" value = "{{ $hexId}}">
                                    <input type="hidden" name="contact_profile" value = "contact_profile">
                                    <input type="hidden" name="userId" value = "{{ $user_detail->id}}">                                  

                                    <div class="form-row mb-3">
                                        <div class="col-md-6">
                                            <label for="item_conntact_email_name" class="text-black">{{ __('frontend.item.name') }}</label>
                                            <input id="item_conntact_email_name" type="text" class="form-control @error('item_conntact_email_name') is-invalid @enderror" name="item_conntact_email_name" value="{{ isset(auth()->user()->name) ? auth()->user()->name : old('item_conntact_email_name') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                            @error('item_conntact_email_name')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="item_contact_email_from_email" class="text-black">{{ __('frontend.item.email') }}</label>
                                            <input id="item_contact_email_from_email" type="email" class="form-control @error('item_contact_email_from_email') is-invalid @enderror" name="item_contact_email_from_email" value="{{ isset(auth()->user()->email) ? auth()->user()->email : old('item_contact_email_from_email') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                            @error('item_contact_email_from_email')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        {{-- <div class="col-md-4">
                                            <label for="item_share_email_to_email" class="text-black">{{ __('frontend.item.email-to') }}</label>
                                            <input id="item_share_email_to_email" type="email" class="form-control @error('item_share_email_to_email') is-invalid @enderror" name="item_share_email_to_email" value="{{ old('item_share_email_to_email') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                            @error('item_share_email_to_email')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div> --}}
                                    </div>
                                    <div class="form-row mb-3">
                                        <div class="col-md-12">
                                            <label for="item_contact_email_note" class="text-black">{{ __('frontend.item.add-note') }}</label>
                                            <textarea class="form-control @error('item_contact_email_note') is-invalid @enderror" id="item_contact_email_note" rows="3" name="item_contact_email_note" {{ Auth::check() ? '' : 'disabled' }}>{{ old('item_contact_email_note') }}</textarea>
                                            @error('item_contact_email_note')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <button type="submit" id="submit" class="btn btn-primary py-2 px-4 text-white rounded" {{ Auth::check() ? '' : 'disabled' }}>
                                                {{ __('frontend.item.send-email') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal - share -->
<div class="modal fade" id="share-modal" tabindex="-1" role="dialog" aria-labelledby="share-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('frontend.item.share-listing') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">

                        <p>{{ __('frontend.item.share-listing-social-media') }}</p>

                        <!-- Create link with share to Facebook -->
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-facebook" href="" data-social="facebook">
                            <i class="fab fa-facebook-f"></i>
                            {{ __('social_share.facebook') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-twitter" href="" data-social="twitter">
                            <i class="fab fa-twitter"></i>
                            {{ __('social_share.twitter') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-linkedin" href="" data-social="linkedin">
                            <i class="fab fa-linkedin-in"></i>
                            {{ __('social_share.linkedin') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-blogger" href="" data-social="blogger">
                            <i class="fab fa-blogger-b"></i>
                            {{ __('social_share.blogger') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-pinterest" href="" data-social="pinterest">
                            <i class="fab fa-pinterest-p"></i>
                            {{ __('social_share.pinterest') }}
                        </a>
                        {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-evernote" href="" data-social="evernote">
                            <i class="fab fa-evernote"></i>
                            {{ __('social_share.evernote') }}
                        </a> --}}
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-reddit" href="" data-social="reddit">
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
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-skype" href="" data-social="skype">
                            <i class="fab fa-skype"></i>
                            {{ __('social_share.skype') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-telegram" href="" data-social="telegram">
                            <i class="fab fa-telegram-plane"></i>
                            {{ __('social_share.telegram') }}
                        </a>
                        {{-- <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-viber" href="" data-social="viber">
                            <i class="fab fa-viber"></i>
                            {{ __('social_share.viber') }}
                        </a> --}}
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-whatsapp" href="" data-social="whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            {{ __('social_share.whatsapp') }}
                        </a>
                        <a class="btn btn-primary text-white btn-sm rounded mb-2 btn-wechat" href="" data-social="wechat">
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
                        @if(!Auth::check())
                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ __('frontend.item.login-require') }}
                                </div>
                            </div>
                        </div>
                        @endif
                        <form action="{{ route('page.emailProfile.email', ['profile_slug' => $hexId]) }}" method="POST">
                            @csrf
                            <div class="form-row mb-3">
                                <div class="col-md-4">
                                    <label for="profile_share_email_name" class="text-black">{{ __('frontend.item.name') }}</label>
                                    <input id="profile_share_email_name" type="text" class="form-control @error('profile_share_email_name') is-invalid @enderror" name="profile_share_email_name" value="{{ old('profile_share_email_name') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                    @error('profile_share_email_name')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="profile_share_email_from_email" class="text-black">{{ __('frontend.item.email') }}</label>
                                    <input id="profile_share_email_from_email" type="email" class="form-control @error('profile_share_email_from_email') is-invalid @enderror" name="profile_share_email_from_email" value="{{ old('profile_share_email_from_email') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                    @error('profile_share_email_from_email')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="profile_share_email_to_email" class="text-black">{{ __('frontend.item.email-to') }}</label>
                                    <input id="profile_share_email_to_email" type="email" class="form-control @error('profile_share_email_to_email') is-invalid @enderror" name="profile_share_email_to_email" value="{{ old('profile_share_email_to_email') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                    @error('profile_share_email_to_email')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row mb-3">
                                <div class="col-md-12">
                                    <label for="profile_share_email_note" class="text-black">{{ __('frontend.item.add-note') }}</label>
                                    <textarea class="form-control @error('profile_share_email_note') is-invalid @enderror" id="profile_share_email_note" rows="3" name="profile_share_email_note" {{ Auth::check() ? '' : 'disabled' }}>{{ old('profile_share_email_note') }}</textarea>
                                    @error('profile_share_email_note')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <button type="submit"class="btn btn-primary py-2 px-4 text-white rounded" {{ Auth::check() ? '' : 'disabled' }}>
                                        {{ __('frontend.item.send-email') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
        <section class="middle">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="middle_bg">
                            <div class="middle_info">
                                <h3>Contact Details</h3>
                            </div>
                            <div class="middle_detail">
                                <!-- <div class="middle_main"><img src="{{ asset('frontend/images/email.svg') }}" alt="" />
                                    <p>{{ $user_detail['email'] }}</p>
                                </div>
                                <div class="middle_main"><img src="{{ asset('frontend/images/call.svg') }}" alt="" />
                                    <p>{{ $user_detail['phone'] }}</p>
                                </div> -->
                                <div class="middle_main"><img src="{{ asset('frontend/images/web.svg') }}" alt="" />
                                    <a href="{{ $user_detail['website'] }}" target="_blank"><p>{{ $user_detail['website'] }}</p></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <div class="middle_bg_two">
                            <div class="middle_info">
                                <h3>Social Links</h3>
                            </div>
                            <div class="middle_detail">
                                <div class="middle_main"><img src="{{ asset('frontend/images/linkedin.svg') }}" alt="" />
                                    <a href="{{ $user_detail['linkedin'] }}"><p>{{ $user_detail['linkedin'] }}</p></a>
                                </div>
                                <div class="middle_main"><img src="{{ asset('frontend/images/instagram.svg') }}" alt="" />
                                    <a href="{{ $user_detail['instagram'] }}"><p>{{ $user_detail['instagram'] }}</p></a>
                                </div>
                                <div class="middle_main"><img src="{{ asset('frontend/images/facebook.svg') }}" alt="" />
                                    <a href="{{ $user_detail['facebook'] }}"><p>{{ $user_detail['facebook'] }}</p></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="below">
            <div class="container">
                <div class="below_bg">
                @if(isset($user_detail['youtube']) && !empty($user_detail['youtube']) )
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="below_info">
                                <h3>Youtube</h3>
                            </div>
                        </div>
                        <div class="col-lg-12 plr-45">
                            <div class="row">
                                <div class="col-lg-6">
                                     <div class="video">
                                     <div class='c-video__image-container vid-fit-user' data-id="{{$user_detail->id}}" id="#js-fitvideouser_{{$user_detail->id}}">
                                                <div class="bt-play" id="bt-playuser_{{$user_detail->id}}"></div>
                                                <iframe width="500" height="380" src="{{ $user_detail['youtube'] }}" frameborder="0" allowfullscreen id="vid-reveal" class="c-video-reveal" frameborder="0" allow="autoplay"></iframe>                            
                                                </div>
                                        
                                        <!-- <iframe width="500" height="380" src="{{ $user_detail['youtube'] }}"
                                            title="YouTube video player" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe> -->
                                        @php
                                        //print_r($user_detail);exit;
                                        @endphp
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
                                            {{ $user_detail['youtube_intro_description'] }}
                                        </p>
                                        @if(!empty($user_detail['youtube_intro_title']) || !empty($user_detail['youtube_intro_title']))
                                            <a href="{{ route('page.profile.youtube', encrypt($user_detail['id'])) }}">Read More</a>
                                        @endif
                                    </div>
                                </div> 
                            </div>
                        
                        </div>
                    </div>
                    @endif

                    @if(isset($video_media_array) && !empty($video_media_array) && $video_media_array->count() > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="below_info">
                                    <h3>Keep Learning</h3>
                                    @if(isset($video_media_array) && !empty($video_media_array) && $video_media_array->count() >= 5)
                                        <a href="{{ route('page.profile.youtube', encrypt($user_detail['id'])) }}">View all</a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 plr-45">
                                <div id="news-slider" class="owl-carousel">
                                    @foreach($video_media_array as $video_key => $video)                                                                   
                                        <div class="post-slide">
                                            <div class="post-img">
                                                <div class='c-video__image-container vid-fit-reveal' data-id="{{$video->id}}" id="#js-fitvideo_{{$video->id}}">
                                                <div class="bt-play" id="bt-play_{{$video->id}}"></div>
                                                    <iframe width="250" height="215" src="{{ $video['media_url']}}" title="YouTube video player" frameborder="0" id="vid-reveal_{{$video->id}}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>                            
                                                </div>
                                            </div>
                                        </div>
                                        
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($ebook_media_array) && !empty($ebook_media_array) && $ebook_media_array->count() > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="below_info">
                                    <h3>Our E-books</h3>
                                    @if(isset($ebook_media_array) && !empty($ebook_media_array) && $ebook_media_array->count() >= 5)
                                        <a href="{{ route('page.profile.ebook', encrypt($user_detail['id'])) }}">View all</a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 plr-45">
                                <div class="post-slide">
                                    <div class="row">
                                        @foreach($ebook_media_array as $ebook_key => $ebook)
                                            <div class="col-md-3 col-6">
                                                <div class="post-img">                                                    
                                                    <a href="{{ Storage::disk('public')->url('media_files/'. $ebook->media_image) }}" target="_blank"><img src="{{ Storage::disk('public')->url('media_files/'. $ebook->media_cover) }}" alt="" class="ebook" data-id="{{$ebook->id}}" id="#ebook_{{$ebook->id}}"></a>
                                                </div>
                                                <div class="post-information">
                                                    <h4 class="content">{{ $ebook->media_name }}</h4>
                                                </div>
                                            </div>
                                            @if($ebook_key==3)
                                                @php break; @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($podcast_media_array) && !empty($podcast_media_array) && $podcast_media_array->count() > 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="below_info">
                                    <h3>Our Podcast</h3>
                                    @if(isset($podcast_media_array) && !empty($podcast_media_array) && $podcast_media_array->count() >= 5)
                                        <a href="{{ route('page.profile.podcast', encrypt($user_detail['id'])) }}">View all</a>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12 plr-45">
                                <div class="post-slide">
                                    <div class="row audio-players">
                                        @foreach($podcast_media_array as $podcast_key => $podcast)
                                            <div class="col-md-3 col-6">
                                                <div class="audio-player js-audio-player" data-id="{{$podcast->id}}" id="#js-fitvideo_{{$podcast->id}}">
                                                    <button class="audio-player__control js-control">
                                                        <div class="audio-player__control-icon"></div>
                                                    </button>
                                                    <h4 class="audio-player__title">{{ $podcast->media_name }}</h4>
                                                    <audio preload="auto">
                                                        <source src="{{ Storage::disk('public')->url('media_files/'. $podcast->media_image) }}"/>
                                                    </audio>
                                                    <!-- <img class="audio-player__cover" src="https://unsplash.it/g/300?image=29"/> -->
                                                    <img class="audio-player__cover" src="{{ Storage::disk('public')->url('media_files/'. $podcast->media_cover) }}">
                                                    <video preload="auto" loop="loop">
                                                        <source src="" type="video/mp4"/>
                                                    </video>
                                                </div>
                                            </div>
                                            @if($podcast_key==3)
                                                @php break; @endphp
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($free_items) && !empty($free_items) && $free_items->count() > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info">
                                <h3>Topics</h3>
                                @if(isset($free_items) && !empty($free_items) && $free_items->count() >=4 )
                                    <a href="{{ route('page.user.categories', encrypt($user_detail['id'])) }}">View all</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-12 plr-45">
                            <div class="row">
                                @if($free_items->count() > 0)
                                    @foreach($free_items as $free_items_key => $item)
                                        <div class="col-md-3">               
                                            @include('frontend.partials.free-item-block')
                                        </div>
                                
                                    @endforeach
                                @endif
                                    
                        </div>                             
                        </div>                               
                    </div>
                    @endif
                    </div>                    
            </div>
        </section>
    <!-- </div> -->

    <div class="modal fade" id="share-refferal-modal" tabindex="-1" role="dialog" aria-labelledby="share-refferal-modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Share a Referral Link') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>{{ __('frontend.item.share-listing-email') }}</p>
                            @if(!Auth::check())
                            <div class="row mb-2">
                                <div class="col-12">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ __('frontend.item.login-require') }}
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(isset(Auth::user()->id) && !empty(Auth::user()->id) && $user_detail['id'] == Auth::user()->id)
                                <form action="{{ route('page.referral.email', ['referral_link' => Auth::user()->id]) }}" method="POST">
                                    @csrf
                                    <div class="form-row mb-3">
                                        <div class="col-md-4">
                                            <label for="item_share_email_name" class="text-black">{{ __('frontend.item.name') }}</label>
                                            <input id="item_share_email_name" type="text" class="form-control @error('item_share_email_name') is-invalid @enderror" name="item_share_email_name" value="{{ old('item_share_email_name') }}" {{ Auth::check() ? '' : 'disabled' }}>
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
                                            <label for="item_share_email_to_email" class="text-black">{{ __('frontend.item.email-to') }}</label>
                                            <input id="item_share_email_to_email" type="email" class="form-control @error('item_share_email_to_email') is-invalid @enderror" name="item_share_email_to_email" value="{{ old('item_share_email_to_email') }}" {{ Auth::check() ? '' : 'disabled' }}>
                                            @error('item_share_email_to_email')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row mb-3">
                                        <div class="col-md-12">
                                            <label for="item_share_email_note" class="text-black">{{ __('frontend.item.add-note') }}</label>
                                            <textarea class="form-control @error('item_share_email_note') is-invalid @enderror" id="item_share_email_note" rows="3" name="item_share_email_note" {{ Auth::check() ? '' : 'disabled' }}>{{ old('item_share_email_note') }}</textarea>
                                            @error('item_share_email_note')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary py-2 px-4 text-white rounded" {{ Auth::check() ? '' : 'disabled' }}>
                                                {{ __('frontend.item.send-email') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>  
@endsection

@section('scripts')

    {{-- <script src="{{ asset('frontend/vendor/justified-gallery/jquery.justifiedGallery.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('frontend/vendor/colorbox/jquery.colorbox-min.js') }}"></script> --}}
    <script src="{{ asset('frontend/vendor/goodshare/goodshare.min.js') }}"></script>

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="{{ asset('frontend/vendor/leaflet/leaflet.js') }}"></script>
    @endif

    @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
        <!-- Youtube Background for Header -->
        <script src="{{ asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js') }}"></script>
    @endif

    <script src="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.1.3/firebase.js"></script>
   

    @include('frontend.partials.bootstrap-select-locale')
            <script>
                $(".ebook").on('click', function() {
                    var id = $(this).attr('data-id');                                      
                    $.ajax({
                    type:'POST',
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                    url:'<?php echo url("/media-visitor"); ?>',
                    data: {'id':id},
                    success:function(data){
                        console.log(data)
                    },
                });
                });
            $('#submit').on('click', function(){
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
            messaging.onMessage(function (payload) {
                const title = payload.notification.title;
                const options = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };
                new Notification(title, options);
            });
        });
            $(document).ready(function(){
                 $('.item-contact-button').on('click', function(){
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
            $(document).ready(function(){
                $(".vid-fit-user").on('click', function() {
                    var userid = $(this).attr('data-id'); 
                    var main_id = $(this).attr('id');
                    $(main_id).addClass("reveal-video");
                    $('#bt-playuser_'+userid).hide();
                    var myFrame = $(main_id).find('#vid-revealuser_'+userid);
                    var url = $(myFrame).attr('src') + '?autoplay=1';                   
                    $(myFrame).attr('src', url);                  
                    
                    $.ajax({
                    type:'POST',
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                    url:'<?php echo url("/youtube-visitor"); ?>',
                    data: {'userid':userid},
                    success:function(data){
                    },
                });
                });
            });
                $(document).ready(function(){
                $(".vid-fit-reveal").on('click', function() {
                    var id = $(this).attr('data-id'); 
                    var main_id = $(this).attr('id');
                    
                    $(main_id).addClass("reveal-video");
                    $('#bt-play_'+id).hide();
                    var myFrame = $(main_id).find('#vid-reveal_'+id);
                    var url = $(myFrame).attr('src') + '?autoplay=1';                    
                    $(myFrame).attr('src', url);                   
                    
                    $.ajax({
                    type:'POST',
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                    url:'<?php echo url("/media-visitor"); ?>',
                    data: {'id':id},
                    success:function(data){
                    },
                });
                });
            
                $(".js-audio-player").on('click', function() {
                    var id = $(this).attr('data-id');                                      
                    $.ajax({
                    type:'POST',
                    headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                    url:'<?php echo url("/media-visitor"); ?>',
                    data: {'id':id},
                    success:function(data){
                    },
                });
                });
            });
                //  $('#eye').on('click', function(){
                //     $('#share-refferal-modal2').modal('show');
                // });
                $(function(){
        
        $('#eye').click(function(){
            
                if($(this).hasClass('fa-eye')){
                
                $(this).removeClass('fa-eye');
                
                $(this).addClass('fa-eye');
                
                
                
                    
                }else{
                
                $(this).removeClass('fa-eye');
                
                $(this).addClass('fa-eye');  
                
                
                }
            });
        });
        $(document).ready(function(){
            $("#news-slider").owlCarousel({
                items : 4,
                itemsDesktop:[1199,4],
                itemsDesktopSmall:[980,2],
                itemsMobile : [600,1],
                navigation:true,
                navigationText:["",""],
                pagination:true,
                autoPlay:true
            });

            "use strict";

            @if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO)
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            @endif
        });

        $('.item-share-refferal-button').on('click', function(){
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

        var $player = $('.js-audio-player'), $playbackClass = 'is-playing', $fadeDuration = 500

        $player.each(function(index) {
            var $this = $(this), id = 'audio-player-' + index

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
                $audio.animate({ volume: 1 }, $fadeDuration)
                $player.addClass($playbackClass)
            } else {
                $audio.animate({ volume: 0 }, $fadeDuration, function() {
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
                    $this.find('audio').animate({ volume: 0 }, $fadeDuration, function() {
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
    

    @if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_GOOGLE_MAP)
        <script>
              
            // Initial the google map
            function initMap() {

                @if(count($free_items))

                var window_height = $(window).height();
                $('#mapid-box').css('height', window_height + 'px');

                var locations = [];

                @foreach($free_items as $free_items_key => $free_item)
                    @if($free_item->item_type == \App\Item::ITEM_TYPE_REGULAR)

                        var popup_item_title = '{{ $free_item->item_title }}';

                        @if($free_item->item_address_hide)
                        var popup_item_address = '{{ $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code }}';
                        @else
                        var popup_item_address = '{{ $free_item->item_address . ', ' . $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code }}';
                        @endif
                        var popup_item_get_direction = '<a target="_blank" href="'+ '{{ 'https://www.google.com/maps/dir/?api=1&destination=' . $free_item->item_lat . ',' . $free_item->item_lng }}' +'"><i class="fas fa-directions"></i> '+ '{{ __('google_map.get-directions') }}' +'</a>';

                        @if($free_item->getCountRating() > 0)
                        var popup_item_rating = '{{ $free_item->item_average_rating }}' + '/5';
                        var popup_item_reviews = ' - {{ $free_item->getCountRating() }}' + ' ' + '{{ __('category_image_option.map.review') }}';
                        @else
                        var popup_item_rating = '';
                        var popup_item_reviews = '';
                        @endif

                        var popup_item_feature_image_link = '<img src="'+ '{{ !empty($free_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $free_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp') }}' +'">';
                        var popup_item_link = '<a href="' + '{{ route('page.item', $free_item->item_slug) }}' + '" target="_blank">' + popup_item_title + '</a>';

                        locations.push(["<div class='google_map_scrollFix'>" + popup_item_feature_image_link + "<br><br>" + popup_item_link + "<br>" + popup_item_rating + popup_item_reviews + "<br>" + popup_item_address + '<br>' + popup_item_get_direction + "</div>", {{ $free_item->item_lat }}, {{ $free_item->item_lng }} ]);

                     @endif
                @endforeach

                var infowindow = null;
                var infowindow_hover = null;

                if(locations.length === 0)
                {
                    // Destroy mapid-box DOM since no regular listings found
                    $("#mapid-box").remove();
                }
                else
                {
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

                                if(infowindow_hover)
                                {
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

                        if(map_item_reviews > 0)
                        {
                            map_item_rating = this.getAttribute("data-map-rating") + '/5';
                            map_item_reviews = ' - ' + this.getAttribute("data-map-reviews") + ' ' + '{{ __('category_image_option.map.review') }}';
                        }
                        else
                        {
                            map_item_rating = '';
                            map_item_reviews = '';
                        }

                        var map_item_link = '<a href="' + this.getAttribute("data-map-link") + '" target="_blank">' + map_item_title + '</a>';
                        var map_item_feature_image_link = '<img src="'+ this.getAttribute("data-map-feature-image-link") + '">';
                        var map_item_get_direction = '<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=' + map_item_lat + ',' + map_item_lng + '"><i class="fas fa-directions"></i> '+ '{{ __('google_map.get-directions') }}' +'</a>';

                        if(map_item_lat !== '' && map_item_lng !== '')
                        {
                            var center = new google.maps.LatLng(map_item_lat, map_item_lng);
                            var contentString = "<div class='google_map_scrollFix'>" + map_item_feature_image_link + "<br><br>" + map_item_link + "<br>" + map_item_rating + map_item_reviews + "<br>" + map_item_address + "<br>" + map_item_get_direction + "</div>";

                            if(infowindow_hover)
                            {
                                infowindow_hover.close();
                            }
                            if(infowindow)
                            {
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
        <script async defer src="https://maps.googleapis.com/maps/api/js??v=quarterly&key={{ $site_global_settings->setting_site_map_google_api_key }}&callback=initMap"></script>
    @endif

    <script>
        $(document).ready(function(){

            $('.item-share-button').on('click', function(){
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
    

@endsection
