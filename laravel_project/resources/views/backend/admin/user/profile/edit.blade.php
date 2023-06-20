@extends('backend.admin.layouts.app')

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
                    <div class="alert alert-danger alert-dismissible fade show" id="image_error_div" role="alert" style="display:none">
                        <strong id="img_error"></strong>
                    </div>
                    <form method="POST" action="{{ route('admin.users.profile.update') }}" class="">
                        @csrf

                        <div class="row">
                            <div class="col-sm-2">
                                @error('user_image')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button id="upload_image" type="button" class="btn btn-primary btn-block mb-2">{{ __('backend.user.select-image') }}</button>
                                        @if(empty($user_admin->user_image))
                                            <img id="image_preview" src="{{ asset('backend/images/placeholder/profile-' . intval($user_admin->id % 10) . '.webp') }}" class="img-responsive">
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
                                        <label for="name" class="text-black">{{ __('auth.name') }}<span class="text-danger">*</span></label>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user_admin->name) }}" required>
                                        @error('name')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="text-black" for="email">{{ __('auth.email-addr') }}<span class="text-danger">*</span></label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user_admin->email) }}" required>
                                        @error('email')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="phone" class="text-black">Phone<span class="text-danger">*</span></label>
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user_admin->phone) }}" onkeypress="validatePostalCode(event)" required>
                                        @error('phone')
                                        <span class="invalid-tooltip" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label class="text-black" for="user_about">{{ __('backend.user.user-about') }}</label>
                                        <textarea id="user_about" class="form-control @error('user_about') is-invalid @enderror" name="user_about" rows="8">{{ old('user_about', $user_admin->user_about) }}</textarea>
                                        @error('user_about')
                                        <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-5">

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary m-2 text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                                <a class="btn btn-warning m-2 text-white" href="{{ route('admin.users.profile.password.edit') }}">
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
                                <input id="upload_image_input" type="file" class="custom-file-input" accept=".jpg,.jpeg,.png">
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
            $('#upload_image').on('click', function(){
                $('#image-crop-modal').modal('show');
                $('#image_error_div').hide();
                $('#img_error').text('');
            });

            var fileTypes = ['jpg', 'jpeg', 'png'];
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
                var file = this.files[0]; // Get your file here
                var fileExt = file.type.split('/')[1]; // Get the file extension

                if(fileTypes.indexOf(fileExt) !== -1) {
                    reader.onload = function (event) {
                        image_crop.croppie('bind', {
                            url: event.target.result
                        }).then(function(){
                        });
                    };
                reader.readAsDataURL(this.files[0]);
            }else{
                    // alert('Please choose only .jpg,.jpeg,.png file');
                    $('#image-crop-modal').trigger('reset');
                    $('#image-crop-modal').modal('hide');
                    $('#upload_image_input').val('');
                    image_crop = null;
                    $('#image_demo').croppie('destroy');
                    $('#img_error').text('Please choose only .jpg,.jpeg,.png file');
                    $('#image_error_div').show();
                }
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
            $('#delete_user_profile_image_button').on('click', function(){
                $('#delete_user_profile_image_button').attr("disabled", true);
                var ajax_url = '/ajax/user/image/delete/' + '{{ $user_admin->id }}';
                jQuery.ajax({
                    url: ajax_url,
                    method: 'post',
                    success: function(result) {
                        $('#image_preview').attr("src", "{{ asset('backend/images/placeholder/profile-' . intval($user_admin->id % 10) . '.webp') }}");
                        $('#feature_image').val("");
                        $('#delete_user_profile_image_button').attr("disabled", false);
                    }
                });
            });
            /* End delete feature image button */
        });
    </script>

@endsection
