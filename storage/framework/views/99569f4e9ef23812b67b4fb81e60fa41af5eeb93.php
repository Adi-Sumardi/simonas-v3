

<?php $__env->startSection('title'); ?> Create Task <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<!-- datepicker css -->
<link href="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')); ?>" rel="stylesheet">
<!-- Summernote css -->
<link href="<?php echo e(URL::asset('/libs/summernote/summernote.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('common-components.breadcrumb'); ?>
         <?php $__env->slot('title'); ?> Create Task <?php $__env->endSlot(); ?>
         <?php $__env->slot('li_1'); ?> Pages  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create New Task</h4>
                <form class="outer-repeater">
                    <div data-repeater-list="outer-group" class="outer">
                        <div data-repeater-item class="outer">
                            <div class="form-group row mb-4">
                                <label for="taskname" class="col-form-label col-lg-2">Task Name</label>
                                <div class="col-lg-10">
                                    <input id="taskname" name="taskname" type="text" class="form-control" placeholder="Enter Task Name...">
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label col-lg-2">Task Description</label>
                                <div class="col-lg-10">
                                    <div class="summernote">Hello Summernote</div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label col-lg-2">Task Date</label>
                                <div class="col-lg-10">
                                    <div class="input-daterange input-group" data-provide="datepicker">
                                        <input type="text" class="form-control" placeholder="Start Date" name="start" />
                                        <input type="text" class="form-control" placeholder="End Date" name="end" />
                                    </div>
                                </div>
                            </div>

                            <div class="inner-repeater mb-4">
                                <div data-repeater-list="inner-group" class="inner form-group mb-0 row">
                                    <label class="col-form-label col-lg-2">Add Team Member</label>
                                    <div data-repeater-item class="inner col-lg-10 ml-md-auto">
                                        <div class="mb-3 row align-items-center">
                                            <div class="col-md-6">
                                                <input type="text" class="inner form-control" placeholder="Enter Name..." />
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mt-4 mt-md-0">
                                                    <input type="file" class="form-control-file">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mt-2 mt-md-0">
                                                    <input data-repeater-delete type="button" class="btn btn-primary btn-block inner" value="Delete" />
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-lg-10">
                                        <input data-repeater-create type="button" class="btn btn-success inner" value="Add Number" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label for="taskbudget" class="col-form-label col-lg-2">Budget</label>
                                <div class="col-lg-10">
                                    <input id="taskbudget" name="taskbudget" type="text" placeholder="Enter Task Budget..." class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row justify-content-end">
                    <div class="col-lg-10">
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- end row -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<!-- bootstrap datepicker -->
<script src="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>

<!-- Summernote js -->
<script src="<?php echo e(URL::asset('/libs/summernote/summernote.min.js')); ?>"></script>

<!-- form repeater js -->
<script src="<?php echo e(URL::asset('/libs/jquery-repeater/jquery-repeater.min.js')); ?>"></script>

<script src="<?php echo e(URL::asset('/js/pages/task-create.init.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/layouts/default/tasks-create.blade.php ENDPATH**/ ?>