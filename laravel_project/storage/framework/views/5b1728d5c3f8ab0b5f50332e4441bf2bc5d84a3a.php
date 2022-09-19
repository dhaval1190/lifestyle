<?php
    $get_count_rating = $item->getCountRating();
    $get_all_categories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY, isset($parent_category_id) ? $parent_category_id : null);
    $all_categories_count = $item->allCategories()->count();
?>
<div class="d-block d-md-flex listing vertical listing__item_featured_box">

    <a href="<?php echo e(route('page.item', $item->item_slug)); ?>" class="img d-block listing_for_map_hover" style="background-image: url(<?php echo e(!empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp'))); ?>)" data-map-lat="<?php echo e($item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->item_lat : ''); ?>" data-map-lng="<?php echo e($item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->item_lng : ''); ?>" data-map-title="<?php echo e($item->item_title); ?>" data-map-address="<?php echo e($item->item_type == \App\Item::ITEM_TYPE_REGULAR ? ($item->item_address_hide ? $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code : $item->item_address . ', ' . $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code) : ''); ?>" data-map-rating="<?php echo e($item->item_average_rating); ?>" data-map-reviews="<?php echo e($get_count_rating); ?>" data-map-link="<?php echo e(route('page.item', $item->item_slug)); ?>" data-map-feature-image-link="<?php echo e(!empty($item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')); ?>">
        <span class="text-white pl-1 pr-1 pt-1 pb-1 m-0 item-featured-label"><?php echo e(__('frontend.item.featured')); ?></span>
    </a>
    <div class="lh-content">

        <?php $__currentLoopData = $get_all_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paid_item_categories_key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('page.category', $category->category_slug)); ?>">
                <span class="category">
                    <?php if(!empty($category->category_icon)): ?>
                        <i class="<?php echo e($category->category_icon); ?>"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-heart"></i>
                    <?php endif; ?>
                    <?php echo e($category->category_name); ?>

                </span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($all_categories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY): ?>
            <span class="category"><?php echo e(__('categories.and') . " " . strval($all_categories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY) . " " . __('categories.more')); ?></span>
        <?php endif; ?>


        <?php if(!empty($item->item_price)): ?>
            <span class="category">$<?php echo e(number_format($item->item_price)); ?></span>
        <?php endif; ?>

        <h3 class="pt-2 listing_for_map_hover" data-map-lat="<?php echo e($item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->item_lat : ''); ?>" data-map-lng="<?php echo e($item->item_type == \App\Item::ITEM_TYPE_REGULAR ? $item->item_lng : ''); ?>" data-map-title="<?php echo e($item->item_title); ?>" data-map-address="<?php echo e($item->item_type == \App\Item::ITEM_TYPE_REGULAR ? ($item->item_address_hide ? $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code : $item->item_address . ', ' . $item->city->city_name . ', ' . $item->state->state_name . ' ' . $item->item_postal_code) : ''); ?>" data-map-rating="<?php echo e($item->item_average_rating); ?>" data-map-reviews="<?php echo e($get_count_rating); ?>" data-map-link="<?php echo e(route('page.item', $item->item_slug)); ?>" data-map-feature-image-link="<?php echo e(!empty($item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')); ?>"><a href="<?php echo e(route('page.item', $item->item_slug)); ?>"><?php echo e($item->item_title); ?></a></h3>

        <?php if($item->item_type == \App\Item::ITEM_TYPE_REGULAR): ?>
            <address>
                <?php echo e($item->item_address_hide == \App\Item::ITEM_ADDR_NOT_HIDE ? $item->item_address . ',' : ''); ?>

                <a href="<?php echo e(route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug])); ?>"><?php echo e($item->city->city_name); ?></a>,
                <a href="<?php echo e(route('page.state', ['state_slug'=>$item->state->state_slug])); ?>"><?php echo e($item->state->state_name); ?></a>
                <?php echo e($item->item_postal_code); ?>

            </address>
        <?php endif; ?>

        <?php if($get_count_rating > 0): ?>
            <div class="row">
                <div class="col-12">
                    <div class="pl-0 rating_stars rating_stars_<?php echo e($item->item_slug); ?>" data-id="rating_stars_<?php echo e($item->item_slug); ?>" data-rating="<?php echo e($item->item_average_rating); ?>"></div>
                    <address class="mt-1">
                        <?php if($get_count_rating == 1): ?>
                            <?php echo e('(' . $get_count_rating . ' ' . __('review.frontend.review') . ')'); ?>

                        <?php else: ?>
                            <?php echo e('(' . $get_count_rating . ' ' . __('review.frontend.reviews') . ')'); ?>

                        <?php endif; ?>
                    </address>
                </div>
            </div>
        <?php endif; ?>

        <hr class="item-box-hr">

        <div class="row align-items-center">

            <div class="col-5 col-md-7 pr-0">
                <div class="row align-items-center item-box-user-div">
                    <div class="col-3 item-box-user-img-div">
                        <?php if(empty($item->user->user_image)): ?>
                            <img src="<?php echo e(asset('frontend/images/placeholder/profile-'. intval($item->user->id % 10) . '.webp')); ?>" alt="Image" class="img-fluid rounded-circle">
                        <?php else: ?>
                            <img src="<?php echo e(Storage::disk('public')->url('user/' . $item->user->user_image)); ?>" alt="<?php echo e($item->user->name); ?>" class="img-fluid rounded-circle">
                        <?php endif; ?>
                    </div>
                    <div class="col-9 line-height-1-2 item-box-user-name-div">
                        <div class="row pb-1">
                            <div class="col-12">
                                <span class="font-size-13"><?php echo e(str_limit($item->user->name, 12, '.')); ?></span>
                            </div>
                        </div>
                        <div class="row line-height-1-0">
                            <div class="col-12">
                                <span class="review"><?php echo e($item->created_at->diffForHumans()); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-7 col-md-5 pl-0 text-right">
                <?php if($item->item_hour_show_hours == \App\Item::ITEM_HOUR_SHOW): ?>
                    <?php if($item->hasOpened()): ?>
                        <span class="item-box-hour-span-opened"><?php echo e(__('item_hour.frontend-item-box-hour-opened')); ?></span>
                    <?php else: ?>
                        <span class="item-box-hour-span-closed"><?php echo e(__('item_hour.frontend-item-box-hour-closed')); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>


    </div>
</div>
<?php /**PATH /var/www/html/Laravel/Techcrave/listdirectory/http/laravel_project/resources/views/frontend/partials/paid-item-block.blade.php ENDPATH**/ ?>