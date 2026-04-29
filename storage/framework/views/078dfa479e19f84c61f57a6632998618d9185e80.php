

<?php $__env->startSection('title'); ?> Pricing <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

     <?php $__env->startComponent('common-components.breadcrumb'); ?>
         <?php $__env->slot('title'); ?> Pricing  <?php $__env->endSlot(); ?>
         <?php $__env->slot('li_1'); ?> Pages  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="text-center mb-5">
            <h4>Choose your Pricing plan</h4>
            <p class="text-muted">To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words If several languages coalesce</p>
        </div>
    </div>
</div>

<div class="row">

     <?php $__env->startComponent('common-components.price-section'); ?>
         <?php $__env->slot('title'); ?> Starter  <?php $__env->endSlot(); ?>
         <?php $__env->slot('desc'); ?> Neque quis est  <?php $__env->endSlot(); ?>
         <?php $__env->slot('icon'); ?> bx bx-walk h1 text-primary  <?php $__env->endSlot(); ?>
         <?php $__env->slot('price'); ?> 19  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>

     <?php $__env->startComponent('common-components.price-section'); ?>
         <?php $__env->slot('title'); ?> Professional  <?php $__env->endSlot(); ?>
         <?php $__env->slot('desc'); ?> Quis autem iure  <?php $__env->endSlot(); ?>
         <?php $__env->slot('icon'); ?> bx bx-run h1 text-primary  <?php $__env->endSlot(); ?>
         <?php $__env->slot('price'); ?> 29  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>

     <?php $__env->startComponent('common-components.price-section'); ?>
         <?php $__env->slot('title'); ?> Enterprise <?php $__env->endSlot(); ?>
         <?php $__env->slot('desc'); ?> Sed ut neque unde  <?php $__env->endSlot(); ?>
         <?php $__env->slot('icon'); ?> bx bx-cycling h1 text-primary  <?php $__env->endSlot(); ?>
         <?php $__env->slot('price'); ?> 39  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>

     <?php $__env->startComponent('common-components.price-section'); ?>
         <?php $__env->slot('title'); ?> Unlimited <?php $__env->endSlot(); ?>
         <?php $__env->slot('desc'); ?> Itaque earum hic  <?php $__env->endSlot(); ?>
         <?php $__env->slot('icon'); ?> bx bx-car h1 text-primary  <?php $__env->endSlot(); ?>
         <?php $__env->slot('price'); ?> 49  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
    
</div>
<!-- end row -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/layouts/default/pages-pricing.blade.php ENDPATH**/ ?>