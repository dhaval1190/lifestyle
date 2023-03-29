@extends('backend.user.layouts.app')

@section('styles')
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="container">
    <div class="row">
        <div class="col-lg-12 order-lg-0">          
            @if(isset($Articledetail) && !empty($Articledetail))
            <section class="article_section col-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="below_info padding-tb-30-lr-45">
                                <h3>{{ __('backend.homepage.article-details') }}</h3>                               
                            </div>
                        </div>
                        <div class="col-md-12 plr-45">
                            <div class="post-slide">
                                <div class="row">
                                    @foreach($Articledetail as $article_key => $Article)
                                    <div class="col-md-3 col-6 mb-5">
                                        <div class="post-img">
                                            <a href="{{ route('page.item', $Article['monthly']['item_slug']) }}"> <img
                                                    src="{{ !empty($Article['monthly']['item_image']) ? Storage::disk('public')->url('item/' . $Article['monthly']['item_image']): asset('frontend/images/placeholder/full_item_feature_image_medium.webp')}}"
                                                    alt="" class="w-100" /></a>
                                        </div>
                                        <div class="post-information">
                                            <h4 class="content"><a class="decoration-none"
                                                    href="{{ route('page.item', $Article['monthly']['item_slug']) }}">{{$Article['monthly']['item_title']}}</a>
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