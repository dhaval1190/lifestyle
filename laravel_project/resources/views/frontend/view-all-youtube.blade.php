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

    .view a {
        color: red;
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
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"></h1>
        <div class="toggle">
            <div class="ico"><img src="{{ asset('frontend/images/Svg/notification_alert.svg') }}" alt=""class="notification_alert" /></div>
        </div>
    </div>
    <div class="sidebar_two" id="main">
        <div class="d-flex">
            <h2>Notifications</h2>
            <div class="toggle_two" style="color:red;"><button type="button" class="btn btn-primary">Close</button>
            </div>
        </div>

        <div class="notibox">
            <div class="container">
                <div class="row">
                    @if(isset($notifications) && !empty($notifications) && $notifications->count() >= 10)
                    <div class="view"><a href="{{ route('page.user.notification', $login_user['id']) }}">View all</a>
                    </div>
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
    <div class="row">
            @if($login_user->isCoach())
            @php $main_div_class = 'col-lg-12 order-lg-0 order-1'; @endphp
            @else
            @php $main_div_class = 'col-lg-12 order-lg-0 order-1'; @endphp
            @endif
        <div class="{{ $main_div_class }}">          
        @if(isset($media) && !empty($media) || isset($Youtube) && !empty($Youtube))
                <section class="youtube_section col-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info padding-tb-30-lr-45">
                                <h3>Our Youtube</h3>
                                @if(isset($PodcastImage) && !empty($PodcastImage) && count($PodcastImage) >= 4)
                                <a href="#">View all</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 plr-45">
                            <div class="row">
                                @foreach($media as $video_key => $video)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="post-slide">
                                        <div class="post-img">
                                            <iframe width="560" height="315" src="{{ $video['monthly']['media_url']}}"
                                                title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>
                                        </div>
                                        <div class="post-information">
                                            <h4 class="content">Lorem Ipsum doller</h4>
                                        </div>
                                        <div class="view_eye_post">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <p class="views">All</p>
                                            <span class="number">: {{$video['monthly']['totalcount'] }} </span>
                                        </div>
                                        <div class="view_calander_post">
                                            <i class="fa fa-calendar"></i>
                                            <p class="calander">Today</p>
                                            <span class="number">: {{$video['daily']['totalcount'] }} </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @if(isset($Youtube) && !empty($Youtube))
                                @foreach($Youtube as $youtube_key => $youtubevideo)
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="post-slide">
                                        <div class="post-img">
                                            <iframe width="560" height="315"
                                                src="{{ $youtubevideo['monthly']['media_url']}}"
                                                title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; 
                                                encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen></iframe>
                                        </div>
                                        <div class="post-information">
                                            <h4 class="content">Lorem Ipsum doller</h4>
                                        </div>
                                        <div class="view_eye_post">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                            <p class="views">All</p>
                                            <span class="number">: {{$youtubevideo['monthly']['totalcount'] }} </span>
                                        </div>
                                        <div class="view_calander_post">
                                            <i class="fa fa-calendar"></i>
                                            <p class="calander">Today</p>
                                            <span class="number">: {{$youtubevideo['daily']['totalcount'] }} </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
                @endif
        </div>
    </div>
    @endsection
    @section('scripts')    
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
    </script>
    @endsection