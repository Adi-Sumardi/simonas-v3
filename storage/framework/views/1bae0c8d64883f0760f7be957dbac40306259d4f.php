<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Data Asrama YAPI</h3>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <a href="/asrama-create" class="btn btn-icon icon-left btn-success"><i class="fas fa-plus"></i> Tambah Data</a>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" href="/asrama/asgj">ASGJ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/asrama/asg">ASG</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/asrama/aws">AWS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/asrama/dqf">Asrama Putri</a>
                    </li>
                </ul>
            </div>
        </div>
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
                        <th>Aksi</th>
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
                        <td>                            
                            <form action="/asrama-delete/<?php echo e($asrama->id); ?>" method="POST">
                                <a href="/asrama-edit/<?php echo e($asrama->id); ?>" class="btn btn-icon btn-primary btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Edit" ><i class="fas fa-edit"></i></a>
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                               <button type="submit" class="btn btn-danger btn-action btn-sm" data-toggle="tooltip" title="" data-original-title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/direktur-ketua/asg.blade.php ENDPATH**/ ?>