<?php $__env->startSection('title'); ?>
Kalender
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>

    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('super.common-components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
        Kalender
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title_li'); ?>
        Kalender
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>


    <div class="container mt-5">
        


        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="form-action-update" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="form-action" method="PUT">
                <?php echo csrf_field(); ?>

                <div class="modal-content">
                    <div class="modal-header">
                        <input type="hidden" name="event_id" id="event_id" value="">
                        <h5 class="modal-title">Kegiatan Asrama</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <h5>Nama Kegiatan</h5>
                                    <input type="text" name="NAMA_KEGIATAN" readonly class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h5>Tujuan</h5>
                                    <input type="text" name="TUJUAN" readonly  class="form-control datepicker" >
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h5>Penyelenggara</h5>
                                    <input type="text" name="PENYELENGGARA" readonly class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h5>Jenis Kegiatan</h5>
                                    <input type="text" name="JENIS_KEGIATAN" readonly class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h5>Tanggal</h5>
                                    <input type="text" name="WAKTU" readonly class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h5>keterangan</h5>
                                    <input type="text" name="KETERANGAN" readonly  class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <img type="text" name="fotonya" src="" class="form-control datepicker" style="width: 100%; height: 100%; background-size: cover; background-position: center; background-repeat: no-repeat;">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        
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
            var events = <?php echo json_encode($events, 15, 512) ?>;
    console.log(events);
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: events,
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap5',
                editable: true,
                eventClick: function (info) {
                    // Show the modal
                    $('#form-action-update').modal('show');
                    // Set form values based on event data
                    var startDate = new Date(info.event.start);
                    var endDate = new Date(info.event.end);
                    var dateOnly = startDate.toISOString().split('T')[0];
                    var endnya = endDate.toISOString().split('T')[0];

                    // $('input[name="strat_timeupdate"]').val(info.event.extendedProps.event_start_time);
                    $('input[name="NAMA_KEGIATAN"]').val(info.event.title);
                    $('input[name="TUJUAN"]').val(info.event.extendedProps.tujuan);
                    $('input[name="PENYELENGGARA"]').val(info.event.extendedProps.penyelenggara);
                    $('input[name="JENIS_KEGIATAN"]').val(info.event.extendedProps.jenis_kegiatan);
                    $('input[name="WAKTU"]').val(dateOnly);
                    $('input[name="KETERANGAN"]').val(info.event.extendedProps.keterangan);
                    $('img[name="fotonya"]').attr('src', 'data_file_kegiatan/' + info.event.extendedProps.file);
                    // $('input[name="categoryupdate"]').prop('checked', false);
                    // console.log("INPO",info.event.extendedProps)
                    // var categoryValue = info.event.extendedProps.event_description.toLowerCase();
                    // $('input[name="categoryupdate"][value="' + categoryValue + '"]').prop('checked', true);

                }

            });
            calendar.render();
        });

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make($layouts, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/master/calender.blade.php ENDPATH**/ ?>