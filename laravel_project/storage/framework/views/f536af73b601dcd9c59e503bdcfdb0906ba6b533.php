<?php $__env->startSection('styles'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php if($warning_smtp): ?>
        <div class="alert alert-warning" role="alert">
            <p>
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo e(__('theme_directory_hub.setting.warning-smtp')); ?>

            </p>
            <p>
                <a href="<?php echo e(route('admin.settings.general.edit')); ?>" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-cog"></i>
                    <?php echo e(__('theme_directory_hub.setting.warning-smtp-action')); ?>

                </a>
            </p>
        </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo e(__('backend.homepage.dashboard')); ?></h1>
        <a href="<?php echo e(route('admin.items.create')); ?>" class="btn btn-info btn-icon-split">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
            <span class="text"><?php echo e(__('backend.homepage.post-a-listing')); ?></span>
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"><?php echo e(__('backend.homepage.categories')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($category_count)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-th-large fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1"><?php echo e(__('backend.homepage.listings')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($item_count)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1"><?php echo e(__('backend.homepage.posts')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($post_count)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rss fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"><?php echo e(__('backend.homepage.users')); ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($user_count)); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-cog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('backend.homepage.message')); ?></h6>
                </div>
                <div class="card-body">

                    <?php $__currentLoopData = $recent_threads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recent_threads_key => $thread): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="row pt-2 pb-2 <?php echo e($recent_threads_key%2 == 0 ? 'bg-light' : ''); ?>">
                            <div class="col-9">
                                <span><?php echo e($thread->latestMessage->body); ?></span>
                            </div>
                            <div class="col-3 text-right">
                                <span><?php echo e($thread->latestMessage->created_at->diffForHumans()); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="row text-center mt-3">
                        <div class="col-12">
                            <a href="<?php echo e(route('admin.messages.index')); ?>"><?php echo e(__('backend.homepage.view-all-message')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><?php echo e(__('backend.homepage.comment')); ?></h6>
                </div>
                <div class="card-body">

                    <?php $__currentLoopData = $recent_comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recent_comments_key => $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="row pt-2 pb-2 <?php echo e($recent_comments_key%2 == 0 ? 'bg-light' : ''); ?>">
                            <div class="col-9">
                                <span><?php echo e($comment->comment); ?></span>
                            </div>
                            <div class="col-3 text-right">
                                <span><?php echo e($comment->created_at->diffForHumans()); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="row text-center mt-3">
                        <div class="col-12">
                            <a href="<?php echo e(route('admin.comments.index')); ?>"><?php echo e(__('backend.homepage.view-all-comment')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/Laravel/Techcrave/listdirectory/http/laravel_project/resources/views/backend/admin/index.blade.php ENDPATH**/ ?>