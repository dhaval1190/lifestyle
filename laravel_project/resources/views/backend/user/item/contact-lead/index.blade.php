@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/datatables/buttons.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="row justify-content-between">
        <div class="col-lg-9 col-12">
            @if(auth()->user()->isCoach())
                <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('Prospective Client List') }}</h1>
            @else
                <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('Chat Lists') }}</h1>
            @endif
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pb-4">
        <div class="col-12">
            @if ($all_contact_leads->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>{{ __('backend.city.id') }}</th>                                
                                <th>{{ __('From') }}</th>
                                <th>{{ __('To') }}</th>
                                <th>{{ __('role_permission.item-leads.item-lead-email') }}</th>
                                <th>{{ __('Mail Page') }}</th>
                                <th>{{ __('role_permission.item-leads.item-lead-received-at') }}</th>
                                <th>{{ __('View Prospects') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_contact_leads as $all_contact_leads_key => $contact_lead)
                                <tr>
                                    <td>{{ $contact_lead->id }}</td>
                                    <td>{{ getUserCoachName($contact_lead->sender_id)->name }}</td>
                                    <td>{{ getUserCoachName($contact_lead->receiver_id)->name }}</td>
                                    <td>{{ $contact_lead->email }}</td>
                                    <td>{{ $contact_lead->profile_article }}</td>
                                    <td>{{ $contact_lead->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('user.contact-leads.edit', ['contact_lead' => $contact_lead]) }}"
                                            class="btn btn-primary btn-circle">
                                            <i class="fas fa-address-book"></i>
                                        </a>
                                        @php
                                        $cid = $contact_lead->receiver_id;
                                        $uid = $contact_lead->sender_id;
                                            $temp_user =  \DB::table('chat_messages')->where(function ($query) use ($cid,$uid) {
                                                                                        $query->where('sender_id',$cid)
                                                                                        ->where('receiver_id',$uid);
                                                                                    })
                                                                                    ->orWhere(function ($query) use ($cid,$uid) {
                                                                                        $query->where('sender_id',$uid)
                                                                                        ->where('receiver_id',$cid);
                                                                                    })
                                                                                    ->orderBy('created_at','asc')
                                                                                    ->get();
                                        @endphp
                                        @if($temp_user->isNotEmpty())
                                        {{-- <a href="{{ route('user.chat.index',['uid' =>$uid,'cid'=>$contact_lead->receiver_id,'con_id'=>$contact_lead->id]) }}" --}}
                                            <a href="{{ route('user.chat.index',['uid' =>base64_encode($uid),'cid'=>base64_encode($contact_lead->receiver_id),'con_id'=>base64_encode($contact_lead->id)]) }}"
                                                class="btn btn-primary btn-circle">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-12">
                        {{ $all_contact_leads->links() }}
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
