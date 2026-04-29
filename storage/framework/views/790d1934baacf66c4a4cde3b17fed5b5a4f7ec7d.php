<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/kegiatan-create" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Tambah Data</a>
                <a href="/kegiatan/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Kegiatan PDF</a>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="/kegiatan/asgj">ASGJ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/kegiatan/asg">ASG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/kegiatan/aws">AWS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/kegiatan/dqf">Asrama Putri</a>
                    </li>
                    
                </ul>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
    
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kegiatan</th>
                        <th>Jenis Kegiatan</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $kegiatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        
                        <td><?php echo e($index + $kegiatans->firstItem()); ?></td>
                        <td><?php echo e($kegiatan->nama_kegiatan); ?></td>
                        <td><?php echo e($kegiatan->jenis_kegiatan); ?></td>
                        <td><?php echo e($kegiatan->waktu); ?></td>
                        <td><?php echo e($kegiatan->tempat); ?></td>
                        <td>                           
                            <form action="/kegiatan-delete/<?php echo e($kegiatan->id); ?>" method="POST">
                                <a href="/kegiatan-detail/<?php echo e($kegiatan->id); ?>" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                                <a href="/kegiatan-edit/<?php echo e($kegiatan->id); ?>" class="btn btn-icon btn-primary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Edit" ><i class="fas fa-edit"></i></a>
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            Jumlah Data: <?php echo e($kegiatans->total()); ?><br/>

            <?php echo e($kegiatans->links()); ?>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/kegiatan/aws.blade.php ENDPATH**/ ?>