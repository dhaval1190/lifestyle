@extends('backend.admin.layouts.app')

@section('styles')
    <!-- Bootstrap FD Css-->
    <link href="{{ asset('backend/vendor/bootstrap-fd/bootstrap.fd.css') }}" rel="stylesheet" />
@endsection

@section('content')
 
    <div class="row justify-content-between">
        <div class="col-8 col-md-9">
            <h1 class="h3 mb-2 text-gray-800 font-sm-20">{{ __('review.backend.edit-a-review') }}</h1>
            <p class="mb-4 font-sm-14">{{ __('review.backend.write-a-review-desc') }}</p>
        </div>
        <div class="col-4 col-md-3 text-right">
            <a href="{{ route('admin.page.reviews.index') }}" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-backspace"></i>
                </span>
                <span class="text">{{ __('backend.shared.back') }}</span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 font_icon_color pb-4">
        <div class="col-12">          
        
         <div class="row">
                <div class="col-12">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <span class="text-lg text-gray-800">{{ __('review.backend.status') }}: </span>

                            @if($review->approved == \App\Item::ITEM_REVIEW_APPROVED)

                                <span class="text-success">{{ __('review.backend.review-approved') }}</span>
                            @else

                                <span class="text-warning">{{ __('review.backend.review-pending') }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="rating" class="text-black">{{ __('review.backend.overall-rating') }}</label>

                            <div class="pl-0 rating_stars rating_stars_{{ $review->id }}" data-id="rating_stars_{{ $review->id }}" data-rating="{{ $review->rating }}"></div>
                        </div>
                    </div>

                </div>
                <div class="col-12">

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-2">
                            @if(empty(\App\User::find($review->author_id)->user_image))
                                <img src="{{ asset('backend/images/placeholder/profile-'. intval(\App\User::find($review->author_id)->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                            @else

                                <img src="{{ Storage::disk('public')->url('user/' . \App\User::find($review->author_id)->user_image) }}" alt="{{ \App\User::find($review->author_id)->name }}" class="img-fluid rounded-circle">
                            @endif
                        </div>
                        <div class="col-md-4">
                            <span>{{ \App\User::find($review->author_id)->name }}</span>
                        </div>
                        <div class="col-md-6 text-md-right text-sm-left">
                            <span>{{ __('review.backend.posted-at') . ' ' . \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</span>
                            @if($review->created_at != $review->updated_at)
                                <br>
                                <span>{{ __('review.backend.updated-at') . ' ' . \Carbon\Carbon::parse($review->updated_at)->diffForHumans() }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="text-lg text-gray-800">{{ $review->title }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p>{!! clean(nl2br($review->body), array('HTML.Allowed' => 'b,strong,i,em,u,ul,ol,li,p,br')) !!}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            @if($review->recommend == \App\Item::ITEM_REVIEW_RECOMMEND_YES)
                                <span class="text-success">{{ __('review.backend.recommend') }}</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-6">
                    @if($review->approved == \App\Item::ITEM_REVIEW_APPROVED)
                        <form action="{{ route('admin.page.reviews.disapprove',$review->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning">{{ __('backend.shared.disapprove') }}</button>
                        </form>
                    @else
                        <form action="{{ route('admin.page.reviews.approve',$review->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success">{{ __('backend.shared.approve') }}</button>
                        </form>
                    @endif
                </div>
                <div class="col-md-6 col-6 text-right">
                    <a class="text-white btn btn-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                        {{ __('backend.shared.delete') }}
                    </a>
                </div>
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
                    <form action="{{ route('admin.page.reviews.delete',$review->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('backend.shared.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
   
@endsection



