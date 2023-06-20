@extends('backend.user.layouts.app')

@section('styles')
    <!-- Bootstrap FD Css-->
    <link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="row justify-content-between">
        <div class="col-md-8 col-8">
            <h1 class="h3 mb-2 font-set-sm text-gray-800 font-sm-20">{{ __('review.backend.edit-a-review') }}</h1>
            <p class="mb-4 font-sm-14">{{ __('review.backend.write-a-review-desc') }}</p>
        </div>
        <div class="col-4 col-md-4 text-right">
            <a href="{{ route('user.items.reviews.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4 custom_set">
        <div class="col-12 p-0">

            <div class="row">
                <div class="col-md-12 col-lg-3 col-12">
                    @if(empty($item->item_image))
                        <img id="image_preview" src="{{ asset('backend/images/placeholder/full_item_feature_image.webp') }}" class="img-responsive rounded">
                    @else
                        <img id="image_preview" src="{{ Storage::disk('public')->url('item/'. $item->item_image) }}" class="img-responsive rounded">
                    @endif

                    <a target="_blank" href="{{ route('page.item', $item->item_slug) }}" class="btn btn-primary btn-block mt-2">{{ __('backend.message.view-listing') }}</a>

                </div>
                <div class="col-md-12 col-lg-9 col-12">
                    <p class="sub_set_btn">
                        @foreach($item->allCategories()->get() as $key => $category)
                            <span class="bg-info rounded text-white pl-2 pr-2 pt-1 pb-1 mr-1">
                                {{ $category->category_name }}
                            </span>
                        @endforeach
                    </p>
                    <h1 class="h4 mb-2 text-gray-800">{{ $item->item_title }}</h1>
                    <p class="mb-4">
                        @if($item->item_type == \App\Item::ITEM_TYPE_REGULAR)
                        {{ $item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE ? $item->item_address . ', ' : '' }} {{ $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code }}
                        @else
                            <span class="bg-primary text-white pl-1 pr-1 rounded">{{ __('theme_directory_hub.online-listing.online-listing') }}</span>
                        @endif
                    </p>
                    <hr/>
                    {{-- <p class="mb-4">{{ $item->item_description }}</p> --}}
                    <div class="read_more">
                        @if (strlen($item->item_description) > 600)
                            <input type="checkbox" class="read-more-state"
                                id="post-1" />
                        @endif
                        <p class="read-more-wrap">
                            {{-- <p class="mb-4">{!! html_entity_decode($item->item_description) !!} --}}
                                {!! Str::limit($item->item_description, $limit = 600, $end = '') !!}<span
                                class="read-more-target">{{ substr($item->item_description, $limit) }}</span>
                        </p> 
                        @if (strlen($item->item_description) > 600)
                            <label for="post-1" class="read-more-trigger"></label>
                        @endif 
                    </div>                  
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-12 col-12">
                    <form method="POST" action="{{ route('user.items.reviews.update', ['item_slug' => $item->item_slug, 'review' => $review->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-row mb-3">
                            <div class="col-md-12 text-right">
                                @if($review->approved == \App\Item::ITEM_REVIEW_APPROVED)

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
                                <select class="rating_stars" name="rating">
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_ONE }}" {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_ONE ? 'selected' : '' }}>{{ __('rating_summary.1-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_TWO }}" {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_TWO ? 'selected' : '' }}>{{ __('rating_summary.2-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_THREE }}" {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_THREE ? 'selected' : '' }}>{{ __('rating_summary.3-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FOUR }}" {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_FOUR ? 'selected' : '' }}>{{ __('rating_summary.4-stars') }}</option>
                                    <option value="{{ \App\Item::ITEM_REVIEW_RATING_FIVE }}" {{ $review->rating == \App\Item::ITEM_REVIEW_RATING_FIVE ? 'selected' : '' }}>{{ __('rating_summary.5-stars') }}</option>
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
                                <textarea class="form-control @error('body') is-invalid @enderror" id="body" rows="5" name="body">{{ old('body') ? old('body') : $review->body }}</textarea>
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
                                    <input {{ (old('recommend') ? old('recommend') : ($review->recommend == \App\Item::ITEM_REVIEW_RECOMMEND_YES ? 1 : 0)) == 1 ? 'checked' : '' }} class="form-check-input" type="checkbox" id="recommend" name="recommend" value="1">
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
                            <div class="col-md-8 col-8">
                                <button type="submit" class="btn btn-primary py-2 px-4 text-white font-14">
                                    {{ __('review.backend.update-review') }}
                                </button>
                            </div>
                            <div class="col-md-4 col-4 text-right">
                                <a class="text-danger font-14" href="#" data-toggle="modal" data-target="#deleteModal">
                                    <button class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                                </a>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="col-12 col-md-12 col-lg-4"></div>
            </div>

        </div>
    </div>

    <!-- Modal -->
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
                    <form action="{{ route('user.items.reviews.destroy', ['item_slug' => $item->item_slug, 'review' => $review->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
    </script>

@endsection
