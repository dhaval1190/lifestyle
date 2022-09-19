<?php $__env->startSection('styles'); ?>
    <!-- searchable selector -->
    <link href="<?php echo e(asset('backend/vendor/bootstrap-select/bootstrap-select.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800"><?php echo e(__('backend.custom-field.custom-field')); ?></h1>
            <p class="mb-4"><?php echo e(__('backend.custom-field.custom-field-desc')); ?></p>
        </div>
        <div class="col-3 text-right">
            <a href="<?php echo e(route('admin.custom-fields.create')); ?>" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text"><?php echo e(__('backend.custom-field.add-custom-field')); ?></span>
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
                                <?php echo e(number_format($custom_fields_count) . ' ' . __('category_description.records')); ?>

                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                    <tr class="bg-info text-white">
                                        <th><?php echo e(__('backend.custom-field.name')); ?></th>
                                        <th><?php echo e(__('backend.custom-field.type')); ?></th>
                                        <th><?php echo e(__('backend.custom-field.seed-value')); ?></th>
                                        <th><?php echo e(__('backend.custom-field.order')); ?></th>
                                        <th><?php echo e(__('backend.category.category')); ?></th>
                                        <th><?php echo e(__('backend.shared.action')); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $custom_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $custom_fields_key => $custom_field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($custom_field->custom_field_name); ?></td>
                                            <td>
                                                <?php if($custom_field->custom_field_type == \App\CustomField::TYPE_TEXT): ?>
                                                    <?php echo e(__('backend.custom-field.text')); ?>

                                                <?php elseif($custom_field->custom_field_type == \App\CustomField::TYPE_SELECT): ?>
                                                    <?php echo e(__('backend.custom-field.select')); ?>

                                                <?php elseif($custom_field->custom_field_type == \App\CustomField::TYPE_MULTI_SELECT): ?>
                                                    <?php echo e(__('backend.custom-field.multi-select')); ?>

                                                <?php elseif($custom_field->custom_field_type == \App\CustomField::TYPE_LINK): ?>
                                                    <?php echo e(__('backend.custom-field.link')); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($custom_field->custom_field_seed_value); ?></td>
                                            <td><?php echo e($custom_field->custom_field_order); ?></td>
                                            <td>
                                                <?php
                                                    $custom_field_categories = $custom_field->allCategories()->get();
                                                    $custom_field_categories_count = $custom_field_categories->count();
                                                ?>

                                                <?php $__currentLoopData = $custom_field_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $custom_field_categories_key => $custom_field_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($custom_field_categories_count == $custom_field_categories_key + 1): ?>
                                                        <?php echo e($custom_field_category->category_name); ?>

                                                    <?php else: ?>
                                                        <?php echo e($custom_field_category->category_name . ", "); ?>

                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                            <td>
                                                <a target="_blank" href="<?php echo e(route('admin.custom-fields.edit', ['custom_field' => $custom_field])); ?>" class="btn btn-primary btn-circle">
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
                            <form method="GET" action="<?php echo e(route('admin.custom-fields.index')); ?>">

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

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label for="filter_categories" class="text-gray-800"><?php echo e(__('listings_filter.categories')); ?></label>

                                        <?php $__currentLoopData = $all_printable_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $all_printable_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="form-check filter_category_div">
                                                <input <?php echo e(in_array($all_printable_category['category_id'], $filter_categories) ? 'checked' : ''); ?> name="filter_categories[]" class="form-check-input" type="checkbox" value="<?php echo e($all_printable_category['category_id']); ?>" id="filter_categories_<?php echo e($all_printable_category['category_id']); ?>">
                                                <label class="form-check-label" for="filter_categories_<?php echo e($all_printable_category['category_id']); ?>">
                                                    <?php echo e($all_printable_category['category_name']); ?>

                                                </label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <a href="javascript:;" class="show_more"><?php echo e(__('listings_filter.show-more')); ?></a>
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
                                    </div>
                                </div>

                                <hr>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label class="text-gray-800"><?php echo e(__('backend.custom-field.type')); ?></label>

                                        <div class="form-check">
                                            <input <?php echo e(in_array(\App\CustomField::TYPE_TEXT, $filter_custom_field_type) ? 'checked' : ''); ?> name="filter_custom_field_type[]" class="form-check-input" type="checkbox" value="<?php echo e(\App\CustomField::TYPE_TEXT); ?>" id="filter_custom_field_type_<?php echo e(\App\CustomField::TYPE_TEXT); ?>">
                                            <label class="form-check-label" for="filter_custom_field_type_<?php echo e(\App\CustomField::TYPE_TEXT); ?>">
                                                <?php echo e(__('backend.custom-field.text')); ?>

                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input <?php echo e(in_array(\App\CustomField::TYPE_SELECT, $filter_custom_field_type) ? 'checked' : ''); ?> name="filter_custom_field_type[]" class="form-check-input" type="checkbox" value="<?php echo e(\App\CustomField::TYPE_SELECT); ?>" id="filter_custom_field_type_<?php echo e(\App\CustomField::TYPE_SELECT); ?>">
                                            <label class="form-check-label" for="filter_custom_field_type_<?php echo e(\App\CustomField::TYPE_SELECT); ?>">
                                                <?php echo e(__('backend.custom-field.select')); ?>

                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input <?php echo e(in_array(\App\CustomField::TYPE_MULTI_SELECT, $filter_custom_field_type) ? 'checked' : ''); ?> name="filter_custom_field_type[]" class="form-check-input" type="checkbox" value="<?php echo e(\App\CustomField::TYPE_MULTI_SELECT); ?>" id="filter_custom_field_type_<?php echo e(\App\CustomField::TYPE_MULTI_SELECT); ?>">
                                            <label class="form-check-label" for="filter_custom_field_type_<?php echo e(\App\CustomField::TYPE_MULTI_SELECT); ?>">
                                                <?php echo e(__('backend.custom-field.multi-select')); ?>

                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input <?php echo e(in_array(\App\CustomField::TYPE_LINK, $filter_custom_field_type) ? 'checked' : ''); ?> name="filter_custom_field_type[]" class="form-check-input" type="checkbox" value="<?php echo e(\App\CustomField::TYPE_LINK); ?>" id="filter_custom_field_type_<?php echo e(\App\CustomField::TYPE_LINK); ?>">
                                            <label class="form-check-label" for="filter_custom_field_type_<?php echo e(\App\CustomField::TYPE_LINK); ?>">
                                                <?php echo e(__('backend.custom-field.link')); ?>

                                            </label>
                                        </div>
                                        <?php $__errorArgs = ['filter_custom_field_type'];
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
                                            <option value="<?php echo e(\App\CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_CREATED); ?>" <?php echo e($filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_CREATED ? 'selected' : ''); ?>><?php echo e(__('prefer_country.item-sort-by-newest-created')); ?></option>
                                            <option value="<?php echo e(\App\CustomField::CUSTOM_FIELDS_SORT_BY_OLDEST_CREATED); ?>" <?php echo e($filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_OLDEST_CREATED ? 'selected' : ''); ?>><?php echo e(__('prefer_country.item-sort-by-oldest-created')); ?></option>

                                            <option value="<?php echo e(\App\CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_UPDATED); ?>" <?php echo e($filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_NEWEST_UPDATED ? 'selected' : ''); ?>><?php echo e(__('prefer_country.item-sort-by-newest-updated')); ?></option>
                                            <option value="<?php echo e(\App\CustomField::CUSTOM_FIELDS_SORT_BY_OLDEST_UPDATED); ?>" <?php echo e($filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_OLDEST_UPDATED ? 'selected' : ''); ?>><?php echo e(__('prefer_country.item-sort-by-oldest-updated')); ?></option>

                                            <option value="<?php echo e(\App\CustomField::CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_A_Z); ?>" <?php echo e($filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_A_Z ? 'selected' : ''); ?>><?php echo e(__('category_index.custom-field-name-a-z')); ?></option>
                                            <option value="<?php echo e(\App\CustomField::CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_Z_A); ?>" <?php echo e($filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_CUSTOM_FIELD_NAME_Z_A ? 'selected' : ''); ?>><?php echo e(__('category_index.custom-field-name-z-a')); ?></option>

                                            <option value="<?php echo e(\App\CustomField::CUSTOM_FIELDS_SORT_BY_MOST_RELEVANT); ?>" <?php echo e($filter_sort_by == \App\CustomField::CUSTOM_FIELDS_SORT_BY_MOST_RELEVANT ? 'selected' : ''); ?>><?php echo e(__('item_search.most-relevant')); ?></option>
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
                                            <option value="<?php echo e(\App\CustomField::COUNT_PER_PAGE_10); ?>" <?php echo e($filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_10 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-10')); ?></option>
                                            <option value="<?php echo e(\App\CustomField::COUNT_PER_PAGE_25); ?>" <?php echo e($filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_25 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-25')); ?></option>
                                            <option value="<?php echo e(\App\CustomField::COUNT_PER_PAGE_50); ?>" <?php echo e($filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_50 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-50')); ?></option>
                                            <option value="<?php echo e(\App\CustomField::COUNT_PER_PAGE_100); ?>" <?php echo e($filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_100 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-100')); ?></option>
                                            <option value="<?php echo e(\App\CustomField::COUNT_PER_PAGE_250); ?>" <?php echo e($filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_250 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-250')); ?></option>
                                            <option value="<?php echo e(\App\CustomField::COUNT_PER_PAGE_500); ?>" <?php echo e($filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_500 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-500')); ?></option>
                                            <option value="<?php echo e(\App\CustomField::COUNT_PER_PAGE_1000); ?>" <?php echo e($filter_count_per_page == \App\CustomField::COUNT_PER_PAGE_1000 ? 'selected' : ''); ?>><?php echo e(__('importer_csv.import-listing-per-page-1000')); ?></option>
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
                                        <a class="btn btn-outline-primary btn-block" href="<?php echo e(route('admin.custom-fields.index')); ?>">
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

            "use strict";

            /**
             * Start show more/less
             */
            //this will execute on page load(to be more specific when document ready event occurs)
            if ($(".filter_category_div").length > 5)
            {
                $(".filter_category_div:gt(5)").hide();
                $(".show_more").show();
            }

            $(".show_more").on('click', function() {
                //toggle elements with class .ty-compact-list that their index is bigger than 2
                $(".filter_category_div:gt(5)").toggle();
                //change text of show more element just for demonstration purposes to this demo
                $(this).text() === "<?php echo e(__('listings_filter.show-more')); ?>" ? $(this).text("<?php echo e(__('listings_filter.show-less')); ?>") : $(this).text("<?php echo e(__('listings_filter.show-more')); ?>");
            });
            /**
             * End show more/less
             */
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/Laravel/Techcrave/listdirectory/http/laravel_project/resources/views/backend/admin/custom-field/index.blade.php ENDPATH**/ ?>