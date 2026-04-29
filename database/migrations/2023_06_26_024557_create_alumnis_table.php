<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlumnisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumnis', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('email')->nullable();
            $table->string('gelar_depan')->nullable();
            $table->string('gelar_belakang')->nullable();
            $table->string('provinsi_asal')->nullable();
            $table->string('tanggal_lahir')->nullable();
            $table->string('alamat_domisili')->nullable();
            $table->string('alamat_jalan')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('no_whatsapp')->nullable();
            $table->string('jumlah_anak')->nullable();
            $table->string('asal_asrama')->nullable();
            $table->string('tahun_masuk_asrama')->nullable();
            $table->string('tahun_keluar_asrama')->nullable();
            $table->string('pengalaman_organisasi')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('jurusan_s1')->nullable();
            $table->string('kampus_s1')->nullable();
            $table->string('jurusan_s2')->nullable();
            $table->string('kampus_s2')->nullable();
            $table->string('jurusan_s3')->nullable();
            $table->string('kampus_s3')->nullable();
            $table->string('pekerjaan_sekarang')->nullable();
            $table->string('bidang_pekerjaan')->nullable();
            $table->string('bidang_keahlian')->nullable();
            $table->string('pengalaman_pekerjaan')->nullable();
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
        Schema::dropIfExists('alumnis');
    }
}
