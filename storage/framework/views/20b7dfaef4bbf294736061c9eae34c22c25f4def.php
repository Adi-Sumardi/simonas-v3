

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Direktur & Ketua Asrama YAPI</h3>
    </div>
    <div class="section-body">
        <div class="row">
        </div>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="/alumni/asrama/asgj">ASGJ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/alumni/asrama/asg">ASG</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/alumni/asrama/aws">AWS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/alumni/asrama/dqf">Asrama Putri</a>
            </li>
        </ul>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            <div class="row">
            </div> 
            <table class="table table-bordered table-striped table-hover" id="data-asrama">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Asrama</th>
                        <th>Tahun Jabatan</th>
                        <th>Nama Direktur</th>
                        <th>Nama Ketua</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $asramas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asrama): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($asrama->nama_asrama); ?></td>
                        <td><?php echo e($asrama->tahun_jabatan); ?></td>
                        <td><?php echo e($asrama->direktur); ?></td>
                        <td><?php echo e($asrama->ketua); ?></td>
                    </tr>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('alumni.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/alumni/pages/asrama_aws.blade.php ENDPATH**/ ?>