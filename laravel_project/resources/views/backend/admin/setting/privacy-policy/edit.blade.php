@extends('backend.admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/dist/ui/trumbowyg.min.css') }}">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('backend.privacy-policy.privacy-policy-page') }}</h1>
            <p class="mb-4">{{ __('backend.privacy-policy.privacy-policy-page-desc') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.settings.page.privacy-policy.update') }}" class="">
                        @csrf

                        <div class="row form-group">

                            <div class="col-md-4">

                                <div class="custom-control custom-checkbox">
                                    <input value="1" name="setting_page_privacy_policy_enable" type="checkbox" class="custom-control-input" id="setting_page_privacy_policy_enable" {{ $all_page_privacy_policy_settings->setting_page_privacy_policy_enable == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="setting_page_privacy_policy_enable">{{ __('backend.privacy-policy.show-privacy-policy-page') }}</label>
                                </div>
                                @error('setting_page_privacy_policy_enable')
                                <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>

                        </div>

                        <div class="row form-group">

                            <div class="col-md-12">

                                <label class="text-black" for="setting_page_privacy_policy">{{ __('backend.shared.page-editor') }}</label>
                                <textarea id="setting_page_privacy_policy" type="text" class="form-control @error('setting_page_privacy_policy') is-invalid @enderror" name="setting_page_privacy_policy">{{ old('setting_page_privacy_policy') ? old('setting_page_privacy_policy') : $all_page_privacy_policy_settings->setting_page_privacy_policy }}</textarea>
                                @error('setting_page_privacy_policy')
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

            $('#setting_page_privacy_policy')
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
