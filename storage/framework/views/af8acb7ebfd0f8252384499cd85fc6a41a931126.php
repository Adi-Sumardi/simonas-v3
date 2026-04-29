<?php $__env->startSection('title'); ?>
    Alumni Asrama Create
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>

    <!-- DataTables -->
    <link href="<?php echo e(URL::asset('/libs/datatables/datatables.min.css')); ?>" rel="stylesheet" type="text/css" />

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('super.common-components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
        Alumni Asrama Create
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title_li'); ?>
        Alumni Asrama Create
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <H4>Data Pribadi</H4>
                        <form method="POST" action="/super-alumni-asrama-store" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="alumni_academic" value="" id="alumni_academic" />
                            <input type="hidden" name="alumni_organization" value="" id="alumni_organization" />
                            <input type="hidden" name="alumni_job_history" value="" id="alumni_job_history" />
                            <input type="hidden" name="alumni_achievement" value="" id="alumni_achievement" />
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama">Nama Alumni</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="nama" required>
                                    </div>
                                    <?php $__errorArgs = ['nama'];
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nia">NIA</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['nia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="nia" required>
                                    </div>
                                    <?php $__errorArgs = ['nia'];
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="email" required>
                                    </div>
                                    <?php $__errorArgs = ['email'];
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="foto">FOTO</label>
                                        <input type="file" name="foto" accept="image/*" required>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_whatsapp">No. HP/Whatsapp</label>
                                        <input type="tel" class="form-control <?php $__errorArgs = ['no_whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="no_whatsapp" required>
                                    </div>
                                    <?php $__errorArgs = ['no_whatsapp'];
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="provinsi_asal">Tempat Lahir</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['provinsi_asal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="provinsi_asal">
                                    </div>
                                    <?php $__errorArgs = ['provinsi_asal'];
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" class="form-control <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="tanggal_lahir">
                                    </div>
                                    <?php $__errorArgs = ['tanggal_lahir'];
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="provinsi"
                                            class="control-label">Provinsi</label><span
                                            class="text-danger">
                                        <select name="id_province" class="form-control <?php echo e($errors->has('id_province') ? ' is-invalid': ''); ?>">
                                            <option value="">Pilih Provinsi</option>
                                            <?php $__currentLoopData = $province; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($province->id); ?>"><?php echo e($province->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <?php $__errorArgs = ['id_province'];
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_regency"
                                            class="control-label">Kota/Kabupaten</label><span
                                            class="text-danger">
                                        <select name="id_district" class="form-control <?php echo e($errors->has('id_district') ? ' is-invalid': ''); ?>">
                                            <option value="">Pilih Kecamatan</option>
                                            <?php if(isset($selectedProvince)): ?> <!-- Cek apakah provinsi sudah dipilih -->
                                                <?php $__currentLoopData = $districts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $district): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($district); ?>"><?php echo e($district); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                    <?php $__errorArgs = ['id_regency'];
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="alamat_domisili">Alamat Lengkap</label>
                                        <textarea class="form-control <?php $__errorArgs = ['alamat_domisili'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="alamat_domisili"></textarea>
                                    </div>
                                    <?php $__errorArgs = ['alamat_domisili'];
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="kode_pos">Kode Pos</label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['kode_pos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="kode_pos">
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
                            </div>

                            <br/>

                            <H4>Data Asrama</H4>
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tahun_masuk_asrama">Tahun Masuk</label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['tahun_masuk_asrama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="tahun_masuk_asrama" max="<?php echo e(Date('Y')); ?>">
                                    </div>
                                    <?php $__errorArgs = ['tahun_masuk_asrama'];
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tahun_keluar_asrama">Tahun Keluar</label>
                                        <input type="number" class="form-control <?php $__errorArgs = ['tahun_keluar_asrama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="tahun_keluar_asrama" max="<?php echo e(Date('Y')); ?>">
                                    </div>
                                    <?php $__errorArgs = ['tahun_keluar_asrama'];
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Daftar Asrama"
                                            class="control-label">Daftar Asrama</label><span
                                            class="text-danger">
                                        <select name="id_asrama" class="form-control <?php echo e($errors->has('id_asrama') ? ' is-invalid': ''); ?>" required>
                                            <option value="">Pilih Asrama</option>
                                            <?php $__currentLoopData = $asrama; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($row->id); ?>"><?php echo e($row->nama_asrama); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <?php $__errorArgs = ['id_asrama'];
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jabatan_asrama">Jabatan Terakhir</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['jabatan_asrama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="jabatan_asrama">
                                    </div>
                                    <?php $__errorArgs = ['jabatan_asrama'];
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="teman_angkatan">Teman 1 Angkatan</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['teman_angkatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            name="teman_angkatan">
                                    </div>
                                    <?php $__errorArgs = ['teman_angkatan'];
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
                                    <span class="invalid-feedback" style="display: block; color: #a0a0a0">
                                        <strong>Tulis Nama Teman lebih dari 1, pisahkan dengan @ (Tag)</strong>
                                    </span>
                                </div>
                            </div>

                            <br/>

                            <H4>Riwayat Pendidikan</H4>
                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".modal-academic">
                                    <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                                </button>
                            </div>
                            <br/>
                            <div style="overflow: scroll; overflow-x: hidden;">
                                <table class="dt-academic datatable-rmodz table table-striped table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Jenjang Pendidikan</th>
                                            <th>Nama Universitas</th>
                                            <th>Fakultas/Jurusan</th>
                                            <th>Tahun Masuk-Lulus</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <br/>

                            <H4>Pengalaman Organisasi</H4>
                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal"      data-target=".modal-organization">
                                    <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                                </button>
                            </div>
                            <br/>
                            <div style="overflow: scroll; overflow-x: hidden;">
                                <table class="dt-organization datatable-rmodz table table-striped table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Jenis Organisasi</th>
                                            <th>Nama Organisasi</th>
                                            <th>Jabatan</th>
                                            <th>Tahun</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <br/>

                            <H4>Riwayat Pekerjaan</H4>
                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".modal-job">
                                    <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                                </button>
                            </div>
                            <br/>
                            <div style="overflow: scroll; overflow-x: hidden;">
                                <table class="dt-job datatable-rmodz table table-striped table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Jabatan</th>
                                            <th>Nama Perusahaan</th>
                                            <th>Tahun</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <br/>

                            <H4>Riwayat Prestasi/Penghargaan</H4>
                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-primary waves-effect waves-light" data-toggle="modal" data-target=".modal-achievement">
                                    <i class="mdi mdi-plus-thick font-size-16 align-middle mr-2"></i> Add Data
                                </button>
                            </div>
                            <br/>
                            <div style="overflow: scroll; overflow-x: hidden;">
                                <table class="dt-achievement datatable-rmodz table table-striped table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Prestasi atau Penghargaan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                            <br/>

                            <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary" id="sa-position">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-center modal-academic" tabindex="-1" role="dialog" aria-labelledby="input-academic" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Input Riwayat Pendidikan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="gelar" class="control-label">Jenjang Pendidikan</label>
                            <select name="gelar" class="form-control">
                                <option value="S1">S1 - Sarjana</option>
                                <option value="S2">S2 - Magister</option>
                                <option value="S3">S3 - Doktor</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama_kampus">Nama Universitas</label>
                            <input type="text" class="form-control" name="nama_kampus">
                        </div>
                        <div class="form-group">
                            <label for="fakultas_jurusan">Fakultas / Jurusan</label>
                            <input type="text" class="form-control" name="fakultas_jurusan">
                        </div>
                        <div class="form-group">
                            <label for="tahun_ajaran">Tahun Masuk - Lulus</label>
                            <input type="text" class="form-control" name="tahun_ajaran" placeholder="contoh : 2013-2017">
                        </div>
                    </div>
                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" id="sa-position" onclick="saveData('.modal-academic')">Submit</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-center modal-organization" tabindex="-1" role="dialog" aria-labelledby="input-organization" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Input Riwayat Organisasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="type_organization" class="control-label">Jenis Organisasi</label>
                            <select name="type_organization" class="form-control">
                                <option value="Kemahasiswaan">Kemahasiswaan</option>
                                <option value="Kemasyarakatan">Kemasyarakatan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nama_organisasi">Nama Organisasi</label>
                            <input type="text" class="form-control" name="nama_organisasi">
                        </div>
                        <div class="form-group">
                            <label for="organisasi_jabatan">Jabatan</label>
                            <input type="text" class="form-control" name="organisasi_jabatan">
                        </div>
                        <div class="form-group">
                            <label for="tahun_organisasi">Tahun</label>
                            <input type="text" class="form-control" name="tahun_organisasi" placeholder="contoh tahun 2001-2002">
                        </div>
                    </div>
                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" id="sa-position" onclick="saveData('.modal-organization')">Submit</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-center modal-job" tabindex="-1" role="dialog" aria-labelledby="input-job" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Input Riwayat Pekerjaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="nama_jabatan">Nama jabatan</label>
                            <input type="text" class="form-control" name="nama_jabatan">
                        </div>
                        <div class="form-group">
                            <label for="bidang_pekerjaan">Nama Perusahaan</label>
                            <input type="text" class="form-control" name="bidang_pekerjaan">
                        </div>
                        <div class="form-group">
                            <label for="tahun_kerjaan">Tahun </label>
                            <input type="text" class="form-control" name="tahun_kerjaan" placeholder="Contoh tahun 2001-2002">
                        </div>
                    </div>
                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" id="sa-position" onclick="saveData('.modal-job')">Submit</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade bs-example-modal-center modal-achievement" tabindex="-1" role="dialog" aria-labelledby="input-achievement" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Input Riwayat Prestasi/Penghargaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="in-" class="form-group">
                        <div class="form-group">
                            <label for="nama_penghargaan">Prestasi/Penghargaan</label>
                            <input type="text" class="form-control" name="nama_penghargaan">
                        </div>
                    </div>
                    <div class="button-items d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" id="sa-position" onclick="saveData('.modal-achievement')">Submit</button>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

    <!-- Required datatable js -->
    <script src="<?php echo e(URL::asset('/libs/datatables/datatables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/jszip/jszip.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/libs/pdfmake/pdfmake.min.js')); ?>"></script>

    <!-- Datatable init js -->
    <script src="<?php echo e(URL::asset('/js/pages/datatables.init.js')); ?>"></script>

    <script>
        var dt_academic = $(".dt-academic").DataTable();
        var dt_organization = $(".dt-organization").DataTable();
        var dt_job = $(".dt-job").DataTable();
        var dt_achievement = $(".dt-achievement").DataTable();

        var renderTableAcademic = function() {
            dt_academic.clear().draw();
            var val_academic = $("#alumni_academic").val() ? JSON.parse($("#alumni_academic").val()) : [];
            val_academic.forEach((val, i) => {
                dt_academic.row.add([
                    i+1,
                    val.gelar,
                    val.nama_kampus,
                    val.fakultas_jurusan,
                    val.tahun_ajaran,
                    `<button type="button" class="btn btn-sm btn-danger btn-action" onclick="delAcademic(${i})"><i class="fas fa-trash-alt"></i></button>`
                ]);
            });
            dt_academic.draw();
        }

        var renderTableOrganization = function() {
            dt_organization.clear().draw();
            var val_organization = $("#alumni_organization").val() ? JSON.parse($("#alumni_organization").val()) : [];
            val_organization.forEach((val, i) => {
                dt_organization.row.add([
                    i+1,
                    val.type_organization,
                    val.nama_organisasi,
                    val.organisasi_jabatan,
                    val.tahun_organisasi,
                    `<button type="button" class="btn btn-sm btn-danger btn-action" onclick="delOrganization(${i})"><i class="fas fa-trash-alt"></i></button>`
                ]);
            });
            dt_organization.draw();
        }

        var renderTableJob = function() {
            dt_job.clear().draw();
            var val_job_history = $("#alumni_job_history").val() ? JSON.parse($("#alumni_job_history").val()) : [];
            val_job_history.forEach((val, i) => {
                dt_job.row.add([
                    i+1,
                    val.nama_jabatan,
                    val.bidang_pekerjaan,
                    val.tahun_kerjaan,
                    `<button type="button" class="btn btn-sm btn-danger btn-action" onclick="delJob(${i})"><i class="fas fa-trash-alt"></i></button>`
                ]);
            });
            dt_job.draw();
        }

        var renderTableachievement = function() {
            dt_achievement.clear().draw();
            var val_achievement = $("#alumni_achievement").val() ? JSON.parse($("#alumni_achievement").val()) : [];
            console.log({val_achievement})
            val_achievement.forEach((val, i) => {
                dt_achievement.row.add([
                    i+1,
                    val.nama_penghargaan,
                    `<button type="button" class="btn btn-sm btn-danger btn-action" onclick="delAchievement(${i})"><i class="fas fa-trash-alt"></i></button>`
                ]);
            });
            dt_achievement.draw();
        }

        var delAcademic = function(index) {
            var val_academic = $("#alumni_academic").val() ? JSON.parse($("#alumni_academic").val()) : [];
            // dt_academic.row(index).remove().draw();
            console.log(val_academic);
            val_academic.splice(index, 1);
            console.log(val_academic);
            $("#alumni_academic").val(JSON.stringify(val_academic));
            renderTableAcademic();

        }
        var delOrganization = function(index) {
            var val_organization = $("#alumni_organization").val() ? JSON.parse($("#alumni_organization").val()) : [];
            // dt_organization.row(index).remove().draw();
            console.log(val_organization);
            val_organization.splice(index, 1);
            console.log(val_organization);
            $("#alumni_organization").val(JSON.stringify(val_organization));
            renderTableOrganization();

        }
        var delJob = function(index) {
            var val_job_history = $("#alumni_job_history").val() ? JSON.parse($("#alumni_job_history").val()) : [];
            // dt_job.row(index).remove().draw();
            console.log(val_job_history);
            val_job_history.splice(index, 1);
            console.log(val_job_history);
            $("#alumni_job_history").val(JSON.stringify(val_job_history));
            renderTableJob();

        }
        var delAchievement = function(index) {
            var val_achievement = $("#alumni_achievement").val() ? JSON.parse($("#alumni_achievement").val()) : [];
            // dt_achievement.row(index).remove().draw();
            console.log(val_achievement);
            val_achievement.splice(index, 1);
            $("#alumni_achievement").val(JSON.stringify(val_achievement));
            console.log("DARI HTML", $("#alumni_achievement").val());
            renderTableachievement();

        }

        var saveData = function(className) {
            var serialize = $(className).find("input").serializeArray();
            var serializeOption = $(className).find("select").serializeArray();
            serialize = serialize.concat(serializeOption);
            var data = {};
            serialize.forEach((val, i) => {
                data[val.name] = val.value;
            });
            data['id'] = '';
            switch (className) {
                case ".modal-academic":
                    var val_academic = $("#alumni_academic").val() ? JSON.parse($("#alumni_academic").val()) : [];
                    val_academic.push(data);
                    console.log(val_academic);
                    $("#alumni_academic").val(JSON.stringify(val_academic));
                    renderTableAcademic();
                    break;
                case ".modal-organization":
                    var val_organization = $("#alumni_organization").val() ? JSON.parse($("#alumni_organization").val()) : [];
                    val_organization.push(data);
                    $("#alumni_organization").val(JSON.stringify(val_organization));
                    renderTableOrganization();
                    break;
                case ".modal-job":
                    var val_job_history = $("#alumni_job_history").val() ? JSON.parse($("#alumni_job_history").val()) : [];
                    val_job_history.push(data);
                    $("#alumni_job_history").val(JSON.stringify(val_job_history));
                    renderTableJob();
                    break;
                case ".modal-achievement":
                    var val_achievement = $("#alumni_achievement").val() ? JSON.parse($("#alumni_achievement").val()) : [];
                    console.log(val_achievement);
                    val_achievement.push(data);
                    $("#alumni_achievement").val(JSON.stringify(val_achievement));
                    renderTableachievement();
                    break;
            }
            $('.modal-academic').modal('hide');
            $('.modal-organization').modal('hide');
            $('.modal-job').modal('hide');
            $('.modal-achievement').modal('hide');
        }

        $(document).ready(function () {
            var regency = <?php echo json_encode($regency, 15, 512) ?>;

            $('.modal-academic').on('hidden.bs.modal', function () {
                $(this).find("input").val("");
                $(this).find("select").val("S1");
            });
            $('.modal-organization').on('hidden.bs.modal', function () {
                $(this).find("input").val("");
                $(this).find("select").val("O1");
            });
            $('.modal-job').on('hidden.bs.modal', function () {
                $(this).find("input").val("");
            });
            $('.modal-achievement').on('hidden.bs.modal', function () {
                $(this).find("input").val("");
            });

            renderTableAcademic();
            renderTableOrganization();
            renderTableJob();
            renderTableachievement();
        });


    </script>
    
    <script>
        $(document).ready(function () {
            // Deteksi perubahan pada dropdown provinsi
            $('select[name="id_province"]').on('change', function () {
                var selectedProvince = $(this).val();

                // Lakukan permintaan AJAX untuk mengambil kecamatan berdasarkan provinsi yang dipilih
                $.ajax({
                    url: '/get-districts',
                    type: 'GET',
                    data: { province: selectedProvince },
                    success: function (data) {
                        $('select[name="id_district"]').empty();
                        $('select[name="id_district"]').append('<option value="">Pilih Kecamatan</option>');
                        $.each(data, function (key, value) {
                            $('select[name="id_district"]').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            });
        });
    </script>
    <script>
        var userId = <?php echo e(auth()->user()->id); ?>;
        console.log(userId)
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('super.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/yapi/Adi/appdev/simonas-app/resources/views/super/pages/alumni/create.blade.php ENDPATH**/ ?>