

<?php $__env->startSection('title'); ?> Login <?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>

<body>
    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('content'); ?>

    <?php if(session()->has('status')): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> Berhasil Reset Password, Silahkan Login!.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    
    <div class="account-pages my-5 pt-sm-5" style="background-size: cover; background-image: url(<?php echo e(asset('images/bg-effect.png')); ?>)">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden">
                        <div class="bg-login text-center">
                            <div class="bg-login-overlay"></div>
                            <div class="position-relative">
                                <h5 class="text-white font-size-20">Welcome Back !</h5>
                                <p class="text-white-50 mb-0">Sign in to continue to SIMONAS App.</p>
                                <a href="index" class="logo logo-admin mt-4">
                                    <img src="images/simonas_logo.png" alt="" height="75">
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-5">
                            <div class="p-2">
                                <form method="POST" action="<?php echo e(route('login')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="username"><?php echo e(__('E-Mail Address')); ?></label>
                                        <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" <?php if(old('email')): ?> value="<?php echo e(old('email')); ?>" <?php endif; ?> required autocomplete="email" autofocus>
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

                                    <div class="form-group">
                                        <label for="userpassword"><?php echo e(__('Password')); ?></label>
                                        <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password">
                                        <?php $__errorArgs = ['password'];
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

                                    <div class="form-group">
                                        <div class="captcha">
                                            <span><?php echo captcha_img('math'); ?></span>
                                            <button type="button" class="btn btn-danger reload" id="reload" >
                                                <i class="mdi mdi-reload"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="text" name="captcha" class="form-control <?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="captcha" placeholder="Enter Captcha">
                                        <?php $__errorArgs = ['captcha'];
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

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="remember" id="customControlInline" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                        <label class="custom-control-label" for="customControlInline"><?php echo e(__('Remember Me')); ?></label>
                                    </div>

                                    <div class="mt-3">
                                        <button class="btn btn-primary btn-block waves-effect waves-light" id="login" type="submit"><?php echo e(__('Login')); ?></button>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <a href="<?php echo e(route('lupa.password')); ?>" class="text-muted"><i class="mdi mdi-lock mr-1"></i> <?php echo e(__('Forgot Your Password?')); ?></a>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>Belum punya account ? <a href="register" class="font-weight-medium text-primary"> Signup now </a> </p>
                        <p>© <script>
                                document.write(new Date().getFullYear())
                            </script> SIMONAS App. Crafted with <i class="mdi mdi-heart text-danger"></i> by TIM IT YAPI</p>
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

        <script>
            $('#reload').click(function(){
                $.ajax({
                    type:'Get',
                    url:'reload-captcha-login',
                    success:function(data){
                        $(".captcha span").html(data.captcha)
                    }
                });
            });
        </script>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/auth/login.blade.php ENDPATH**/ ?>