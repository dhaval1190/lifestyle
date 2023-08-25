@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .filter_bg_sec {
            background: #ffffff 0% 0% no-repeat padding-box;
            box-shadow: 0px 20px 20px #00000008;
            border-radius: 20px;
            /* padding: 20px; */
            position: relative;
        }

        .data_list_title_b {
            position: relative;
            padding: 20px;
        }

        .heading_filter::after {
            position: absolute;
            bottom: 0px;
            /* border-top: 1px solid #b4b4b4; */
            display: block;
            content: "";
            background: #dee2e6;
            width: 100%;
            height: 3%;
            left: 0;
        }

        .data_sec_main_b {
            padding: 20px;
        }

        .data_sec_list {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 100px 100px;
            align-items: center;
            column-gap: 15px;
        }

        .list_btn_b_one {
            background: #4471fa 0% 0% no-repeat padding-box;
            border-radius: 12px;
            padding: 10px 24px;
            color: #fff;
            letter-spacing: 0px;
            font-size: 16px;
            font-weight: 600;
            outline: none;
            border: none;
        }

        .list_btn_b_one:hover {
            color: #fff;
            text-decoration: none;
        }

        .list_btn_b_two:hover {
            color: #fff;
            text-decoration: none;
        }

        .list_btn_b_two {
            background: #f15031 0% 0% no-repeat padding-box;
            border-radius: 12px;
            padding: 10px 24px;
            color: #fff;
            letter-spacing: 0px;
            font-size: 16px;
            font-weight: 600;
            outline: none;
            border: none;
            display: block;
            text-align:center;
        }

        /* ================================== */


        .list_detalis_b .table td,
        .table th {
            border-top: none;
            vertical-align: baseline;
            white-space: nowrap;
        }

        .list_detalis_b .table thead th {
            color: #272727;
            font-size: 20px;
            font-weight: 700;
            white-space: nowrap;
        }

        .list_image {
            width: 121px;
            border-radius: 12px;
        }

        .list_name_b {
            background: #03A4381A 0% 0% no-repeat padding-box;
            border-radius: 6px;
            padding: 5px 10px;
            color: #03A438;
            font-size: 16px;
            font-weight: 600;
        }

        .list_name_a {
            background: #a45e031a 0% 0% no-repeat padding-box;
            border-radius: 6px;
            padding: 5px 10px;
            color: #ff0000;
            font-size: 16px;
            font-weight: 600;
        }

        .list_name_b:hover {
            text-decoration: none;
            color: #03A438;
        }

        .list_name_a:hover {
            text-decoration: none;
            color: #ff0000;
        }

        .list_b {
            margin-bottom: 0px;
        }

        /* ============================ */





        /* You just need to get this field - start */

        .data_sec_list .search-box {
            width: 100%;
            position: relative;
            display: flex;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }

        .data_sec_list .search-input {
            width: 100%;
            font-family: 'Montserrat', sans-serif;
            font-size: 16px;
            padding: 10px 37px 10px 19px;
            background-color: #fff;
            border: 1px solid #ced4da;
            color: #6c6c6c;
            border-radius: 6px;
        }

        .data_sec_list .search-input:focus {
            border: none;
            outline: none;
            box-shadow: 0 1px 12px #b8c6db;
            -moz-box-shadow: 0 1px 12px #b8c6db;
            -webkit-box-shadow: 0 1px 12px #b8c6db;
        }

        .data_sec_list .search-btn {
            background-color: transparent;
            font-size: 18px;
            padding: 6px 9px;
            margin-left: -45px;
            border: none;
            color: #6c6c6c;
            transition: all .4s;
            z-index: 10;
        }

        .data_sec_list .search-btn:hover {
            transform: scale(1.2);
            cursor: pointer;
            color: black;
        }

        .data_sec_list .search-btn:focus {
            outline: none;
            color: black;
        }

        .heading_filter {
            margin: 0;
            font-weight: 700;
        }

        /* You just need to get this field - end */

        @media (max-width:576px) {

            .data_sec_list {
                grid-template-columns: 1fr;
            }

            .data_sec_main_b {
                padding: 20px 0px;
            }

            .list_btn_b_one,
            .list_btn_b_two {
                display: block;
                text-align: center;
                margin-bottom: 15px;
            }

            .data_list_title_b {
                padding: 20px 0px;
            }

            .filter_bg_sec {
                padding: 10px;
            }

            .search-box {
                /* width: 380px; */
                margin-bottom: 20px;
            }

            .list_b {
                margin-bottom: 20px;
            }

            .data_sec_list .search-box {
                margin-bottom: 15px;
            }



        }

        @media (min-width:768px) and (max-width:992px) {
            .search-box {
                width: auto;
            }

            .data_sec_list {
                grid-template-columns: 1fr 1fr 1fr;
                row-gap: 15px;
            }

            .list_btn_b_one,
            .list_btn_b_two {
                display: block;
                text-align: center;
            }

            .data_sec_main_b {
                padding: 20px 0px;
            }

            .data_list_title_b {
                padding: 20px 0px;
            }
            .filter_bg_sec{
                padding: 10px 15px
            }
        }

        @media (min-width:993px) and (max-width:1025px) {
            .search-box {
                width: auto;
            }

            .list_detalis_b .table thead th {
                font-size: 14px;
            }

            .data_sec_list .search-input {
                padding: 10px 37px 10px 10px;
            }
            .data_sec_list {
                grid-template-columns: 1fr 1fr 1fr ;
                row-gap: 10px;
}
        }

        @media (min-width:1026px) and (max-width:1300px) {
            .search-box {
                width: 260px;
            }

        }
    </style>

    <div class="row justify-content-between">
        <div class="col-4 col-md-6">
            <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('backend.user.user') }}</h1>
        </div>
        <div class="col-8 col-md-6 text-right">
            <a href="{{ route('admin.users.create') }}?is_coach=1" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add a Coach</span>
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">{{ __('backend.user.add-user') }}</span>
            </a>
        </div>
        <div class="col-12">
            <p class="mb-4 mt-2 mt-sm-0 font-sm-14">{{ __('backend.user.user-desc') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <section class="data_section mb-3">
        <div class="row">
            <div class="col-md-12">
                <div class="filter_bg_sec">
                    <div class="data_list_title_b">
                        <h2 class="heading_filter">Data Filter</h2>
                    </div>
                    <form class="" action="{{ route('admin.users.index') }}" method="GET">
                        <div class="data_sec_main_b">
                            <div class="data_sec_list">
                                <div class="search-box">
                                    <input class="search-input" type="text" placeholder="Search "
                                        value="{{ request()->get('search') }}" id="search" name="search" />
                                    <button type="submit" class="search-btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div>
                                    <ul class="list_b">
                                        <select id="priority-filter" class="form-control" name="user_email_verified">
                                            <option value="{{ \App\User::USER_EMAIL_VERIFIED }}"
                                                {{ $user_email_verified == \App\User::USER_EMAIL_VERIFIED ? 'selected' : '' }}>
                                                {{ __('admin_users_table.email-verified') }}</option>
                                            <option value="{{ \App\User::USER_EMAIL_NOT_VERIFIED }}"
                                                {{ $user_email_verified == \App\User::USER_EMAIL_NOT_VERIFIED ? 'selected' : '' }}>
                                                {{ __('admin_users_table.email-un-verified') }}</option>
                                        </select>
                                    </ul>
                                </div>

                                <div>
                                    <ul class="list_b">
                                        <select id="priority-filter" class="form-control" name="user_suspended">
                                            <option value="{{ \App\User::USER_NOT_SUSPENDED }}"
                                                {{ $user_suspended == \App\User::USER_NOT_SUSPENDED ? 'selected' : '' }}>
                                                {{ __('admin_users_table.status-active') }}</option>
                                            <option value="{{ \App\User::USER_SUSPENDED }}"
                                                {{ $user_suspended == \App\User::USER_SUSPENDED ? 'selected' : '' }}>
                                                {{ __('admin_users_table.status-suspend') }}</option>
                                        </select>
                                    </ul>
                                </div>

                                <div>
                                    <ul class="list_b">
                                        <select id="priority-filter" class="form-control" name="order_by">
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
                                    </ul>
                                </div>
                                <div>
                                    <ul class="list_b">
                                        <select id="priority-filter" class="form-control" name="count_per_page">
                                            <option value="{{ \App\User::COUNT_PER_PAGE_10 }}"
                                                {{ $count_per_page == \App\User::COUNT_PER_PAGE_10 ? 'selected' : '' }}>
                                                {{ __('admin_users_table.shared.count-per-page-10') }}</option>
                                            <option value="{{ \App\User::COUNT_PER_PAGE_25 }}"
                                                {{ $count_per_page == \App\User::COUNT_PER_PAGE_25 ? 'selected' : '' }}>
                                                {{ __('admin_users_table.shared.count-per-page-25') }}</option>
                                            <option value="{{ \App\User::COUNT_PER_PAGE_50 }}"
                                                {{ $count_per_page == \App\User::COUNT_PER_PAGE_50 ? 'selected' : '' }}>
                                                {{ __('admin_users_table.shared.count-per-page-50') }}</option>
                                            <option value="{{ \App\User::COUNT_PER_PAGE_100 }}"
                                                {{ $count_per_page == \App\User::COUNT_PER_PAGE_100 ? 'selected' : '' }}>
                                                {{ __('admin_users_table.shared.count-per-page-100') }}</option>
                                            <option value="{{ \App\User::COUNT_PER_PAGE_250 }}"
                                                {{ $count_per_page == \App\User::COUNT_PER_PAGE_250 ? 'selected' : '' }}>
                                                {{ __('admin_users_table.shared.count-per-page-250') }}</option>
                                            <option value="{{ \App\User::COUNT_PER_PAGE_500 }}"
                                                {{ $count_per_page == \App\User::COUNT_PER_PAGE_500 ? 'selected' : '' }}>
                                                {{ __('admin_users_table.shared.count-per-page-500') }}</option>
                                            <option value="{{ \App\User::COUNT_PER_PAGE_1000 }}"
                                                {{ $count_per_page == \App\User::COUNT_PER_PAGE_1000 ? 'selected' : '' }}>
                                                {{ __('admin_users_table.shared.count-per-page-1000') }}</option>
                                        </select>
                                    </ul>
                                </div>

                                <div>
                                    <ul class="list_b">
                                        <select id="priority-filter" class="form-control" name="role_search"
                                            value="{{ request()->get('role_search') }}">
                                            <option value="">Select</option>
                                            @foreach ($role as $key => $value)
                                                @php
                                                    $selected = '';
                                                    if (request()->get('role_search') == $value->id) {
                                                        $selected = 'selected';
                                                    }
                                                @endphp
                                                <option {{ $selected }} value="{{ $value->id }}">
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </ul>
                                </div>
                                <div class="data_list_btn_one">
                                    <button type="submit"
                                        class="list_btn_b_one w-100">{{ __('backend.shared.update') }}</button>
                                </div>

                                <div class="data_list_btn_one w-100">
                                    <a href="{{ route('admin.users.index') }}" class="list_btn_b_two">Reset</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="list_detalis_b">
        <div class="container-fluid p-0">
            <div class="row mb-3">
                <div class="col-12">
                    {{ $all_users_count . ' ' . __('category_description.records') }}
                </div>
            </div>
            <div class="table-responsive filter_bg_sec mb-3">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Select</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Role</th>
                            <th scope="col">Category</th>
                            <th scope="col">Email Verified</th>
                            <th scope="col">Status</th>
                            <th scope="col">Created at</th>
                            <th>{{ __('backend.shared.action') }}</th>
                            <th>{{ __('Login') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($all_users as $key => $user)
                            <tr>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input users_table_index_checkbox" type="checkbox" id="users_table_index_checkbox_{{ $user->id }}" value="{{ $user->id }}">
                                        @if (empty($user->user_image))
                                            <img id="image_preview"
                                                src="{{ asset('backend/images/placeholder/profile-' . intval($user->id % 10) . '.webp') }}"
                                                class="list_image">
                                        @else
                                            <img id="image_preview"
                                                src="{{ Storage::disk('public')->url('user/' . $user->user_image) }}"
                                                class="list_image">
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ? $user->phone : '---' }}</td>
                                <td>{{ $user->role ? $user->role->name : '-' }}</td>
                                {{-- <td>{{ $user->categories ? implode(', ',$user->categories()->orderBy('category_name', 'asc')->pluck('category_name')->all()) : '-' }}</td> --}}
                                @if(count($user->categories) <= 0)
                                    <td>{{ "---" }}</td>
                                @else
                                    <td>{{ implode(', ',$user->categories()->orderBy('category_name', 'asc')->pluck('category_name')->all()) }}</td>
                                @endif
                                <td>
                                    <div class="list_active">
                                        @if (empty($user->email_verified_at))
                                            <a href="javascript:void(0)" class="list_name_a">Pending</a>
                                        @else
                                            <a href="javascript:void(0)" class="list_name_b">Verified</a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="list_active">
                                        @if ($user->user_suspended == \App\User::USER_SUSPENDED)
                                            <a href="javascript:void(0)" class="list_name_a">Suspended</a>
                                        @else
                                            <a href="javascript:void(0)" class="list_name_b">Active</a>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-circle mb-1">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.login', $user->id) }}" class="btn btn-primary btn-circle mb-1">
                                        <i class="fas fa-user"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    {{ $all_users_count . ' ' . __('category_description.records') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12 col-md-8 ">
                    <button id="select_all_button" class="btn btn-sm btn-primary text-white btn-100-width-sm mb-2 mb-sm-0" >
                        <i class="far fa-check-square"></i>
                        {{ __('admin_users_table.shared.select-all') }}
                    </button>
                    <button id="un_select_all_button" class="btn btn-sm btn-primary text-white btn-100-width-sm">
                        <i class="far fa-square"></i>
                        {{ __('admin_users_table.shared.un-select-all') }}
                    </button>
                </div>
                <div class="col-12 col-md-4 text-right">
                    <div class="dropdown">
                        <button class="btn btn-info btn-sm dropdown-toggle text-white btn-100-width-sm  mt-2 mt-sm-0" type="button" id="table_option_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-tasks"></i>
                            {{ __('admin_users_table.shared.options') }}
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="table_option_dropdown">
                            <button class="dropdown-item" type="button" id="verify_selected_button">
                                <i class="far fa-check-circle"></i>
                                {{ __('admin_users_table.verify-selected') }}
                            </button>
                            <button class="dropdown-item" type="button" id="suspend_selected_button">
                                <i class="fas fa-lock"></i>
                                {{ __('admin_users_table.suspend-selected') }}
                            </button>
                            <button class="dropdown-item" type="button" id="un_lock_selected_button">
                                <i class="fas fa-unlock-alt"></i>
                                {{ __('admin_users_table.un-lock-selected') }}
                            </button>
                            <div class="dropdown-divider"></div>
                            <button class="dropdown-item text-danger" type="button" data-toggle="modal" data-target="#deleteModal">
                                <i class="far fa-trash-alt"></i>
                                {{ __('admin_users_table.shared.delete-selected') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $search_keyword = request()->get('search') ? request()->get('search') : '';
            $role_id = request()->get('role_search') ? request()->get('role_search') : '';
        @endphp

        <div class="row mb-3">
            <div class="col-12">
                {{ $all_users->appends(['search' => $search_keyword, 'user_email_verified' => $user_email_verified, 'user_suspended' => $user_suspended, 'role_search' => $role_id, 'order_by' => $order_by])->links() }}
            </div>
        </div>
    </section>

    <!-- Start forms for selected buttons -->
    <form action="{{ route('admin.users.bulk.verify', $request_query_array) }}" method="POST"
        id="form_verify_selected">
        @csrf
    </form>

    <form action="{{ route('admin.users.bulk.suspend', $request_query_array) }}" method="POST"
        id="form_suspend_selected">
        @csrf
    </form>

    <form action="{{ route('admin.users.bulk.unsuspend', $request_query_array) }}" method="POST"
        id="form_unsuspend_selected">
        @csrf
    </form>
    <!-- End forms for selected buttons -->

    <!-- Modal Delete User -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ __('admin_users_table.delete-selected-users-confirm') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>

                    <form action="{{ route('admin.users.bulk.delete', $request_query_array) }}" method="POST"
                        id="form_delete_selected">
                        @csrf
                        <button id="delete_selected_button"
                            class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            "use strict";

            /**
             * Start select all button
             */
            $('#select_all_button').on('click', function() {

                $(".users_table_index_checkbox").each(function(index) {
                    $(this).prop('checked', true);
                });

            });
            /**
             * End select all button
             */

            /**
             * Start un-select all button
             */
            $('#un_select_all_button').on('click', function() {

                $(".users_table_index_checkbox").each(function(index) {
                    $(this).prop('checked', false);
                });

            });
            /**
             * End un-select all button
             */

            /**
             * Start verify selected button action
             */
            $('#verify_selected_button').on('click', function() {

                $(".users_table_index_checkbox:checked").each(function(index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'user_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_verify_selected');

                });

                $("#form_verify_selected").submit();
            });
            /**
             * End verify selected button action
             */


            /**
             * Start suspend selected button action
             */
            $('#suspend_selected_button').on('click', function() {

                $(".users_table_index_checkbox:checked").each(function(index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'user_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_suspend_selected');

                });

                $("#form_suspend_selected").submit();
            });
            /**
             * End suspend selected button action
             */


            /**
             * Start unlock selected button action
             */
            $('#un_lock_selected_button').on('click', function() {

                $(".users_table_index_checkbox:checked").each(function(index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'user_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_unsuspend_selected');

                });

                $("#form_unsuspend_selected").submit();
            });
            /**
             * End unlock selected button action
             */


            /**
             * Start delete selected button action
             */
            $('#delete_selected_button').on('click', function() {

                $(".users_table_index_checkbox:checked").each(function(index) {

                    var selected_checkbox_value = $(this).val();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'user_id[]',
                        value: selected_checkbox_value
                    }).appendTo('#form_delete_selected');

                });

                $("#form_delete_selected").submit();
            });
            /**
             * End delete selected button action
             */
        });
    </script>
@endsection
