<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKreatifsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kreatifs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_warga');
            $table->string('komponen')->nullable();
            $table->string('asrama');
            $table->string('kegiatan');
            $table->string('waktu');
            $table->string('tempat');
            $table->string('keterangan')->nullable();
            $table->string('file')->nullable();
            $table->string('nama_penilai')->nullable();
            $table->string('nilai')->nullable();
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
        Schema::dropIfExists('kreatifs');
    }
}
