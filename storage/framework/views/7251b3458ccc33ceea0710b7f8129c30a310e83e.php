

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Akademik Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="/mentor/penilaian/akademik/asgj">ASGJ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mentor/penilaian/akademik/asg">ASG</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mentor/penilaian/akademik/aws">AWS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/mentor/penilaian/akademik/dqf">Asrama Putri</a>
                </li>
            </ul>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-akademik">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Warga</th>
                        <th>Kegiatan</th>
                        <th>Waktu</th>
                        <th>Nama Penilai</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $akademiks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $akademik): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($akademik->nama_warga); ?></td>
                        <td><?php echo e($akademik->kegiatan); ?></td>
                        <td><?php echo e($akademik->waktu); ?></td>
                        <td><?php echo e($akademik->nama_penilai); ?></td>
                        <td><?php echo e($akademik->nilai); ?></td>
                        <td>
                            <a href="/mentor/penilaian/akademik/detail/<?php echo e($akademik->id); ?>" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                            <a href="/mentor/penilaian/akademik/edit/<?php echo e($akademik->id); ?>" class="btn btn-icon btn-success btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Nilai" ><i class="fas fa-file-signature"></i></a>                                                 
                        </td>
                    </tr>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mentor.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/mentor/pages/akademik/penilaian_index.blade.php ENDPATH**/ ?>