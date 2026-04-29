<?php $__env->startSection('title'); ?> Profile <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<!-- DataTables -->
<link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php $__env->startComponent('super.common-components.breadcrumb'); ?>
<?php $__env->slot('title'); ?> Profile <?php $__env->endSlot(); ?>
<?php $__env->slot('li_1'); ?> Pages <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>


<!-- start row -->
<div class="row">
    <div class="col-md-12 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="profile-widgets py-3">

                    <div class="text-center">
                        <div class="">
                            <img src="<?php echo e(url('/data_photo/' . $user->avatar)); ?>" alt=""
                                class="avatar-lg mx-auto img-thumbnail rounded-circle">
                            <div class="online-circle"><i class="fas fa-circle text-success"></i></div>
                        </div>

                        <div class="mt-3 ">
                            <a href="#" class="text-dark font-weight-medium font-size-16"><?php echo e($user->name); ?></a>
                            <p class="text-body mt-1 mb-1"><?php echo e($user->asrama); ?></p>

                        </div>

                        <div class="mt-4">

                            <ui class="list-inline social-source-list">
                                <li class="list-inline-item">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle">
                                            <i class="mdi mdi-facebook"></i>
                                        </span>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-info">
                                            <i class="mdi mdi-twitter"></i>
                                        </span>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-danger">
                                            <i class="mdi mdi-google-plus"></i>
                                        </span>
                                    </div>
                                </li>

                                <li class="list-inline-item">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-pink">
                                            <i class="mdi mdi-instagram"></i>
                                        </span>
                                    </div>
                                </li>
                            </ui>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Personal Information</h5>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Email</p>
                    <h6 class=""><?php echo e($user->email); ?></h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Tanggal Lahir</p>
                    <h6 class=""><?php echo e($user->tgl_lahir); ?></h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Nomor Telepon</p>
                    <h6 class=""><?php echo e($user->no_telp); ?></h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Alamat Asal</p>
                    <h6 class=""><?php echo e($user->alamat); ?></h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Kecamatan</p>
                    <h6 class=""><?php echo e($user->kecamatan); ?></h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Kota/Kabupaten</p>
                    <h6 class=""><?php echo e($user->kota); ?></h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Provinsi</p>
                    <h6 class=""><?php echo e($user->provinsi); ?></h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Kampus</p>
                    <h6 class=""><?php echo e($user->universitas); ?></h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Fakultas</p>
                    <h6 class=""><?php echo e($user->fakultas); ?></h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Program Studi</p>
                    <h6 class=""><?php echo e($user->prodi); ?></h6>
                </div>

                <div class="mt-3">
                    <p class="font-size-12 text-muted mb-1">Organisasi</p>
                    <h6 class=""><?php echo e($user->organisasi); ?></h6>
                </div>

            </div>
        </div>

    </div>

    <div class="col-md-12 col-xl-9">
        <div class="row">
            <div class="col-md-12 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-2">IPK</p>
                                <?php if($data_ipks !== null): ?>
                                <h4 class="mb-0 badge badge-soft-success font-size-14"><?php echo e(number_format($data_ipks, 2)); ?>

                                </h4>
                                <?php else: ?>
                                <h4 class="mb-0 badge badge-soft-success font-size-14">Data tidak tersedia</h4>
                                <?php endif; ?>
                            </div>
                            <div class="col-4">
                                <div class="text-right">
                                    <div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-2">Status Warga</p>
                                <label class="mb-0 badge badge-soft-info font-size-14"><?php echo e($user->status_warga); ?></label>
                            </div>
                            <div class="col-4">
                                <div class="text-right">
                                    <div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <p class="mb-2">Nomor Induk Warga</p>
                                <label class="mb-0 badge badge-soft-primary font-size-14"><?php echo e($user->no_induk); ?></label>
                            </div>
                            <div class="col-4">
                                <div class="text-right">
                                    <div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Data Statistik Keseluruhan</h4>

                        <div class="col-xl-8">
                            <div class="form-group">
                                <label for="">Filter by date:</label>

                                <form action="<?php echo e(route('super-warga-asrama-detail', ['id' => $id])); ?>" method="get">
                                    <div class="input-group">
                                        <select class="form-select" name="date_filter">
                                            <option value="">All Dates</option>
                                            <option value="today">Today</option>
                                            <option value="yesterday">Yesterday</option>
                                            <option value="this_week">This Week</option>
                                            <option value="last_week">Last Week</option>
                                            <option value="this_month">This Month</option>
                                            <option value="last_month">Last Month</option>
                                            <option value="this_year">This Year</option>
                                            <option value="last_year">Last Year</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br>

                        <div id="statistik_total" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#akademik" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-school-outline"></i></span>
                            <span class="d-none d-sm-block">Akademik</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#leadership" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-account-tie-outline"></i></span>
                            <span class="d-none d-sm-block">Leadership</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#karakter" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-islam"></i></span>
                            <span class="d-none d-sm-block">Karakter Islami</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kreatif" role="tab">
                            <span class="d-block d-sm-none"><i class="mdi mdi-lightbulb-on-outline"></i></span>
                            <span class="d-none d-sm-block">Kreativitas & Kewirausahaan</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content p-3 text-muted">
                    <!-- Tab akademik -->
                    <div class="tab-pane active" id="akademik" role="tabpanel">
                        <h4 class="card-title mb-4">Data Statistik Komponen Akademik</h4>
                        <div id="akademik_chart" class="apex-charts mt-4"></div>

                        <br>
                        <br>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5>Data Kegiatan Akademik</h5>
                                    </div>
                                    <br>
                                    <div class="panel-body">
                                        <div style="overflow: scroll">
                                            <table class="table table-condensed" style="border-collapse:collapse;">

                                                <thead>
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th><strong>Kode</strong></th>
                                                        <th><strong>Komponen</strong></th>
                                                        <th><strong>Jumlah</strong></th>
                                                        <th><strong>Presentase</strong></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $komponen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akademik): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($akademik->aspek == 'Akademik'): ?>
                                                    <tr data-toggle="collapse" data-target="#demo<?php echo e($akademik->id); ?>"
                                                        class="accordion-toggle">
                                                        <td><button class="btn btn-default btn-xs"><span
                                                                    class="mdi mdi-format-list-bulleted-square"></span></button>
                                                        </td>

                                                        <td> <?php echo e($akademik->kode); ?></td>
                                                        <td><?php echo e($akademik->nama_komponen); ?></td>
                                                        

                                                        <td><?php echo e($akademiks->where('komponen',
                                                            $akademik->nama_komponen)->count()); ?></td>
                                                        <td>
                                                            <?php if($akademiks->count() > 0): ?>
                                                            <?php echo e(number_format(($akademiks->where('komponen',
                                                            $akademik->nama_komponen)->count() / $akademiks->count()) *
                                                            100, 2)); ?>%
                                                            <?php else: ?>
                                                            0%
                                                            <?php endif; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse"
                                                                id="demo<?php echo e($akademik->id); ?>">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Kegiatan</th>
                                                                            <th>Waktu</th>
                                                                            <th>Tempat</th>
                                                                            <th>Uraian Kegiatan</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        <?php $__currentLoopData = $akademiks->where('komponen',
                                                                        $akademik->nama_komponen); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akademika): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if(is_object($akademika)): ?>
                                                                        <tr>
                                                                            <td><?php echo e($akademika->kegiatan); ?></td>
                                                                            <td><?php echo e(date('d-m-Y',
                                                                                strtotime($akademika->waktu))); ?></td>
                                                                            <td><?php echo e($akademika->tempat); ?></td>
                                                                            <td><?php echo e($akademika->keterangan); ?></td>
                                                                        </tr>
                                                                        <?php endif; ?>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </tbody>

                                                                </table>
                                                            </div>
                                                        </td>
                                                        <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tr>


                                                    
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Tab Leadership -->
                    <div class="tab-pane" id="leadership" role="tabpanel">
                        <h4 class="card-title mb-4">Data Statistik Komponen Leadership</h4>
                        <div id="leadership_chart" class="apex-charts mt-4"></div>

                        <br>
                        <br>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5>Data Kegiatan Leadership</h5>
                                    </div>
                                    <br>
                                    <div class="panel-body">
                                        <div style="overflow: scroll">
                                            <table class="table table-condensed" style="border-collapse:collapse;">

                                                <thead>
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th><strong>Kode</strong></th>
                                                        <th><strong>Komponen</strong></th>
                                                        <th><strong>Jumlah</strong></th>
                                                        <th><strong>Presentase</strong></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $komponen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Leader): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($Leader->aspek == 'Leadership'): ?>
                                                    <tr data-toggle="collapse" data-target="#dewi<?php echo e($Leader->id); ?>"
                                                        class="accordion-toggle">
                                                        <td><button class="btn btn-default btn-xs"><span
                                                                    class="mdi mdi-format-list-bulleted-square"></span></button>
                                                        </td>

                                                        <td> <?php echo e($Leader->kode); ?></td>
                                                        <td><?php echo e($Leader->nama_komponen); ?></td>
                                                        

                                                        <td><?php echo e($leaderships->where('komponen',
                                                            $Leader->nama_komponen)->count()); ?></td>
                                                        <td>
                                                            <?php if($leaderships->count() > 0): ?>
                                                            <?php echo e(number_format(($leaderships->where('komponen',
                                                            $Leader->nama_komponen)->count() / $leaderships->count()) *
                                                            100, 2)); ?>%
                                                            <?php else: ?>
                                                            0%
                                                            <?php endif; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse"
                                                                id="dewi<?php echo e($Leader->id); ?>">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Kegiatan</th>
                                                                            <th>Waktu</th>
                                                                            <th>Tempat</th>
                                                                            <th>Uraian Kegiatan</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        <?php $__currentLoopData = $leaderships->where('komponen',
                                                                        $Leader->nama_komponen); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leaderships11): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if(is_object($leaderships11)): ?>
                                                                        <tr>
                                                                            <td><?php echo e($leaderships11->kegiatan); ?></td>
                                                                            <td><?php echo e(date('d-m-Y',
                                                                                strtotime($leaderships11->waktu))); ?>

                                                                            </td>
                                                                            <td><?php echo e($leaderships11->tempat); ?></td>
                                                                            <td><?php echo e($leaderships11->keterangan); ?></td>
                                                                        </tr>
                                                                        <?php endif; ?>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </tbody>

                                                                </table>
                                                            </div>
                                                        </td>
                                                        <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- Tab Karakter -->
                    <div class="tab-pane" id="karakter" role="tabpanel">
                        <h4 class="card-title mb-4">Data Statistik Komponen Karakter Islami</h4>
                        <div id="karakter_chart" class="apex_chart mt-4"></div>

                        <br>
                        <br>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5>Data Kegiatan Karakter Islami</h5>
                                    </div>
                                    <br>
                                    <div class="panel-body">
                                        <div style="overflow: scroll">
                                            <table class="table table-condensed" style="border-collapse:collapse;">

                                                <thead>
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th><strong>Kode</strong></th>
                                                        <th><strong>Komponen</strong></th>
                                                        <th><strong>Jumlah</strong></th>
                                                        <th><strong>Presentase</strong></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $komponen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karak): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($karak->aspek == 'Karakter Islami'): ?>
                                                    <tr data-toggle="collapse" data-target="#dewi<?php echo e($karak->id); ?>"
                                                        class="accordion-toggle">
                                                        <td><button class="btn btn-default btn-xs"><span
                                                                    class="mdi mdi-format-list-bulleted-square"></span></button>
                                                        </td>

                                                        <td> <?php echo e($karak->kode); ?></td>
                                                        <td><?php echo e($karak->nama_komponen); ?></td>
                                                        

                                                        <td><?php echo e($karakters->where('komponen',
                                                            $karak->nama_komponen)->count()); ?></td>
                                                        <td>
                                                            <?php if($karakters->count() > 0): ?>
                                                            <?php echo e(number_format(($karakters->where('komponen',
                                                            $karak->nama_komponen)->count() / $karakters->count()) *
                                                            100, 2)); ?>%
                                                            <?php else: ?>
                                                            0%
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse"
                                                                id="dewi<?php echo e($karak->id); ?>">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Kegiatan</th>
                                                                            <th>Waktu</th>
                                                                            <th>Tempat</th>
                                                                            <th>Uraian Kegiatan</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        <?php $__currentLoopData = $karakters->where('komponen',
                                                                        $karak->nama_komponen); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karakters1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if(is_object($karakters1)): ?>
                                                                        <tr>
                                                                            <td><?php echo e($karakters1->kegiatan); ?></td>
                                                                            <td><?php echo e(date('d-m-Y',
                                                                                strtotime($karakters1->waktu))); ?></td>
                                                                            <td><?php echo e($karakters1->tempat); ?></td>
                                                                            <td><?php echo e($karakters1->keterangan); ?></td>
                                                                        </tr>
                                                                        <?php endif; ?>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </tbody>

                                                                </table>
                                                            </div>
                                                        </td>
                                                        <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- Tab kreatif -->
                    <div class="tab-pane" id="kreatif" role="tabpanel">
                        <h4 class="card-title mb-4">Data Statistik Komponen Kreativitas & Kewirausahaan</h4>
                        <div id="kreatif_chart" class="apex_chart mt-4"></div>

                        <br>
                        <br>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5>Data Kegiatan Kreativitas & Kewirausahaan
                                        </h5>
                                    </div>
                                    <br>
                                    <div class="panel-body">
                                        <div style="overflow: scroll">
                                            <table class="table table-condensed" style="border-collapse:collapse;">

                                                <thead>
                                                    <tr>
                                                        <th>&nbsp;</th>
                                                        <th><strong>Kode</strong></th>
                                                        <th><strong>Komponen</strong></th>
                                                        <th><strong>Jumlah</strong></th>
                                                        <th><strong>Presentase</strong></th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $komponen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $krea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($krea->aspek == 'Kreativitas & Kewirausahaan'): ?>
                                                    <tr data-toggle="collapse" data-target="#dewi<?php echo e($krea->id); ?>"
                                                        class="accordion-toggle">
                                                        <td><button class="btn btn-default btn-xs"><span
                                                                    class="mdi mdi-format-list-bulleted-square"></span></button>
                                                        </td>

                                                        <td> <?php echo e($krea->kode); ?></td>
                                                        <td><?php echo e($krea->nama_komponen); ?></td>
                                                        

                                                        <td><?php echo e($kreatifs->where('komponen',
                                                            $krea->nama_komponen)->count()); ?></td>
                                                        <td>
                                                            <?php if($kreatifs->count() > 0): ?>
                                                            <?php echo e(number_format(($kreatifs->where('komponen',
                                                            $krea->nama_komponen)->count() / $kreatifs->count()) * 100,
                                                            2)); ?>%
                                                            <?php else: ?>
                                                            0%
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse"
                                                                id="dewi<?php echo e($krea->id); ?>">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Kegiatan</th>
                                                                            <th>Waktu</th>
                                                                            <th>Tempat</th>
                                                                            <th>Uraian Kegiatan</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                        <?php $__currentLoopData = $kreatifs->where('komponen',
                                                                        $krea->nama_komponen); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kreatifs1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if(is_object($kreatifs1)): ?>
                                                                        <tr>
                                                                            <td><?php echo e($kreatifs1->kegiatan); ?></td>
                                                                            <td><?php echo e(date('d-m-Y',
                                                                                strtotime($kreatifs1->waktu))); ?></td>
                                                                            <td><?php echo e($kreatifs1->tempat); ?></td>
                                                                            <td><?php echo e($kreatifs1->keterangan); ?></td>
                                                                        </tr>
                                                                        <?php endif; ?>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </tbody>

                                                                </table>
                                                            </div>
                                                        </td>
                                                        <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Data Index Prestasi Akademik per Semester</h4>

                <div style="overflow: scroll">
                    <div class="table-responsive">
                        <table class="table table-centered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Semester ke-</th>
                                    <th scope="col">Tahun</th>
                                    <th scope="col">IP</th>
                                    <th scope="col" colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $ipks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr>
                                    <td><?php echo e($item->semester); ?></td>
                                    <td><?php echo e($item->tahun); ?></td>
                                    <td><?php echo e($item->ip); ?></td>
                                    <td>
                                        <a href="/super-ipk-detail/<?php echo e($item->id); ?>"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="mdi mdi-eye-circle-outline"></i> View</a>
                                    </td>
                                </tr>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="d-print-none">
    <div class="float-right">
        <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i>
            Print</a>
    </div>
