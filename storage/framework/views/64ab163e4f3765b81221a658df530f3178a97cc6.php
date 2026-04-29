<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h3 class="page__heading">Kartu Hasil Penilaian Warga Asrama</h3>
    </div>
    <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs shadow-sm p-3 mb-5 bg-white rounded">
                        <li class="nav-item">
                            <a class="nav-link active" href="/kartu/asgj">ASGJ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/kartu/asg">ASG</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/kartu/aws">AWS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/kartu/dqf">Asrama Putri</a>
                        </li>
                    </ul>
                </div>
            </div>
        <div class="col-12 col-md-12 col-lg-12" style="overflow: scroll;">

            <div class="container">
                <div class="row mb-3">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6">
                        <div class="d-flex flex-row border rounded">
                              <div class="card-image p-0 w-25">
                                  <img src="<?php echo e(url ('/uploads/avatars/'.$user->avatar)); ?>" class="img-thumbnail border-0" />
                                  
                              </div>
                              <div class="pl-3 pt-2 pr-2 pb-2 w-75 border-left">
                                      <h4 class="text-primary"><?php echo e($user->name); ?></h4>
                                      <h5 class="text-info"><?php echo e($user->asrama); ?></h5>
                                      <ul class="m-0 float-left" style="list-style: none; margin:0; padding: 0">
                                          <li><i class="card-universitas far fa-building"></i><?php echo e($user->universitas); ?></li>
                                      </ul>
                                    <p class="text-right m-0"><a href="/kartu/detail/<?php echo e($user->id); ?>" class="btn btn-primary"><i class="far fa-user"></i> View Detail</a></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                </div>

                <?php echo e($users->links()); ?>

            
            </div>
            
        </div> 
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/kartu/index.blade.php ENDPATH**/ ?>