<?php $__env->startSection('title'); ?> Starter Page <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('common-components.breadcrumb'); ?>
         <?php $__env->slot('title'); ?> Starter Page  <?php $__env->endSlot(); ?>
         <?php $__env->slot('li_1'); ?> Pages  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/layouts/default/pages-starter.blade.php ENDPATH**/ ?>