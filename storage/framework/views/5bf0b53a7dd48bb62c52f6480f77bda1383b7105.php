
<?php $__env->startSection('content'); ?>
<!-- BEGIN Tiles -->
    <?php if(Auth::user()->hasRole('super_admin')): ?>
        <div class="row">
            <div class="col-md-7">
                <div class="row">
                    <div class="col-md-6">
                        <a class="tile tile-pink" data-stop="500" href="<?php echo e(url('users')); ?>">
                            <div class="img img-center">
                                <i class="fa fa-user"></i>
                                <p class="title text-center">+<?php echo e($users); ?></p>
                                <p class="title text-center"><?php echo app('translator')->get('messages.users.users'); ?></p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ivas_template_laravel8.0\resources\views/dashboard/index.blade.php ENDPATH**/ ?>