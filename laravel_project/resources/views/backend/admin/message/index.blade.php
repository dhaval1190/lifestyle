@extends('backend.admin.layouts.app')

@section('styles')
<!-- Custom styles for this page -->
<link href="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="row justify-content-between">
    <div class="col-8 col-md-9">
        <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('backend.message.message') }}</h1>
        <p class="mb-4 font-sm-14">{{ __('backend.message.message-desc') }}</p>
    </div>
    <div class="col-4 col-md-3 text-right">

    </div>
</div>

<!-- Content Row -->
<div class="row bg-white pt-4 font_icon_color pb-4">
    <div class="col-12">
        <div class="row mb-2">
            <div class="col-12"><span class="text-lg">{{ __('backend.shared.data-filter') }}</span></div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <form class="form-inline" action="{{ route('admin.messages.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mr-2">
                                <select class="custom-select" name="user_id">
                                    <option value="0">{{ __('backend.message.all-users') }}</option>
                                    @foreach($all_users as $key => $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $user_id ? 'selected' : '' }}>
                                        {{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary ">{{ __('backend.shared.update') }}</button>
                            <div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<div class="row bg-white pt-4 font_icon_color pb-4">
    <div class="col-12 ">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('backend.message.id') }}</th>
                        <th>{{ __('backend.message.subject') }}</th>
                        <th>{{ __('backend.message.creator') }}</th>
                        <th>{{ __('backend.message.participants') }}</th>
                        <th>{{ __('backend.message.last-message') }}</th>
                        <th>{{ __('backend.shared.action') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>{{ __('backend.message.id') }}</th>
                        <th>{{ __('backend.message.subject') }}</th>
                        <th>{{ __('backend.message.creator') }}</th>
                        <th>{{ __('backend.message.participants') }}</th>
                        <th>{{ __('backend.message.last-message') }}</th>
                        <th>{{ __('backend.shared.action') }}</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($threads as $key => $thread)
                    <tr>
                        <td>{{ $thread->id }}</td>
                        <td>{{ $thread->subject }}</td>
                        <td>{{ $thread->creator()->name }}</td>
                        <td>{{ $thread->participantsString() }}</td>
                        <td>{{ $thread->latestMessage->body }}</td>
                        <td>
                            <a href="{{ route('admin.messages.show', $thread->id) }}"
                                class="btn btn-primary btn-circle">
                                <i class="fas fa-search"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Page level plugins -->
<script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
// Call the dataTables jQuery plugin
$(document).ready(function() {

    "use strict";

    $('#dataTable').DataTable({
        order: [[0 , "desc" ]]
    });

});
</script>
@endsection