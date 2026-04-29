

<?php $__env->startSection('title'); ?> Form File Upload <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<!-- Plugins css -->
<link href="<?php echo e(URL::asset('/libs/dropzone/dropzone.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

   <?php $__env->startComponent('common-components.breadcrumb'); ?>
         <?php $__env->slot('title'); ?> Form File Upload  <?php $__env->endSlot(); ?>
         <?php $__env->slot('li_1'); ?> Forms  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Dropzone</h4>
                <p class="card-title-desc">DropzoneJS is an open source library that provides drag’n’drop file uploads with image previews.
                </p>

                <div>
                    <form action="#" class="dropzone">
                        <div class="fallback">
                            <input name="file" type="file" multiple="multiple">
                        </div>
                        <div class="dz-message needsclick">
                            <div class="mb-3">
                                <i class="display-4 text-muted mdi mdi-upload-network-outline"></i>
                            </div>

                            <h4>Drop files here or click to upload.</h4>
                        </div>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <button type="button" class="btn btn-primary waves-effect waves-light">Send Files</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<!-- Plugins js -->
<script src="<?php echo e(URL::asset('/libs/dropzone/dropzone.min.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/layouts/default/form-uploads.blade.php ENDPATH**/ ?>