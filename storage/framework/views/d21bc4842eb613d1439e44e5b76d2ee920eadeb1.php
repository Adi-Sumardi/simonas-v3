

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">History Penilaian Leadership Warga Asrama</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                    <a class="nav-link" href="/mentor/history/akademik">Akademik</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link active" href="/mentor/history/leadership">Leadership</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="/mentor/history/karakter">Karakter</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="/mentor/history/kreatif">Kreatif</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card card-primary col-12 col-md-12 col-lg-12" style="overflow: scroll;">
            <div class="row">
            </div>
            <table class="table table-bordered table-striped table-hover" id="data-leadership">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Warga</th>
                        <th>Komponen Pengaderan</th>
                        <th>Nama Penilai</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $leaderships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leadership): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($leadership->nama_warga); ?></td>
                        <td><?php echo e($leadership->komponen); ?></td>
                        <td><?php echo e($leadership->nama_penilai); ?></td>
                        <td><?php echo e($leadership->nilai); ?></td>
                        
                    </tr>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div> 
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mentor.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/mentor/pages/history/leadership_index.blade.php ENDPATH**/ ?>