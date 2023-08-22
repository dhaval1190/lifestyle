@extends('backend.admin.layouts.app')

@section('styles')
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('Email ID') }}</h1>
            <p class="mb-4">{{ __('This page allows you edit the configuration of email ID.') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <form method="POST" action="{{ route('admin.settings.email.update') }}" class="">
                        @csrf

                        <div class="row form-group">
                            <div class="col-md-12">
                                <label for="email_id" class="text-black">{{ __('Enter email IDs') }}</label>
                                <input id="email_id" type="text" class="form-control @error('email_id') is-invalid @enderror" name="email_id" value="{{ $email_ids->email_id }}">
                                <small class="form-text text-muted">
                                    {{ __('Enter email IDs separated by comma(,)') }}
                                </small>
                                @error('email_id')
                                <span class="invalid-tooltip ">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row form-group" style="margin-top:40px;">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary py-2 px-4 text-white">
                                    {{ __('backend.shared.update') }}
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
@endsection
