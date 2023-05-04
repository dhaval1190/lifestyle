@extends('backend.admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/dist/ui/trumbowyg.min.css') }}">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800">{{ __('Profile Share Email Template Page') }}</h1>
            <p class="mb-4">{{ __('This page allows you to edit email template page body content of the website.') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.settings.page.email-template.update') }}" class="" id="updateMail">
                        @csrf
                        <input type="hidden" name="is_contact_profile" value="profile">
                        <div class="row form-group">
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox">
                                    {{-- <input value="1" name="setting_page_about_enable" type="checkbox" class="custom-control-input" id="setting_page_about_enable"> --}}
                                    {{-- <p>Last Updated on : <b></b></p> --}}
                                </div>
                                
                                {{-- @error('setting_page_about_enable')
                                <span class="invalid-tooltip">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror --}}
                            </div>
                        </div>
                        {{-- <div class="fv-row form-group">
                            <div class="col-md-12">
                                <label class="text-black" for="setting_page_email_template">{{ __('backend.shared.page-editor') }}</label>
                                <textarea id="setting_page_email_template" type="text" class="form-control @error('setting_page_email_template') is-invalid @enderror" name="setting_page_email_template">{{ $email_template_data->email_template }}</textarea>
                                @error('setting_page_email_template')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="mt-1 pl-2">
                                @foreach(emailTemplateConstants() as $snippets)
                                    <a href="javascript:void(0)" class="text-hover-primary snippest badge badge-light-primary me-2 my-1" data-tag="{{$snippets}}">{{str_replace(['[', ']'], ['',''], $snippets)}}</a>
                                @endforeach
                            </div>
                        </div> --}}
                        <div class="fv-row mb-10">
                            <label class="form-label mb-3">{{ __('Message') }}:</label>
                            <div class="fv-row fv-plugins-icon-container">
                                <div class="quill message" style="height: 200px" id="message"></div>
                                <input type="hidden" name="message" class="hidden-message" id="hidden-message" value="{{ isset($template_data->email_template) ? $template_data->email_template : '' }}">
                            </div>
                            @error('message')
                                <span class="invalid-tooltip">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if(Session::has('message_error'))
                                <span class="invalid-tooltip">
                                    <strong>{{ Session::get('message_error') }}</strong>
                                </span>
                            @endif
                            <div class="mt-2">
                                @foreach(profileShareEmailTemplateConstants() as $snippets)
                                    <a href="javascript:void(0)" class="text-hover-primary snippest badge badge-light-primary me-2 my-1" data-tag="{{$snippets}}">{{str_replace(['[', ']'], ['',''], $snippets)}}</a>
                                @endforeach
                            </div>
                        </div>
                        <hr/>
                        <div class="row form-group justify-content-between">
                            <div class="col-8">
                                <button type="submit" class="btn btn-primary py-2 px-4 text-white">
                                    {{ __('backend.shared.update') }}
                                </button>
                                <a class="btn btn-secondary" href="{{ route('admin.index') }}">{{ __('backend.shared.cancel') }}</a>
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
    <script src="{{ asset('backend/js/plugins.bundle.js') }}"></script>

    <script>
        
        $(document).ready(function() {

            "use strict";

            //     ClassicEditor
            //     .create( document.querySelector('#setting_page_email_template'),{
            //         // removePlugins: ['MediaEmbed'],
            //         ckfinder:{
            //             uploadUrl:'{{ route('admin.article.description.image').'?_token='.csrf_token() }}'
            //         }
            //     } )
            //     .then(editor => {
            //         // console.log(editor);
            //     })
            //     .catch( error => {
			//     // console.error( error );
		    // });

            var quill = new Quill('.quill', {
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, 3, 4, 5, 6, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'align': []
                    }],
                    ['image', ]
                ]
            },
            placeholder: '{{__('Type your content here...')}}',
            theme: 'snow',
            name: 'header' // or 'bubble'
            });

            $('#updateMail').on('click', () => {
                var html = quill.root.innerHTML;
                $('#hidden-message').val(html);
            });
            $('.snippest').on('click', function() {
                               
                // var caretPos =document.getElementById("setting_page_email_template").selectionStart;
                // // console.log(caretPos);
                // var caretEnd = document.getElementById("setting_page_email_template").selectionEnd;
                // // console.log(caretEnd);

                // var textAreaTxt = $("#setting_page_email_template").val();
                // console.log(textAreaTxt)
                // var txtToAdd = $(this).data('tag');
                // $("#setting_page_email_template").val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring( caretEnd ) );
                
                // $('#setting_page_email_template').focus();
                // document.getElementById('setting_page_email_template').selectionStart = caretPos + txtToAdd.length
                // document.getElementById('setting_page_email_template').selectionEnd = caretPos + txtToAdd.length
                // FCKEditor.insertHTML(document.getElementByClass('snippest').value)
                
                    var selection = null;
                    var cursorPosition = null;
                    var selection = quill.getSelection();
                    var cursorPosition = quill.getLength()
                    if (selection != null ) {
                        cursorPosition = selection.index;
                    }
			        quill.clipboard.dangerouslyPasteHTML(cursorPosition, $(this).data('tag'));
                
            });
                $('#message .ql-blank').html($('#hidden-message').val());
        });
    </script>
@endsection
