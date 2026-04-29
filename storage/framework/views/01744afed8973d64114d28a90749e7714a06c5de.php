

<?php $__env->startSection('title'); ?>
    Profile
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- Plugin css -->
    <link href="<?php echo e(URL::asset('/libs/fullcalendar/fullcalendar.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('mentor.common-components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
            Profile
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('li_1'); ?>
            Pages
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>


    <!-- start row -->
    <div class="row">
        <div class="col-12">
            <?php if(session()->has('success-akademik')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Kegiatan Akademik berhasil dibuat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if(session()->has('success-leadership')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Kegiatan Leadership berhasil dibuat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if(session()->has('success-karakter')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Kegiatan Karakter Islami berhasil dibuat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if(session()->has('success-kreatif')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Kegiatan Kreativitas & Kewirausahaan berhasil dibuat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if(session()->has('success-ip')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data Index Prestasi Akademik berhasil dibuat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if(session()->has('info-foto')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Edit!</strong> Foto Profile berhasil diperbarui.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if(session()->has('info-status')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Edit!</strong> Data Status Warga berhasil diperbarui.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if(session()->has('info-personal')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Edit!</strong> Data Informasi Personal berhasil diperbarui.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if(session()->has('info-ip')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Edit!</strong> Data Informasi Personal berhasil diperbarui.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if(session()->has('info-khs')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Update!</strong> File Kartu Hasil Studi berhasil diupdate.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-12 col-xl-3">
            <div class="card">
                
                
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Update Foto</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/mentor-profile-foto-update/<?php echo e(Auth::user()->id); ?>" method="POST" enctype="multipart/form-data">                            
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <input type="file" name="avatar" required="required" id="image">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <img id="preview-image-before-upload" src="https://static.vecteezy.com/system/resources/previews/005/337/799/original/icon-image-not-found-free-vector.jpg"
                                                alt="preview image" style="max-height: 250px;">
                                        </div>
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary" id="sa-position">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                </div>

                

                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="modal fade bs-info-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Update Personal Information</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="/mentor-profile-info-update/<?php echo e(Auth::user()->id); ?>" method="POST" enctype="multipart/form-data">
                                    <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <h5 style="text-align: center" class="mb-4"><strong>DATA PERSONAL</strong></h5>
                                            <p class="badge badge-soft-secondary font-size-12">Perhatikan tanda (<span style="color: red">*</span>) pada form wajib diisi!</p>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="asrama">Asrama:</label>
                                                            <select class="form-control <?php $__errorArgs = ['asrama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="asrama">
                                                                <option value="<?php echo e(Auth::user()->asrama); ?>"><?php echo e(Auth::user()->asrama); ?></option>
                                                                <option value="Asrama Sunan Gunung Jati">Asrama Sunan Gunung Djati</option>
                                                                <option value="Asrama Sunan Giri">Asrama Sunan Giri</option>
                                                                <option value="Asrama Wali Songo">Asrama Wali Songo</option>
                                                                <option value="Asrama Putri">Asrama Putri</option>
                                                            </select>
                                                        </div>
                                                        <?php $__errorArgs = ['asrama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                    
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="no_induk">Nomor Induk Warga<span style="color: red">*</span>:</label>
                                                            <input type="number" class="form-control <?php $__errorArgs = ['no_induk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="no_induk" value="<?php echo e(Auth::user()->no_induk); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['no_induk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
        
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tgl_masuk">Tanggal Masuk<span style="color: red">*</span>:</label>
                                                            <input type="date" class="form-control <?php $__errorArgs = ['tgl_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="tgl_masuk" value="<?php echo e(Auth::user()->tgl_masuk); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['tgl_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
        
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="nik">NIK<span style="color: red">*</span>:</label>
                                                            <input type="number" class="form-control <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="nik" value="<?php echo e(Auth::user()->nik); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['nik'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
        
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tgl_lahir">Tanggal Lahir<span style="color: red">*</span>:</label>
                                                            <input type="date" class="form-control <?php $__errorArgs = ['tgl_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="tgl_lahir" value="<?php echo e(Auth::user()->tgl_lahir); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['tgl_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
        
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="alamat">Alamat Jalan<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="alamat" value="<?php echo e(Auth::user()->alamat); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="kecamatan">Kecamatan<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['kecamatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="kecamatan" value="<?php echo e(Auth::user()->kecamatan); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['kecamatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="kota">Kota/Kabupaten<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['kota'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="kota" value="<?php echo e(Auth::user()->kota); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['kota'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="provinsi">Provinsi<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['provinsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="provinsi" value="<?php echo e(Auth::user()->provinsi); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['provinsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="kode_pos">Kode POS<span style="color: red">*</span>:</label>
                                                            <input type="number" class="form-control <?php $__errorArgs = ['kode_pos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="kode_pos" value="<?php echo e(Auth::user()->kode_pos); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['kode_pos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
        
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="no_telp">Nomor Telepon<span style="color: red">*</span>:</label>
                                                            <input type="number" class="form-control <?php $__errorArgs = ['no_telp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="no_telp" value="<?php echo e(Auth::user()->no_telp); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['no_telp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
        
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="asal_sekolah">Asal Sekolah<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['asal_sekolah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="asal_sekolah" value="<?php echo e(Auth::user()->asal_sekolah); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['asal_sekolah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
        
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="nama_ayah">Nama Ayah<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['nama_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="nama_ayah" value="<?php echo e(Auth::user()->nama_ayah); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['nama_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
        
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="nama_ibu">Nama Ibu<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['nama_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="nama_ibu" value="<?php echo e(Auth::user()->nama_ibu); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['nama_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <h5 style="text-align: center" class="mb-4"><strong>DATA PENDIDIKAN</strong></h5>
                                            <p class="badge badge-soft-secondary font-size-12">Perhatikan tanda (<span style="color: red">*</span>) pada form wajib diisi!</p>
                                            <div class="form-group">
                                                <div class="row">                
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="universitas">Universitas<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['universitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="universitas" value="<?php echo e(Auth::user()->universitas); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['universitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="fakultas">Fakultas<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['fakultas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="fakultas" value="<?php echo e(Auth::user()->fakultas); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['fakultas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="prodi">Program Studi<span style="color: red">*</span>:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['prodi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="prodi" value="<?php echo e(Auth::user()->prodi); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['prodi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="angkatan">Tahun Angkatan<span style="color: red">*</span>:</label>
                                                            <input type="number" class="form-control <?php $__errorArgs = ['angkatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="angkatan" value="<?php echo e(Auth::user()->angkatan); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['angkatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tgl_seminar">Tanggal Seminar Skripsi:</label>
                                                            <input type="date" class="form-control <?php $__errorArgs = ['tgl_seminar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="tgl_seminar" value="<?php echo e(Auth::user()->tgl_seminar); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['tgl_seminar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tgl_skripsi">Tanggal Sidang Skripsi:</label>
                                                            <input type="date" class="form-control <?php $__errorArgs = ['tgl_skripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="tgl_skripsi" value="<?php echo e(Auth::user()->tgl_skripsi); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['tgl_seminar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="tgl_wisuda">Tanggal Wisuda:</label>
                                                            <input type="date" class="form-control <?php $__errorArgs = ['tgl_wisuda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="tgl_wisuda" value="<?php echo e(Auth::user()->tgl_wisuda); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['tgl_wisuda'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="prestasi">Prestasi:</label>
                                                            <input type="text" class="form-control <?php $__errorArgs = ['prestasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="prestasi" value="<?php echo e(Auth::user()->prestasi); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['prestasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="organisasi">Organisasi<span style="color: red">*</span>:</label>
                                                            
                                                            <input type="text" class="form-control <?php $__errorArgs = ['organisasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="organisasi" value="<?php echo e(Auth::user()->organisasi); ?>">
                                                        </div>
                                                        <?php $__errorArgs = ['organisasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong><?php echo e($message); ?></strong>
                                                            </span>
                                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary" id="sa-position">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                </div>

                <div class="card-body">
                    <div class="profile-widgets py-3">

                        <div class="text-center">
                            <div class="btn" data-toggle="modal" data-target=".bs-example-modal-center">
                                <img src="<?php echo e(url('/data_photo/' . Auth::user()->avatar)); ?>" alt=""
                                    class="avatar-lg mx-auto img-thumbnail rounded-circle">
                            </div>

                            <div class="mt-3 ">
                                <p class="text-dark font-weight-medium font-size-16"><?php echo e(Auth::user()->name); ?></p>
                                <p class="text-body mb-1"><?php echo e(Auth::user()->asrama); ?></p>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <div class="float-right">
                        <a href="/mentor-profile-info-edit/<?php echo e(Auth::user()->id); ?>" class="btn btn-outline-primary" data-toggle="modal" data-target=".bs-info-modal-xl">
                            <i class="mdi mdi-account-edit-outline font-size-16"></i></a>
                    </div>
                    <br>
                    <br>
                    <h5 class="card-title mb-3">Personal Information</h5>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Email</p>
                        <h6 class=""><?php echo e(Auth::user()->email); ?></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Tanggal Lahir</p>
                        <h6 class=""><?php echo e(date('d-m-Y', strtotime(Auth::user()->tgl_lahir))); ?></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Nomor Telepon</p>
                        <h6 class=""><?php echo e(Auth::user()->no_telp); ?></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Alamat Asal</p>
                        <h6 class=""><?php echo e(Auth::user()->alamat); ?></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Kecamatan</p>
                        <h6 class=""><?php echo e(Auth::user()->kecamatan); ?></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Kota/Kabupaten</p>
                        <h6 class=""><?php echo e(Auth::user()->kota); ?></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Provinsi</p>
                        <h6 class=""><?php echo e(Auth::user()->provinsi); ?></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Kampus</p>
                        <h6 class=""><?php echo e(Auth::user()->universitas); ?></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Fakultas</p>
                        <h6 class=""><?php echo e(Auth::user()->fakultas); ?></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Program Studi</p>
                        <h6 class=""><?php echo e(Auth::user()->prodi); ?></h6>
                    </div>

                    <div class="mt-3">
                        <p class="font-size-12 text-muted mb-1">Organisasi</p>
                        <h6 class=""><?php echo e(Auth::user()->organisasi); ?></h6>
                    </div>

                </div>
            </div>

        </div>

        <div class="col-md-12 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <div id='calendar'></div>

                    <div style='clear:both'></div>
                </div>
            </div>
        </div>

    </div>

    <!-- end row -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

    <!-- plugin js -->
    <script src="<?php echo e(URL::asset('/libs/moment/moment.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/fullcalendar/fullcalendar.min.js')); ?>"></script>

    <!-- Calendar init -->
    <script src="<?php echo e(URL::asset('/js/pages/calendar.init.js')); ?>"></script>

    <script>
        $(document).ready(function () {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay'
                },
                defaultDate: new Date(),
                editable: true,
                eventLimit: true, // Allow "more" link when too many events,
                selectable: true,
                select: function (start, end) {
                    var title = prompt('Masukkan judul event:');
                    if (title) {
                        $('#calendar').fullCalendar('renderEvent', {
                            title: title,
                            start: start,
                            end: end,
                            allDay: false
                        }, true); // Tambahkan event ke kalender
                    }
                    $('#calendar').fullCalendar('unselect');
                },
                eventClick: function (event) {
                    var title = prompt('Ubah judul event:', event.title);
                    if (title) {
                        event.title = title;
                        $('#calendar').fullCalendar('updateEvent', event);
                    }
                },
                eventDrop: function (event, delta, revertFunc) {
                    // Saat event di-drop
                }
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('mentor.layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/mentor/index.blade.php ENDPATH**/ ?>