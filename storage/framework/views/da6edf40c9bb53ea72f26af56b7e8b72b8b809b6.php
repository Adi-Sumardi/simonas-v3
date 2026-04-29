<?php $__env->startSection('title'); ?> Dashboard <?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('super.common-components.breadcrumb'); ?>
         <?php $__env->slot('title'); ?> Dashboard   <?php $__env->endSlot(); ?>
         <?php $__env->slot('title_li'); ?> Welcome to SIMONAS Dashboard   <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="media">
                        <div class="avatar-sm font-size-20 mr-3">
                            <span class="avatar-title bg-soft-info text-primary rounded">
                                <?php echo e($jumlah_warga_asgj); ?>

                                </span>
                        </div>
                        <div class="media-body">
                            <h4 class="card-title mb-4 mt-2">DATA WARGA ASGJ</h4>
                        </div>
                    </div>

                    <div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Warga Percobaan</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-warcob-asgj-modal-xl" class="mb-0"><?php echo e($jumlah_warga_percobaan_asgj); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-warcob-asgj-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Warga Percobaan ASGJ</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $wargaPercobaanASGJ = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Sunan Gunung Jati'],
                                                            ['status_warga', 'Warga Percobaan']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $wargaPercobaanASGJ; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Pengurus</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-pengurus-asgj-modal-xl" class="mb-0"><?php echo e($jumlah_warga_pengurus_asgj); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-pengurus-asgj-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Pengurus ASGJ</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $pengurusASGJ = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Sunan Gunung Jati'],
                                                            ['status_warga', 'Pengurus Asrama']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $pengurusASGJ; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Warga Tetap</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-wartap-asgj-modal-xl" class="mb-0"><?php echo e($jumlah_warga_tetap_asgj); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-wartap-asgj-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Warga Tetap ASGJ</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $wartapASGJ = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Sunan Gunung Jati'],
                                                            ['status_warga', 'Warga Tetap']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $wartapASGJ; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2" style="background: linear-gradient(to bottom, #0074b4, #0056a0); border-radius: 10px; text-align: center;">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <p class="mb-2 mt-2" style="color: white; font-size: 18px;">Kapasitas Ideal</p>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div>
                                        <h4 class="mb-2" style="color: white; font-size: 20px;">16</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-content-center">
                                <div class="col-12">
                                    <?php
                                        $jumlah_warga_asgj = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Sunan Gunung Jati'],
                                                        ])->count();
                                        $kapasitas_ideal = 16;
                                        $persentase_terpakai = ($jumlah_warga_asgj / $kapasitas_ideal) * 100;
                                        $rounded_persentase = intval($persentase_terpakai);
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo e($persentase_terpakai); ?>%;" aria-valuenow="<?php echo e($jumlah_warga_asgj); ?>" aria-valuemin="0" aria-valuemax="<?php echo e($kapasitas_ideal); ?>">
                                            <?php echo e($rounded_persentase); ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body">

                    <div class="media">
                        <div class="avatar-sm font-size-20 mr-3">
                            <span class="avatar-title bg-soft-info text-primary rounded">
                                    <?php echo e($jumlah_warga_asg); ?>

                                </span>
                        </div>
                        <div class="media-body">
                            <h4 class="card-title mb-4 mt-2">DATA WARGA ASG</h4>
                        </div>
                    </div>

                    <div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Warga Percobaan</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-warcob-asg-modal-xl" class="mb-0"><?php echo e($jumlah_warga_percobaan_asg); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-warcob-asg-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Warga Percobaan ASG</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $wargaPercobaanASG = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Sunan Giri'],
                                                            ['status_warga', 'Warga Percobaan']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $wargaPercobaanASG; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Pengurus</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-pengurus-asg-modal-xl" class="mb-0"><?php echo e($jumlah_warga_pengurus_asg); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-pengurus-asg-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Pengurus ASG</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $pengurusASG = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Sunan Giri'],
                                                            ['status_warga', 'Pengurus Asrama']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $pengurusASG; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Warga Tetap</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-wartap-asg-modal-xl" class="mb-0"><?php echo e($jumlah_warga_tetap_asg); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-wartap-asg-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Warga Tetap ASG</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $wartapASG = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Sunan Giri'],
                                                            ['status_warga', 'Warga Tetap']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $wartapASG; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2" style="background: linear-gradient(to bottom, #0074b4, #0056a0); border-radius: 10px; text-align: center;">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <p class="mb-2 mt-2" style="color: white; font-size: 18px;">Kapasitas Ideal</p>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div>
                                        <h4 class="mb-2" style="color: white; font-size: 20px;">36</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-content-center">
                                <div class="col-12">
                                    <?php
                                        $jumlah_warga_asg = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Sunan Giri'],
                                                        ])->count();
                                        $kapasitas_ideal = 36;
                                        $persentase_terpakai = ($jumlah_warga_asg / $kapasitas_ideal) * 100;
                                        $rounded_persentase = intval($persentase_terpakai);
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo e($persentase_terpakai); ?>%;" aria-valuenow="<?php echo e($jumlah_warga_asg); ?>" aria-valuemin="0" aria-valuemax="<?php echo e($kapasitas_ideal); ?>">
                                            <?php echo e($rounded_persentase); ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body">

                    <div class="media">
                        <div class="avatar-sm font-size-20 mr-3">
                            <span class="avatar-title bg-soft-info text-primary rounded">
                                    <?php echo e($jumlah_warga_aws); ?>

                                </span>
                        </div>
                        <div class="media-body">
                            <h4 class="card-title mb-4 mt-2">DATA WARGA AWS</h4>
                        </div>
                    </div>

                    <div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Warga Percobaan</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-warcob-aws-modal-xl" class="mb-0"><?php echo e($jumlah_warga_percobaan_aws); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-warcob-aws-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Warga Percobaan AWS</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $wargaPercobaanAWS = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Wali Songo'],
                                                            ['status_warga', 'Warga Percobaan']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $wargaPercobaanAWS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">
                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Pengurus</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-pengurus-aws-modal-xl" class="mb-0"><?php echo e($jumlah_warga_pengurus_aws); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-pengurus-aws-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Pengurus AWS</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $pengurusAWS = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Wali Songo'],
                                                            ['status_warga', 'Pengurus Asrama']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $pengurusAWS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">

                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Warga Tetap</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-wartap-aws-modal-xl" class="mb-0"><?php echo e($jumlah_warga_tetap_aws); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-wartap-aws-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Warga Tetap AWS</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $wartapAWS = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Wali Songo'],
                                                            ['status_warga', 'Warga Tetap']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $wartapAWS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">

                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2" style="background: linear-gradient(to bottom, #0074b4, #0056a0); border-radius: 10px; text-align: center;">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <p class="mb-2 mt-2" style="color: white; font-size: 18px;">Kapasitas Ideal</p>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div>
                                        <h4 class="mb-2" style="color: white; font-size: 20px;">32</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-content-center">
                                <div class="col-12">
                                    <?php
                                        $jumlah_warga_aws = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Wali Songo'],
                                                        ])->count();
                                        $kapasitas_ideal = 32;
                                        $persentase_terpakai = ($jumlah_warga_aws / $kapasitas_ideal) * 100;
                                        $rounded_persentase = intval($persentase_terpakai);
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo e($persentase_terpakai); ?>%;" aria-valuenow="<?php echo e($jumlah_warga_aws); ?>" aria-valuemin="0" aria-valuemax="<?php echo e($kapasitas_ideal); ?>">
                                            <?php echo e($rounded_persentase); ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card">
                <div class="card-body">

                    <div class="media">
                        <div class="avatar-sm font-size-20 mr-3">
                            <span class="avatar-title bg-soft-info text-primary rounded">
                                    <?php echo e($jumlah_warga_aspuri); ?>

                                </span>
                        </div>
                        <div class="media-body">
                            <h4 class="card-title mb-4 mt-2">DATA WARGA ASPURI</h4>
                        </div>
                    </div>

                    <div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Warga Percobaan</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-warcob-aspuri-modal-xl" class="mb-0"><?php echo e($jumlah_warga_percobaan_aspuri); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-warcob-aspuri-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Warga Percobaan ASPURI</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $wargaPercobaanASPURI = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Putri'],
                                                            ['status_warga', 'Warga Percobaan']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $wargaPercobaanASPURI; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">

                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Pengurus</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-pengurus-aspuri-modal-xl" class="mb-0"><?php echo e($jumlah_warga_pengurus_aspuri); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-pengurus-aspuri-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Pengurus ASPURI</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $pengurusASPURI = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Putri'],
                                                            ['status_warga', 'Pengurus Asrama']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $pengurusASPURI; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">

                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <p class="mb-2">Warga Tetap</p>
                                    <h4 type="button" data-toggle="modal" data-target=".bs-wartap-aspuri-modal-xl" class="mb-0"><?php echo e($jumlah_warga_tetap_aspuri); ?></h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-right">
                                        <div>
                                            <i class="mdi mdi-account-supervisor-outline text-secondary ml-1"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-xl-3">
                                <div class="modal fade bs-wartap-aspuri-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title mt-0" id="myExtraLargeModalLabel">List Warga Tetap ASPURI</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="table-responsive">
                                                    <?php
                                                        $wartapASPURI = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Putri'],
                                                            ['status_warga', 'Warga Tetap']
                                                        ])->get();
                                                    ?>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="card">
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <?php $__currentLoopData = $wartapASPURI; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-6">
                                                                            <div class="card" style="box-shadow: 4px 4px 8px 2px rgba(0, 0, 0, 0.2);">
                                                                                <div class="row no-gutters align-items-center">
                                                                                    <div class="col-md-2">
                                                                                        <img class="avatar-lg mx-auto img-thumbnail rounded-end" src="<?php echo e(asset('images/user.gif')); ?>" alt="Card image" height="40" width="40">

                                                                                    </div>
                                                                                    <div class="col-md-10">
                                                                                        <div class="card-body">
                                                                                            <a href="/super-warga-asrama-detail/<?php echo e($user->id); ?>" class="card-title"><?php echo e($user->name); ?></a>
                                                                                            <br>
                                                                                            <h6 class="card-text"><?php echo e($user->asrama); ?></h6>
                                                                                            <p class="card-text"><small class="text-muted"><?php echo e($user->status_warga); ?></small></p>

                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </div>
                                                                    <!-- end row -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                <!-- /.modal -->
                            </div>
                        </div>
                        <div class="pb-3 border-bottom mt-2" style="background: linear-gradient(to bottom, #0074b4, #0056a0); border-radius: 10px; text-align: center;">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <p class="mb-2 mt-2" style="color: white; font-size: 18px;">Kapasitas Ideal</p>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div>
                                        <h4 class="mb-2" style="color: white; font-size: 20px;">20</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row align-content-center">
                                <div class="col-12">
                                    <?php
                                        $jumlah_warga_aspuri = \App\User::where([
                                                            ['role', 'mahasiswa'],
                                                            ['asrama', 'Asrama Putri'],
                                                        ])->count();
                                        $kapasitas_ideal = 20;
                                        $persentase_terpakai = ($jumlah_warga_aspuri / $kapasitas_ideal) * 100;
                                        $rounded_persentase = intval($persentase_terpakai);
                                    ?>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo e($persentase_terpakai); ?>%;" aria-valuenow="<?php echo e($jumlah_warga_aspuri); ?>" aria-valuemin="0" aria-valuemax="<?php echo e($kapasitas_ideal); ?>">
                                            <?php echo e($rounded_persentase); ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card" style="background-color: rgb(5, 122, 176)">
                <div class="card-body">
                    <p><strong class="text-white">Eksekutif Summary</strong></p>
                    <form action="<?php echo e(route('super-eksekutif-summary')); ?>" method="GET">
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <label class="text-white" for="startDate">Tanggal Awal</label>
                                <input type="text" name="startDate" id="startDate" class="form-control datepicker" placeholder="YYYY-MM-DD" autocomplete="off">
                            </div>
                            <div class="col-md-6 col-6">
                                <label class="text-white" for="endDate">Tanggal Akhir</label>
                                <input type="text" name="endDate" id="endDate" class="form-control datepicker" placeholder="YYYY-MM-DD" autocomplete="off">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6 col-8">
                                <select name="asrama" class="form-control select2">
                                    <option>Pilih Asrama</option>
                                    <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Jati</option>
                                    <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                    <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                    <option value="Asrama Putri">Asrama Putri</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-2">
                                <button type="submit" class="btn btn-secondary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Data Warga Asrama</h4>

                    <div id="data_warga" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Data Alumni Asrama</h4>

                    <div id="data_alumni" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Kegiatan Asrama Terakhir:</h4>
                    <div style="overflow: scroll">
                        <div class="table-responsive">
                            <table class="table table-centered">
                                <thead>
                                    <tr>
                                        <th scope="col">Waktu</th>
                                        <th scope="col">Kegiatan</th>
                                        <th scope="col">Penyelenggara</th>
                                        <th scope="col">Tempat</th>
                                        <th scope="col">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $data_kegiatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="table-row" data-toggle="modal" data-target="#myModal<?php echo e($item->id); ?>">
                                        <td><?php echo e(date('d-m-Y', strtotime($item->waktu))); ?></td>
                                        <td><?php echo e($item->nama_kegiatan); ?></td>
                                        <td><?php echo e($item->penyelenggara); ?></td>
                                        <td><?php echo e($item->tempat); ?></td>
                                        <td><?php echo e($item->keterangan); ?></td>
                                    </tr>

                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal<?php echo e($item->id); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo e($item->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="myModalLabel<?php echo e($item->id); ?>"><?php echo e($item->nama_kegiatan); ?></h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Tanggal: <?php echo e(date('d-m-Y', strtotime($item->waktu))); ?></p>
                                                    <p>Penyelenggara: <?php echo e($item->penyelenggara); ?></p>
                                                    <p>Tempat: <?php echo e($item->tempat); ?></p>
                                                    <p>Keterangan: <?php echo e($item->keterangan); ?></p>
                                                    <img src="<?php echo e(asset('storage/data_file_kegiatan/' . $item->file)); ?>" class="img-thumbnail" style="height:auto; width:auto;" alt="Gambar kegiatan">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>

                            </table>
                            <?php echo e($data_kegiatan->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <!-- plugin js -->
    <script src="<?php echo e(URL::asset('libs/apexcharts/apexcharts.min.js')); ?>"></script>

    <!-- Calendar init -->
    <script src="<?php echo e(URL::asset('js/pages/dashboard.init.js')); ?>"></script>

    <!-- apexcharts -->
    <script src="<?php echo e(URL::asset('/libs/apexcharts/apexcharts.min.js')); ?>"></script>

    <!-- apexcharts init -->
    <script src="<?php echo e(URL::asset('/js/pages/apexcharts.init.js')); ?>"></script>

    <!-- Select2-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="<?php echo e(URL::asset('/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')); ?>"></script>

    <script>
        // Donut chart Data Alumni

        var options = {
            chart: {
                height: 320,
                type: 'donut',
            },
            series: [<?php echo e($data_alumni_asgj); ?>, <?php echo e($data_alumni_asg); ?>, <?php echo e($data_alumni_aws); ?>, <?php echo e($data_alumni_aspuri); ?>],
            labels: ["Alumni ASGJ", "Alumni ASG", "Alumni AWS", "Alumni ASPURI"],
            colors: ["#45cb85", "#3b5de7","#ff715b", "#0caadc"],
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
            document.querySelector("#data_alumni"),
            options
        );

        chart.render();
    </script>

    <script>
        // pie chart total
        var options = {
            chart: {
                height: 320,
                type: 'pie',
            },
            series: [<?php echo e($jumlah_warga_asgj); ?>, <?php echo e($jumlah_warga_asg); ?>, <?php echo e($jumlah_warga_aws); ?>, <?php echo e($jumlah_warga_aspuri); ?>],
            labels: ["Warga ASGJ", "Warga ASG", "Warga AWS", "Warga ASPURI"],
            colors: ["#45cb85", "#3b5de7", "#ff715b", "#eeb902"],
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
            document.querySelector("#data_warga"),
            options
        );

        chart.render();
    </script>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>

    <script>
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
        });

        $('#searchButton').click(function() {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            if (startDate && endDate) {
                filterTableByDateRange(startDate, endDate);
            } else {
                alert('Silahkan tanggal mulai dan tanggal akhirnya');
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/index.blade.php ENDPATH**/ ?>