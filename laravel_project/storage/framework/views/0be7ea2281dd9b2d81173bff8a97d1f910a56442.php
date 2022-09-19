<?php $__env->startSection('styles'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row justify-content-between">
        <div class="col-9">
            <h1 class="h3 mb-2 text-gray-800"><?php echo e(__('license_verify.license-setting')); ?></h1>
            <p class="mb-4">
                <?php echo e(__('license_verify.license-setting-desc')); ?>

            </p>
        </div>
        <div class="col-3 text-right">
        </div>
    </div>

    <!-- Content Row -->
    <div class="row bg-white pt-4 pl-3 pr-3 pb-4">
        <div class="col-12">

            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <p>
                            <strong>
                                <i class="far fa-question-circle"></i>
                                <?php echo e(__('importer_csv.csv-file-upload-listing-instruction')); ?>

                            </strong>
                        </p>
                        <ul>
                            <li>
                                <?php echo e(__('license_verify.license-instruction-guide')); ?>

                                <a href="https://alphastir.com/how-to-verify-purchase-code-domain/" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                    <?php echo e(__('license_verify.license-terms-guide-link')); ?>

                                </a>
                            </li>
                            <li>
                                <?php echo e(__('license_verify.license-instruction-terms')); ?>

                                <a href="https://alphastir.com/directoryhub/license/" target="_blank">
                                    <i class="fas fa-external-link-alt"></i>
                                    <?php echo e(__('license_verify.license-terms-link')); ?>

                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <?php if($verify_response_status): ?>
                        <?php if($verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_ERROR): ?>
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo e($verify_response_body->status_message); ?>

                            </div>
                        <?php else: ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle"></i>
                                <?php echo e($verify_response_body->status_message); ?>

                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo e($verify_response_message); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($verify_response_status): ?>
                <?php if($verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_SUCCESS): ?>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-sm-12">



                        <div class="row mb-2">
                            <div class="col-12 text-gray-800">
                                <?php echo e(__('license_verify.license-information')); ?>

                            </div>
                        </div>

                        <div class="row p-2 bg-light">
                            <div class="col-6">
                                <?php echo e(__('license_verify.license-type')); ?>

                            </div>
                            <div class="col-6 text-right">
                                <?php echo e($verify_response_body->license->license_type); ?>

                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col-6">
                                <?php echo e(__('license_verify.license-supported-until')); ?>

                            </div>
                            <div class="col-6 text-right">
                                <?php echo e($verify_response_body->license->supported_until); ?>

                            </div>
                        </div>

                        <div class="row p-2 bg-light">
                            <div class="col-6">
                                <?php echo e(__('license_verify.license-last-check')); ?>

                            </div>
                            <div class="col-6 text-right">
                                <?php echo e(date('Y-m-d', $verify_response_body->license->last_check_timestamp)); ?>

                            </div>
                        </div>

                        <div class="row p-2">
                            <div class="col-6">
                                <?php echo e(__('license_verify.license-last-check-status')); ?>

                            </div>
                            <div class="col-6 text-right">
                                <?php if($verify_response_body->license->license_last_check_status == \App\SettingLicense::LICENSE_API_LICENSE_VALID): ?>
                                    <span class="pl-2 pr-2 bg-success text-white rounded">
                                    <?php echo e(__('license_verify.license-valid')); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="pl-2 pr-2 bg-danger text-white rounded">
                                    <?php echo e(__('license_verify.license-invalid')); ?>

                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row p-2 bg-light">
                            <div class="col-6">
                                <?php echo e(__('license_verify.license-domains')); ?>

                                (
                                <?php echo e(__('license_verify.up-to') . ' ' . $verify_response_body->license->domain_quota . ' ' . __('license_verify.up-to-domains')); ?>

                                )
                            </div>
                            <div class="col-6 text-right">
                                <?php
                                    $license_domains = $verify_response_body->license->domains;
                                ?>

                                <?php $__currentLoopData = $license_domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license_domains_key => $license_domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <?php echo e($license_domain->domain_host); ?>

                                            |
                                            <?php if($license_domain->domain_status == \App\SettingLicense::LICENSE_API_DOMAIN_VERIFIED): ?>
                                                <span class="pl-2 pr-2 bg-success text-white rounded">
                                                    <?php echo e(__('license_verify.domain-verified')); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="pl-2 pr-2 bg-warning text-white rounded">
                                                    <?php echo e(__('license_verify.domain-unverified')); ?>

                                                </span>
                                            <?php endif; ?>
                                            |
                                            <a class="text-danger" href="#" data-toggle="modal" data-target="#revokeDomainModal_<?php echo e($license_domains_key); ?>">
                                                <?php echo e(__('license_verify.license-revoke-domain')); ?>

                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row border-left-primary mb-4">
                <div class="col-12">

                    <div class="row mb-4 bg-primary pl-1 pt-1 pb-1">
                        <div class="col-md-12">
                            <span class="text-lg text-white">
                                <i class="fas fa-bars"></i>
                                <?php echo e(__('license_verify.verify-step-1')); ?>

                            </span>
                            <small class="form-text text-white">
                                <?php echo e(__('license_verify.verify-step-1-desc')); ?>

                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <form method="POST" action="<?php echo e(route('admin.settings.license.update')); ?>" class="">
                                <?php echo csrf_field(); ?>

                                <div class="row form-group">
                                    <div class="col-md-6">
                                        <label for="settings_license_purchase_code" class="text-black"><?php echo e(__('installer_messages.environment.wizard.form.app_purchase_code_label')); ?></label>
                                        <input id="settings_license_purchase_code" type="text" class="form-control <?php $__errorArgs = ['settings_license_purchase_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="settings_license_purchase_code" value="<?php echo e(is_demo_mode() ? '******-****-****-****-**********' : $settings->settingLicense->settings_license_purchase_code); ?>">
                                        <small class="form-text text-muted">
                                            <?php echo e(__('installer_messages.environment.wizard.form.app_purchase_code_placeholder')); ?>

                                        </small>
                                        <?php $__errorArgs = ['settings_license_purchase_code'];
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

                                    <div class="col-md-6">
                                        <label for="settings_license_codecanyon_username" class="text-black"><?php echo e(__('installer_messages.environment.wizard.form.to_verify_codecanyon_username_label')); ?></label>
                                        <input id="settings_license_codecanyon_username" type="text" class="form-control <?php $__errorArgs = ['settings_license_codecanyon_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="settings_license_codecanyon_username" value="<?php echo e(is_demo_mode() ? '********' : $settings->settingLicense->settings_license_codecanyon_username); ?>">
                                        <small class="form-text text-muted">
                                            <?php echo e(__('installer_messages.environment.wizard.form.to_verify_codecanyon_username_placeholder')); ?>

                                        </small>
                                        <?php $__errorArgs = ['settings_license_codecanyon_username'];
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
                                        <button type="submit" class="btn btn-sm btn-success text-white">
                                            <i class="fas fa-shield-alt"></i>
                                            <?php echo e(__('license_verify.register-purchase-code')); ?>

                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row border-left-primary mb-4">
                <div class="col-12">
                    <div class="row mb-4 bg-primary pl-1 pt-1 pb-1">
                        <div class="col-md-12">
                            <span class="text-lg text-white">
                                <i class="fas fa-bars"></i>
                                <?php echo e(__('license_verify.verify-step-2')); ?>

                            </span>
                            <small class="form-text text-white">
                                <?php echo e(__('license_verify.verify-step-2-desc')); ?>

                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a class="btn btn-sm btn-success text-white" target="_blank" href="<?php echo e(is_demo_mode() ? '' : get_domain_verify_token_url($settings->settingLicense->settings_license_purchase_code, $settings->settingLicense->settings_license_codecanyon_username)); ?>">
                                <i class="fas fa-external-link-alt"></i>
                                <?php echo e(__('license_verify.get-domain-verify-token')); ?>

                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row border-left-primary mb-4">
                <div class="col-12">
                    <div class="row mb-4 bg-primary pl-1 pt-1 pb-1">
                        <div class="col-md-12">
                            <span class="text-lg text-white">
                                <i class="fas fa-bars"></i>
                                <?php echo e(__('license_verify.verify-step-3')); ?>

                            </span>
                            <small class="form-text text-white">
                                <?php echo e(__('license_verify.verify-step-3-desc')); ?>

                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">

                            <form method="POST" action="<?php echo e(route('admin.settings.license.domain-verify-token.update')); ?>" class="">
                                <?php echo csrf_field(); ?>

                                <div class="row form-group">
                                    <div class="col-12">
                                        <label for="settings_license_domain_verify_token" class="text-black"><?php echo e(__('license_verify.domain-verify-token')); ?></label>
                                        <input id="settings_license_domain_verify_token" type="text" class="form-control <?php $__errorArgs = ['settings_license_domain_verify_token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="settings_license_domain_verify_token" value="<?php echo e(is_demo_mode() ? '*************' : $settings->settingLicense->settings_license_domain_verify_token); ?>">
                                        <?php $__errorArgs = ['settings_license_domain_verify_token'];
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
                                        <button type="submit" class="btn btn-sm btn-success text-white">
                                            <i class="far fa-save"></i>
                                            <?php echo e(__('license_verify.domain-verify-token-button')); ?>

                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>

            <div class="row border-left-primary mb-4">
                <div class="col-12">
                    <div class="row mb-4 bg-primary pl-1 pt-1 pb-1">
                        <div class="col-md-12">
                            <span class="text-lg text-white">
                                <i class="fas fa-bars"></i>
                                <?php echo e(__('license_verify.verify-step-4')); ?>

                            </span>
                            <small class="form-text text-white">
                                <?php echo e(__('license_verify.verify-step-4-desc')); ?>

                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a class="btn btn-sm btn-success text-white" target="_blank" href="<?php echo e(is_demo_mode() ? '' : get_domain_verify_do_url($settings->settingLicense->settings_license_purchase_code, $settings->settingLicense->settings_license_codecanyon_username)); ?>">
                                <i class="fas fa-external-link-alt"></i>
                                <?php echo e(__('license_verify.domain-verify-button')); ?>

                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<?php if($verify_response_status && $verify_response_body->status_code == \App\SettingLicense::LICENSE_API_STATUS_CODE_SUCCESS): ?>
    <?php
        $license_domains = $verify_response_body->license->domains;
    ?>

    <?php $__currentLoopData = $license_domains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license_domains_key => $license_domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="modal fade" id="revokeDomainModal_<?php echo e($license_domains_key); ?>" tabindex="-1" role="dialog" aria-labelledby="revokeDomainModal_<?php echo e($license_domains_key); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle"><?php echo e(__('license_verify.license-revoke-domain-modal-title')); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo e(__('license_verify.license-revoke-domain-modal-desc-1')); ?></p>
                        <p><?php echo e(__('license_verify.license-revoke-domain-modal-desc-2')); ?></p>
                        <p><?php echo e(__('license_verify.license-revoke-domain-modal-desc-3')); ?></p>

                        <p>
                            <span class="pl-2 pr-2 bg-primary text-white rounded">
                            <?php echo e($license_domain->domain_host); ?>

                            </span>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('backend.shared.cancel')); ?></button>
                        <form action="<?php echo e(route('admin.settings.license.revoke', ['domain_host' => $license_domain->domain_host])); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-danger"><?php echo e(__('license_verify.license-revoke-domain-modal-button-confirm')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/Laravel/Techcrave/listdirectory/http/laravel_project/resources/views/backend/admin/setting/license/edit.blade.php ENDPATH**/ ?>