@extends('backend.admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/dist/ui/trumbowyg.min.css') }}">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-12 col-md-12 col-lg-12 col-xl-6">
            <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('Agreement Page') }}</h1>
            <p class="mb-4 font-sm-14">{{ __('This page allows you to edit agreement page body content of the website.') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 font_icon_color pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.settings.page.agreement.update') }}" class="">
                        @csrf
                        <input type="hidden" name="last_update_date" value="{{ $last_update_date }}">

                        <div class="row form-group">

                            <div class=" col-12 col-md-12 col-lg-6 col-xl-6">

                                <div class="custom-control custom-checkbox">
                                    {{-- <input value="1" name="setting_page_about_enable" type="checkbox" class="custom-control-input" id="setting_page_about_enable"> --}}
                                    <p>Last Updated on : <b>{{ $agreement_data->setting_page_agreement_updated_date }}</b></p>
                                </div>                              
                            </div>

                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">

                                <label class="text-black" for="setting_page_agreement">{{ __('backend.shared.page-editor') }}</label>
                                <textarea id="setting_page_agreement" type="text" class="form-control @error('setting_page_agreement') is-invalid @enderror" name="setting_page_agreement">{{ $agreement_data->setting_page_agreement_text }}</textarea>
                                @error('setting_page_agreement')
                                <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>

                        </div>

                        <hr/>

                        <div class="row form-group justify-content-between">
                            <div class="col-8">
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

    <!-- Import only if you use JQuery UI with Resizable interaction -->
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/resizimg/resizable-resolveconflict.min.js') }}"></script>
    <!-- Import dependency for Resizimg. For a production setup, follow install instructions here: https://github.com/RickStrahl/jquery-resizable -->
    <script src="{{ asset('backend/vendor/jquery-resizable/dist/jquery-resizable.min.js') }}"></script>


    <!-- Import Trumbowyg -->
    <script src="{{ asset('backend/vendor/trumbowyg/dist/trumbowyg.min.js') }}"></script>

    <!-- Import all plugins you want AFTER importing jQuery and Trumbowyg -->
    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/base64/trumbowyg.base64.min.js') }}"></script>

    <script src="{{ asset('backend/vendor/trumbowyg/dist/plugins/resizimg/trumbowyg.resizimg.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            "use strict";

            $('#setting_page_agreement')
                .trumbowyg({
                    plugins: {
                        resizimg: {
                            minSize: 32,
                            step: 16,
                        }
                    },
                    btnsDef: {
                        // Create a new dropdown
                        image: {
                            dropdown: ['insertImage', 'base64'],
                            ico: 'insertImage'
                        }
                    },
                    // Redefine the button pane
                    btns: [
                        ['viewHTML'],
                        ['formatting'],
                        ['strong', 'em', 'del'],
                        ['superscript', 'subscript'],
                        ['link'],
                        ['image'], // Our fresh created dropdown
                        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                        ['unorderedList', 'orderedList'],
                        ['horizontalRule'],
                        ['removeformat'],
                        ['fullscreen']
                    ]
                });
        });
    </script>
@endsection
