<div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-8">
                                <div>
                                    <p class="text-muted font-weight-medium mt-1 mb-2"><?php echo e($title); ?></p>
                                    <h4><?php echo e($total); ?></h4>
                                </div>
                            </div>

                            <div class="col-4">
                                <div>
                                    <div id="<?php echo e($chartId); ?>"></div>
                                </div>
                            </div>
                        </div>

                        <p class="mb-0"><span class="badge badge-soft-success mr-2"> <?php echo e($percentage); ?> <i class="mdi mdi-arrow-up"></i> </span> From previous period</p>
                    </div>
                </div>
                   <?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/mentor/common-components/dashboard2-widget.blade.php ENDPATH**/ ?>