<?php $__env->startSection('title'); ?> Register <?php $__env->stopSection(); ?>

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
                                <h5 class="text-white font-size-20">Lupa Password ya?</h5>
                                <p class="text-white-50 mb-0"><?php echo e(__('Reset Password')); ?></p>
                                <a href="index" class="logo logo-admin mt-4">
                                    <img src="<?php echo e(asset('../images/simonas_logo.png')); ?>" alt="" height="75">
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <?php if(session()->has('message')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Sukses!</strong> Silahkan cek email Anda untuk tautan reset kata sandi.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                            <div class="p-2">
                            <form method="POST" action="<?php echo e(route('email.password')); ?>">
                                <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="useremail"><?php echo e(__('E-Mail Address')); ?></label>
                                        <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus placeholder="Enter email">
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" id="register" type="submit"> <?php echo e(__('Send Password Reset Link')); ?></button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="mb-0">By registering you agree to the Skote <a href="#" class="text-primary">Terms of Use</a></p>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>Already have an account ? <a href="/login" class="font-weight-medium text-primary"> Login</a> </p>
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
<?php echo $__env->make('layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/auth/passwords/email.blade.php ENDPATH**/ ?>