

<?php $__env->startSection('title'); ?> Calender <?php $__env->stopSection(); ?>

<?php $__env->startSection('head'); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Schedule Tracker</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>

    <link href="<?php echo e(URL::asset('libs/select2/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('libs/bootstrap-datepicker/bootstrap-datepicker.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('libs/bootstrap-colorpicker/bootstrap-colorpicker.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('libs/bootstrap-touchspin/bootstrap-touchspin.min.css')); ?>" rel="stylesheet" />

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <?php $__env->startComponent('common-components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> Kegiatan <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="container mt-5">
        
        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search events">
                    <div class="input-group-append">
                        <button id="searchButton" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="btn-group mb-3" role="group" aria-label="Calendar Actions">
                    <button id="exportButton" class="btn btn-success">Export Calendar</button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="form-action" action="<?php echo e($action); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php if(isset($data) && $data->id): ?>
                    <?php echo method_field('PUT'); ?>
                <?php endif; ?>
    
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <input type="text" name="start_date" readonly value="<?php echo e(isset($data) ? $data->start_date : request()->input('start_date')); ?>" class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <input type="text" name="end_date" readonly value="<?php echo e(isset($data) ? $data->end_date : request()->input('end_date')); ?>" class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <textarea name="title" class="form-control"><?php echo e(isset($data) ? $data->title : ''); ?></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="category-success" value="success" <?php echo e((isset($data) && $data->category == 'success') ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="category-success">Success</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="category-danger" value="danger" <?php echo e((isset($data) && $data->category == 'danger') ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="category-danger">Danger</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="category-warning" value="warning" <?php echo e((isset($data) && $data->category == 'warning') ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="category-warning">Warning</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="category" id="category-info" value="info" <?php echo e((isset($data) && $data->category == 'info') ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="category-info">Info</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="delete" role="switch" id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Delete</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    

    

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js'></script>
    
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                events: `<?php echo e(route('events.list')); ?>`,
                dateClick: function(info) {
                    $('#eventModal').modal('show');
                    $('#eventTitle').val(''); // Clear the title input
                }
            });
            calendar.render();
        });

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('alumni.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/alumni/pages/calender/index.blade.php ENDPATH**/ ?>