<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Kreatifitas dan Wirausaha Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/kreatif/cetak_pdf" class="btn btn-icon icon-left btn-info"><i class="fas fa-download"></i> Download Data Penilaian Kreatifitas PDF</a>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="/penilaian/kreatif/asgj">ASGJ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/penilaian/kreatif/asg">ASG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/penilaian/kreatif/aws">AWS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/penilaian/kreatif/dqf">Asrama Putri</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            <div class="row">    
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-kreatif">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Warga</th>
                        <th>Komponen</th>
                        <th>Waktu</th>
                        <th>Nama Penilai</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $kreatifs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kreatif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($kreatif->nama_warga); ?></td>
                        <td><?php echo e($kreatif->komponen); ?></td>
                        <td><?php echo e($kreatif->waktu); ?></td>
                        <td><?php echo e($kreatif->nama_penilai); ?></td>
                        <td><?php echo e($kreatif->nilai); ?></td>
                        <td>                            
                                <a href="/penilaian/kreatif/detail/<?php echo e($kreatif->id); ?>" class="btn btn-icon btn-secondary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Detail" ><i class="fas fa-info-circle"></i></a>
                                <a href="/penilaian/kreatif/edit/<?php echo e($kreatif->id); ?>" class="btn btn-icon btn-success btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Nilai" ><i class="fas fa-file-signature"></i></a>
                                
                        </td>
                    </tr>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div> 
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/kreatif/penilaian_asg.blade.php ENDPATH**/ ?>