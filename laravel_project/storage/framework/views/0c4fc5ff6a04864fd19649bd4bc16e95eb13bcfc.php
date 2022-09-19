<?php $__env->startSection('styles'); ?>
    <link rel="preload" href="<?php echo e(asset('frontend/images/placeholder/header-1.webp')); ?>" as="image">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_DEFAULT): ?>
        <div class="site-blocks-cover overlay" style="background-image: url( <?php echo e(asset('frontend/images/placeholder/header-1.webp')); ?>);">

    <?php elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_COLOR): ?>
        <div class="site-blocks-cover overlay" style="background-color: <?php echo e($site_homepage_header_background_color); ?>;">

    <?php elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_IMAGE): ?>
        <div class="site-blocks-cover overlay" style="background-image: url( <?php echo e(Storage::disk('public')->url('customization/' . $site_homepage_header_background_image)); ?>);">

    <?php elseif($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO): ?>
        <div class="site-blocks-cover overlay" style="background-color: #333333;">
    <?php endif; ?>

            <?php if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO): ?>
            <div data-youtube="<?php echo e($site_homepage_header_background_youtube_video); ?>"></div>
            <?php endif; ?>

    <div class="container">

        <!-- Start hero section desktop view-->
        <div class="row align-items-center justify-content-center text-center d-none d-md-flex">

            <div class="col-md-12">
                <div class="row justify-content-center mb-1">
                    <div class="col-md-12 text-center">
                        <h1 class="" data-aos="fade-up" style="color: <?php echo e($site_homepage_header_title_font_color); ?>;"><?php echo e(__('frontend.homepage.title')); ?></h1>
                        <p data-aos="fade-up" data-aos-delay="100" style="color: <?php echo e($site_homepage_header_paragraph_font_color); ?>;"><?php echo e(__('frontend.homepage.description')); ?></p>
                    </div>
                </div>
                <div class="form-search-wrap" data-aos="fade-up" data-aos-delay="200">
                    <?php echo $__env->make('frontend.partials.search.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>

        </div>
        <!-- End hero section desktop view-->

        <!-- Start hero section mobile view-->
        <div class="row align-items-center justify-content-center text-center d-md-none mt-5">

            <div class="col-md-12">
                <div class="row justify-content-center mb-1">
                    <div class="col-md-10 text-center">
                        <h1 class="" data-aos="fade-up"><?php echo e(__('frontend.homepage.title')); ?></h1>
                        <p data-aos="fade-up" data-aos-delay="100"><?php echo e(__('frontend.homepage.description')); ?></p>
                    </div>
                </div>
                <div class="form-search-wrap" data-aos="fade-up" data-aos-delay="200">
                    <?php echo $__env->make('frontend.partials.search.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>

        </div>
        <!-- End hero section mobile view-->

    </div>
</div>

<div class="site-section bg-light">
    <div class="container">

        <!-- Start categories section desktop view-->
        <div class="overlap-category mb-4 d-none d-md-block">
            <div class="row align-items-stretch no-gutters">

                <?php
                    $categories_count = $categories->count();
                ?>

                <?php if($categories_count > 0): ?>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categories_key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">

                            <?php if($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON): ?>
                                <a href="<?php echo e(route('page.category', $category->category_slug)); ?>" class="popular-category h-100">
                                    <span class="icon">
                                        <span>
                                            <?php if($category->category_icon): ?>
                                                <i class="<?php echo e($category->category_icon); ?>"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-heart"></i>
                                            <?php endif; ?>
                                        </span>
                                    </span>

                                    <span class="caption d-block"><?php echo e($category->category_name); ?></span>
                                </a>
                            <?php elseif($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_IMAGE): ?>
                                <a href="<?php echo e(route('page.category', $category->category_slug)); ?>" class="popular-category h-100 image-category">
                                    <span class="icon image-category-span">
                                        <span>
                                            <?php if($category->category_image): ?>
                                                <img src="<?php echo e(\Illuminate\Support\Facades\Storage::disk('public')->url('category/'. $category->category_image)); ?>" alt="Image" class="img-fluid rounded image-category-img">
                                            <?php else: ?>
                                                <img src="<?php echo e(asset('frontend/images/placeholder/category-image.webp')); ?>" alt="Image" class="img-fluid rounded image-category-img">
                                            <?php endif; ?>
                                        </span>
                                    </span>
                                    <span class="caption d-block image-category-caption"><?php echo e($category->category_name); ?></span>
                                </a>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                        <p><?php echo e(__('frontend.homepage.no-categories')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- End categories section desktop view-->

        <!-- Start categories section mobile view-->
        <div class="overlap-category-sm mb-4 d-md-none">
            <div class="row align-items-stretch no-gutters">

                <?php if($categories_count > 0): ?>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categories_key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                            <?php if($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON): ?>
                                <a href="<?php echo e(route('page.category', $category->category_slug)); ?>" class="popular-category h-100">
                                    <span class="icon">
                                        <span>
                                            <?php if($category->category_icon): ?>
                                                <i class="<?php echo e($category->category_icon); ?>"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-heart"></i>
                                            <?php endif; ?>
                                        </span>
                                    </span>

                                    <span class="caption d-block"><?php echo e($category->category_name); ?></span>
                                </a>
                            <?php elseif($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_IMAGE): ?>
                                <a href="<?php echo e(route('page.category', $category->category_slug)); ?>" class="popular-category h-100 image-category">
                                    <span class="icon image-category-span">
                                        <span>
                                            <?php if($category->category_image): ?>
                                                <img src="<?php echo e(\Illuminate\Support\Facades\Storage::disk('public')->url('category/'. $category->category_image)); ?>" alt="Image" class="img-fluid rounded image-category-img">
                                            <?php else: ?>
                                                <img src="<?php echo e(asset('frontend/images/placeholder/category-image.webp')); ?>" alt="Image" class="img-fluid rounded image-category-img">
                                            <?php endif; ?>
                                        </span>
                                    </span>
                                    <span class="caption d-block image-category-caption"><?php echo e($category->category_name); ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php else: ?>
                    <div class="col-sm-6 col-md-4 mb-4 mb-lg-0 col-lg-2">
                        <p><?php echo e(__('frontend.homepage.no-categories')); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- End categories section mobile view-->

        <div class="row mt-5 mb-5">
            <div class="col-12 text-center">
                <a href="<?php echo e(route('page.categories')); ?>" class="btn btn-primary rounded text-white">
                    <i class="fas fa-th"></i>
                    <?php echo e(__('frontend.homepage.all-categories')); ?>

                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary"><?php echo e(__('frontend.homepage.featured-ads')); ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12  block-13">
                <div class="owl-carousel nonloop-block-13">

                    <?php if($paid_items->count() > 0): ?>
                        <?php $__currentLoopData = $paid_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paid_items_key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-block d-md-flex listing vertical">
                                <a href="<?php echo e(route('page.item', $item->item_slug)); ?>" class="img d-block" style="background-image: url(<?php echo e(!empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp'))); ?>)"></a>
                                <div class="lh-content">

                                    <?php
                                    $paid_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                    ?>
                                    <?php $__currentLoopData = $paid_item_getAllCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_all_categories_key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

                                    <?php
                                    $paid_item_allCategories_count = $item->allCategories()->count();
                                    ?>
                                    <?php if($paid_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE): ?>
                                        <span class="category"><?php echo e(__('categories.and') . " " . strval($paid_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . " " . __('categories.more')); ?></span>
                                    <?php endif; ?>

                                    <h3 class="pt-2"><a href="<?php echo e(route('page.item', $item->item_slug)); ?>"><?php echo e(str_limit($item->item_title, 44, '...')); ?></a></h3>

                                    <?php if($item->item_type == \App\Item::ITEM_TYPE_REGULAR): ?>
                                    <address>
                                        <a href="<?php echo e(route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug])); ?>"><?php echo e($item->city->city_name); ?></a>,
                                        <a href="<?php echo e(route('page.state', ['state_slug'=>$item->state->state_slug])); ?>"><?php echo e($item->state->state_name); ?></a>
                                    </address>
                                    <?php endif; ?>

                                    <?php
                                    $paid_item_getCountRating = $item->getCountRating();
                                    ?>
                                    <?php if($paid_item_getCountRating > 0): ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="pl-0 rating_stars rating_stars_<?php echo e($item->item_slug); ?>" data-id="rating_stars_<?php echo e($item->item_slug); ?>" data-rating="<?php echo e($item->item_average_rating); ?>"></div>
                                                <address class="mt-1">
                                                    <?php if($paid_item_getCountRating == 1): ?>
                                                        <?php echo e('(' . $paid_item_getCountRating . ' ' . __('review.frontend.review') . ')'); ?>

                                                    <?php else: ?>
                                                        <?php echo e('(' . $paid_item_getCountRating . ' ' . __('review.frontend.reviews') . ')'); ?>

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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="d-block d-md-flex listing vertical">
                        </div>
                    <?php endif; ?>

                </div>
            </div>


        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary"><?php echo e(__('frontend.homepage.nearby-listings')); ?></h2>
                <p class="color-black-opacity-5"><?php echo e(__('frontend.homepage.popular-listings')); ?></p>
            </div>
        </div>

        <div class="row">

            <?php if($popular_items->count() > 0): ?>
                <?php $__currentLoopData = $popular_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $popular_items_key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 mb-4 mb-lg-4 col-lg-4">

                        <div class="listing-item listing">
                            <div class="listing-image">
                                <img src="<?php echo e(!empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp'))); ?>" alt="Image" class="img-fluid">
                            </div>
                            <div class="listing-item-content">

                                <?php
                                    $popular_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                ?>
                                <?php $__currentLoopData = $popular_item_getAllCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_all_categories_key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a class="px-3 mb-3 category" href="<?php echo e(route('page.category', $category->category_slug)); ?>">
                                        <?php if(!empty($category->category_icon)): ?>
                                            <i class="<?php echo e($category->category_icon); ?>"></i>
                                        <?php else: ?>
                                            <i class="fa-solid fa-heart"></i>
                                        <?php endif; ?>
                                        <?php echo e($category->category_name); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php
                                    $popular_item_allCategories_count = $item->allCategories()->count();
                                ?>
                                <?php if($popular_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE): ?>
                                    <span class="category"><?php echo e(__('categories.and') . " " . strval($popular_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . " " . __('categories.more')); ?></span>
                                <?php endif; ?>

                                <h2 class="mb-1 pt-2"><a href="<?php echo e(route('page.item', $item->item_slug)); ?>"><?php echo e($item->item_title); ?></a></h2>

                                <?php if($item->item_type == \App\Item::ITEM_TYPE_REGULAR): ?>
                                <span class="address">
                                    <a href="<?php echo e(route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug])); ?>"><?php echo e($item->city->city_name); ?></a>,
                                    <a href="<?php echo e(route('page.state', ['state_slug'=>$item->state->state_slug])); ?>"><?php echo e($item->state->state_name); ?></a>
                                </span>
                                <?php endif; ?>

                                <?php
                                    $popular_item_getCountRating = $item->getCountRating();
                                ?>
                                <?php if($popular_item_getCountRating > 0): ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pl-0 rating_stars rating_stars_<?php echo e($item->item_slug); ?>" data-id="rating_stars_<?php echo e($item->item_slug); ?>" data-rating="<?php echo e($item->item_average_rating); ?>"></div>
                                            <address class="mt-1">
                                                <?php if($popular_item_getCountRating == 1): ?>
                                                    <span><?php echo e('(' . $popular_item_getCountRating . ' ' . __('review.frontend.review') . ')'); ?></span>
                                                <?php else: ?>
                                                    <span><?php echo e('(' . $popular_item_getCountRating . ' ' . __('review.frontend.reviews') . ')'); ?></span>
                                                <?php endif; ?>
                                            </address>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <hr class="item-box-hr item-box-index-nearby-hr">

                                <div class="row mt-1 align-items-center">

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
                                                        <span class="font-size-13"><?php echo e(str_limit($item->user->name, 14, '.')); ?></span>
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
                                                <span class="item-box-index-nearby-hour-span-opened"><?php echo e(__('item_hour.frontend-item-box-hour-opened')); ?></span>
                                            <?php else: ?>
                                                <span class="item-box-index-nearby-hour-span-closed"><?php echo e(__('item_hour.frontend-item-box-hour-closed')); ?></span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

        </div>
    </div>
</div>


<div class="site-section bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-7 text-left border-primary">
                <h2 class="font-weight-light text-primary"><?php echo e(__('frontend.homepage.recent-listings')); ?></h2>
            </div>
        </div>
        <div class="row mt-5">

            <?php if($latest_items->count() > 0): ?>
                <?php $__currentLoopData = $latest_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $latest_items_key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-6">
                        <div class="d-block d-md-flex listing">
                            <a href="<?php echo e(route('page.item', $item->item_slug)); ?>" class="img d-block" style="background-image: url(<?php echo e(!empty($item->item_image_medium) ? Storage::disk('public')->url('item/' . $item->item_image_medium) : (!empty($item->item_image) ? Storage::disk('public')->url('item/' . $item->item_image) : asset('frontend/images/placeholder/full_item_feature_image_medium.webp'))); ?>)"></a>
                            <div class="lh-content">

                                <?php
                                    $latest_item_getAllCategories = $item->getAllCategories(\App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE);
                                ?>
                                <?php $__currentLoopData = $latest_item_getAllCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_all_categories_key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

                                <?php
                                    $latest_item_allCategories_count = $item->allCategories()->count();
                                ?>
                                <?php if($latest_item_allCategories_count > \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE): ?>
                                    <span class="category"><?php echo e(__('categories.and') . " " . strval($latest_item_allCategories_count - \App\Item::ITEM_TOTAL_SHOW_CATEGORY_HOMEPAGE) . " " . __('categories.more')); ?></span>
                                <?php endif; ?>

                                <h3 class="pt-2"><a href="<?php echo e(route('page.item', $item->item_slug)); ?>"><?php echo e($item->item_title); ?></a></h3>

                                <?php if($item->item_type == \App\Item::ITEM_TYPE_REGULAR): ?>
                                <address>
                                    <a href="<?php echo e(route('page.city', ['state_slug'=>$item->state->state_slug, 'city_slug'=>$item->city->city_slug])); ?>"><?php echo e($item->city->city_name); ?></a>,
                                    <a href="<?php echo e(route('page.state', ['state_slug'=>$item->state->state_slug])); ?>"><?php echo e($item->state->state_name); ?></a>
                                </address>
                                <?php endif; ?>

                                <?php
                                    $latest_item_getCountRating = $item->getCountRating();
                                ?>
                                <?php if($latest_item_getCountRating > 0): ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="pl-0 rating_stars rating_stars_<?php echo e($item->item_slug); ?>" data-id="rating_stars_<?php echo e($item->item_slug); ?>" data-rating="<?php echo e($item->item_average_rating); ?>"></div>
                                            <address class="mt-1">
                                                <?php if($latest_item_getCountRating == 1): ?>
                                                    <?php echo e('(' . $latest_item_getCountRating . ' ' . __('review.frontend.review') . ')'); ?>

                                                <?php else: ?>
                                                    <?php echo e('(' . $latest_item_getCountRating . ' ' . __('review.frontend.reviews') . ')'); ?>

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
                                                        <span class="font-size-13"><?php echo e(str_limit($item->user->name, 14, '.')); ?></span>
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
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="<?php echo e(route('page.categories')); ?>" class="btn btn-primary rounded text-white">
                    <?php echo e(__('all_latest_listings.view-all-latest')); ?>

                </a>
            </div>
        </div>
    </div>
</div>

<?php if($all_testimonials->count() > 0): ?>
<div class="site-section bg-white">
    <div class="container">

        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary"><?php echo e(__('frontend.homepage.testimonials')); ?></h2>
            </div>
        </div>

        <div class="slide-one-item home-slider owl-carousel">

                <?php $__currentLoopData = $all_testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <div class="testimonial">
                            <figure class="mb-4">
                                <?php if(empty($testimonial->testimonial_image)): ?>
                                    <img src="<?php echo e(asset('frontend/images/placeholder/profile-'. intval($testimonial->id % 10) . '.webp')); ?>" alt="Image" class="img-fluid mb-3">
                                <?php else: ?>
                                    <img src="<?php echo e(Storage::disk('public')->url('testimonial/' . $testimonial->testimonial_image)); ?>" alt="Image" class="img-fluid mb-3">
                                <?php endif; ?>
                                <p>
                                    <?php echo e($testimonial->testimonial_name); ?>

                                    <?php if($testimonial->testimonial_job_title): ?>
                                        <?php echo e('• ' . $testimonial->testimonial_job_title); ?>

                                    <?php endif; ?>
                                    <?php if($testimonial->testimonial_company): ?>
                                        <?php echo e('• ' . $testimonial->testimonial_company); ?>

                                    <?php endif; ?>
                                </p>
                            </figure>
                            <blockquote>
                                <p><?php echo e($testimonial->testimonial_description); ?></p>
                            </blockquote>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    </div>
</div>
<?php endif; ?>


<?php if($recent_blog->count() > 0): ?>
<div class="site-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-md-7 text-center border-primary">
                <h2 class="font-weight-light text-primary"><?php echo e(__('frontend.homepage.our-blog')); ?></h2>
                <p class="color-black-opacity-5"><?php echo e(__('frontend.homepage.our-blog-decr')); ?></p>
            </div>
        </div>
        <div class="row mb-3 align-items-stretch">

                <?php $__currentLoopData = $recent_blog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recent_blog_key => $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4 mb-4 mb-lg-4">
                        <div class="h-entry">
                            <?php if(empty($post->featured_image)): ?>
                                <div class="mb-3" style="min-height:300px;border-radius: 0.25rem;background-image:url(<?php echo e(asset('frontend/images/placeholder/full_item_feature_image_medium.webp')); ?>);background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
                            <?php else: ?>
                                <div class="mb-3" style="min-height:300px;border-radius: 0.25rem;background-image:url(<?php echo e(url('laravel_project/public' . $post->featured_image)); ?>);background-size:cover;background-repeat:no-repeat;background-position: center center;"></div>
                            <?php endif; ?>
                            <h2 class="font-size-regular"><a href="<?php echo e(route('page.blog.show', $post->slug)); ?>" class="text-black"><?php echo e($post->title); ?></a></h2>
                            <div class="meta mb-3">
                                by <?php echo e($post->user()->first()->name); ?><span class="mx-1">&bullet;</span> <?php echo e($post->updated_at->diffForHumans()); ?> <span class="mx-1">&bullet;</span>
                                <?php if($post->topic()->count() != 0): ?>
                                <a href="<?php echo e(route('page.blog.topic', $post->topic()->first()->slug)); ?>"><?php echo e($post->topic()->first()->name); ?></a>
                                <?php else: ?>
                                    <?php echo e(__('frontend.blog.uncategorized')); ?>

                                <?php endif; ?>

                            </div>
                            <p><?php echo e(str_limit(preg_replace("/&#?[a-z0-9]{2,8};/i"," ", strip_tags($post->body)), 200)); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <div class="col-12 text-center mt-4">
                <a href="<?php echo e(route('page.blog')); ?>" class="btn btn-primary rounded py-2 px-4 text-white"><?php echo e(__('frontend.homepage.all-posts')); ?></a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modal -->
<div class="modal fade" id="reload_page_modal" tabindex="-1" role="dialog" aria-labelledby="reload_page_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reload_page_modal_label"><?php echo e(__('category_image_option.geolocation.modal-title')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo e(__('category_image_option.geolocation.modal-description')); ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded" data-dismiss="modal"><?php echo e(__('category_image_option.geolocation.modal-button-no')); ?></button>
                <a href="<?php echo e(route('page.home')); ?>" class="btn btn-primary text-white rounded"><?php echo e(__('category_image_option.geolocation.modal-button-reload')); ?></a>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

    <?php if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO): ?>
    <!-- Youtube Background for Header -->
    <script src="<?php echo e(asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js')); ?>"></script>
    <?php endif; ?>

    <script>
        $(document).ready(function(){

            "use strict";

            /**
             * Start get user lat & lng location
             */
            function success(position) {
                const latitude  = position.coords.latitude;
                const longitude = position.coords.longitude;

                console.log("Latitude: " + latitude + ", Longitude: " + longitude);

                var ajax_url = '/ajax/location/save/' + latitude + '/' + longitude;

                console.log(ajax_url);

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

                        if(result.reload)
                        {
                            $('#reload_page_modal').modal('show');
                        }
                    }});
            }

            function error() {
                console.log("Unable to retrieve your geolocation");
            }

            if(!navigator.geolocation) {

                console.log("Geolocation is not supported by your browser");
            } else {

                console.log("Locating geolocation ...");
                navigator.geolocation.getCurrentPosition(success, error);
            }
            /**
             * End get user lat & lng location
             */

            <?php if($site_homepage_header_background_type == \App\Customization::SITE_HOMEPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO): ?>
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            <?php endif; ?>

        });

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/Laravel/Techcrave/listdirectory/http/laravel_project/resources/views/frontend/index.blade.php ENDPATH**/ ?>