@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    {{-- @if($paid_subscription_days_left == 1)
        <div class="alert alert-warning" role="alert">
            {{ __('backend.subscription.subscription-end-soon-day') }}
        </div>
    @elseif($paid_subscription_days_left > 1 && $paid_subscription_days_left <= \App\Subscription::PAID_SUBSCRIPTION_LEFT_DAYS)
        <div class="alert alert-warning" role="alert">
            {{ __('backend.subscription.subscription-end-soon-days', ['days_left' => $paid_subscription_days_left]) }}
        </div>
    @endif --}}

    <div class="row justify-content-between">
        <div class="col-md-8 col-xl-9 col-8">
            <h1 class="h3 mb-2 text-gray-800 font-set-sm">{{ __('backend.subscription.subscription') }}</h1>
            <p class="mb-4 f-12">{{ __('backend.subscription.subscription-desc-user') }}</p>
        </div>
        <div class="col-4 col-md-4 col-xl-3 text-right">            
                <a href="{{ route('user.plans.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.subscription.switch-plan') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4 font_set_span_sm">
        @if(Session::has('success'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
        @if(Session::has('error'))
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
        <div class="col-12 p-0">

            <div class="row mb-4">
                <div class="col-12 col-md-6">
                    <div class="row mb-3">
                        <div class="col-12">
                            <span class="text-gray-800 border_set_sm">{{ __('backend.plan.plan-info') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6 col-lg-4">
                          <span>  {{ __('backend.plan.plan-name') }}:</span>
                        </div>
                        <div class="col-6 col-md-6 col-lg-8">
                           <span> {{ $plan_details->plan_name }} </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6 col-lg-4">
                          <span>  {{ __('backend.plan.price') }}:</span>
                        </div>
                        <div class="col-6 col-md-6 col-lg-8">
                          <span>${{ $plan_details->plan_price }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="row mb-3">
                        <div class="col-12">
                            <span class="text-gray-800 border_set_sm">{{ __('backend.subscription.subscription') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6 col-lg-6">
                           <span> {{ __('backend.subscription.started-at') }}:</span>
                        </div>
                        <div class="col-6 col-md-6 col-lg-6">
                           <span>@if($plan_details->plan_type != '1') {{ $current_subscription->subscription_start_date }} @endif </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6 col-lg-6">
                            <span>@if ($current_subscription->ends_at == null) {{ "Renew on" }} @else {{ __('backend.subscription.end-at') }}{{ " (Cancelled)" }} @endif:</span>
                        </div>
                        <div class="col-6 col-md-6 col-lg-6">
                           <span>@if ($current_subscription->ends_at == null) {{ $current_subscription->subscription_end_date  }} @else {{ $current_subscription->ends_at }}  @endif</span>
                        </div>
                    </div>                    
                </div>
            </div>

            <hr/>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="row mb-3">
                        <div class="col-12">
                            <span class="text-gray-800 border_set_sm">Invoices</span>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-8 gy-5" id="kt_table_invoices">
                                        <thead>
                                            <tr class="text-start fw-bolder fs-7 text-uppercase gs-0">
                                                <th class="min-w-125px">Number</th>
                                                <th class="min-w-125px">Amount</th>
                                                <th class="min-w-125px">Paid For</th>
                                                <th class="min-w-125px">Method</th>
                                                <th class="min-w-125px">Created At</th>
                                                <th class="min-w-80px">Status</th>
                                                <th class="text-end min-w-100px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoices as $invoice)
                                                
                                                <tr id="{{$invoice->id}}">
                                                    <td>{{$invoice->number}}</td>
                                                    <td>
                                                        @if($invoice->billing_reason == 'manual' && $invoice->metadata && isset($invoice->metadata->campaign_register_id) )
                                                            ${{ number_format( (isset($invoice->lines->data[0]) ? $invoice->lines->data[0]->amount : $invoice->amount_paid)/100, 2) }}
                                                        @else
                                                            ${{number_format($invoice->amount_paid/100, 2)}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($invoice->billing_reason == 'manual' && $invoice->metadata && isset($invoice->metadata->campaign_register_id) )
                                                            Campaign Registry Fee
                                                        @else
                                                            Subscription
                                                        @endif
                                                    </td>
                                                    <td>{{$invoice->collection_method}}</td>
                                                    <td>{{ date('Y-m-d',$invoice->created) }}</td>
                        
                                                    <td>{{$invoice->status}}</td>
                                                    <td class="text-end">                                                       
                                                        
                                                        <a href="{{$invoice->invoice_pdf}}" class="menu-link px-3">Download</a>
                                                            
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            "use strict";

            $('#dataTable').DataTable();
        });
    </script>
@endsection
