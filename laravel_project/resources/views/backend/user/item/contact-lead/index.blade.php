@extends('backend.user.layouts.app')

@section('styles')
    <!-- Custom styles for this page -->
    <link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendor/datatables/buttons.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('content')
@php
    $contact_leads_count = $all_contact_leads->count();
@endphp
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
    @if($contact_leads_count > 0)
        <div class="row">
            <div class="col-3">
                <form class="" action="" method="GET" id="filterLead">
                    <select id="priority-filter" class="form-control" name="filter_lead">
                        <option value="all" {{ Request::get('filter_lead') == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                        <option value="deleted" {{ Request::get('filter_lead') == 'deleted' ? 'selected' : '' }}>{{ __('Deleted') }}</option>
                    </select>
                </form>
            </div>
            <div class="col-3">
                <a href="{{ route('user.contact-leads.index') }}" class="btn btn-primary">Reset</a>
            </div>
        </div>
    @endif
    <div class="row bg-white pt-4 pb-4">
        <div class="col-12">
            @if ($contact_leads_count > 0)
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
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_contact_leads as $all_contact_leads_key => $contact_lead)
                                <tr>
                                    <td>{{ $contact_lead->id }}</td>
                                    <td>{{ getUserCoachName($contact_lead->sender_id) ? getUserCoachName($contact_lead->sender_id)->name : '---' }}</td>
                                    <td>{{ getUserCoachName($contact_lead->receiver_id) ? getUserCoachName($contact_lead->receiver_id)->name : '---' }}</td>
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
                                                class="btn btn-primary btn-circle view">
                                                <i class="fas fa-eye" style="margin-left: -3px;margin-top: 4px;"></i>
                                            </a>
                                            @if($contact_lead->status == 0)
                                                <a href="javascript:void(0)" class="btn btn-danger btn-circle deleteLead" data-id="{{ $contact_lead->id }}"><i class="fas fa-trash"></i></a>
                                            @else
                                                <a href="javascript:void(0)" class="btn btn-success btn-circle restoreLead" data-id="{{ $contact_lead->id }}"><i class="fas fa-plus"></i></a>
                                            @endif
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

    <!-- Modal Delete Listing -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="color: black;">{{ __('Confirm') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="confirm_text">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('user.contact-leads.destroy','leadID') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="action_type" id="action_type">
                        <input type="hidden" name="leadID" id="leadID">
                        <button type="submit" class="btn btn-danger" id="confirm_button"></button>
                    </form>
                </div>
            </div>
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
        $(function(){
            toastr.options.timeOut = 8000;
            @if (Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
            @elseif (Session::has('success'))
                toastr.success("{{ Session::get('success') }}");
            @endif

            $('.deleteLead').click(function(){
                $('#confirm_text').empty();
                let lead_id = $(this).data('id')
                $('#action_type').val('delete');

                $('#confirm_text').text('Do you want to delete this record?');
                $('#confirm_button').removeClass('btn-success');
                $('#confirm_button').addClass('btn-danger');
                $('#confirm_button').text('Delete');
                
                $('#confirmModal').modal('show');
                $('#leadID').val(lead_id);
            });

            $('.restoreLead').click(function(){
                $('#confirm_text').empty();
                let lead_id = $(this).data('id');
                $('#action_type').val('restore');

                $('#confirm_text').text('Do you want to restore this record?');
                $('#confirm_button').removeClass('btn-danger');
                $('#confirm_button').addClass('btn-success');
                $('#confirm_button').text('Restore');
                $('#confirmModal').modal('show');
                $('#leadID').val(lead_id);
            });

            $('#filterLead').change(function(){
                $('#filterLead').submit();
            });
        });
    </script>
@endsection
