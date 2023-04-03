@extends('backend.user.layouts.app')
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
    /* text-align: left; */
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
  .pagination{
    padding-top: 2px;
  }

</style>

@section('content')

<div class="row justify-content-between">
    <div class="col-9">
        <h1 class="h3 mb-2 text-gray-800">{{ __('Notification') }}</h1>
        <p class="mb-4">{{ __('All notifications will be shown here') }}</p>
    </div>
    
</div>

<div class="container mt-5 mb-5">
    <div class="row">
        <table>
          <thead>            
              <th style="font-weight:700;padding: 14px 10px;">Notification(s)</th>      
              <th style="font-weight:700;padding: 14px 10px;">Status</th>      
          </thead>
          <tbody>
            @foreach($notifications as $notification)
            <tr>
                <td>{{$notification->notification}}</td>   
                <td>{{ $notification->is_read == '0' ? 'Unread' : 'Read' }}</td>   
            </tr>
            
            @endforeach
          </tbody>
        </table>
        <div class="pagination">
          {!! $notifications->links('pagination::bootstrap-4') !!}

        </div>
    </div>
</div>



@endsection


