<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <img class="navbar-brand-full app-header-logo" src="<?php echo e(asset('img/logo-yapi.png')); ?>" width="65"
             alt="Infyom Logo">
        <a href="<?php echo e(url('/')); ?>"></a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="<?php echo e(url('/')); ?>" class="small-sidebar-text">
            <img class="navbar-brand-full" src="<?php echo e(asset('img/logo-yapi.png')); ?>" width="45px" alt=""/>
        </a>
    </div>
    <ul class="sidebar-menu">
        <?php echo $__env->make('mentor.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </ul>
</aside>
<?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/mentor/pages/sidebar.blade.php ENDPATH**/ ?>