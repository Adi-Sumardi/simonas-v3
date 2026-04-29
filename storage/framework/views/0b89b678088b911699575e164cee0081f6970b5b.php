<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Management Akses User</h3>
    </div>
    <div class="card card-primary">
        <div class="card-header"><h4>Tambah Data Akses User</h4></div>

        <div class="card-body pt-1">
            <form method="POST" action="/user-store" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="first_name">Full Name:</label><span
                                    class="text-danger">*</span>
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
                                    class="text-danger">*</span>
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
                            <label for="password" class="control-label">Password
                                :</label><span
                                    class="text-danger">*</span>
                            <input id="password" type="password"
                                   class="form-control<?php echo e($errors->has('password') ? ' is-invalid': ''); ?>"
                                   placeholder="Set account password" name="password" tabindex="2" required>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('password')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation"
                                   class="control-label">Confirm Password:</label><span
                                    class="text-danger">*</span>
                            <input id="password_confirmation" type="password" placeholder="Confirm account password"
                                   class="form-control<?php echo e($errors->has('password_confirmation') ? ' is-invalid': ''); ?>"
                                   name="password_confirmation" tabindex="2">
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('password_confirmation')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role"
                                   class="control-label">Role:</label><span
                                    class="text-danger">*</span>
                            <select name="role" class="form-control <?php echo e($errors->has('role') ? ' is-invalid': ''); ?>">
                                <option value="super">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="mentor">Mentor</option>
                                <option value="mahasiswa">Mahasiswa</option>
                            </select>
                            <div class="invalid-feedback">
                                <?php echo e($errors->first('role')); ?>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/akun/create.blade.php ENDPATH**/ ?>