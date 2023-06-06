@extends('backend.user.layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('backend/vendor/trumbowyg/dist/ui/trumbowyg.min.css') }}">
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-lg-9 col-12">
            <h1 class="h3 mb-2 text-gray-800 font-sm-20 ">{{ __('Profile Share Email Template Page') }}</h1>
            <p class="mb-4 font-sm-14" >{{ __('This page allows you to edit email template page body content of the website.') }}</p>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white">
        <div class="col-12">            
            <form method="POST" action="{{ route('user.email.template.update') }}" class="" id="updateMail">
                @csrf
                <input type="hidden" name="is_contact_profile" value="profile">
                <div class="row form-group">
                    <div class="col-md-12">                                
                            <label for="youtube" class="text-black">Subject:</label>
                            <input name="subject" type="text" class="form-control" id="subject" value="{{ isset($template_data->subject) ? $template_data->subject : '' }}">                               
                        
                        @error('subject')
                            <p class="name_error error_color">
                                <strong>{{ $message }}</strong>
                            </p>
                        @enderror
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
                        <p class="name_error error_color">
                            <strong>{{ $message }}</strong>
                        </p>
                    @enderror
                    @if(Session::has('message_error'))
                        <p class="name_error error_color">
                            <strong>{{ Session::get('message_error') }}</strong>
                        </p>
                    @endif
                    <div class="mt-2">
                        @foreach(profileShareEmailTemplateConstants() as $snippets)
                            <a href="javascript:void(0)" class="text-hover-primary snippest badge badge-light-primary me-2 my-1" data-tag="{{$snippets}}">{{str_replace(['[', ']'], ['',''], $snippets)}}</a>
                        @endforeach
                    </div>
                </div>
                <hr/>
                <div class="row form-group justify-content-between">
                    <div class="col-12 col-md-8">
                        <div class="two_btn_set_">
                            <button type="submit" class="btn btn-primary py-2 px-4 text-white">
                                {{ __('backend.shared.update') }}
                            </button>
                            <a class="btn btn-secondary" href="{{ url('/user/dashboard') }}">{{ __('backend.shared.cancel') }}</a>
                        </div>
                    </div>
                    @if(isset($template_data->id))
                        <div class="col-md-4 col-4 text-right">
                            <a class="text-danger font-14" href="#" data-toggle="modal" data-target="#deleteModal">
                                <button class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                            </a>
                        </div>
                    @endif
                </div>
            </form>                
        </div>
    </div>

    <!-- Modal -->
    @if(isset($template_data->id))
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('backend.shared.delete-confirm') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ __('Do you want to delete the template?') }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                        <form action="{{ route('user.email.template.destroy',['id'=>$template_data->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="is_contact_profile" value="profile">
                            <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
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
                // console.log($(this).data('tag'));
                
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
