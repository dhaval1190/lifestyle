@extends('backend.user.layouts.app')

@section('styles')
    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.user.edit-profile') }}</h1>
            <p class="mb-4">{{ __('backend.user.edit-profile-desc') }}</p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('user.profile.update') }}" class="">
                        @csrf

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
                                        @if(empty($login_user->user_image))
                                            <img id="image_preview" src="{{ asset('backend/images/placeholder/profile-' . intval($login_user->id % 10) . '.webp') }}" class="img-responsive">
                                        @else
                                            <img id="image_preview" src="{{ Storage::disk('public')->url('user/'. $login_user->user_image) }}" class="img-responsive">
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
                                            <option value="{{ $printable_category["category_id"] }}" {{ in_array($printable_category["category_id"] ,old('category_ids', $login_user->categories()->pluck('categories.id')->all())) ? 'selected' : '' }}>{{ $printable_category["category_name"] }}</option>
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
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $login_user->name) }}" required>
                                        @error('name')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="company_name" class="text-black">Company Name</label>
                                        <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name', $login_user->company_name) }}" required>
                                        @error('company_name')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="text-black" for="email">{{ __('auth.email-addr') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $login_user->email) }}" required>
                                        @error('email')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="phone" class="text-black">Phone</label>
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $login_user->phone) }}" required>
                                        @error('phone')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        <label for="preferred_pronouns" class="text-black">Preferred Pronouns</label>
                                        <select class="form-control selectpicker @error('preferred_pronouns') is-invalid @enderror" name="preferred_pronouns" required title="Select Preferred Pronouns">
                                            <option value="she_her" {{ old('preferred_pronouns', $login_user->preferred_pronouns) == 'she_her' ? 'selected' : '' }} >She / Her</option>
                                            <option value="he_his" {{ old('preferred_pronouns', $login_user->preferred_pronouns) == 'he_his' ? 'selected' : '' }} >He / His</option>
                                        </select>
                                        @error('preferred_pronouns')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="gender" class="text-black">Gender</label>
                                        <select class="form-control selectpicker @error('gender') is-invalid @enderror" name="gender" required title="Select Gender">
                                            <option value="male" {{ old('gender', $login_user->gender) == 'male' ? 'selected' : '' }} >Male</option>
                                            <option value="female" {{ old('gender', $login_user->gender) == 'female' ? 'selected' : '' }} >Female</option>
                                        </select>
                                        @error('gender')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="experience_year" class="text-black">Experience Year</label>
                                        <select class="form-control selectpicker @error('experience_year') is-invalid @enderror" name="experience_year" required title="Select Experience">
                                            <option value="0-2" {{ old('experience_year', $login_user->experience_year) == '0-2' ? 'selected' : '' }}>0-2</option>
                                            <option value="3-5" {{ old('experience_year', $login_user->experience_year) == '3-5' ? 'selected' : '' }}>3-5</option>
                                            <option value="5-10" {{ old('experience_year', $login_user->experience_year) == '5-10' ? 'selected' : '' }}>5-10</option>
                                            <option value="10+" {{ old('experience_year', $login_user->experience_year) == '10+' ? 'selected' : '' }}>10 or more</option>
                                        </select>
                                        @error('experience_year')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="website" class="text-black">Website</label>
                                        <input id="website" type="url" class="form-control @error('website') is-invalid @enderror" name="website" value="{{ old('website', $login_user->website) }}" required>
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
                                        <input id="instagram" type="url" class="form-control @error('instagram') is-invalid @enderror" name="instagram" value="{{ old('instagram', $login_user->instagram) }}" required>
                                        @error('instagram')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="linkedin" class="text-black">LinkedIn</label>
                                        <input id="linkedin" type="url" class="form-control @error('linkedin') is-invalid @enderror" name="linkedin" value="{{ old('linkedin', $login_user->linkedin) }}" required>
                                        @error('linkedin')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="facebook" class="text-black">Facebook</label>
                                        <input id="facebook" type="url" class="form-control @error('facebook') is-invalid @enderror" name="facebook" value="{{ old('facebook', $login_user->facebook) }}" required>
                                        @error('facebook')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="youtube" class="text-black">Youtube</label>
                                        <input id="youtube" type="url" class="form-control @error('youtube') is-invalid @enderror" name="youtube" value="{{ old('youtube', $login_user->youtube) }}" required>
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
                                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $login_user->address) }}" required>
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
                                        @if($country->country_status == \App\Country::COUNTRY_STATUS_ENABLE || ($country->country_status == \App\Country::COUNTRY_STATUS_DISABLE && $login_user->country_id == $country->id))
                                            <option value="{{ $country->id }}" {{ $country->id == old('country_id', $login_user->country_id) ? 'selected' : '' }}>{{ $country->country_name }}</option>
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
                                <select id="select_state_id" class="selectpicker form-control @error('state_id') is-invalid @enderror" name="state_id" data-live-search="true" data-size="10" required title="{{ __('backend.item.select-state') }}">
                                    @if($all_states)
                                        @foreach($all_states as $key => $state)
                                            <option {{ $login_user->state_id == $state->id ? 'selected' : '' }} value="{{ $state->id }}">{{ $state->state_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('state_id')
                                <span class="invalid-tooltip" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-2">
                                <label for="city_id" class="text-black">City</label>
                                <select id="select_city_id" class="selectpicker form-control @error('city_id') is-invalid @enderror" name="city_id" data-live-search="true" data-size="10" required title="{{ __('backend.item.select-city') }}">
                                    @if($all_cities)
                                        @foreach($all_cities as $key => $city)
                                            <option {{ $login_user->city_id == $city->id ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->city_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('city_id')
                                <span class="invalid-tooltip" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-sm-2">
                                <label for="post_code" class="text-black">Post Code</label>
                                <input id="post_code" type="text" class="form-control @error('post_code') is-invalid @enderror" name="post_code" value="{{ old('post_code', $login_user->post_code) }}" required>
                                @error('post_code')
                                <span class="invalid-tooltip" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="text-black" for="user_about">{{ __('backend.user.user-about') }}</label>
                                <textarea rows="4" id="user_about" class="form-control @error('user_about') is-invalid @enderror" name="user_about">{{ old('user_about') ? old('user_about') : $login_user->user_about }}</textarea>
                                @error('user_about')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        <hr class="mt-5">

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success m-2 text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                                <a class="btn btn-warning m-2 text-white" href="{{ route('user.profile.password.edit') }}">
                                    {{ __('backend.user.change-password') }}
                                </a>
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

            $('#crop_image').on("click", function(event) {
                image_crop.croppie('result', {
                    type: 'base64',
                    size: 'viewport'
                }).then(function(response){
                    $('#feature_image').val(response);
                    $('#image_preview').attr("src", response);
                });
                $('#image-crop-modal').modal('hide')
            });
            /* End the croppie image plugin */

            /* Start delete feature image button */
            $('#delete_user_profile_image_button').on('click', function() {
                $('#delete_user_profile_image_button').attr("disabled", true);
                var ajax_url = '/ajax/user/image/delete/' + '{{ $login_user->id }}';
                jQuery.ajax({
                    url: ajax_url,
                    method: 'post',
                    success: function(result){
                        console.log(result);

                        $('#image_preview').attr("src", "{{ asset('backend/images/placeholder/profile-' . intval($login_user->id % 10) . '.webp') }}");
                        $('#feature_image').val("");

                        $('#delete_user_profile_image_button').attr("disabled", false);
                    }
                });
            });
            /* End delete feature image button */

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

        });
    </script>

@endsection
