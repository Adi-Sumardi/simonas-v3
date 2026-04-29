<?php $__env->startSection('title'); ?>
List Warga Asrama
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<!-- DataTables -->
<link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('super.common-components.breadcrumb'); ?>
<?php $__env->slot('title'); ?>
List Warga Asrama
<?php $__env->endSlot(); ?>
<?php $__env->slot('title_li'); ?>
List Warga Asrama
<?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>

<div class="row">
    <div class="col-12">
        <div class="card bg-gradient" style="background: linear-gradient(90deg, #c0fff3, #0095ff);">
            <div class="card-body">
                <div class="button-items d-flex justify-content-center">
                    <button id="super-warga-asgj" name="super-warga-asgj"
                        class="btn btn-primary waves-effect waves-light btn-category"
                        data-category="Asrama Sunan Gunung Jati">ASGJ</button>
                    <button id="super-warga-asg" name="super-warga-asg"
                        class="btn btn-primary waves-effect waves-light btn-category"
                        data-category="Asrama Sunan Giri">ASG</button>
                    <button id="super-warga-aws" name="super-warga-aws"
                        class="btn btn-primary waves-effect waves-light btn-category"
                        data-category="Asrama Wali Songo">AWS</button>
                    <button id="super-warga-aspuri" name="super-warga-aspuri"
                        class="btn btn-primary waves-effect waves-light btn-category"
                        data-category="Asrama Putri">ASPURI</button>

                    <div class="d-flex align-items-center ms-2">
                        <select class="form-select btn btn-primary waves-effect waves-light" name="date_filter"
                            id="date_filter">
                            <option value="all">All Dates</option>
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="this_week">This Week</option>
                            <option value="last_week">Last Week</option>
                            <option value="this_month">This Month</option>
                            <option value="last_month">Last Month</option>
                            <option value="this_year">This Year</option>
                            <option value="last_year">Last Year</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<h4 class="card-title">List Warga</h4>
<div style="overflow: scroll;">
    <table id="datatable-buttons" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Asrama</th>
                <th>Status Warga</th>
                <th>Universitas</th>
                <th>Jurusan</th>
                <th>Semester</th>
                <th>Ipk</th>
                <th>Jumlah Kegiatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="user-row" data-category="<?php echo e($user->asrama); ?>">
                <td><?php echo e($user->name); ?><br></td>
                <td><?php echo e($user->asrama); ?><br></td>
                <td><?php echo e($user->status_warga); ?></td>
                <td><?php echo e($user->universitas); ?></td>
                <td><?php echo e($user->fakultas); ?></td>
                <td><?php if($user->ipks->isNotEmpty()): ?>
                    <?php echo e($user->ipks->last()->semester); ?>

                    <?php endif; ?></td>
                <td><?php if($user->ipks->isNotEmpty()): ?>
                    <?php echo e($user->ipks->last()->ip); ?>

                    <?php endif; ?></td>
                <td>
                    <a href="#" class="btn btn-action" style="font-weight: bold;" data-toggle="tooltip"
                        data-placement="top" title="Detail" onclick="showDetailModal('<?php echo e($user->id); ?>')">
                        <?php echo e($user->karakters->count() + $user->leaderships->count() + $user->kreatifs->count()); ?>

                    </a>
                </td>
                <td>
                    <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="btn btn-primary btn-action"
                        data-toggle="tooltip" data-placement="top" title="Detail">
                        <i class="far fa-eye"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </tbody>
    </table>
</div>
<!-- end row -->
<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel"
    aria-hidden="true">
    <div class="modal-dialog " role="document" style="max-width: 90%; margin: 1.75rem auto;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="akademik-tab" data-toggle="tab" href="#akademik"
                            role="tab">Akademik</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="leadership-tab" data-toggle="tab" href="#leadership"
                            role="tab">Leadership</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="leadership-tab" data-toggle="tab" href="#karakter"
                            role="tab">Karakter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="leadership-tab" data-toggle="tab" href="#kreatif"
                            role="tab">Kreativitas</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Akademik Tab -->
                    <div class="tab-pane active" id="akademik" role="tabpanel">
                        <table class="table table-striped" id="modalBodyAkademik_">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kegiatan</th>
                                    <th>Waktu</th>
                                    <th>Tempat</th>
                                    <th>Uraian Kegiatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data Akademik -->
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-end" id="paginationModalBodyAkademik_"></ul>
                        </nav>
                    </div>

                    <!-- Leadership Tab -->
                    <div class="tab-pane" id="leadership" role="tabpanel">
                        <table class="table table-striped" id="modalBodyLeadership_">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kegiatan</th>
                                    <th>Waktu</th>
                                    <th>Tempat</th>
                                    <th>Uraian Kegiatan</th>

                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data Leadership -->
                            </tbody>
                        </table>
                    </div>
                    <!-- Karakter Tab -->
                    <div class="tab-pane" id="karakter" role="tabpanel">
                        <table class="table table-striped" id="modalBodykarakter_">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kegiatan</th>
                                    <th>Waktu</th>
                                    <th>Tempat</th>
                                    <th>Uraian Kegiatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data karakter -->
                            </tbody>
                        </table>
                    </div>
                    <!-- kreatif Tab -->
                    <div class="tab-pane" id="kreatif" role="tabpanel">
                        <table class="table table-striped" id="modalBodykreatif_">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kegiatan</th>
                                    <th>Waktu</th>
                                    <th>Tempat</th>
                                    <th>Uraian Kegiatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data kreatif -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>

        </div>
    </div>
