<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('avatar')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role');
            $table->string('asrama')->nullable();
            $table->string('status_warga')->nullable();
            $table->integer('no_induk')->nullable();
            $table->string('tgl_masuk')->nullable();
            $table->string('tgl_keluar')->nullable();
            $table->string('alamat_sekarang')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('universitas')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('prodi')->nullable();
            $table->string('angkatan')->nullable();
            $table->string('tgl_seminar')->nullable();
            $table->string('tgl_skripsi')->nullable();
            $table->string('tgl_wisuda')->nullable();
            $table->string('nik')->nullable();
            $table->string('alamat')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('tgl_lahir')->nullable();
            $table->string('prestasi')->nullable();
            $table->string('organisasi')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
