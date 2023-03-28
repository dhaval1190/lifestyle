@extends('backend.user.layouts.app')

@section('styles')
@endsection
@section('content')
    <!-- Page Heading -->
    <div class="container">
    <div class="row">
        <div class="col-lg-12 order-lg-0">          
        @if(isset($Ebooks) && !empty($Ebooks))
        <section class="ebook_section col-12">
                    <div class="row ">
                        <div class="col-md-12">
                            <div class="below_info padding-tb-30-lr-45">
                                <h3>Our E-Book</h3>
                            </div>
                        </div>
                        <div class="col-md-12 plr-45">
                            <div class="row">
                                @foreach($Ebooks as $ebook_key => $Ebook)
                                <div class="col-md-3 col-6 pb-3">
                                    <div class="post-img">
                                        <a href="{{ Storage::disk('public')->url('media_files/'. $Ebook['monthly']['media_image']) }}"
                                            target="_blank">
                                            <img src="{{ Storage::disk('public')->url('media_files/'. $Ebook['monthly']['media_cover']) }}"
                                                alt="" class="w-100" /></a>
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
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
                @endif
        </div>
    </div>
  </div>  
    @endsection