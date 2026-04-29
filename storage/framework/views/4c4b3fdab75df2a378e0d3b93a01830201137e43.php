

<?php $__env->startSection('title'); ?> Success <?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>

<body>
    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('content'); ?>

    <div class="home-btn d-none d-sm-block">
        
    </div>
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-login text-center">
                            <div class="bg-login-overlay"></div>
                            <div class="position-relative">
                                <h5 class="text-white font-size-20">Password Sudah Sukes Dirubah Silahkan Login! !</h5>
                                <p class="text-white-50 mb-0"><?php echo e(__('Reset Password')); ?></p>
                                <a href="index" class="logo logo-admin mt-4">
                                    <img src="<?php echo e(asset('../images/simonas_logo.png')); ?>" alt="" height="75">
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <div class="p-2">
                                <p>Already have an account ? <a href="/login" class="font-weight-medium text-primary"> Login</a> </p>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>© <script> document.write(new Date().getFullYear()) </script> Qovex. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="<?php echo e(URL::asset('libs/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('libs/bootstrap/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('libs/metismenu/metismenu.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('libs/simplebar/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('libs/node-waves/node-waves.min.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('js/app.min.js')); ?>"></script>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/auth/success.blade.php ENDPATH**/ ?>