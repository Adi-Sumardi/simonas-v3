<?php $__env->startSection('title'); ?> Profile <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('super.common-components.breadcrumb'); ?>
         <?php $__env->slot('title'); ?> Profile  <?php $__env->endSlot(); ?>
         <?php $__env->slot('li_1'); ?> Pages  <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>


                    <!-- start row -->

                    <div class="row">
                        <div class="col-md-12 col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="profile-widgets py-3">

                                        <div class="text-center">
                                            <div class="">
                                                <img src="<?php echo e(asset('images/' .$alumni->foto)); ?>" alt="Foto" style="border-radius: 50%; object-fit: cover;"  width="150" height="150">
                                                <div class="online-circle"><br><br><br>
                                            <i class="fas fa-circle text-success"></i></div>
                                            </div>

                                            <div class="mt-4">
                                                <div class="mt-3">
                                                    <h6 ><?php echo e($alumni->nama); ?></h6>
                                                    <p class="font-size-12 text-muted mb-1"><?php echo e($Asrama->nama_asrama); ?></p>

                                                    <div style="display: inline-block; padding: 10px; background-color: skyblue; border-radius: 15%; height: 35px; width:120px;">
                                                        <h6 style="display: flex; align-items: center; justify-content: center; margin: 0;">Alumni</h6>
                                                    </div>

                                                                                                    </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3" style="border-bottom: 2px solid #f9f6f6; padding-bottom: 10px;">BIODATA</h5>
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Tempat dan Tanggal Lahir</p>
                                        <h6 class=""><?php echo e($alumni->provinsi_asal); ?>,<?php echo e(\Carbon\Carbon::parse($alumni->tanggal_lahir)->format('d M Y')); ?></h6>
                                    </div>
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Kota Asal</p>
                                        <h6 class=""><?php echo e($alumni->id_province); ?></h6>
                                    </div>
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Alamat Domisili</p>
                                        <h6 class=""><?php echo e($alumni->alamat_domisili); ?></h6>
                                    </div>
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Nomer Telpon / WA</p>
                                        <h6 class=""><?php echo e($alumni->no_whatsapp); ?></h6>
                                    </div>
                                    <div class="mt-3">
                                        <p class="font-size-12 text-muted mb-1">Email</p>
                                        <h6 class=""><?php echo e($alumni->email); ?></h6>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-md-1 col-xl-9">
                            <div class="row mx-n2">
                                <div class="col-md-4 px-2">
                                    <div class="card h-100 border">
                                        <div class="card-body">
                                            <fieldset  style="height: 560px;">
                                                <h4 class="card-title mb-4" style="border-bottom: 2px solid #f9f6f6; padding-bottom: 10px;"><strong>DATA KEASRAMAAN</strong></h4>
                                                <div class="mt-3">
                                                    <p class="font-size-12 text-muted mb-1">Asal Asrama</p>
                                                    <h6><?php echo e($Asrama->nama_asrama); ?></h6>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mt-3">
                                                            <p class="font-size-12 text-muted mb-1">Tahun Masuk</p>
                                                            <h6><?php echo e($alumni->tahun_masuk_asrama); ?></h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mt-3">
                                                            <p class="font-size-12 text-muted mb-1">Tahun Keluar</p>
                                                            <h6><?php echo e($alumni->tahun_keluar_asrama); ?></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <p class="font-size-12 text-muted mb-1">Jabatan Terakhir</p>
                                                    <h6 class=""><?php echo e($Asrama->ketua); ?></h6>
                                                </div>
                                                <div class="mt-3">
                                                    <p class="font-size-12 text-muted mb-1">Nama Direktur</p>
                                                    <h6 class=""><?php echo e($Asrama->direktur); ?></h6>
                                                </div>
                                                <div class="mt-3 mb-auto">
                                                    <p class="font-size-12 text-muted mb-1">Teman Satu Angkatan</p>
                                                    <?php $__currentLoopData = explode('@', $alumni->teman_angkatan); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <h6 class=""><?php echo e($teman); ?></h6>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8 px-1">
                                    <div class="card">
                                        <div class="card-body">
                                            <fieldset style="height: 580px; overflow-y: auto;">
                                                <h4 class="card-title mb-2" ><strong>PENDIDIKAN</strong></h4>
                                                <div class="form-group row">
                                                      <div class="container">
                                                        <div class="row">
                                                          <div class="col-md-5">
                                                            <div class="mt-3 mb-auto">
                                                                <p class="font-size-12 text-muted mb-1">Jenjang Pendidikan Terakhir</p>
                                                                <?php
                                                                $sortedPendidikan = $AlumniPendidikan->sortByDesc(function ($pendidikan) {
                                                                    switch ($pendidikan->gelar) {
                                                                        case 'S3':
                                                                            return 3;
                                                                        case 'S2':
                                                                            return 2;
                                                                        case 'S1':
                                                                            return 1;
                                                                        default:
                                                                            return 0;
                                                                    }
                                                                });

                                                            ?>

                                                            <?php if($sortedPendidikan->isNotEmpty()): ?>
                                                                <?php
                                                                    $recentPendidikan = $sortedPendidikan->first();
                                                                ?>

                                                                <div class="mt-1"><br>
                                                                    <h6 class=""><?php echo e($recentPendidikan->gelar); ?></h6>
                                                                </div><br><br>
                                                            <?php endif; ?>
                                                                                                  </div>
                                                     </div>
                                                          <div class="col-md-1" style="border-left: 2px solid #f9f6f6; border-right: 2px solid #f9f6f6; padding-left: 10px; padding-right: 10px;">
                                                            <?php $__currentLoopData = $AlumniPendidikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendidikan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="mt-1 "><br>
                                                                <h6 class=""><?php echo e($pendidikan->gelar); ?></h6>
                                                            </div><br><br>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                          </div>
                                                          <div class="col-md-5">
                                                            <div class="mt-3 ">
                                                                <?php $__currentLoopData = $AlumniPendidikan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $AlumniPendidikan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <p class="font-size-12 text-muted mb-1">Jurusan</p>
                                                                <h6 class=""><?php echo e($AlumniPendidikan->fakultas_jurusan); ?></h6>
                                                                <p class="font-size-12 text-muted mb-1">Universitas</p>
                                                                <h6 class=""><?php echo e($AlumniPendidikan->nama_kampus); ?></h6>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                          </div>

                                                        </div>
                                                      </div>
                                                    </div>
                                                    <h4 class="card-title mb-2"><strong>PEKERJAAN</strong></h4>
                                                    <div class="form-group row">
                                                        <div class="container">
                                                            <div class="row">
                                                              <div class="col-md-2">
                                                                <div class="mt-3" >
                                                                    <p class="font-size-12 text-muted mb-1" >Jabatan</p>
                                                                    <?php $__currentLoopData = $AlumniPekerjaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namjab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <h6 class="mb-2"><?php echo e($namjab->tempat_pekerjaan); ?></h6>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </div>
                                                              </div>
                                                              <div class="col-md-6" style="border-left: 2px solid #f9f6f6; border-right: 2px solid #f9f6f6; padding-left: 10px; padding-right: 10px;">
                                                                <div class="mt-3">
                                                                    <p class="font-size-12 text-muted mb-1">Perusahaan</p>
                                                                    <?php $__currentLoopData = $AlumniPekerjaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perusahaan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <h6 class="mb-2"><?php echo e($perusahaan->bidang_pekerjaan); ?></h6>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </div>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <div class="mt-3 mb-auto">
                                                                    <p class="font-size-12 text-muted mb-1">Tahun</p>
                                                                    <?php $__currentLoopData = $AlumniPekerjaan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <h6 class="mb-2"><?php echo e($tahun->tahun_kerjaan); ?></h6>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                    </div>