</div>
</div>

<!-- end row -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<!-- Required datatable js -->
<script src="<?php echo e(URL::asset('/libs/datatables/datatables.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/jszip/jszip.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/libs/pdfmake/pdfmake.min.js')); ?>"></script>

<!-- Datatable init js -->
<script src="<?php echo e(URL::asset('/js/pages/datatables.init.js')); ?>"></script>

<!-- apexcharts -->
<script src="<?php echo e(URL::asset('/libs/apexcharts/apexcharts.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/js/pages/profile.init.js')); ?>"></script>

<!-- apexcharts init -->
<script src="<?php echo e(URL::asset('/js/pages/apexcharts.init.js')); ?>"></script>

<script>
    var akademiksCount = <?php echo e($akademiks->count()); ?>;
            var leadershipsCount = <?php echo e($leaderships->count()); ?>;
            var karaktersCount = <?php echo e($karakters->count()); ?>;
            var kreatifsCount = <?php echo e($kreatifs->count()); ?>;
                var options = {
                    chart: {
                        height: 320,
                        type: 'pie',
                    },
                series: [akademiksCount, leadershipsCount, karaktersCount, kreatifsCount],
                labels: ["Akademik", "Leadership", "Karakter Islami", "Kreativitas & Kewirausahaan"],
                colors: ["#45cb85", "#3b5de7","#ff715b", "#eeb902"],
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    verticalAlign: 'middle',
                    floating: false,
                    fontSize: '14px',
                    offsetX: 0,
                },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]

        }

        var chart = new ApexCharts(
            document.querySelector("#statistik_total"),
            options
        );

        chart.render();
