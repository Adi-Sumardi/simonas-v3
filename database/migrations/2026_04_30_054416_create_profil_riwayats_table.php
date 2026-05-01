<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('profil_riwayats')) {
            Schema::create('profil_riwayats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->enum('tipe', ['pendidikan', 'organisasi', 'pekerjaan', 'penghargaan', 'sertifikasi']);
                $table->string('judul');
                $table->string('posisi')->nullable();
                $table->date('mulai')->nullable();
                $table->date('selesai')->nullable();
                $table->boolean('masih_berlangsung')->default(false);
                $table->text('deskripsi')->nullable();
                $table->string('lokasi')->nullable();
                $table->timestamps();
                $table->index(['user_id', 'tipe']);
            });
        }
        // Add extra profile fields to users if they don't exist
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'mentor_id')) {
                $table->foreignId('mentor_id')->nullable()->after('role')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('users', 'nim')) {
                $table->string('nim')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'asrama')) {
                $table->string('asrama')->nullable()->after('nim');
            }
            if (!Schema::hasColumn('users', 'angkatan')) {
                $table->string('angkatan')->nullable()->after('asrama');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('angkatan');
            }
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp')->nullable()->after('bio');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_riwayats');
    }
};
