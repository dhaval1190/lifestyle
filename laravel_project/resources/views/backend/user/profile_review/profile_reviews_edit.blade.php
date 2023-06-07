@extends('backend.user.layouts.app')

@section('styles')
    <!-- Bootstrap FD Css-->
    <link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />
@endsection

@section('content')
 
    <div class="row justify-content-between">
        <div class="col-lg-9 col-8">
            <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('review.backend.edit-a-review') }}</h1>
            <p class="mb-4 font-sm-14">{{ __('review.backend.write-a-review-desc') }}</p>
        </div>
        <div class="col-4 col-lg-3 text-right">
            <a href="{{ route('user.page.reviews.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4  pb-4">
        <div class="col-12">          
         @foreach($profile_Reviews as $profile_Review)
            <div class="row">
                <div class="col-lg-8 col-12">
                    <form method="POST" action="{{ route('user.page.reviews.update',$profile_Review->id) }}" name="review_form" id="review_form">
                        @csrf
                        @method('PUT')
                        <div class="alert alert-success alert-dismissible fade show" id="register_success_error_div" role="alert" style="display:none;margin:4px;">
                        <span id="register_success">Review Update SuccessFull</span>
                                </div>
                        <div class="form-row mb-3">
                            <div class="col-md-12 text-right">
                                @if($profile_Review->approved == \App\Item::ITEM_REVIEW_APPROVED)

                                    <a class="btn btn-success btn-sm text-white">{{ __('review.backend.review-approved') }}</a>
                                @else

                                    <a class="btn btn-warning btn-sm text-white">{{ __('review.backend.review-pending') }}</a>
                                @endif

                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('review.backend.select-rating') }}</span>
                                <small class="form-text text-muted">
                                </small>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label for="rating" class="text-black">{{ __('review.backend.overall-rating') }}</label><br>
                                <select class="rating_stars" name="rating" id="rating">
                                <p class="profile_review_rating_error error_color" role="alert"></p>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}" {{ $profile_Review->rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>{{ __('rating_summary.1-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}" {{ $profile_Review->rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>{{ __('rating_summary.2-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}" {{ $profile_Review->rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>{{ __('rating_summary.3-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}" {{ $profile_Review->rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>{{ __('rating_summary.4-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}" {{ $profile_Review->rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>{{ __('rating_summary.5-stars') }}</option>
                                </select>
                                @error('rating')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <span class="text-lg text-gray-800">{{ __('review.backend.tell-experience') }}</span>
                                <small class="form-text text-muted">
                                </small>
                            </div>
                        </div>
                        <div class="form-row mb-3">
                            <div class="col-md-12">
                                <label for="body" class="text-black">{{ __('review.backend.description') }}</label>
                                <textarea class="form-control @error('body') is-invalid @enderror" id="body" rows="5" name="body">{{ old('body') ? old('body') : $profile_Review->body }}</textarea>
                                <p class="profile_review_body_error error_color" role="alert"></p>
                                @error('body')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row mb-3">

                            <div class="col-md-12">
                                <div class="form-check form-check-inline">
                                    <input {{ (old('recommend') ? old('recommend') : ($profile_Review->recommend == \App\Item::ITEM_REVIEW_RECOMMEND_YES ? 1 : 0)) == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" id="recommend" name="recommend" value="1">
                                    <label class="form-check-label" for="recommend">
                                        {{ __('review.backend.recommend') }}
                                    </label>
                                </div>
                                @error('recommend')
                                <span class="invalid-tooltip">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                      

                        <div class="form-row mb-3">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary py-2 px-4 text-white">
                                    {{ __('review.backend.update-review') }}
                                </button>
                            </div>
                            <div class="col-md-4 text-right">
                                <a class="text-danger" data-toggle="modal" data-target="#deleteModal">
                                    <button class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="col-4"></div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    @foreach($profile_Reviews as $profile_Review)
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
                    {{ __('review.backend.delete-a-review') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('backend.shared.cancel') }}</button>
                    <form action="{{ route('user.page.reviews.destroy',$profile_Review->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endsection

@section('scripts')

    <!-- Bootstrap Fd Plugin Js-->
    <script src="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.js') }}"></script>

    <script>
        function deleteGallery(domId)
        {
            //$("form :submit").attr("disabled", true);

            var ajax_url = '/ajax/item/review/gallery/delete/' + domId;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: ajax_url,
                method: 'post',
                data: {
                },
                success: function(result){
                    console.log(result);
                    $('#review_image_gallery_' + domId).remove();
                }});

        }

        $(document).ready(function() {

            "use strict";

            /**
             * Start image gallery uplaod
             */
            $('#upload_gallery').on('click', function(){
                $('#gallery_image_error_div').hide();
                $('#gallery_img_error').text('');
                window.selectedImages = [];

                $.FileDialog({
                    // accept: "image/jpeg",
                    accept: ".jpeg,.jpg,.png",
                }).on("files.bs.filedialog", function (event) {
                    var html = "";
                    for (var a = 0; a < event.files.length; a++) {

                        if(a == 12) {break;}
                        selectedImages.push(event.files[a]);
                        html += "<div class='col-lg-3 col-md-4 col-sm-6 mb-2' id='review_image_gallery_" + a + "'>" +
                            "<img style='max-width: 120px;' src='" + event.files[a].content + "'>" +
                            "<br/><button class='btn btn-danger btn-sm text-white mt-1' onclick='$(\"#review_image_gallery_" + a + "\").remove();'>" + "{{ __('backend.shared.delete') }}" + "</button>" +
                            "<input type='hidden' value='" + event.files[a].content + "' name='review_image_galleries[]'>" +
                            "</div>";

                            var img_str = event.files[a].content;
                            var img_str_split = img_str.split(";base64")[0];
                            var img_ext = img_str_split.split("/")[1];
                            if (img_ext != 'jpeg' && img_ext != 'png' && img_ext != 'jpg') {
                                $('#gallery_img_error').text('Please choose only .jpg,.jpeg,.png file');
                                $('#gallery_image_error_div').show();
                                return false;
                            }
                    }
                    document.getElementById("selected-images").innerHTML += html;
                });
            });
            /**
             * End image gallery uplaod
             */

        });
        $(document).ready(function() {
            $('.profile_review_rating_error').text('');
            $('.profile_review_body_error').text('');           
            $('.please_wait').text('');

            $('#rating').on('input', function(e) {
                $('.profile_review_rating_error').text('');
            });
            $('#body').on('input', function(e) {
                $('.profile_review_body_error').text('');
            });           
            $("#review-modal").on("hidden.bs.modal", function() {
                $('.profile_review_rating_error').text('');
                $('.profile_review_rating_error').text('');
            });
            $('#review_form').on('submit', function(e) {
                e.preventDefault();
                $('.please_wait').text('Please Wait..');
                var user_id = <?php echo json_encode($profile_Review->id); ?>;               
                // alert(role_id);
               
                var url = "{{route('user.page.reviews.update',$profile_Review->id)}}";                   
                
                var formData = new FormData(this);

                jQuery.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                    },

                    success: function(response) {
                        console.log(response);
                        if (response.status == 'success') {
                            // console.log(response)
                            $(".error_color").text("");
                            $('.please_wait').text('');
                            // location.reload(); 
                            // window.location.href = "{{ route('login') }}";
                            $('#register_success_error_div').show();
                            $(':input[type="submit"]').prop('disabled', false);	
                            $('.btn-danger').prop('disabled', false);	


                        }
                        if (response.status == 'error') {
                            // console.log(response.msg.item_contact_email_note)
                            $('.please_wait').text('');
                            $.each(response.msg, function(key, val) {
                                if (response.msg.rating) {
                                    $('.profile_review_rating_error').text(response.msg.rating)
                                }
                                if (response.msg.body) {
                                    console.log(response.msg.body);
                                    $('.profile_review_body_error').text(response.msg.body);
                                }
                                $(':input[type="submit"]').prop('disabled', false);

                            });

                        }
                    }
                });

            });
        });
    </script>

@endsection