</script>
<script>
    function generateRandomColor() {
                const letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
                            }
           const akademiklabel = <?php echo json_encode($komponen->where('aspek', 'Akademik')->pluck('nama_komponen')); ?>;
           const seriesnama = <?php echo json_encode($akademiks->whereIn('komponen', $komponen->pluck('nama_komponen')->toArray())->pluck('komponen')->toArray(), 512) ?>;
            const countsMap = {};
            seriesnama.forEach(nama => {
                countsMap[nama] = (countsMap[nama] || 0) + 1;
            });
            const seriesData = akademiklabel.map(label => countsMap[label] || 0);
            const colorsak = akademiklabel.map(() => generateRandomColor())
            var options = {
                chart: {
                    height: 320,
                    type: 'donut',
                },
                series:seriesData,
                labels:akademiklabel,
                color:colorsak,
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    verticalAlign: 'middle',
                    floating: false,
                    fontSize: '14px',
                    offsetX: 0,
                },
                plotOptions: {
                pie: {
                donut: {
                    size: '50%', // ukuran donut
                },
                customScale: 1,
                offsetX: 0,
                offsetY: 0,
                dataLabels: {
                    formatter: function (val, opts) {
                        return "Akademik";
                    },
                    dropShadow: {
                        enabled: false
                    }
                }
            }
        },
    };
    var chart = new ApexCharts(document.querySelector("#akademik_chart"), options);
    chart.render();