<br>
                                                    <h4 class="card-title mb-2"><strong>ORGANISASI</strong></h4>
                                                    <div class="form-group row">
                                                        <div class="container">
                                                            <div class="row">
                                                              <div class="col-md-4">
                                                                <div class="mt-3 mb-auto" >
                                                                    <p class="font-size-12 text-muted mb-1" >Jabatan</p>
                                                                    <?php $__currentLoopData = $AlumniOrganisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namjab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <h6 class=""><?php echo e($namjab->organisasi_jabatan); ?></h6>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </div>
                                                              </div>
                                                              <div class="col-md-5" style="border-left: 2px solid #f9f6f6; border-right: 2px solid #f9f6f6; padding-left: 10px; padding-right: 10px;">
                                                                <div class="mt-3">
                                                                    <p class="font-size-12 text-muted mb-1">Organisasi</p>
                                                                    <?php $__currentLoopData = $AlumniOrganisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namjab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <h6 class=""><?php echo e($namjab->nama); ?></h6>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </div>
                                                              </div>
                                                              <div class="col-md-3">
                                                                <div class="mt-3 mb-auto">
                                                                    <p class="font-size-12 text-muted mb-1">Tahun</p>
                                                                    <?php $__currentLoopData = $AlumniOrganisasi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $namjab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <h6 class=""><?php echo e($namjab->tahun_organisasi); ?></h6>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                    </div>
                                                    </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="d-print-none">
                                <div class="float-right">
                                    <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i> Print</a>
                                </div>
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


    <?php $__env->stopSection(); ?>

<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/alumni/detail.blade.php ENDPATH**/ ?>