@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection
@php
    $date = request()->get('search_date');
    $date_ex = explode('-',$date);

    @endphp
@section('content')

    <div class="row justify-content-between">
        <div class="col-8 col-md-9">
            <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('Search History') }}</h1>
            <!-- <p class="mb-4 font-sm-14">{{ __('review.backend.manage-reviews-desc') }}</p> -->
        </div>
        <div class="col-4 col-md-3 text-right">

        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 font_icon_color pb-4">
        <div class="col-12">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="row mb-2">
                        <div class="col-12"><span class="text-lg">{{ __('Date-filter') }}</span></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-12">
                            <form class="form-inline" action="{{ route('page.search.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-6" id='kt_daterangepicker_6'>        
                                        <input type='text' name="search_date" value="{{isset($date) ? $date_ex[0]. '-' .$date_ex[1] : '' }}" autocomplete="off" class="form-control" readonly placeholder="Select date range" />
                                </div>
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-primary" style ="margin-left: 30px;">{{ __('Search') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>{{ __('Id') }}</th>
                                <th>{{ __('Search Input') }}</th>
                                <th>{{ __('User') }}</th>                           
                                <th>{{ __('Date') }}</th>                                
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                 <th>{{ __('Id') }}</th>
                                <th>{{ __('Search Input') }}</th>  
                                <th>{{ __('User') }}</th>                           
                                <th>{{ __('Date') }}</th>                                
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($search_data as $key => $search)                           
                                @php
                                    if($search->user_id){
                                        $_name = \App\User::find($search->user_id)->name; 
                                    }else{
                                        $_name = "-";   
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $search->id }}</td>
                                    <td>{{ $search->search_input }}</td> 
                                    <td>{{$_name }}</td>                                                          
                                    <td>{{ date('Y-m-d',strtotime($search->created_at)) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- Page level plugins -->
    
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css" />
    <script type="text/javascript" src="{{asset('frontend/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function() {

            var KTBootstrapDaterangepicker = function() {
                // Private functions
                var demos = function() {
                    // predefined ranges
                    var start = moment().subtract(29, 'days');
                    var end = moment();

                    $('#kt_daterangepicker_6').daterangepicker({
                        startDate: start,
                        endDate: end,
                        ranges: {
                            'Today': [moment(), moment()],
                            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                            'This Month': [moment().startOf('month'), moment().endOf('month')],
                            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                        }
                    }, function(start, end, label) {
                        $('#kt_daterangepicker_6 .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
                    });
                }
                return {
                    // public functions
                    init: function() {
                        demos();
                    }
                };
            }();

            jQuery(document).ready(function() {
                KTBootstrapDaterangepicker.init();
            });

            "use strict";
            $('#dataTable').DataTable({
            order: [[0 , "desc" ]]
            });
        });
        
    </script>
@endsection
