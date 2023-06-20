@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/datatables/buttons.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row justify-content-between">
        <div class="col-lg-9 col-12">
            <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('All referral list') }}</h1>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pb-4">
        <div class="col-12">

            @if ($all_referrals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>{{ __('backend.city.id') }}</th>
                                <th>{{ __('role_permission.item-leads.item-lead-name') }}</th>
                                <th>{{ __('role_permission.item-leads.item-lead-email') }}</th>
                                <th>{{ __('role_permission.item-leads.item-lead-received-at') }}</th>
                            </tr>
                        </thead>                        
                        <tbody>
                            @foreach ($all_referrals as $all_referrals_key => $referral)
                                <tr>
                                    <td>{{ $referral->id }}</td>
                                    <td>{{ $referral->name }}</td>
                                    <td>{{ $referral->email }}</td>
                                    <td>{{ $referral->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-12">
                        {{ $all_referrals->links() }}
                    </div>
                </div>
            @else
                <p class="text-center">No Record Found!</p>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/datatables/buttons.print.min.js') }}"></script>

    <script>
        // Call the dataTables jQuery plugin
        // $(document).ready(function() {

        //     "use strict";

        //     $('#dataTable').DataTable({
        //         "order": [[ 0, "desc" ]],
        //         "columnDefs": [
        //             {
        //                 "targets": [ 0 ],
        //                 "visible": false,
        //                 "searchable": false
        //             }
        //         ],
        //         dom: 'lBfrtip',
        //         buttons: [
        //             {
        //                 extend: 'copy',
        //                 exportOptions: {
        //                     columns: [1,2,3,4,5,6,7]
        //                 }
        //             },
        //             {
        //                 extend: 'csv',
        //                 exportOptions: {
        //                     columns: [1,2,3,4,5,6,7]
        //                 }
        //             },
        //             {
        //                 extend: 'excel',
        //                 exportOptions: {
        //                     columns: [1,2,3,4,5,6,7]
        //                 }
        //             },
        //             {
        //                 extend: 'pdf',
        //                 exportOptions: {
        //                     columns: [1,2,3,4,5,6,7]
        //                 }
        //             },
        //             {
        //                 extend: 'print',
        //                 exportOptions: {
        //                     columns: [1,2,3,4,5,6,7]
        //                 }
        //             },
        //         ]
        //     });
        // });
    </script>
@endsection
