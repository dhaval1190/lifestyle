@extends('backend.user.layouts.app')

@section('content')
    <!-- Content Row -->
    <div class="row bg-white  pr-3 pb-4">
        <div class="col-12">

            <div class="row border-left-info bg-light pt-3">
                <div class="col-12">

                    <form class="" action="{{ route('user.users.coach.list') }}" method="GET">

                        <div class="row form-group">
                            <div class="col-12">
                                <span class="text-gray-800">{{ __('backend.shared.data-filter') }}:</span>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-12 col-md-4 col-lg-3 col-xl-2 mt-2 mt-lg-0 mb-2 mb-sm-0">
                                <input type="text" data-kt-customer-table-filter="search"
                                    value="{{ request()->get('search') }}" id="search" name="search"
                                    class="form-control form-control-solid w-250px ps-15" placeholder="Search">
                            </div>

                            <div class="col-12 col-md-4 col-lg-3 col-xl-2 mt-2 mt-lg-0 mb-2 mb-sm-0">
                                <select class="custom-select" name="user_email_verified">
                                    <option value="{{ \App\User::USER_EMAIL_VERIFIED }}"
                                        {{ $user_email_verified == \App\User::USER_EMAIL_VERIFIED ? 'selected' : '' }}>
                                        {{ __('admin_users_table.email-verified') }}</option>
                                    <option value="{{ \App\User::USER_EMAIL_NOT_VERIFIED }}"
                                        {{ $user_email_verified == \App\User::USER_EMAIL_NOT_VERIFIED ? 'selected' : '' }}>
                                        {{ __('admin_users_table.email-un-verified') }}</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 col-lg-3 col-xl-2 mt-2 mt-lg-0 mb-2 mb-sm-0">
                                <select class="custom-select" name="user_suspended">
                                    <option value="{{ \App\User::USER_NOT_SUSPENDED }}"
                                        {{ $user_suspended == \App\User::USER_NOT_SUSPENDED ? 'selected' : '' }}>
                                        {{ __('admin_users_table.status-active') }}</option>
                                    <option value="{{ \App\User::USER_SUSPENDED }}"
                                        {{ $user_suspended == \App\User::USER_SUSPENDED ? 'selected' : '' }}>
                                        {{ __('admin_users_table.status-suspend') }}</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-4 col-lg-3 col-xl-2 mt-2 mt-lg-0 mb-2 mb-sm-0">
                                <select class="custom-select" name="order_by">
                                    <option value="{{ \App\User::ORDER_BY_USER_NEWEST }}"
                                        {{ $order_by == \App\User::ORDER_BY_USER_NEWEST ? 'selected' : '' }}>
                                        {{ __('admin_users_table.user-order-by-newest') }}</option>
                                    <option value="{{ \App\User::ORDER_BY_USER_OLDEST }}"
                                        {{ $order_by == \App\User::ORDER_BY_USER_OLDEST ? 'selected' : '' }}>
                                        {{ __('admin_users_table.user-order-by-oldest') }}</option>
                                    <option value="{{ \App\User::ORDER_BY_USER_NAME_A_Z }}"
                                        {{ $order_by == \App\User::ORDER_BY_USER_NAME_A_Z ? 'selected' : '' }}>
                                        {{ __('admin_users_table.user-order-by-name-a-z') }}</option>
                                    <option value="{{ \App\User::ORDER_BY_USER_NAME_Z_A }}"
                                        {{ $order_by == \App\User::ORDER_BY_USER_NAME_Z_A ? 'selected' : '' }}>
                                        {{ __('admin_users_table.user-order-by-name-z-a') }}</option>
                                    <option value="{{ \App\User::ORDER_BY_USER_EMAIL_A_Z }}"
                                        {{ $order_by == \App\User::ORDER_BY_USER_EMAIL_A_Z ? 'selected' : '' }}>
                                        {{ __('admin_users_table.user-order-by-email-a-z') }}</option>
                                    <option value="{{ \App\User::ORDER_BY_USER_EMAIL_Z_A }}"
                                        {{ $order_by == \App\User::ORDER_BY_USER_EMAIL_Z_A ? 'selected' : '' }}>
                                        {{ __('admin_users_table.user-order-by-email-z-a') }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4 col-lg-3 col-xl-1 mt-2 mt-lg-0 mb-2 mb-sm-0">
                                <select class="custom-select" name="role_search"
                                    value="{{ request()->get('role_search') }}">
                                    <option value="">Select</option>
                                    @foreach ($role as $key => $value)
                                        @php
                                            $selected = '';
                                            if (request()->get('role_search') == $value->id) {
                                                $selected = 'selected';
                                            }
                                        @endphp
                                        <option {{ $selected }} value="{{ $value->id }}">{{ $value->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 col-md-2 col-lg-3 col-xl-1 mt-2 mt-lg-0 mb-2 mb-sm-0">
                                <button type="submit"
                                    class="btn btn-primary btn-100-width-sm mr-2">{{ __('backend.shared.update') }}</button>
                            </div>
                            <div class="col-12 col-md-2 col-lg-3 col-xl-1 mt-2 mt-lg-0 mb-2 mb-sm-0">
                                <a href="{{ route('user.users.coach.list') }}"
                                    class="btn btn-info btn-100-width-sm mr-2">{{ __('Reset') }}</a>
                            </div>

                        </div>

                    </form>

                </div>
            </div>

            <div class="row pt-4">
                <div class="col-12">
                    {{ $all_users_count . ' ' . __('category_description.records') }}
                </div>
            </div>

            <hr class="mt-1">

            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>{{ __('importer_csv.select') }}</th>
                                    <th>{{ __('backend.user.name') }}</th>
                                    <th>{{ __('backend.user.email') }}</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Category</th>
                                    <th>{{ __('backend.user.email-verified') }}</th>
                                    <th>{{ __('backend.user.status') }}</th>
                                    <th>{{ __('backend.user.created-at') }}</th>
                                    {{-- <th>{{ __('backend.shared.action') }}</th>
                                <th>{{ __('Login') }}</th> --}}
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>{{ __('importer_csv.select') }}</th>
                                    <th>{{ __('backend.user.name') }}</th>
                                    <th>{{ __('backend.user.email') }}</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Category</th>
                                    <th>{{ __('backend.user.email-verified') }}</th>
                                    <th>{{ __('backend.user.status') }}</th>
                                    <th>{{ __('backend.user.created-at') }}</th>
                                    {{-- <th>{{ __('backend.shared.action') }}</th>
                                <th>{{ __('Login') }}</th> --}}
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($all_users as $key => $user)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input users_table_index_checkbox" type="checkbox"
                                                    id="users_table_index_checkbox_{{ $user->id }}"
                                                    value="{{ $user->id }}">
                                                @if (empty($user->user_image))
                                                    <img id="image_preview"
                                                        src="{{ asset('backend/images/placeholder/profile-' . intval($user->id % 10) . '.webp') }}"
                                                        class="img-responsive rounded">
                                                @else
                                                    <img id="image_preview"
                                                        src="{{ Storage::disk('public')->url('user/' . $user->user_image) }}"
                                                        class="img-responsive rounded">
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->role ? $user->role->name : '-' }}</td>
                                        <td>{{ $user->categories? implode(', ',$user->categories()->orderBy('category_name', 'asc')->pluck('category_name')->all()): '-' }}
                                        </td>
                                        <td>
                                            @if (empty($user->email_verified_at))
                                                <span
                                                    class="rounded bg-warning text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.pending') }}</span>
                                            @else
                                                <span
                                                    class="rounded bg-success text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.verified') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->user_suspended == \App\User::USER_SUSPENDED)
                                                <span
                                                    class="rounded bg-warning text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.suspended') }}</span>
                                            @else
                                                <span
                                                    class="rounded bg-success text-white pl-2 pr-2 pt-1 pb-1">{{ __('backend.user.active') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->diffForHumans() }}</td>
                                        {{-- <td>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-circle mb-1">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.login', $user->id) }}" class="btn btn-primary btn-circle mb-1">
                                            <i class="fas fa-user"></i>
                                        </a>
                                    </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <hr class="mb-1">

            <div class="row mb-3">
                <div class="col-12">
                    {{ $all_users_count . ' ' . __('category_description.records') }}
                </div>
            </div>
            @php
                $search_keyword = request()->get('search') ? request()->get('search') : '';
                $role_id = request()->get('role_search') ? request()->get('role_search') : '';
            @endphp

            <div class="row mb-3">
                <div class="col-12">
                    {{ $all_users->appends(['search'=>$search_keyword,'user_email_verified' => $user_email_verified, 'user_suspended' => $user_suspended,'role_search'=>$role_id, 'order_by' => $order_by])->links() }}
                </div>
            </div>

            <hr>

        </div>
    </div>
@endsection
