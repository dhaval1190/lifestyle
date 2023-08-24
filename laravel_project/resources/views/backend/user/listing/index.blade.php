@extends('backend.user.layouts.app')

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
    padding: 20px;
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
    background: #b4b4b4;
    width: 100%;
    height: 3%;
    left: 0;
}

.data_sec_main_b {
    padding: 20px;
}

.data_sec_list {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 100px 100px;
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

.list_name_b:hover{
    text-decoration: none;
    color: #03A438;
}

.list_b{
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
    border:none;
    outline:none;
    box-shadow: 0 1px 12px #b8c6db;
    -moz-box-shadow: 0 1px 12px #b8c6db;
    -webkit-box-shadow: 0 1px 12px #b8c6db;
  }
  
  .data_sec_list .search-btn {
    background-color: transparent;
    font-size: 18px;
    padding: 6px 9px;
    margin-left:-45px;
    border:none;
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
    outline:none;
    color:black;
  }

  .heading_filter{
    margin: 0;
    font-weight: 700;
  }
  
  /* You just need to get this field - end */

  @media (max-width:576px) {

    .data_sec_list{
        grid-template-columns: 1fr;
    }

    .data_sec_main_b {
        padding: 20px 0px;
    }

    .list_btn_b_one, .list_btn_b_two {
        display: block;
        text-align: center;
        margin-bottom: 15px;
    }
    .data_list_title_b {
    padding: 20px 0px;
}

    .filter_bg_sec {
        padding: 10px 20px;
    }

    .search-box {
        /* width: 380px; */
        margin-bottom: 20px;
    }

    .list_b{
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

    .list_btn_b_one, .list_btn_b_two {
        display: block;
        text-align: center;
    }
    .data_sec_main_b {
    padding: 20px 0px;
}
.data_list_title_b {
    padding: 20px 0px;
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
    
  }

  @media (min-width:1026px) and (max-width:1300px) {
    .search-box {
        width: 260px;
    }
    
  }
</style>
    <!-- Content Row -->
    <section class="data_section mb-5 mt-5">
        <div class="row">
            <div class="col-md-12">
              <div class="filter_bg_sec">
                <div class="data_list_title_b">
                  <h2 class="heading_filter">Data Filter</h2>
                </div>
                <form class="" action="{{ route('user.users.coach.list') }}" method="GET">
                    <div class="data_sec_main_b">
                    <div class="data_sec_list">
                        <div class="search-box">
                        <input
                            class="search-input"
                            type="text"
                            placeholder="Search "
                            value="{{ request()->get('search') }}" id="search" name="search"
                        />
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
                                            <option {{ $selected }} value="{{ $value->id }}">{{ $value->name }}
                                            </option>
                                        @endforeach
                                </select>
                            </ul>
                        </div>
                        <div class="data_list_btn_one">
                        <button type="submit"
                                    class="list_btn_b_one w-100">{{ __('backend.shared.update') }}</button>
                        </div>
    
                        <div class="data_list_btn_one">
                        <a href="{{ route('user.users.coach.list') }}" class="list_btn_b_two">Reset</a>
                        </div>
                    </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
      </section>
  
      <section class="list_detalis_b">
        <div class="container-fluid">
          <div class="table-responsive filter_bg_sec">
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
                </tr>
              </thead>
              <tbody>
                @foreach ($all_users as $key => $user)
                    <tr>
                    <td>
                        {{-- <img src="https://coacheshq.com/laravel_project/public/storage/user/user-harsh-2023-07-21-64ba52754a0a5.jpg" alt="" class="list_image" /> --}}
                        @if (empty($user->user_image))
                            <img id="image_preview"
                                src="{{ asset('backend/images/placeholder/profile-' . intval($user->id % 10) . '.webp') }}"
                                class="list_image">
                        @else
                            <img id="image_preview"
                                src="{{ Storage::disk('public')->url('user/' . $user->user_image) }}"
                                class="list_image">
                        @endif
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->role ? $user->role->name : '-' }}</td>
                    <td>{{ $user->categories? implode(', ',$user->categories()->orderBy('category_name', 'asc')->pluck('category_name')->all()): '-' }}</td>
                    <td>
                        <div class="list_active">
                            @if (empty($user->email_verified_at))
                                <a href="javascript:void(0)" class="list_name_b">Pending</a>
                            @else
                                <a href="javascript:void(0)" class="list_name_b">Verified</a>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="list_active">
                            @if ($user->user_suspended == \App\User::USER_SUSPENDED)
                                <a href="javascript:void(0)" class="list_name_b">Suspended</a>
                            @else
                                <a href="javascript:void(0)" class="list_name_b">Active</a>
                            @endif
                        </div>
                    </td>
                    <td>{{ $user->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
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
      </section>
@endsection
