<?php if($site_global_settings->setting_site_maintenance_mode == \App\Setting::SITE_MAINTENANCE_MODE_ON): ?>
    <div class="row mb-2">
        <div class="col-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <p class="">
                    <strong>
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo e(__('maintenance_mode.maintenance-mode-warning-title')); ?>

                    </strong>
                </p>
                <p class="">
                    <?php echo e(__('maintenance_mode.maintenance-mode-warning')); ?>

                </p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if( !empty(Session::get('flash_message')) ): ?>
    <div class="row mb-2">
        <div class="col-12">
            <div class="alert alert-<?php echo e(Session::get('flash_type')); ?> alert-dismissible fade show" role="alert">
                <?php echo e(Session::get('flash_message')); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/html/Laravel/Techcrave/listdirectory/http/laravel_project/resources/views/backend/admin/partials/alert.blade.php ENDPATH**/ ?>