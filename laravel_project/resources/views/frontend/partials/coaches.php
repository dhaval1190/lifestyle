<div class="d-block d-md-flex listing vertical paid_users_item listing__item_featured_box">
    <div class="lh-content">        
        <hr class="item-box-hr">
        <div class="row align-items-center">
            <div class="col-5 col-md-7 pr-0">
                <div class="row align-items-center item-box-user-div">
                    <div class="col-3 item-box-user-img-div">
                        @if(empty($coach->user_image))
                            <img src="{{ asset('frontend/images/placeholder/profile-'. intval($coach->id % 10) . '.webp') }}" alt="Image" class="img-fluid rounded-circle">
                        @else
                            <img src="{{ Storage::disk('public')->url('user/' . $coach->user_image) }}" alt="{{ $coach->name }}" class="img-fluid rounded-circle">
                        @endif
                    </div>
                    <div class="col-9 line-height-1-2 item-box-user-name-div">
                        <div class="row pb-1">
                            <div class="col-12">
                                <span class="font-size-13">{{ str_limit($coach->name, 12, '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center item-box-user-div">
                    <div class="col-12">
                        <span class="font-size-13">{{ $coach->email }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
