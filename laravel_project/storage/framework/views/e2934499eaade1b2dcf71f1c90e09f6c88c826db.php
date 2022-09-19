<?php $__env->startSection('styles'); ?>
    <!-- searchable selector -->
    <link href="<?php echo e(asset('backend/vendor/bootstrap-select/bootstrap-select.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800"><?php echo e(__('backend.category.category')); ?></h1>
            <p class="mb-4"><?php echo e(__('backend.category.category-desc')); ?></p>
        </div>
        <div class="col-3 text-right">
            <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text"><?php echo e(__('backend.category.add-category')); ?></span>
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row">
                <div class="col-12 col-md-10">

                    <div class="row pb-2">
                        <div class="col-12">
                            <span class="text-gray-800">
                                <?php echo e(number_format($categories_count) . ' ' . __('category_description.records')); ?>

                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr class="bg-info text-white">
                                        <th><?php echo e(__('backend.category.name')); ?></th>
                                        <th><?php echo e(__('backend.category.slug')); ?></th>
                                        <th><?php echo e(__('category_image_option.index-table-icon-image')); ?></th>
                                        <th><?php echo e(__('categories.parent-cat')); ?></th>
                                        <th><?php echo e(__('backend.shared.action')); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categories_key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($category->category_name); ?></td>
                                            <td><?php echo e($category->category_slug); ?></td>
                                            <td>
                                                <?php if(!empty($category->category_icon)): ?>
                                                    <i class="<?php echo e($category->category_icon); ?>"></i>
                                                    <?php echo e($category->category_icon); ?>

                                                <?php endif; ?>

                                                <?php if(!empty($category->category_image)): ?>
                                                    <hr>
                                                    <img class="img-responsive category-img-preview" src="<?php echo e(\Illuminate\Support\Facades\Storage::disk('public')->url('category/'. $category->category_image)); ?>">
                                                <?php endif; ?>
                                                <hr>
                                                <?php if($category->category_thumbnail_type == \App\Category::CATEGORY_THUMBNAIL_TYPE_ICON): ?>
                                                    <span class="text-sm border border-info text-info pl-1 pr-1 rounded">
                                                        <i class="fa-solid fa-font-awesome"></i>
                                                        <?php echo e(__('category_image_option.form-category-icon-image-select-icon')); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-sm border border-info text-info pl-1 pr-1 rounded">
                                                        <i class="fa-solid fa-image"></i>
                                                        <?php echo e(__('category_image_option.form-category-icon-image-select-image')); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($category->category_parent_id)): ?>
                                                    <?php echo e(\App\Category::find($category->category_parent_id)->category_name); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('admin.categories.edit', ['category' => $category])); ?>" class="btn btn-primary btn-circle">
                                                    <i class="fas fa-cog"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <?php echo e($pagination->links()); ?>

                        </div>
                    </div>

                </div>

                <div class="col-12 col-md-2 pt-3 border-left-info">

                    <div class="row mb-3">
                        <div class="col-12">
                            <span class="text-gray-800">
                                <i class="fas fa-filter"></i>
                                <?php echo e(__('listings_filter.filters')); ?>

                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <form method="GET" action="<?php echo e(route('admin.categories.index')); ?>">

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label for="search_query" class="text-black"><?php echo e(__('frontend.search.search')); ?></label>
                                        <input id="search_query" type="text" class="form-control <?php $__errorArgs = ['search_query'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="search_query" value="<?php echo e($search_query); ?>">
                                        <?php $__errorArgs = ['search_query'];
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

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-gray-800" for="filter_sort_by"><?php echo e(__('listings_filter.sort-by')); ?></label>
                                        <select class="selectpicker form-control <?php $__errorArgs = ['filter_sort_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="filter_sort_by" id="filter_sort_by">
                                            <option value="<?php echo e(\App\Category::CATEGORIES_SORT_BY_NEWEST_CREATED); ?>" <?php echo e($filter_sort_by == \App\Category::CATEGORIES_SORT_BY_NEWEST_CREATED ? 'selected' : ''); ?>><?php echo e(__('prefer_country.item-sort-by-newest-created')); ?></option>
                                            <option value="<?php echo e(\App\Category::CATEGORIES_SORT_BY_OLDEST_CREATED); ?>" <?php echo e($filter_sort_by == \App\Category::CATEGORIES_SORT_BY_OLDEST_CREATED ? 'selected' : ''); ?>><?php echo e(__('prefer_country.item-sort-by-oldest-created')); ?></option>

                                            <option value="<?php echo e(\App\Category::CATEGORIES_SORT_BY_NEWEST_UPDATED); ?>" <?php echo e($filter_sort_by == \App\Category::CATEGORIES_SORT_BY_NEWEST_UPDATED ? 'selected' : ''); ?>><?php echo e(__('prefer_country.item-sort-by-newest-updated')); ?></option>
                                            <option value="<?php echo e(\App\Category::CATEGORIES_SORT_BY_OLDEST_UPDATED); ?>" <?php echo e($filter_sort_by == \App\Category::CATEGORIES_SORT_BY_OLDEST_UPDATED ? 'selected' : ''); ?>><?php echo e(__('prefer_country.item-sort-by-oldest-updated')); ?></option>

                                            <option value="<?php echo e(\App\Category::CATEGORIES_SORT_BY_CATEGORY_NAME_A_Z); ?>" <?php echo e($filter_sort_by == \App\Category::CATEGORIES_SORT_BY_CATEGORY_NAME_A_Z ? 'selected' : ''); ?>><?php echo e(__('category_index.category-name-a-z')); ?></option>
                                            <option value="<?php echo e(\App\Category::CATEGORIES_SORT_BY_CATEGORY_NAME_Z_A); ?>" <?php echo e($filter_sort_by == \App\Category::CATEGORIES_SORT_BY_CATEGORY_NAME_Z_A ? 'selected' : ''); ?>><?php echo e(__('category_index.category-name-z-a')); ?></option>

                                            <option value="<?php echo e(\App\Category::CATEGORIES_SORT_BY_MOST_RELEVANT); ?>" <?php echo e($filter_sort_by == \App\Category::CATEGORIES_SORT_BY_MOST_RELEVANT ? 'selected' : ''); ?>><?php echo e(__('item_search.most-relevant')); ?></option>
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

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-gray-800" for="filter_count_per_page"><?php echo e(__('prefer_country.rows-per-page')); ?></label>
                                        <select class="selectpicker form-control <?php $__errorArgs = ['filter_count_per_page'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="filter_count_per_page" id="filter_count_per_page">
                                            <option value="<?php echo e(\App\Category::COUNT_PER_PAGE_10); ?>" <?php echo e($filter_count_per_page == \App\Category::COUNT_PER_PAGE_10 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-10')); ?></option>
                                            <option value="<?php echo e(\App\Category::COUNT_PER_PAGE_25); ?>" <?php echo e($filter_count_per_page == \App\Category::COUNT_PER_PAGE_25 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-25')); ?></option>
                                            <option value="<?php echo e(\App\Category::COUNT_PER_PAGE_50); ?>" <?php echo e($filter_count_per_page == \App\Category::COUNT_PER_PAGE_50 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-50')); ?></option>
                                            <option value="<?php echo e(\App\Category::COUNT_PER_PAGE_100); ?>" <?php echo e($filter_count_per_page == \App\Category::COUNT_PER_PAGE_100 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-100')); ?></option>
                                            <option value="<?php echo e(\App\Category::COUNT_PER_PAGE_250); ?>" <?php echo e($filter_count_per_page == \App\Category::COUNT_PER_PAGE_250 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-250')); ?></option>
                                            <option value="<?php echo e(\App\Category::COUNT_PER_PAGE_500); ?>" <?php echo e($filter_count_per_page == \App\Category::COUNT_PER_PAGE_500 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-500')); ?></option>
                                            <option value="<?php echo e(\App\Category::COUNT_PER_PAGE_1000); ?>" <?php echo e($filter_count_per_page == \App\Category::COUNT_PER_PAGE_1000 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-1000')); ?></option>
                                        </select>
                                        <?php $__errorArgs = ['filter_count_per_page'];
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

                                <div class="row form-group">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary btn-block"><?php echo e(__('backend.shared.update')); ?></button>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-12">
                                        <a class="btn btn-outline-primary btn-block" href="<?php echo e(route('admin.categories.index')); ?>">
                                            <?php echo e(__('theme_directory_hub.filter-link-reset-all')); ?>

                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <!-- searchable selector -->
    <script src="<?php echo e(asset('backend/vendor/bootstrap-select/bootstrap-select.min.js')); ?>"></script>
    <?php echo $__env->make('backend.admin.partials.bootstrap-select-locale', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        $(document).ready(function() {
            // "use strict";
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/Laravel/Techcrave/listdirectory/http/laravel_project/resources/views/backend/admin/category/index.blade.php ENDPATH**/ ?>