<?php $__env->startSection('title'); ?> Calendar <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<!-- Plugin css -->
<link href="<?php echo e(URL::asset('/libs/fullcalendar/fullcalendar.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->

    <?php $__env->startComponent('common-components.breadcrumb'); ?>
         <?php $__env->slot('title'); ?> Calendar  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>

                <div style='clear:both'></div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- plugin js -->
<script src="<?php echo e(URL::asset('/libs/moment/moment.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/fullcalendar/fullcalendar.min.js')); ?>"></script>

<!-- Calendar init -->
<script src="<?php echo e(URL::asset('/js/pages/calendar.init.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/layouts/default/calendar.blade.php ENDPATH**/ ?>