</script>
<script>
    const leadership = <?php echo json_encode($komponen->where('aspek', 'Leadership')->pluck('nama_komponen')); ?>;
            const seriesnamalead = <?php echo json_encode($leaderships->whereIn('komponen', $komponen->pluck('nama_komponen')->toArray())->pluck('komponen')->toArray(), 512) ?>;
            const countsMapled = {};
            seriesnamalead.forEach(nama => {
                countsMapled[nama] = (countsMapled[nama] || 0) + 1;
            });
            const seriesDatalead = leadership.map(label => countsMapled[label] || 0);
            const colorlead= leadership.map(() => generateRandomColor());
            var options = {
            chart: {
                height: 320,
                type: 'donut',
            },
            series:seriesDatalead,
            labels:leadership,
            color:colorlead,
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]

        }

        var chart = new ApexCharts(
            document.querySelector("#leadership_chart"),
            options
        );

        chart.render();
</script>
<script>
    const leaderkreatif = <?php echo json_encode($komponen->where('aspek', 'Kreativitas & Kewirausahaan')->pluck('nama_komponen')); ?>;
            const serieskreatif= <?php echo json_encode($kreatifs->whereIn('komponen', $komponen->pluck('nama_komponen')->toArray())->pluck('komponen')->toArray(), 512) ?>;
            const countsMapkreatif = {};
            serieskreatif.forEach(nama => {
                countsMapkreatif[nama] = (countsMapkreatif[nama] || 0) + 1;
            });
            const seriesDataKreatif = leaderkreatif.map(label => countsMapkreatif[label] || 0);
            const colorkreatif= leaderkreatif.map(() => generateRandomColor());
            console.log("seriesnya karakter",seriesDataKreatif)
            console.log("labelnya",leaderkreatif)
        var options = {
            chart: {
                height: 320,
                type: 'donut',
            },
            series:seriesDataKreatif,
            labels:leaderkreatif,
            colors:colorkreatif,
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]

        }

        var chart = new ApexCharts(
            document.querySelector("#kreatif_chart"),
            options
        );

        chart.render();
</script>

<script>
    const leaderkarakters = <?php echo json_encode($komponen->where('aspek', 'Karakter Islami')->pluck('nama_komponen')); ?>;
            const serieskarakters= <?php echo json_encode($karakters->whereIn('komponen', $komponen->pluck('nama_komponen')->toArray())->pluck('komponen')->toArray(), 512) ?>;
            const countsMapkarakters = {};
            serieskarakters.forEach(nama => {
                countsMapkarakters[nama] = (countsMapkarakters[nama] || 0) + 1;
            });
            const seriesDatakarakters = leaderkarakters.map(label => countsMapkarakters[label] || 0);
            const colorkarakters= leaderkarakters.map(() => generateRandomColor());
        var options = {
            chart: {
                height: 320,
                type: 'donut',
            },
            series:seriesDatakarakters,
            labels:leaderkarakters,
            colors:colorkarakters,
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                verticalAlign: 'middle',
                floating: false,
                fontSize: '14px',
                offsetX: 0,
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        height: 240
                    },
                    legend: {
                        show: false
                    },
                }
            }]

        }

        var chart = new ApexCharts(
            document.querySelector("#karakter_chart"),
            options
        );

        chart.render();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/warga/detail.blade.php ENDPATH**/ ?>