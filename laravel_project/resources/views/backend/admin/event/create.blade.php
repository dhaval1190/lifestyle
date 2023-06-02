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

@section('content')
    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('Add Event') }}</h1>
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
                                        <h2 class="title">Create Event</h2>
                                    </div> --}}
                                    <form method="POST" action="{{ route('admin.events.store') }}">
                                        @csrf
                                        <div class="form_wrap_tag">
                                            <div class="form_column_right_left">
                                                <div class="form_group">
                                                    <label class="group_title">Event Title
                                                        <span>(Required)</span></label>
                                                    <input type="text" @error('event_name') is-invalid @enderror"
                                                        name="event_name" value="{{ old('event_name') }}"
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
                                                        <textarea class="input_control" id="event_description" name="event_description" placeholder="Enter Description">{{ old('event_description') }}</textarea>
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
                                                                    class="form_time" value="{{ old('event_start_date') }}">
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
                                                                    <span>(UTC)</span></label>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div class="icon">
                                                                    <i class="bi bi-clock-fill"></i>
                                                                </div>
                                                                <input type="time" name="event_start_hour"
                                                                    class="form_time" value="{{ old('event_start_hour') }}">
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
                                                                    <span>(UTC)</span></label>
                                                            </div>
                                                            <div class="d-flex">
                                                                <div class="icon">
                                                                    <i class="bi bi-clock-fill"></i>
                                                                </div>
                                                                <input name="event_end_hour" type="time"
                                                                    class="form_time" value="{{ old('event_end_hour') }}">
                                                                </div>
                                                                @error('event_end_hour')
                                                                    <span class="error_color">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <hr>

                                                <!-- <div class="form_group">
                                                                        <label class="group_title">Location
                                                                            <span>(Required)</span></label>
                                                                            <div class="select">
                                                                        <select class="input_control select__field">
                                                                            <option>Select Location</option>
                                                                            <option>Select Location</option>
                                                                            <option>Select Location</option>
                                                                            <option>Select Location</option>
                                                                        </select>
                                                                        </div>
                                                                    </div> -->

                                                <div class="form_group">
                                                    <label class="group_title">URL
                                                        <span>(Required)</span></label>
                                                    <input name="event_social_url" type="url" class="form_time" value="{{ old('event_social_url') }}">
                                                    @error('event_social_url')
                                                        <span class="error_color">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>


                                            </div>
                                            <div class="form_column_right_right">
                                                <div class="form_group input_file">
                                                    <label class="group_title">Event Image</label>
                                                    <button id="upload_image" type="button"
                                                    class="btn btn-primary btn-sm"><i class="fa-solid fa-file-image"></i>
                                                    {{ __('backend.item.select-image') }}
                                                    </button>
                                                    <small class="form-text text-muted">{{ __('backend.article.feature-image-ratio') }}</small>
                                                    <small class="form-text text-muted">{{ __('maximum file size: 10mb') }}</small>
                                                    <small class="form-text text-muted">{{  __('Accepts only JPG,JPEG and PNG image type') }}</small>
                                                    <input type="hidden" name="event_image" class="input_control"
                                                        id="event_image">
                                                    <label class="upload_media" for="file_upload">
                                                        <img src="" id="image_preview" class="img-responsive">
                                                    </label>
                                                </div>

                                                <div class="card_form form_group ">
                                                    <div class="card_body">
                                                        <h3 class="card_heading">Visibility</h3>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="radio" role="switch"
                                                                name="draft_publish" value="0" checked>
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
                                                                name="draft_publish" value="1">
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
                                            <a href="{{ route('admin.events.index') }}" class="form_footer_btn cancel_btn"
                                                type="reset">Cancel</a>
                                            <button type="submit" class="form_footer_btn">
                                                {{ __('backend.shared.create') }}
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

    <!-- Modal - Event image -->
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
    <script src="{{ asset('backend/vendor/croppie/croppie.js') }}"></script>
    <script src="{{ asset('backend/vendor/spectrum/spectrum.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.js') }}"></script>

    <script src="{{ asset('backend/vendor/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.3/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- @include('backend.admin.partials.bootstrap-select-locale') --}}

    <script src="{{ asset('backend/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    {{-- <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/resizimg/resizable-resolveconflict.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/jquery-resizable/dist/jquery-resizable.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/trumbowyg.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/base64/trumbowyg.base64.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/resizimg/trumbowyg.resizimg.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/colors/trumbowyg.colors.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/fontfamily/trumbowyg.fontfamily.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/indent/trumbowyg.indent.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/lineheight/trumbowyg.lineheight.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/noembed/trumbowyg.noembed.min.js') }}"></script>
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/table/trumbowyg.table.min.js') }}"></script> --}}

    <script>
        $(document).ready(function() {

            "use strict";

            /**
             * Start color picker
             */
            $('#category_header_background_color').spectrum({
                type: "component",
                togglePaletteOnly: "true",
                showInput: "true",
                showInitial: "true",
                showAlpha: "false"
            });
            /**
             * End color picker
             */

            /**
             * Start the croppie image plugin
             */
            $('.selectpicker').selectpicker();
            var image_crop = null;

            $('#upload_image').on('click', function() {

                $('#image-crop-modal').modal('show');
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
                $('#image-crop-modal').modal('show');
                $('#image_error_div').hide();
                $('#img_error').text('');
            });

            var fileTypes = ['jpg', 'jpeg', 'png'];

            $('#upload_image_input').on('change', function() {

                if (!image_crop) {
                    image_crop = $('#image_demo').croppie({
                        enableExif: true,
                        mouseWheelZoom: false,
                        viewport: {
                            width: 600,
                            height: 600,
                            type: 'square'
                        },
                        boundary: {
                            width: 700,
                            height: 700
                        },
                        enableOrientation: true
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
                    $('#image-crop-modal').trigger('reset');
                    $('#image-crop-modal').modal('hide');
                    $('#upload_image_input').val('');
                    image_crop = null;
                    $('#image_demo').croppie('destroy');
                    $('#img_error').text('Please choose only .jpg,.jpeg,.png file');
                    $('#image_error_div').show();
                }
            });

            $('#crop_image').on("click", function(event) {

                image_crop.croppie('result', {
                    type: 'base64',
                    size: 'viewport'
                }).then(function(response) {
                    $('#event_image').val(response);
                    $('#image_preview').attr("src", response);
                });

                $('#image-crop-modal').modal('hide')
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
            /**
             * End image file upload preview
             */

            $('#event_start_date').datepicker({
                format: 'yyyy-mm-dd',
            });
            $('#event_end_date').datepicker({
                format: 'yyyy-mm-dd',
            });
        });
    </script>
@endsection
