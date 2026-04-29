<?php $__env->startSection('title'); ?>
    Form Register
<?php $__env->stopSection(); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/register.css')); ?>">
<?php $__env->startSection('content'); ?>
    <div class="card card-primary">
        <div class="card-header"><h4>Register Akun SIMONAS</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/form/store" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">Nama Lengkap:</label><span
                                    class="text-danger">
                            <input id="firstName" type="text"
                                   class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>"
                                   name="name"
                                   tabindex="1" placeholder="Enter Full Name" value="<?php echo e(old('name')); ?>"
                                   autofocus required>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('name')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email:</label><span
                                    class="text-danger">
                            <input id="email" type="email"
                                   class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>"
                                   placeholder="Enter Email address" name="email" tabindex="1"
                                   value="<?php echo e(old('email')); ?>"
                                   required autofocus>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('email')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="no_wa">Nomor Whatsapp:</label><span
                                    class="text-danger">
                            <input id="no_wa" type="text"
                                   class="form-control<?php echo e($errors->has('no_wa') ? ' is-invalid' : ''); ?>"
                                   placeholder="Enter Number Whatsapp" name="no_wa" tabindex="1"
                                   value="<?php echo e(old('no_wa')); ?>"
                                   required autofocus>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('no_wa')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="asrama"
                                   class="control-label">Asal Asrama:</label><span
                                    class="text-danger">*</span>
                            <select name="asrama" class="form-control <?php echo e($errors->has('asrama') ? ' is-invalid': ''); ?>">
                                <option value="asgj">Asrama Sunan Gunung Jati</option>
                                <option value="asg">Asrama Sunan Giri</option>
                                <option value="aws">Asrama Wali Songo</option>
                                <option value="dqf">Asrama Daarul Quran Fatahilah</option>
                            </select>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('asrama')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="form-group">
                            <label for="status"
                                   class="control-label">Status Keasramaan:</label><span
                                    class="text-danger">*</span>
                            <select name="status" class="form-control <?php echo e($errors->has('status') ? ' is-invalid': ''); ?>">
                                <option value="direktur">Direktur Asrama / Pengurus YAPI</option>
                                <option value="mentor">Mentor Warga Asrama</option>
                                <option value="pengurus">Pengurus Asrama</option>
                                <option value="warga">Warga Asrama</option>
                                <option value="alumni">Alumni Asrama</option>
                            </select>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('status')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                Submit
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth_app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/akun/form.blade.php ENDPATH**/ ?>