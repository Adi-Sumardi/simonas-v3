

<?php $__env->startSection('title'); ?> Colors <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

     <?php $__env->startComponent('common-components.breadcrumb'); ?>
         <?php $__env->slot('title'); ?> Colors  <?php $__env->endSlot(); ?>
         <?php $__env->slot('li_1'); ?> UI Elements  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>

                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="color-box bg-primary p-4 rounded">
                                        <h5 class="my-2 text-white">#3b5de7</h5>
                                    </div>
                                    <h5 class="mb-0 mt-3 text-primary">Primary</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="color-box bg-success p-4 rounded">
                                        <h5 class="my-2 text-white">#45cb85</h5>
                                    </div>
                                    <h5 class="mb-0 mt-3 text-success">Success</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="color-box bg-info p-4 rounded">
                                        <h5 class="my-2 text-white">#0caadc</h5>
                                    </div>
                                    <h5 class="mb-0 mt-3 text-info">Info</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="color-box bg-warning p-4 rounded">
                                        <h5 class="my-2 text-white">#eeb902</h5>
                                    </div>
                                    <h5 class="mb-0 mt-3 text-warning">Warning</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="color-box bg-danger p-4 rounded">
                                        <h5 class="my-2 text-white">#ff715b</h5>
                                    </div>
                                    <h5 class="mb-0 mt-3 text-danger">Danger</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="color-box bg-dark p-4 rounded">
                                        <h5 class="my-2 text-light">#343a40</h5>
                                    </div>
                                    <h5 class="mb-0 mt-3 text-dark">Dark</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="color-box bg-secondary p-4 rounded">
                                        <h5 class="my-2 text-light">#9095ad</h5>
                                    </div>
                                    <h5 class="mb-0 mt-3 text-muted">Secondary</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/layouts/default/ui-colors.blade.php ENDPATH**/ ?>