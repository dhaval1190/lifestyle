<?php $__env->startSection('styles'); ?>

    <?php if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP): ?>
    <link href="<?php echo e(asset('frontend/vendor/leaflet/leaflet.css')); ?>" rel="stylesheet" />
    <?php endif; ?>

    <link href="<?php echo e(asset('frontend/vendor/bootstrap-select/bootstrap-select.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_DEFAULT): ?>
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( <?php echo e(asset('frontend/images/placeholder/header-inner.webp')); ?>);">

    <?php elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_COLOR): ?>
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: <?php echo e($site_innerpage_header_background_color); ?>;">

    <?php elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_IMAGE): ?>
        <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url( <?php echo e(Storage::disk('public')->url('customization/' . $site_innerpage_header_background_image)); ?>);">

    <?php elseif($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO): ?>
        <div class="site-blocks-cover inner-page-cover overlay" style="background-color: #333333;">
    <?php endif; ?>

        <?php if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO): ?>
            <div data-youtube="<?php echo e($site_innerpage_header_background_youtube_video); ?>"></div>
        <?php endif; ?>

        <div class="container">
            <div class="row align-items-center justify-content-center text-center">

                <div class="col-md-10" data-aos="fade-up" data-aos-delay="400">


                    <div class="row justify-content-center mt-5">
                        <div class="col-md-8 text-center">
                            <h1 style="color: <?php echo e($site_innerpage_header_title_font_color); ?>;"><?php echo e(__('frontend.categories.title')); ?></h1>
                            <p class="mb-0" style="color: <?php echo e($site_innerpage_header_paragraph_font_color); ?>;"><?php echo e(__('frontend.categories.description')); ?></p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


    <div class="site-section">
        <div class="container">


            <?php if($categories->count() > 0): ?>
                <div class="overlap-category mb-5">

                    <div class="row align-items-stretch no-gutters">
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
                    </div>

                </div>
            <?php endif; ?>

            <!-- Start Filter -->
            <form method="GET" action="<?php echo e(route('page.categories')); ?>" id="filter_form">
                <div class="row pt-3 pb-3 ml-1 mr-1 mb-5 rounded border">
                    <div class="col-12">

                        <?php if($ads_before_breadcrumb->count() > 0): ?>
                            <?php $__currentLoopData = $ads_before_breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ads_before_breadcrumb_key => $ad_before_breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-5">
                                    <?php if($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT): ?>
                                        <div class="col-12 text-left">
                                            <div>
                                                <?php echo $ad_before_breadcrumb->advertisement_code; ?>

                                            </div>
                                        </div>
                                    <?php elseif($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER): ?>
                                        <div class="col-12 text-center">
                                            <div>
                                                <?php echo $ad_before_breadcrumb->advertisement_code; ?>

                                            </div>
                                        </div>
                                    <?php elseif($ad_before_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT): ?>
                                        <div class="col-12 text-right">
                                            <div>
                                                <?php echo $ad_before_breadcrumb->advertisement_code; ?>

                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-md-12">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb bg-white pl-0 pr-0">
                                        <li class="breadcrumb-item">
                                            <a href="<?php echo e(route('page.home')); ?>">
                                                <i class="fas fa-bars"></i>
                                                <?php echo e(__('frontend.shared.home')); ?>

                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('frontend.item.all-categories')); ?></li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <?php if($ads_after_breadcrumb->count() > 0): ?>
                                <?php $__currentLoopData = $ads_after_breadcrumb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ads_after_breadcrumb_key => $ad_after_breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mb-5">
                                        <?php if($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT): ?>
                                            <div class="col-12 text-left">
                                                <div>
                                                    <?php echo $ad_after_breadcrumb->advertisement_code; ?>

                                                </div>
                                            </div>
                                        <?php elseif($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER): ?>
                                            <div class="col-12 text-center">
                                                <div>
                                                    <?php echo $ad_after_breadcrumb->advertisement_code; ?>

                                                </div>
                                            </div>
                                        <?php elseif($ad_after_breadcrumb->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT): ?>
                                            <div class="col-12 text-right">
                                                <div>
                                                    <?php echo $ad_after_breadcrumb->advertisement_code; ?>

                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                        <div class="row form-group align-items-center">
                            <div class="col-12 col-md-2">
                                <strong><?php echo e(number_format($total_results)); ?></strong>
                                <?php echo e(__('theme_directory_hub.filter-results')); ?>

                            </div>
                            <div class="col-12 col-md-1 text-right pl-0">
                                <?php echo e(__('theme_directory_hub.filter-filter-by')); ?>

                            </div>

                            <div class="col-12 col-md-3 pl-0">
                                <select class="selectpicker form-control <?php $__errorArgs = ['filter_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="filter_state" id="filter_state" data-live-search="true">
                                    <option value="0" <?php echo e(empty($filter_state) ? 'selected' : ''); ?>><?php echo e(__('prefer_country.all-state')); ?></option>
                                    <?php $__currentLoopData = $all_states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $all_states_key => $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($state->id); ?>" <?php echo e($filter_state == $state->id ? 'selected' : ''); ?>><?php echo e($state->state_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['filter_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-tooltip">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-12 col-md-3 pl-0">
                                <select class="selectpicker form-control <?php $__errorArgs = ['filter_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="filter_city" id="filter_city" data-live-search="true">
                                    <option value="0" <?php echo e(empty($filter_city) ? 'selected' : ''); ?>><?php echo e(__('prefer_country.all-city')); ?></option>
                                    <?php $__currentLoopData = $all_cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $all_cities_key => $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($city->id); ?>" <?php echo e($filter_city == $city->id ? 'selected' : ''); ?>><?php echo e($city->city_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['filter_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-tooltip">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-12 col-md-3 pl-0">
                                <select class="selectpicker form-control <?php $__errorArgs = ['filter_sort_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="filter_sort_by" id="filter_sort_by">
                                    <option value="<?php echo e(\App\Item::ITEMS_SORT_BY_NEWEST_CREATED); ?>" <?php echo e($filter_sort_by == \App\Item::ITEMS_SORT_BY_NEWEST_CREATED ? 'selected' : ''); ?>><?php echo e(__('listings_filter.sort-by-newest')); ?></option>
                                    <option value="<?php echo e(\App\Item::ITEMS_SORT_BY_OLDEST_CREATED); ?>" <?php echo e($filter_sort_by == \App\Item::ITEMS_SORT_BY_OLDEST_CREATED ? 'selected' : ''); ?>><?php echo e(__('listings_filter.sort-by-oldest')); ?></option>
                                    <option value="<?php echo e(\App\Item::ITEMS_SORT_BY_HIGHEST_RATING); ?>" <?php echo e($filter_sort_by == \App\Item::ITEMS_SORT_BY_HIGHEST_RATING ? 'selected' : ''); ?>><?php echo e(__('listings_filter.sort-by-highest')); ?></option>
                                    <option value="<?php echo e(\App\Item::ITEMS_SORT_BY_LOWEST_RATING); ?>" <?php echo e($filter_sort_by == \App\Item::ITEMS_SORT_BY_LOWEST_RATING ? 'selected' : ''); ?>><?php echo e(__('listings_filter.sort-by-lowest')); ?></option>
                                    <option value="<?php echo e(\App\Item::ITEMS_SORT_BY_NEARBY_FIRST); ?>" <?php echo e($filter_sort_by == \App\Item::ITEMS_SORT_BY_NEARBY_FIRST ? 'selected' : ''); ?>><?php echo e(__('theme_directory_hub.filter-sort-by-nearby-first')); ?></option>
                                </select>
                                <?php $__errorArgs = ['filter_sort_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-tooltip">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <hr>

                        <div class="row">

                            <?php $__currentLoopData = $all_printable_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $all_printable_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 col-sm-4 col-md-3">
                                    <div class="form-check filter_category_div">
                                        <input <?php echo e(in_array($all_printable_category['category_id'], $filter_categories) ? 'checked' : ''); ?> name="filter_categories[]" class="form-check-input" type="checkbox" value="<?php echo e($all_printable_category['category_id']); ?>" id="filter_categories_<?php echo e($all_printable_category['category_id']); ?>">
                                        <label class="form-check-label" for="filter_categories_<?php echo e($all_printable_category['category_id']); ?>">
                                            <?php echo e($all_printable_category['category_name']); ?>

                                        </label>
                                    </div>
                                </div>
                                <?php $__errorArgs = ['filter_categories'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-tooltip">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <a href="javascript:;" class="show_more text-sm"><?php echo e(__('listings_filter.show-more')); ?></a>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-12 text-right">
                                <a class="btn btn-sm btn-outline-primary rounded" href="<?php echo e(route('page.categories')); ?>">
                                    <?php echo e(__('theme_directory_hub.filter-link-reset-all')); ?>

                                </a>
                                <a class="btn btn-sm btn-primary text-white rounded" id="filter_form_submit">
                                    <?php echo e(__('theme_directory_hub.filter-button-filter-results')); ?>

                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
            <!-- End Filter -->

            <div class="row">

                <div class="col-lg-6">

                    <div class="row mb-4">
                        <div class="col-md-12 text-left border-primary">
                            <h2 class="font-weight-light text-primary"><?php echo e(__('frontend.categories.sub-title-1')); ?></h2>
                        </div>
                    </div>

                    <?php if($ads_before_content->count() > 0): ?>
                        <?php $__currentLoopData = $ads_before_content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ads_before_content_key => $ad_before_content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row mb-5">
                                <?php if($ad_before_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT): ?>
                                    <div class="col-12 text-left">
                                        <div>
                                            <?php echo $ad_before_content->advertisement_code; ?>

                                        </div>
                                    </div>
                                <?php elseif($ad_before_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER): ?>
                                    <div class="col-12 text-center">
                                        <div>
                                            <?php echo $ad_before_content->advertisement_code; ?>

                                        </div>
                                    </div>
                                <?php elseif($ad_before_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT): ?>
                                    <div class="col-12 text-right">
                                        <div>
                                            <?php echo $ad_before_content->advertisement_code; ?>

                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    <div class="row">

                        <?php if($paid_items->count() > 0): ?>
                            <?php $__currentLoopData = $paid_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paid_items_key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-6">
                                    <?php echo $__env->make('frontend.partials.paid-item-block', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                        <?php if($free_items->count() > 0): ?>
                            <?php $__currentLoopData = $free_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $free_items_key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-6">
                                    <?php echo $__env->make('frontend.partials.free-item-block', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>

                    </div>

                    <div class="row">
                        <div class="col-12">

                            <?php echo e($pagination->links()); ?>

                        </div>
                    </div>

                    <?php if($ads_after_content->count() > 0): ?>
                        <?php $__currentLoopData = $ads_after_content; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ads_after_content_key => $ad_after_content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row mt-5">
                                <?php if($ad_after_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_LEFT): ?>
                                    <div class="col-12 text-left">
                                        <div>
                                            <?php echo $ad_after_content->advertisement_code; ?>

                                        </div>
                                    </div>
                                <?php elseif($ad_after_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_CENTER): ?>
                                    <div class="col-12 text-center">
                                        <div>
                                            <?php echo $ad_after_content->advertisement_code; ?>

                                        </div>
                                    </div>
                                <?php elseif($ad_after_content->advertisement_alignment == \App\Advertisement::AD_ALIGNMENT_RIGHT): ?>
                                    <div class="col-12 text-right">
                                        <div>
                                            <?php echo $ad_after_content->advertisement_code; ?>

                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                </div>

                <div class="col-lg-6">
                    <div class="sticky-top" id="mapid-box"></div>
                </div>

            </div>

        </div>
    </div>

    <?php if($all_states->count() > 0): ?>
        <div class="site-section bg-light">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-7 text-left border-primary">
                        <h2 class="font-weight-light text-primary"><?php echo e(__('frontend.categories.sub-title-2')); ?></h2>
                    </div>
                </div>
                <div class="row mt-5">

                    <?php $__currentLoopData = $all_states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $all_states_key => $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                            <a href="<?php echo e(route('page.state', ['state_slug' => $state->state_slug])); ?>"><?php echo e($state->state_name); ?></a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

    <?php if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP): ?>
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="<?php echo e(asset('frontend/vendor/leaflet/leaflet.js')); ?>"></script>
    <?php endif; ?>

    <?php if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO): ?>
        <!-- Youtube Background for Header -->
        <script src="<?php echo e(asset('frontend/vendor/jquery-youtube-background/jquery.youtube-background.js')); ?>"></script>
    <?php endif; ?>

    <script src="<?php echo e(asset('frontend/vendor/bootstrap-select/bootstrap-select.min.js')); ?>"></script>
    <?php echo $__env->make('frontend.partials.bootstrap-select-locale', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>

        $(document).ready(function(){

            "use strict";

            <?php if($site_innerpage_header_background_type == \App\Customization::SITE_INNERPAGE_HEADER_BACKGROUND_TYPE_YOUTUBE_VIDEO): ?>
            /**
             * Start Initial Youtube Background
             */
            $("[data-youtube]").youtube_background();
            /**
             * End Initial Youtube Background
             */
            <?php endif; ?>

            /**
             * Start initial map box with OpenStreetMap
             */
            <?php if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_OPEN_STREET_MAP): ?>

                <?php if(count($paid_items) || count($free_items)): ?>

                var window_height = $(window).height();
                $('#mapid-box').css('height', window_height + 'px');

                var map = L.map('mapid-box', {
                    zoom: 15,
                    scrollWheelZoom: true,
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                var bounds = [];
                <?php $__currentLoopData = $paid_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paid_items_key => $paid_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($paid_item->item_type == \App\Item::ITEM_TYPE_REGULAR): ?>

                        bounds.push([ <?php echo e($paid_item->item_lat); ?>, <?php echo e($paid_item->item_lng); ?> ]);
                        var marker = L.marker([<?php echo e($paid_item->item_lat); ?>, <?php echo e($paid_item->item_lng); ?>]).addTo(map);

                        var popup_item_title = '<?php echo e($paid_item->item_title); ?>';

                        <?php if($paid_item->item_address_hide): ?>
                            var popup_item_address = '<?php echo e($paid_item->city->city_name . ', ' . $paid_item->state->state_name . ' ' . $paid_item->item_postal_code); ?>';
                        <?php else: ?>
                            var popup_item_address = '<?php echo e($paid_item->item_address . ', ' . $paid_item->city->city_name . ', ' . $paid_item->state->state_name . ' ' . $paid_item->item_postal_code); ?>';
                        <?php endif; ?>
                        var popup_item_get_direction = '<a target="_blank" href="'+ '<?php echo e('https://www.google.com/maps/dir/?api=1&destination=' . $paid_item->item_lat . ',' . $paid_item->item_lng); ?>' +'"><i class="fas fa-directions"></i> '+ '<?php echo e(__('google_map.get-directions')); ?>' +'</a>';

                        <?php if($paid_item->getCountRating() > 0): ?>
                            var popup_item_rating = '<?php echo e($paid_item->item_average_rating); ?>' + '/5';
                            var popup_item_reviews = ' - <?php echo e($paid_item->getCountRating()); ?>' + ' ' + '<?php echo e(__('category_image_option.map.review')); ?>';
                        <?php else: ?>
                            var popup_item_rating = '';
                            var popup_item_reviews = '';
                        <?php endif; ?>

                        var popup_item_feature_image_link = '<img src="'+ '<?php echo e(!empty($paid_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $paid_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')); ?>' +'">';
                        var popup_item_link = '<a href="' + '<?php echo e(route('page.item', $paid_item->item_slug)); ?>' + '" target="_blank">' + popup_item_title + '</a>';

                        marker.bindPopup(popup_item_feature_image_link + "<br><br>" + popup_item_link + "<br>" + popup_item_rating + popup_item_reviews + "<br>" + popup_item_address + '<br>' + popup_item_get_direction, {
                            minWidth:226,
                            maxWidth:226
                        });

                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php $__currentLoopData = $free_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $free_items_key => $free_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($free_item->item_type == \App\Item::ITEM_TYPE_REGULAR): ?>

                        bounds.push([ <?php echo e($free_item->item_lat); ?>, <?php echo e($free_item->item_lng); ?> ]);
                        var marker = L.marker([<?php echo e($free_item->item_lat); ?>, <?php echo e($free_item->item_lng); ?>]).addTo(map);

                        var popup_item_title = '<?php echo e($free_item->item_title); ?>';

                        <?php if($free_item->item_address_hide): ?>
                        var popup_item_address = '<?php echo e($free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code); ?>';
                        <?php else: ?>
                        var popup_item_address = '<?php echo e($free_item->item_address . ', ' . $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code); ?>';
                        <?php endif; ?>
                        var popup_item_get_direction = '<a target="_blank" href="'+ '<?php echo e('https://www.google.com/maps/dir/?api=1&destination=' . $free_item->item_lat . ',' . $free_item->item_lng); ?>' +'"><i class="fas fa-directions"></i> '+ '<?php echo e(__('google_map.get-directions')); ?>' +'</a>';

                        <?php if($free_item->getCountRating() > 0): ?>
                        var popup_item_rating = '<?php echo e($free_item->item_average_rating); ?>' + '/5';
                        var popup_item_reviews = ' - <?php echo e($free_item->getCountRating()); ?>' + ' ' + '<?php echo e(__('category_image_option.map.review')); ?>';
                        <?php else: ?>
                        var popup_item_rating = '';
                        var popup_item_reviews = '';
                        <?php endif; ?>

                        var popup_item_feature_image_link = '<img src="'+ '<?php echo e(!empty($free_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $free_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')); ?>' +'">';
                        var popup_item_link = '<a href="' + '<?php echo e(route('page.item', $free_item->item_slug)); ?>' + '" target="_blank">' + popup_item_title + '</a>';

                        marker.bindPopup(popup_item_feature_image_link + "<br><br>" + popup_item_link + "<br>" + popup_item_rating + popup_item_reviews + "<br>" + popup_item_address + '<br>' + popup_item_get_direction, {
                            minWidth:226,
                            maxWidth:226
                        });

                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                if(bounds.length === 0)
                {
                    // Destroy mapid-box DOM since no regular listings found
                    $("#mapid-box").remove();
                }
                else
                {
                    map.fitBounds(bounds, {
                        maxZoom:11
                    });
                }

                <?php endif; ?>

                $(".listing_for_map_hover").on('mouseover', function() {

                    var map_item_lat = this.getAttribute("data-map-lat");
                    var map_item_lng = this.getAttribute("data-map-lng");
                    var map_item_title = this.getAttribute("data-map-title");
                    var map_item_address = this.getAttribute("data-map-address");

                    var map_item_rating = '';
                    var map_item_reviews = parseInt(this.getAttribute("data-map-reviews"));

                    if(map_item_reviews > 0)
                    {
                        map_item_rating = this.getAttribute("data-map-rating") + '/5';
                        map_item_reviews = ' - ' + this.getAttribute("data-map-reviews") + ' ' + '<?php echo e(__('category_image_option.map.review')); ?>';
                    }
                    else
                    {
                        map_item_rating = '';
                        map_item_reviews = '';
                    }

                    var map_item_link = '<a href="' + this.getAttribute("data-map-link") + '" target="_blank">' + map_item_title + '</a>';
                    var map_item_feature_image_link = '<img src="'+ this.getAttribute("data-map-feature-image-link") + '">';
                    var map_item_get_direction = '<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=' + map_item_lat + ',' + map_item_lng + '"><i class="fas fa-directions"></i> '+ '<?php echo e(__('google_map.get-directions')); ?>' +'</a>';

                    if(map_item_lat !== '' && map_item_lng !== '')
                    {
                        map_item_lat = parseFloat(map_item_lat);
                        map_item_lng = parseFloat(map_item_lng);

                        var this_latlng = L.latLng(map_item_lat, map_item_lng);

                        var tooltipPopup = L.popup({
                            offset: L.point(0, -27),
                            minWidth:226,
                            maxWidth:226,
                            keepInView:true
                        })
                            .setLatLng(this_latlng)
                            .setContent(map_item_feature_image_link + "<br><br>" + map_item_link + "<br>" + map_item_rating + map_item_reviews + "<br>" + map_item_address + "<br>" + map_item_get_direction)
                            .openOn(map);
                    }
                });
            <?php endif; ?>
            /**
             * End initial map box with OpenStreetMap
             */

            /**
             * Start show more/less
             */
            //this will execute on page load(to be more specific when document ready event occurs)
            <?php if(count($filter_categories) == 0): ?>
            if ($(".filter_category_div").length > 7)
            {
                $(".filter_category_div:gt(7)").hide();
                $(".show_more").show();
            }
            else
            {
                $(".show_more").hide();
            }
            <?php else: ?>
            if ($(".filter_category_div").length > 7)
            {
                $(".show_more").text("<?php echo e(__('listings_filter.show-less')); ?>");
                $(".show_more").show();
            }
            else
            {
                $(".show_more").hide();
            }
            <?php endif; ?>


            $(".show_more").on('click', function() {
                //toggle elements with class .ty-compact-list that their index is bigger than 2
                $(".filter_category_div:gt(7)").toggle();
                //change text of show more element just for demonstration purposes to this demo
                $(this).text() === "<?php echo e(__('listings_filter.show-more')); ?>" ? $(this).text("<?php echo e(__('listings_filter.show-less')); ?>") : $(this).text("<?php echo e(__('listings_filter.show-more')); ?>");
            });
            /**
             * End show more/less
             */


            /**
             * Start state selector in filter
             */
            $('#filter_state').on('change', function() {

                if(this.value > 0)
                {
                    $('#filter_city').html("<option selected><?php echo e(__('prefer_country.loading-wait')); ?></option>");
                    $('#filter_city').selectpicker('refresh');

                    var ajax_url = '/ajax/cities/' + this.value;

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    jQuery.ajax({
                        url: ajax_url,
                        method: 'get',
                        data: {
                        },
                        success: function(result){
                            console.log(result);
                            $('#filter_city').html("<option value='0'><?php echo e(__('prefer_country.all-city')); ?></option>");
                            $('#filter_city').selectpicker('refresh');
                            $.each(JSON.parse(result), function(key, value) {
                                var city_id = value.id;
                                var city_name = value.city_name;
                                $('#filter_city').append('<option value="'+ city_id +'">' + city_name + '</option>');
                            });
                            $('#filter_city').selectpicker('refresh');
                        }});
                }
                else
                {
                    $('#filter_city').html("<option value='0'><?php echo e(__('prefer_country.all-city')); ?></option>");
                    $('#filter_city').selectpicker('refresh');
                }

            });
            /**
             * End state selector in filter
             */

            /**
             * Start filter form submit
             */
            $("#filter_form_submit").on('click', function() {
                $("#filter_form").submit();
            });
            /**
             * End filter form submit
             */
        });

    </script>

    <?php if($site_global_settings->setting_site_map == \App\Setting::SITE_MAP_GOOGLE_MAP): ?>
        <script>
            // Initial the google map
            function initMap() {

                <?php if(count($paid_items) || count($free_items)): ?>

                var window_height = $(window).height();
                $('#mapid-box').css('height', window_height + 'px');

                var locations = [];

                <?php $__currentLoopData = $paid_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paid_items_key => $paid_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($paid_item->item_type == \App\Item::ITEM_TYPE_REGULAR): ?>

                        var popup_item_title = '<?php echo e($paid_item->item_title); ?>';

                        <?php if($paid_item->item_address_hide): ?>
                            var popup_item_address = '<?php echo e($paid_item->city->city_name . ', ' . $paid_item->state->state_name . ' ' . $paid_item->item_postal_code); ?>';
                        <?php else: ?>
                            var popup_item_address = '<?php echo e($paid_item->item_address . ', ' . $paid_item->city->city_name . ', ' . $paid_item->state->state_name . ' ' . $paid_item->item_postal_code); ?>';
                        <?php endif; ?>
                        var popup_item_get_direction = '<a target="_blank" href="'+ '<?php echo e('https://www.google.com/maps/dir/?api=1&destination=' . $paid_item->item_lat . ',' . $paid_item->item_lng); ?>' +'"><i class="fas fa-directions"></i> '+ '<?php echo e(__('google_map.get-directions')); ?>' +'</a>';

                        <?php if($paid_item->getCountRating() > 0): ?>
                            var popup_item_rating = '<?php echo e($paid_item->item_average_rating); ?>' + '/5';
                            var popup_item_reviews = ' - <?php echo e($paid_item->getCountRating()); ?>' + ' ' + '<?php echo e(__('category_image_option.map.review')); ?>';
                        <?php else: ?>
                            var popup_item_rating = '';
                            var popup_item_reviews = '';
                        <?php endif; ?>

                        var popup_item_feature_image_link = '<img src="'+ '<?php echo e(!empty($paid_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $paid_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')); ?>' +'">';
                        var popup_item_link = '<a href="' + '<?php echo e(route('page.item', $paid_item->item_slug)); ?>' + '" target="_blank">' + popup_item_title + '</a>';

                        locations.push(["<div class='google_map_scrollFix'>" + popup_item_feature_image_link + "<br><br>" + popup_item_link + "<br>" + popup_item_rating + popup_item_reviews + "<br>" + popup_item_address + '<br>' + popup_item_get_direction + "</div>", <?php echo e($paid_item->item_lat); ?>, <?php echo e($paid_item->item_lng); ?> ]);

                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php $__currentLoopData = $free_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $free_items_key => $free_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($free_item->item_type == \App\Item::ITEM_TYPE_REGULAR): ?>

                        var popup_item_title = '<?php echo e($free_item->item_title); ?>';

                        <?php if($free_item->item_address_hide): ?>
                        var popup_item_address = '<?php echo e($free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code); ?>';
                        <?php else: ?>
                        var popup_item_address = '<?php echo e($free_item->item_address . ', ' . $free_item->city->city_name . ', ' . $free_item->state->state_name . ' ' . $free_item->item_postal_code); ?>';
                        <?php endif; ?>
                        var popup_item_get_direction = '<a target="_blank" href="'+ '<?php echo e('https://www.google.com/maps/dir/?api=1&destination=' . $free_item->item_lat . ',' . $free_item->item_lng); ?>' +'"><i class="fas fa-directions"></i> '+ '<?php echo e(__('google_map.get-directions')); ?>' +'</a>';

                        <?php if($free_item->getCountRating() > 0): ?>
                        var popup_item_rating = '<?php echo e($free_item->item_average_rating); ?>' + '/5';
                        var popup_item_reviews = ' - <?php echo e($free_item->getCountRating()); ?>' + ' ' + '<?php echo e(__('category_image_option.map.review')); ?>';
                        <?php else: ?>
                        var popup_item_rating = '';
                        var popup_item_reviews = '';
                        <?php endif; ?>

                        var popup_item_feature_image_link = '<img src="'+ '<?php echo e(!empty($free_item->item_image_small) ? \Illuminate\Support\Facades\Storage::disk('public')->url('item/' . $free_item->item_image_small) : asset('frontend/images/placeholder/full_item_feature_image_small.webp')); ?>' +'">';
                        var popup_item_link = '<a href="' + '<?php echo e(route('page.item', $free_item->item_slug)); ?>' + '" target="_blank">' + popup_item_title + '</a>';

                        locations.push(["<div class='google_map_scrollFix'>" + popup_item_feature_image_link + "<br><br>" + popup_item_link + "<br>" + popup_item_rating + popup_item_reviews + "<br>" + popup_item_address + '<br>' + popup_item_get_direction + "</div>", <?php echo e($free_item->item_lat); ?>, <?php echo e($free_item->item_lng); ?> ]);

                     <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                var infowindow = null;
                var infowindow_hover = null;

                if(locations.length === 0)
                {
                    // Destroy mapid-box DOM since no regular listings found
                    $("#mapid-box").remove();
                }
                else
                {
                    var map = new google.maps.Map(document.getElementById('mapid-box'), {
                        zoom: 12,
                        //center: new google.maps.LatLng(-33.92, 151.25),
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    //create empty LatLngBounds object
                    var bounds = new google.maps.LatLngBounds();
                    var infowindow = new google.maps.InfoWindow();

                    var marker, i;

                    for (i = 0; i < locations.length; i++) {
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                            map: map
                        });

                        //extend the bounds to include each marker's position
                        bounds.extend(marker.position);

                        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                            return function() {

                                if(infowindow_hover)
                                {
                                    infowindow_hover.close();
                                }

                                infowindow.setContent(locations[i][0]);
                                infowindow.open(map, marker);
                            }
                        })(marker, i));
                    }

                    //now fit the map to the newly inclusive bounds
                    map.fitBounds(bounds);

                    var listener = google.maps.event.addListener(map, "idle", function() {
                        if (map.getZoom() > 12) map.setZoom(12);
                        google.maps.event.removeListener(listener);
                    });

                    // Start google map hover event
                    $(".listing_for_map_hover").on('mouseover', function() {

                        var map_item_lat = this.getAttribute("data-map-lat");
                        var map_item_lng = this.getAttribute("data-map-lng");
                        var map_item_title = this.getAttribute("data-map-title");
                        var map_item_address = this.getAttribute("data-map-address");

                        var map_item_rating = '';
                        var map_item_reviews = parseInt(this.getAttribute("data-map-reviews"));

                        if(map_item_reviews > 0)
                        {
                            map_item_rating = this.getAttribute("data-map-rating") + '/5';
                            map_item_reviews = ' - ' + this.getAttribute("data-map-reviews") + ' ' + '<?php echo e(__('category_image_option.map.review')); ?>';
                        }
                        else
                        {
                            map_item_rating = '';
                            map_item_reviews = '';
                        }

                        var map_item_link = '<a href="' + this.getAttribute("data-map-link") + '" target="_blank">' + map_item_title + '</a>';
                        var map_item_feature_image_link = '<img src="'+ this.getAttribute("data-map-feature-image-link") + '">';
                        var map_item_get_direction = '<a target="_blank" href="https://www.google.com/maps/dir/?api=1&destination=' + map_item_lat + ',' + map_item_lng + '"><i class="fas fa-directions"></i> '+ '<?php echo e(__('google_map.get-directions')); ?>' +'</a>';

                        if(map_item_lat !== '' && map_item_lng !== '')
                        {
                            var center = new google.maps.LatLng(map_item_lat, map_item_lng);
                            var contentString = "<div class='google_map_scrollFix'>" + map_item_feature_image_link + "<br><br>" + map_item_link + "<br>" + map_item_rating + map_item_reviews + "<br>" + map_item_address + "<br>" + map_item_get_direction + "</div>";

                            if(infowindow_hover)
                            {
                                infowindow_hover.close();
                            }
                            if(infowindow)
                            {
                                infowindow.close();
                            }

                            infowindow_hover = new google.maps.InfoWindow({
                                content: contentString,
                                position: center,
                                pixelOffset: new google.maps.Size(0, -45)
                            });

                            infowindow_hover.open({
                                map,
                                shouldFocus: true,
                            });
                        }
                    });
                    // End google map hover event
                }
                <?php endif; ?>
            }

        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js??v=quarterly&key=<?php echo e($site_global_settings->setting_site_map_google_api_key); ?>&callback=initMap"></script>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/Laravel/Techcrave/listdirectory/http/laravel_project/resources/views/frontend/categories.blade.php ENDPATH**/ ?>