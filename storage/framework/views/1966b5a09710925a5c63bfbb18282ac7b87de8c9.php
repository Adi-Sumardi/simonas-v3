

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kegiatan Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="/mentor/kegiatan/asgj">ASGJ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/mentor/kegiatan/asg">ASG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/mentor/kegiatan/aws">AWS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/mentor/kegiatan/dqf">Asrama Putri</a>
                    </li>
                    
                </ul>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
    
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-kegiatan">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Kegiatan</th>
                        <th>Jenis Kegiatan</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $kegiatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kegiatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($kegiatan->nama_kegiatan); ?></td>
                        <td><?php echo e($kegiatan->jenis_kegiatan); ?></td>
                        <td><?php echo e($kegiatan->waktu); ?></td>
                        <td><?php echo e($kegiatan->tempat); ?></td>
                        
                    </tr>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mentor.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/mentor/pages/kegiatan/dqf.blade.php ENDPATH**/ ?>