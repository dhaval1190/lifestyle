@extends('backend.admin.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            @if(isset($_GET['is_coach']) && $_GET['is_coach'] == 1)
                <h1 class="h3 mb-2 text-gray-800">Add A Coach</h1>
                <p class="mb-4">This page allows you to create a new coach record in the database.</p>
            @else
                <h1 class="h3 mb-2 text-gray-800">{{ __('backend.user.add-user') }}</h1>
                <p class="mb-4">{{ __('backend.user.add-user-desc') }}</p>
            @endif
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.users.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.users.store') }}" class="">
                        @csrf

                        @if(isset($_GET['is_coach']) && $_GET['is_coach'] == 1)
                            <input type="hidden" name="is_coach" value="{{ \App\Role::COACH_ROLE_ID }}">

                            <div class="row">
                                <div class="col-sm-2">
                                    {{-- <span class="text-lg text-gray-800">{{ __('backend.user.profile-image') }}</span> --}}
                                    {{-- <small class="form-text text-muted">{{ __('backend.user.profile-image-help') }}</small> --}}
                                    @error('user_image')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.user.select-image') }}</button>
                                            @if(empty($user_admin->user_image))
                                                <img id="image_preview" src="{{ asset('backend/images/placeholder/profile-' . intval(rand(0,9)) . '.webp') }}" class="img-responsive">
                                            @else
                                                <img id="image_preview" src="{{ Storage::disk('public')->url('user/'. $user_admin->user_image) }}" class="img-responsive">
                                            @endif
                                            <input id="feature_image" type="hidden" name="user_image">
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-12">
                                            <a class="btn btn-danger btn-block text-white" id="delete_user_profile_image_button">
                                                <i class="fas fa-trash-alt"></i>
                                                {{ __('role_permission.user.delete-profile-image') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label for="category_ids" class="text-black">Category</label>
                                            <select class="form-control selectpicker @error('category_ids') is-invalid @enderror" name="category_ids[]" required multiple title="Select Categories" data-size="10" data-live-search="true">
                                                {{-- <option value="">Select Category</option> --}}
                                                @foreach($printable_categories as $key => $printable_category)
                                                @php
                                                if(empty($printable_category["is_parent"])) continue;
                                                @endphp
                                                <option value="{{ $printable_category["category_id"] }}" {{ in_array($printable_category["category_id"] ,old('category_ids', [])) ? 'selected' : '' }}>{{ $printable_category["category_name"] }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_ids')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-3">
                                            <label for="name" class="text-black">{{ __('auth.name') }}</label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="company_name" class="text-black">Company Name</label>
                                            <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" required>
                                            @error('company_name')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="text-black" for="email">{{ __('auth.email-addr') }}</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="phone" class="text-black">Phone</label>
                                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label class="text-black" for="password">{{ __('backend.user.password') }}</label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="">
                                            @error('password')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-black" for="password_confirmation">{{ __('backend.user.confirm-password') }}</label>
                                            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="">
                                            @error('password_confirmation')
                                            <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        {{-- <div class="col-sm-3">
                                            <label for="gender" class="text-black">Gender</label>
                                            <select class="form-control selectpicker @error('gender') is-invalid @enderror" name="gender" required title="Select Gender">
                                                @foreach(\App\User::GENDER_TYPES as $gkey => $gender)
                                                    <option value="{{ $gkey }}" {{ old('gender') == $gkey ? 'selected' : '' }}>{{ $gender }}</option>
                                                @endforeach
                                            </select>
                                            @error('gender')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div> --}}
                                        <div class="col-sm-3">
                                            <label for="preferred_pronouns" class="text-black">Preferred Pronouns</label>
                                            <select class="form-control selectpicker @error('preferred_pronouns') is-invalid @enderror" name="preferred_pronouns" required title="Select Preferred Pronouns">
                                                @foreach(\App\User::PREFERRED_PRONOUNS as $prkey => $pronoun)
                                                    <option value="{{ $prkey }}" {{ old('preferred_pronouns') == $prkey ? 'selected' : '' }} >{{ $pronoun }}</option>
                                                @endforeach
                                            </select>
                                            @error('preferred_pronouns')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="hourly_rate_type" class="text-black">Hourly Rate</label>
                                            <select class="form-control selectpicker @error('hourly_rate_type') is-invalid @enderror" name="hourly_rate_type" required title="Select Hourly Rate">
                                                @foreach(\App\User::HOURLY_RATES as $hrkey => $rate)
                                                    <option value="{{ $hrkey }}" {{ old('hourly_rate_type') == $hrkey ? 'selected' : '' }} >{{ $rate }}</option>
                                                @endforeach
                                            </select>
                                            @error('hourly_rate_type')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-3">
                                            <label for="working_type" class="text-black">Working Method</label>
                                            <select class="form-control selectpicker @error('working_type') is-invalid @enderror" name="working_type" required title="Select Working Method">
                                                @foreach(\App\User::WORKING_TYPES as $wtkey => $working_type)
                                                    <option value="{{ $wtkey }}" {{ old('working_type') == $wtkey ? 'selected' : '' }} >{{ $working_type }}</option>
                                                @endforeach
                                            </select>
                                            @error('working_type')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="experience_year" class="text-black">Experience Year</label>
                                            <select class="form-control selectpicker @error('experience_year') is-invalid @enderror" name="experience_year" required title="Select Experience">
                                                @foreach(\App\User::EXPERIENCE_YEARS as $eykey => $experience_year)
                                                    <option value="{{ $eykey }}" {{ old('experience_year') == $eykey ? 'selected' : '' }} >{{ $experience_year }}</option>
                                                @endforeach
                                            </select>
                                            @error('experience_year')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="website" class="text-black">Website</label>
                                            <input id="website" type="url" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website') }}" required>
                                            @error('website')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-3">
                                            <label for="instagram" class="text-black">IG Handle</label>
                                            <input id="instagram" type="text" class="form-control @error('instagram') is-invalid @enderror" name="instagram" value="{{ old('instagram') }}" required>
                                            @error('instagram')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="linkedin" class="text-black">LinkedIn</label>
                                            <input id="linkedin" type="text" class="form-control @error('linkedin') is-invalid @enderror" name="linkedin" value="{{ old('linkedin') }}" required>
                                            @error('linkedin')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="facebook" class="text-black">Facebook</label>
                                            <input id="facebook" type="text" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{ old('facebook') }}" required>
                                            @error('facebook')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-3">
                                            <label for="youtube" class="text-black">Youtube</label>
                                            <input id="youtube" type="url" class="form-control @error('youtube') is-invalid @enderror" name="youtube" value="{{ old('youtube') }}" required>
                                            @error('youtube')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-sm-4">
                                    <label for="address" class="text-black">Address</label>
                                    <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required>
                                    @error('address')
                                    <span class="invalid-tooltip" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="country_id" class="text-black">Country</label>
                                    <select id="select_country_id" class="selectpicker form-control @error('country_id') is-invalid @enderror" name="country_id" data-live-search="false" required title="{{ __('prefer_country.select-country') }}">
                                        @foreach($all_countries as $all_countries_key => $country)
                                        @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE)
                                        <option value="{{ $country->id }}" {{ $country->id == old('country_id') ? 'selected' : '' }}>{{ $country->country_name }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                    <span class="invalid-tooltip" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="state_id" class="text-black">State</label>
                                    <select id="select_state_id" class="selectpicker form-control @error('state_id') is-invalid @enderror" name="state_id" data-live-search="true" required title="{{ __('backend.item.select-state') }}">
                                    </select>
                                    @error('state_id')
                                    <span class="invalid-tooltip" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="city_id" class="text-black">City</label>
                                    <select id="select_city_id" class="selectpicker form-control @error('city_id') is-invalid @enderror" name="city_id" data-live-search="true" required title="{{ __('backend.item.select-city') }}">
                                    </select>
                                    @error('city_id')
                                    <span class="invalid-tooltip" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label for="zip" class="text-black">Post Code</label>
                                    <input id="zip" type="text" class="form-control @error('post_code') zip is-invalid @enderror" name="post_code" value="{{ old('post_code') }}" required>
                                    @error('post_code')
                                    <span class="invalid-tooltip" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-sm-2">
                                    {{-- <span class="text-lg text-gray-800">{{ __('backend.user.profile-image') }}</span> --}}
                                    {{-- <small class="form-text text-muted">{{ __('backend.user.profile-image-help') }}</small> --}}
                                    @error('user_image')
                                    <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.user.select-image') }}</button>
                                            @if(empty($user_admin->user_image))
                                                <img id="image_preview" src="{{ asset('backend/images/placeholder/profile-' . intval(rand(0,9)) . '.webp') }}" class="img-responsive">
                                            @else
                                                <img id="image_preview" src="{{ Storage::disk('public')->url('user/'. $user_admin->user_image) }}" class="img-responsive">
                                            @endif
                                            <input id="feature_image" type="hidden" name="user_image">
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-12">
                                            <a class="btn btn-danger btn-block text-white" id="delete_user_profile_image_button">
                                                <i class="fas fa-trash-alt"></i>
                                                {{ __('role_permission.user.delete-profile-image') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <div class="row mt-3">
                                        <div class="col-sm-4">
                                            <label for="name" class="text-black">{{ __('auth.name') }}</label>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="text-black" for="email">{{ __('auth.email-addr') }}</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                            @error('email')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="phone" class="text-black">Phone</label>
                                            <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                                            @error('phone')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label class="text-black" for="password">{{ __('backend.user.password') }}</label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="">
                                            @error('password')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="text-black" for="password_confirmation">{{ __('backend.user.confirm-password') }}</label>
                                            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="">
                                            @error('password_confirmation')
                                            <span class="invalid-tooltip">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4">
                                            <label for="gender" class="text-black">Gender</label>
                                            <select class="form-control selectpicker @error('gender') is-invalid @enderror" name="gender" required title="Select Gender">
                                                @foreach(\App\User::GENDER_TYPES as $gkey => $gender)
                                                    <option value="{{ $gkey }}" {{ old('gender') == $gkey ? 'selected' : '' }}>{{ $gender }}</option>
                                                @endforeach
                                            </select>
                                            @error('gender')
                                            <span class="invalid-tooltip" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label class="text-black" for="user_about">{{ __('backend.user.user-about') }}</label>
                                            <textarea id="user_about" class="form-control @error('user_about') is-invalid @enderror" name="user_about" rows="5">{{ old('user_about') }}</textarea>
                                            @error('user_about')
                                            <span class="invalid-tooltip">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <hr class="mt-5">

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success py-2 px-4 text-white">
                                    {{ __('backend.shared.create') }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Croppie Modal -->
    <div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.user.crop-profile-image') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div id="image_demo"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="custom-file">
                                <input id="upload_image_input" type="file" class="custom-file-input">
                                <label class="custom-file-label" for="upload_image_input">{{ __('backend.user.choose-image') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="crop_image" type="button" class="btn btn-primary">{{ __('backend.user.crop-image') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    @include('backend.user.partials.bootstrap-select-locale')
    <script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>
    <script>

        // Call the dataTables jQuery plugin
        $(document).ready(function() {

            "use strict";

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.selectpicker').selectpicker();

            /* Start the croppie image plugin */
            var image_crop = null;
            $('#upload_image').on('click', function() {
                $('#image-crop-modal').modal('show');
            });

            $('#upload_image_input').on('change', function() {
                if(!image_crop) {
                    image_crop = $('#image_demo').croppie({
                        enableExif: true,
                        viewport: {
                            width: 200,
                            height: 200,
                            type: 'square'
                        },
                        boundary: {
                            width: 300,
                            height: 300
                        },
                        enableOrientation: true
                    });
                }
                var reader = new FileReader();
                reader.onload = function (event) {
                    image_crop.croppie('bind', {
                        url: event.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });
                };
                reader.readAsDataURL(this.files[0]);
            });

            $('#crop_image').on("click", function(event){
                image_crop.croppie('result', {
                    type: 'base64',
                    size: 'viewport'
                }).then(function(response){
                    $('#feature_image').val(response);
                    $('#image_preview').attr("src", response);
                });
                $('#image-crop-modal').modal('hide');
            });
            /* End the croppie image plugin */

            /* Start delete feature image button */
            $('#delete_user_profile_image_button').on('click', function() {
                $('#delete_user_profile_image_button').attr("disabled", true);
                $('#image_preview').attr("src", "{{ asset('backend/images/placeholder/profile-' . intval(rand(0,9)) . '.webp') }}");
                $('#feature_image').val("");
                $('#delete_user_profile_image_button').attr("disabled", false);
            });
            /* End delete feature image button */

            @if(isset($_GET['is_coach']) && $_GET['is_coach'] == 1)
                /* Start country, state, city selector */
                $('#select_country_id').on('change', function() {
                    $('#select_state_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                    $('#select_state_id').selectpicker('refresh');
                    if(this.value > 0) {
                        var ajax_url = '/ajax/states/' + this.value;
                        jQuery.ajax({
                            url: ajax_url,
                            method: 'get',
                            success: function(result) {
                                // $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                                $('#select_state_id').empty();
                                $.each(JSON.parse(result), function(key, value) {
                                    var state_id = value.id;
                                    var state_name = value.state_name;
                                    $('#select_state_id').append('<option value="'+ state_id +'">' + state_name + '</option>');
                                });
                                $('#select_state_id').selectpicker('refresh');
                            }
                        });
                    }
                });

                $('#select_state_id').on('change', function() {
                    $('#select_city_id').html("<option selected value='0'>{{ __('prefer_country.loading-wait') }}</option>");
                    $('#select_city_id').selectpicker('refresh');
                    if(this.value > 0) {
                        var ajax_url = '/ajax/cities/' + this.value;
                        jQuery.ajax({
                            url: ajax_url,
                            method: 'get',
                            success: function(result) {
                                // $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                                $('#select_city_id').empty();
                                $.each(JSON.parse(result), function(key, value) {
                                    var city_id = value.id;
                                    var city_name = value.city_name;
                                    $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                                });
                                $('#select_city_id').selectpicker('refresh');
                            }
                        });
                    }
                });

                @if(old('country_id'))
                    var ajax_url_initial_states = '/ajax/states/{{ old('country_id') }}';
                    jQuery.ajax({
                        url: ajax_url_initial_states,
                        method: 'get',
                        success: function(result) {
                            // $('#select_state_id').html("<option selected value='0'>{{ __('backend.item.select-state') }}</option>");
                            $('#select_state_id').empty();
                            $.each(JSON.parse(result), function(key, value) {
                                var state_id = value.id;
                                var state_name = value.state_name;
                                if(state_id === {{ old('state_id') }}) {
                                    $('#select_state_id').append('<option value="'+ state_id +'" selected>' + state_name + '</option>');
                                } else {
                                    $('#select_state_id').append('<option value="'+ state_id +'">' + state_name + '</option>');
                                }
                            });
                            $('#select_state_id').selectpicker('refresh');
                        }
                    });
                @endif

                @if(old('state_id'))
                    var ajax_url_initial_cities = '/ajax/cities/{{ old('state_id') }}';
                    jQuery.ajax({
                        url: ajax_url_initial_cities,
                        method: 'get',
                        success: function(result){
                            // $('#select_city_id').html("<option selected value='0'>{{ __('backend.item.select-city') }}</option>");
                            $('#select_city_id').empty();
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                if(city_id === {{ old('city_id') }}) {
                                    $('#select_city_id').append('<option value="'+ city_id +'" selected>' + city_name + '</option>');
                                } else {
                                    $('#select_city_id').append('<option value="'+ city_id +'">' + city_name + '</option>');
                                }
                            });
                            $('#select_city_id').selectpicker('refresh');
                        }
                    });
                @endif
                /* End country, state, city selector */
            @endif

        });
    </script>
@endsection
