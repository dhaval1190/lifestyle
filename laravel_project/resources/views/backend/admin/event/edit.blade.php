@extends('backend.admin.layouts.app')

@section('styles')
    @if ($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP)
        <link href="{{ asset('backend/vendor/leaflet/leaflet.css') }}" rel="stylesheet" />
    @endif

    <!-- Image Crop Css -->
    <link href="{{ asset('backend/vendor/croppie/croppie.css') }}" rel="stylesheet" />

    <!-- Bootstrap FD Css-->
    <link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />

    <link href="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/css/bootstrap-select.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="{{ asset('backend/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/dist/ui/trumbowyg.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/dist/plugins/colors/ui/trumbowyg.colors.min.css') }}">
@endsection
<style>
    @import url("https://fonts.googleapis.com/css?family=Open+Sans:400,700");
    @import url("https://fonts.googleapis.com/css?family=Pacifico");

    .input-group-addon {
        cursor: pointer;
    }

    .input-group.date {
        text-transform: uppercase;
    }

    .form-control {
        border: 1px solid #ccc;
        box-shadow: none;
    }

    .form-control:hover,
    .form-control:focus,
    .form-control:active {
        box-shadow: none;
    }

    .form-control:focus {
        border: 1px solid #34495e;
    }

    body {
        background: #e0e0e0;
        font-family: "Open Sans", sans-serif;
        font-size: 14px;
        line-height: 21px;
        padding: 15px 0;
    }

    .content {
        background: #fff;
        border-radius: 3px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.075), 0 2px 4px rgba(0, 0, 0, 0.0375);
        padding: 30px 30px 20px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu {
        border: 1px solid #34495e;
        border-radius: 0;
        box-shadow: none;
        margin: 10px 0 0 0;
        padding: 0;
        min-width: 300px;
        max-width: 100%;
        width: auto;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu.bottom:before,
    .bootstrap-datetimepicker-widget.dropdown-menu.bottom:after {
        display: none;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td,
    .bootstrap-datetimepicker-widget.dropdown-menu table th {
        border-radius: 0;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td.old,
    .bootstrap-datetimepicker-widget.dropdown-menu table td.new {
        color: #bbb;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td.today:before {
        border-bottom-color: #0095ff;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td.active,
    .bootstrap-datetimepicker-widget.dropdown-menu table td.active:hover,
    .bootstrap-datetimepicker-widget.dropdown-menu table td span.active {
        background-color: #0095ff;
        text-shadow: none;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td.active.today:before,
    .bootstrap-datetimepicker-widget.dropdown-menu table td.active:hover.today:before,
    .bootstrap-datetimepicker-widget.dropdown-menu table td span.active.today:before {
        border-bottom-color: #fff;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table th {
        height: 40px;
        padding: 0;
        width: 40px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table th.picker-switch {
        width: auto;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table tr:first-of-type th {
        border-bottom: 1px solid #34495e;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td.day {
        height: 32px;
        line-height: 32px;
        padding: 0;
        width: auto;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu table td span {
        border-radius: 0;
        height: 77px;
        line-height: 77px;
        margin: 0;
        width: 25%;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-months tbody tr td,
    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-years tbody tr td,
    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td {
        padding: 0;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td {
        height: 27px;
        line-height: 27px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td span {
        display: block;
        float: left;
        width: 50%;
        height: 46px;
        line-height: 46px !important;
        padding: 0;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .datepicker-decades tbody tr td span:not(.decade) {
        display: none;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td {
        padding: 0;
        width: 30%;
        height: 20px;
        line-height: 20px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td:nth-child(2) {
        width: 10%;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td a,
    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td span,
    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td button {
        border: none;
        border-radius: 0;
        height: 56px;
        line-height: 56px;
        padding: 0;
        width: 100%;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td span {
        color: #333;
        margin-top: -1px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td button {
        background-color: #fff;
        color: #333;
        font-weight: bold;
        font-size: 1.2em;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .timepicker-picker table td button:hover {
        background-color: #eee;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .picker-switch table td {
        border-top: 1px solid #34495e;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu .picker-switch table td a,
    .bootstrap-datetimepicker-widget.dropdown-menu .picker-switch table td span {
        display: block;
        height: 40px;
        line-height: 40px;
        padding: 0;
        width: 100%;
    }

    .todayText:before {
        content: "Today's Date";
    }
</style>

@section('content')
    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('Edit Event') }}</h1>
            <p class="mb-4">{{ __('This page allows you to create a new event record in the database.') }}</p>
        </div>
        <div class="col-3 text-right">
            <a href="{{ route('admin.events.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <div class="create_event_form_design form_main ">
                    <div class="form_wrap">
                        <div class="form_inner">
                            <div class="form_column_right">
                                <div class="form_column_right_wrap">
                                    {{-- <div class="title_bar">
                                        <h2 class="title">Edit Event</h2>
                                    </div> --}}
                                    <form method="POST" action="{{ route('admin.events.update', $event->id) }}">
                                        @method('PUT')
                                        @csrf
                                        <div class="form_wrap_tag">
                                            <div class="form_column_right_left">
                                                <div class="form_group">
                                                    <label class="group_title">Event Title
                                                        <span>(Required)</span></label>
                                                    <input type="text" @error('event_name') is-invalid @enderror"
                                                        name="event_name"
                                                        value="{{ $event->event_name ? $event->event_name : old('event_name') }}"
                                                        class="input_control" placeholder="Event Name">
                                                    @error('event_name')
                                                        <span class="error_color">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form_group">
                                                    <label class="group_title">Description
                                                        {{-- <span>(Optional)</span></label> --}}
                                                        <textarea class="input_control" id="event_description" name="event_description" placeholder="Enter Description">{{ $event->event_description ? $event->event_description : old('event_description') }}</textarea>
                                                        @error('event_description')
                                                            <span class="error_color">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                </div>
                                                <hr>

                                                <div class="time_group">
                                                    <div class="row">
                                                        <div class="col-md-4 col-lg-8 col-xl-4">
                                                            <div class="d_block_element">
                                                                <label class="element_heading">Event Date</label>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div class="icon">
                                                                    <i class="bi bi-calendar"></i>
                                                                </div>
                                                                <input type="date" name="event_start_date"
                                                                    class="form_time"
                                                                    value="{{ $event_start_date ? $event_start_date : old('event_start_date') }}">
                                                            </div>
                                                            @error('event_start_date')
                                                                <span class="error_color">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>

                                                        <div class="col-md-4 col-lg-4 col-xl-4">
                                                            <div class="d_block_element">
                                                                <label class="element_heading">Start
                                                                    <span>(EST)</span></label>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div class="icon">
                                                                    <i class="bi bi-clock-fill"></i>
                                                                </div>
                                                                <div class="input-group time" id="timepicker1">
                                                                    <input class="form-control" name="event_start_hour"
                                                                        placeholder="HH:mm:ss" value="{{ $event_start_hour }}"/><span
                                                                        class="input-group-append input-group-addon"><span
                                                                            class="input-group-text"><i
                                                                                class="fa fa-clock"></i></span></span>
                                                                </div>
                                                            </div>
                                                            @error('event_start_hour')
                                                                <span class="error_color">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-4 col-lg-4 col-xl-4">
                                                            <div class="d_block_element">
                                                                <label class="element_heading">End
                                                                    <span>(EST)</span></label>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div class="icon">
                                                                    <i class="bi bi-clock-fill"></i>
                                                                </div>
                                                                <div class="input-group time" id="timepicker2">
                                                                    <input class="form-control" name="event_end_hour"
                                                                        placeholder="HH:mm:ss"  value="{{ $event_end_hour }}"/><span
                                                                        class="input-group-append input-group-addon"><span
                                                                            class="input-group-text"><i
                                                                                class="fa fa-clock"></i></span></span>
                                                                </div>
                                                            </div>
                                                            @error('event_end_hour')
                                                                <span class="error_color">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                            @if(Session::has('event_end_error'))
                                                                <span class="error_color">
                                                                    <strong>{{ Session::get('event_end_error') }}</strong>
                                                                </span>                                                                
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>                                           

                                                <div class="form_group">
                                                    <label class="group_title">URL
                                                        <span>(Required)</span></label>
                                                    <input name="event_social_url" type="url" class="form_time"
                                                        value="{{ $event->event_social_url ? $event->event_social_url : old('event_social_url') }}">
                                                    @error('event_social_url')
                                                        <span class="error_color">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form_group d-flex">
                                                    <label class="group_title">Only for Coach?
                                                        <span>(Tick if this event is only for coach)</span></label>
                                                    <input name="coach_chk" type="checkbox" class="ml-3 mb-2" value="1" @if($event->for_coach == 1) checked @endif
                                                        value="{{ old('coach_chk') }}">
                                                </div>


                                            </div>
                                            <div class="form_column_right_right">
                                                <div class="form_group input_file">
                                                    <label class="group_title">Event Image</label>
                                                    <div class="alert alert-danger alert-dismissible fade show" id="image_error_div" role="alert" style="display: none;">
                                                    <strong id="img_error"></strong>
                                                </div>
                                                    <button id="upload_image" type="button"
                                                        class="btn btn-primary btn-sm"><i
                                                            class="fa-solid fa-file-image"></i>
                                                        {{ __('backend.item.select-image') }}
                                                    </button>
                                                    <small
                                                        class="form-text text-muted">{{ __('backend.article.feature-image-ratio') }}</small>
                                                    <small
                                                        class="form-text text-muted">{{ __('maximum file size: 10mb') }}</small>
                                                    <small
                                                        class="form-text text-muted">{{ __('Accepts only JPG,JPEG and PNG image type') }}</small>

                                                    <input type="hidden" name="event_image" class="input_control"
                                                        id="event_image">
                                                    <label class="upload_media" for="file_upload">
                                                        @if (empty($event->event_image))
                                                            <img src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}"
                                                                id="image_preview" class="img-responsive">
                                                        @else
                                                            <img src="{{ Storage::disk('public')->url('event/' . $event->event_image) }}"
                                                                id="image_preview" class="img-responsive">
                                                        @endif
                                                    </label>
                                                </div>

                                                <div class="card_form form_group ">
                                                    <div class="card_body">
                                                        <h3 class="card_heading">Visibility</h3>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" role="switch"
                                                                name="draft_publish" value="0"
                                                                @if ($event->status == '0') checked @endif>
                                                            <i class="bi bi-file-earmark-fill"></i>
                                                            <div class="radio_info">
                                                                <label>Drafts</label>
                                                                <p class="icon_info"> <span>Only you can view this
                                                                        content</span></p>
                                                            </div>
                                                        </div>
                                                        <div>
                                                        </div>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" role="switch"
                                                                name="draft_publish" value="1"
                                                                @if ($event->status == '1') checked @endif>
                                                            <i class="bi bi-file-earmark-fill"></i>
                                                            <div class="radio_info">
                                                                <label>Publish</label>
                                                                <p class="icon_info"><span>All members can view this
                                                                        content</span></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form_footer">
                                            <a href="{{ route('admin.events.index') }}"
                                                class="form_footer_btn cancel_btn" type="reset">Cancel</a>
                                            <button type="submit" class="form_footer_btn">
                                                {{ __('backend.shared.update') }}
                                            </button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- Croppie Modal -->
 <div class="modal fade cropImageModal" id="cropImagePop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 
    <button type="button" class="close-modal-custom" data-dismiss="modal" aria-label="Close"><i class="feather icon-x"></i></button>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="modal-header-bg"></div>
                <div class="up-photo-title">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="color: black;">{{ __('category_image_option.modal-category-image-crop-title') }}</h5>
                </div>
                <div class="up-photo-content pb-5">
                <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="custom-file mt-5">
                                    <input id="upload_image_input" type="file" class="custom-file-input" accept=".jpg,.jpeg,.png">
                                    <label class="custom-file-label" for="upload_image_input">{{ __('backend.article.choose-image') }}</label>
                                </div>
                            </div>
                        </div>
                    <div id="upload-demo" class="center-block mt-3">
                        <!-- <h5><i class="fas fa-arrows-alt mr-1"></i> Drag your photo as you require</h5> -->
                        
                    </div>
                    <div class="upload-action-btn text-center px-2">
                        <button type="button" id="cropImageBtn" class="btn btn-default btn-medium bg-blue px-3 mr-2">{{ __('backend.user.crop-image') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </div>
<!-- --------------------------------------- -->
    <div class="modal fade" id="image-crop-modal" tabindex="-1" role="dialog" aria-labelledby="image-crop-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        {{ __('category_image_option.modal-category-image-crop-title') }}</h5>
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
                                <input id="upload_image_input" type="file" class="custom-file-input"
                                    accept=".jpg,.jpeg,.png">
                                <label class="custom-file-label"
                                    for="upload_image_input">{{ __('backend.item.choose-image') }}</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <button id="crop_image" type="button"
                        class="btn btn-primary">{{ __('backend.item.crop-image') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Image Crop Plugin Js -->
    <script src="{{ asset('backend/vendor/spectrum/spectrum.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.js') }}"></script>

    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('backend/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>

    <script>
        $(document).ready(function() {

            "use strict";

            var image_crop = null;

            $('#upload_image').on('click', function() {

                $('#cropImagePop').modal('show');
            });

            var window_height = $(window).height();
            var window_width = $(window).width();
            var viewport_height = 0;
            var viewport_width = 0;

            if (window_width >= 317) {
                viewport_width = 317;
                viewport_height = 200;
            } else {
                viewport_width = window_width * 0.8;
                viewport_height = (viewport_width * 200) / 317;
            }

            $('#upload_image').on('click', function() {
                $('#cropImagePop').modal('show');
                $('#image_error_div').hide();
                $('#img_error').text('');
            });

            var fileTypes = ['jpg', 'jpeg', 'png'];

            $('#upload_image_input').on('change', function() {

                if (!image_crop) {
                    image_crop = $('#upload-demo').croppie({
                        viewport: {
                            width: 200,
                            height: 200,
                            type: 'sqaure'
                        },
                        enforceBoundary: false,
                        enableExif: true
                    });
                }


                var reader = new FileReader();
                var file = this.files[0]; // Get your file here
                var fileExt = file.type.split('/')[1]; // Get the file extension

                if (fileTypes.indexOf(fileExt) !== -1) {
                    reader.onload = function(event) {

                        image_crop.croppie('bind', {
                            url: event.target.result
                        }).then(function() {
                            // console.log('jQuery bind complete');
                        });

                    };
                    reader.readAsDataURL(this.files[0]);
                } else {
                    // alert('Please choose only .jpg,.jpeg,.png file');
                    $('#cropImagePop').trigger('reset');
                    $('#cropImagePop').modal('hide');
                    $('#upload_image_input').val('');
                    image_crop = null;
                    $('#upload-demo').croppie('destroy');
                    $('#img_error').text('Please choose only .jpg,.jpeg,.png file');
                    $('#image_error_div').show();
                }
            });

            $('#cropImageBtn').on("click", function(event) {

                image_crop.croppie('result', {
                    type: 'base64',
                    size: 'original'
                }).then(function(response) {
                    $('#event_image').val(response);
                    $('#image_preview').attr("src", response);
                });

                $('#cropImagePop').modal('hide')
            });
            /**
             * End the croppie image plugin
             */

            /**
             * Start delete feature image button
             */
            $('#delete_event_image_button').on('click', function() {

                $('#delete_event_image_button').attr("disabled", true);

                $('#image_preview').attr("src",
                    "{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}");
                $('#event_image').val("");

                $('#delete_event_image_button').attr("disabled", false);
            });
            /**
             * End delete feature image button
             */


            /**
             * Start image file upload preview
             */
            $(document).on('change', '.btn-file :file', function() {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function(event, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = label;

                if (input.length) {
                    input.val(log);
                } else {
                    if (log) alert(log);
                }

            });

            function readURL(input, preview_img_id, input_id) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#' + preview_img_id).attr('src', e.target.result);
                        $('#' + input_id).attr('value', e.target.result);

                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#category_header_background_image_selector").change(function() {
                readURL(this, "img-upload-homepage", "category_header_background_image");
            });            
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script>
        if (/Mobi/.test(navigator.userAgent)) {
            // if mobile device, use native pickers
            $(".date input").attr("type", "date");
            $(".time input").attr("type", "time");
        } else {
        // if desktop device, use DateTimePicker
            $("#datepicker").datetimepicker({
                useCurrent: false,
                format: "DD-MMM-YYYY",
                showTodayButton: true,
                icons: {
                next: "fa fa-chevron-right",
                previous: "fa fa-chevron-left",
                today: 'todayText',
                }
            });
            $("#timepicker1").datetimepicker({
                format: 'HH:mm:ss',
                icons: {
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down"
                }
            });
            $("#timepicker2").datetimepicker({
                format: 'HH:mm:ss',
                icons: {
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down"
                }
            });
        }

    </script>  
@endsection
