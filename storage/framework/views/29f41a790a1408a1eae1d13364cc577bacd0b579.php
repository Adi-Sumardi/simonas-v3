

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Ganti Foto Profile</h3>
    </div>
    <div class="card card-primary">

        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <img src="/uploads/avatars/<?php echo e(Auth::user()->avatar); ?>" style="width:150px; height:150px; float:left; border-radius:50%; margin-right:25px">
                            <h2><?php echo e(Auth::user()->name); ?>'s Profile</h2>
                            <form action="/mentor/profile/foto/<?php echo e(Auth::user()->id); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <label>Update Profile Image</label><br>
                                <input type="file" name="avatar">
                                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                                <input type="submit" class="right btn btn-sm btn-primary">
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mentor.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/mentor/pages/profile/edit_foto.blade.php ENDPATH**/ ?>