</div>





<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- plugin js -->
<script src="<?php echo e(URL::asset('libs/apexcharts/apexcharts.min.js')); ?>"></script>

<!-- jquery.vectormap map -->
<script src="<?php echo e(URL::asset('libs/jquery-vectormap/jquery-vectormap.min.js')); ?>"></script>

<!-- Calendar init -->
<script src="<?php echo e(URL::asset('js/pages/dashboard.init.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>

<!-- Sweet alert init js-->
<script src="<?php echo e(URL::asset('/js/pages/sweet-alerts.init.js')); ?>"></script>

<!-- Required datatable js -->
<script src="<?php echo e(URL::asset('/libs/datatables/datatables.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/pdfmake/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/js/pages/datatables.init.js')); ?>"></script>

<script>
    $(document).ready(function () {
            var selectElement = document.getElementById('date_filter');
            var user12 = "all";
            var user11 = "super-warga-aspuri";
            var user10 = "super-warga-asgj";
            var user9 = "super-warga-asg";
            var super8 ="super-warga-aws";
            var table = $('#datatable-buttons').DataTable();

            $('#date_filter').on('change', function () {
                updateMasterTable($(this).val(),user12);
            });
            $('#super-warga-aspuri').on('click', function () {
                updateMasterTable(selectElement.value,user11);
             });
             $('#super-warga-asgj').on('click', function () {
                updateMasterTable(selectElement.value,user10);
             });
             $('#super-warga-asg').on('click', function () {
                updateMasterTable(selectElement.value,user9);
             });
             $('#super-warga-aws').on('click', function () {
                updateMasterTable(selectElement.value,super8);
             });

            function updateMasterTable(selectedDateFilter,datanya) {
                $.ajax({
                    type: 'GET',
                    url: '/get-data-by-date/' + datanya,
                    data: { date_filter: selectedDateFilter },
                    success: function (data) {
                        console.log("datantyahh", data)
                        updateMasterTableContent(data);
                    },
                    error: function (error) {
                        console.error('Error:', error);
                    }
                });
                }

            function updateMasterTableContent(data) {
                var table = $('#datatable-buttons').DataTable();
                table.clear(); // Bersihkan data tabel
                data.usernya.forEach(function (item) {
                    console.log("ininya",item.karakters)
                    var row = [
                        item.name,
                        item.asrama,
                        item.status_warga,
                        item.universitas,
                        item.fakultas,
                        item.semester,
                        item.ipk,
                        '<a href="#" class="btn btn-action" style="font-weight: bold;" onclick="showDetailModal(' + item.id + ')" data-toggle="modal" data-placement="top" title="Lihat Detail Kegiatan">' +item.total_kegiatan+
                         '</a>',
                        '<a href="/super-warga-asrama-detail/' + item.id + '" class="btn btn-primary btn-action" data-toggle="tooltip" data-placement="top" title="Detail">' +
                        '<i class="far fa-eye"></i>' +
                        '</a>',
                        '<a href="#" class="btn btn-primary btn-action" onclick="showDetailModal(' + item.id + ')" data-toggle="modal" data-placement="top" title="Detail">' +'klik'+
                         '</a>',
                    ];
                    table.row.add(row);
                });

                // Gambar ulang tabel setelah menambahkan data baru
                table.draw();
            }
        });
</script>



<!-- Modal-->
<script>
    function showDetailModal(userId) {
            var selek="all";
            var selectedDateFilter = $("#date_filter").val();
            $.ajax({
                type: 'GET',
                url: '/get-data-by-date/' + userId,
                data: { date_filter: selek },
                success: function (data) {


                    $('#modalBodyAkademik_ tbody').empty();
                    $('#modalBodyLeadership_ tbody').empty();
                    $('#modalBodykarakter_ tbody').empty();
                    $('#modalBodykreatif_ tbody').empty();

                    addDataToTable(data.akademiks, 'modalBodyAkademik_' );
                    addDataToTable(data.kreatifs, 'modalBodykreatif_' );
                    addDataToTable(data.karakters, 'modalBodykarakter_' );
                    addDataToTable(data.leaderships, 'modalBodyLeadership_' );
                    initializeDataTables(userId);
                    $('#detailModal').modal('show');
                },
                error: function (error) {
                    console.error('Error:', error);
                }
            });
        }

        function addDataToTable(dataArray, tableId) {
            var jumlah = 0;
            dataArray.forEach(function (item) {
                jumlah++;
                var modalContent = `
                    <tr>
                        <td>${jumlah}</td>
                        <td>${item.kegiatan}</td>
                        <td>${new Date(item.waktu).toLocaleDateString()}</td>
                        <td>${item.tempat}</td>
                        <td>${item.keterangan}</td>
                    </tr>
                `;
                $('#' + tableId + ' tbody').append(modalContent);
            });
        }

        function initializeDataTables(userId) {
            if (!$.fn.DataTable.isDataTable('#modalBodyAkademik_')) {
                $('#modalBodyAkademik_').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true,
                });
            }

            if (!$.fn.DataTable.isDataTable('#modalBodyLeadership_')) {
                $('#modalBodyLeadership_').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true,
                });
            }
            if (!$.fn.DataTable.isDataTable('#modalBodykarakter_')) {
                $('#modalBodykarakter_').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true,
                });
            }

            if (!$.fn.DataTable.isDataTable('#modalBodykreatif_')) {
                $('#modalBodykreatif_').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "autoWidth": true,
                });
            }
            }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/warga/index.blade.php ENDPATH**/ ?>