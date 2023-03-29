
@extends('frontend.layouts.app')
@section('styles')   
@if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
<link href="{{ asset('frontend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
@endif
<link href="{{ asset('frontend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
@endsection   
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!-- Fontawesome CSS -->
<style>

  table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }
  th,
  td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
  }
  th {
    background-color: #F05127;
    color: white;
    font-weight: bold;
  }
  tr:nth-child(even) {
    background-color: #f2f2f2;
  }
  tr:hover {
    background-color: #ddd;
  }

</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-12 p-0">
            <div class="upper_white_bg">
                <div class="upper_middle_img">
                        @if(empty($user_detail['user_cover_image']))                            
                        <div class="site-blocks-cover inner-page-cover overlay main_logo" style="background-image: url( {{ asset('frontend/images/placeholder/header-inner.webp') }});">
                            <div class="container">
                                <div class="row align-items-center justify-content-center text-center">
                                  <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">
                                    <div class="row justify-content-center mt-5">
                                        <div class="col-md-8 text-center">
                                            <h1>{{ __('frontend.notification.title') }}</h1>                                        
                                        </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              @else
                              <div class="site-blocks-cover inner-page-cover overlay main_logo" style="background-image: url( {{ Storage::disk('public')->url('user/'. $user_detail['header-inner.webp']) }});">
                          </div>
                              @endif
                        </div>
                  </div>
              </div>
        </div>
      </div>
</div>
    
  <div class="container mt-5 mb-5">
      <div class="row">
          <table>
            <thead>
              <tr>
                <th style="font-weight:700;padding: 14px 10px;">Notification</th>      
              </tr>
            </thead>
            <tbody>
              @foreach($notifications as $notification)
              <tr>
                  <td>{{$notification->notification}}</td>      
              </tr>
              @endforeach
            </tbody>
          </table>
      </div>
  </div>
       
@endsection