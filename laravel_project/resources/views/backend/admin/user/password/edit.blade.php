@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-8 col-md-9">
            <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('backend.user.change-user-password') }}</h1>
            <p class="mb-4 font-sm-14">{{ __('backend.user.change-user-password-desc') }}</p>
        </div>
        <div class="col-4 col-md-3  text-right">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6">
                    <form method="POST" action="{{ route('admin.users.password.update', $user) }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('backend.user.name') }}:</span> {{ $user->name }} <span class="text-lg text-gray-800">{{ __('backend.user.email') }}:</span> {{ $user->email }}
                                <small class="form-text text-muted">
                                </small>
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label for="new_password" class="text-black">{{ __('backend.user.new-password') }}<span class="text-danger">*</span></label>
                                <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password">
                                <span toggle="#new_password" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                @error('new_password')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">
                                <label for="new_password_confirmation" class="text-black">{{ __('backend.user.password-confirm') }}<span class="text-danger">*</span></label>
                                <input id="new_password_confirmation" type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation">
                                <span toggle="#new_password_confirmation" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                @error('new_password_confirmation')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group justify-content-between">
                            <div class="col-md-8 col-12">
                                <button type="submit" class="btn btn-100-width-sm btn-primary text-white">
                                    {{ __('backend.user.change-password') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    $(document).ready(function(){
                $(".toggle-password").click(function() {

                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $($(this).attr("toggle"));
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
                });
    });


</script>
@endsection
