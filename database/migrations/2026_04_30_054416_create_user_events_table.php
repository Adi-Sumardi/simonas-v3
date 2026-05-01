<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('date');
            $table->time('time')->nullable();
            $table->string('type')->default('kegiatan'); // shalat, hafalan, akademik, kegiatan, belajar, olahraga
            $table->string('color')->default('#6366f1');
            $table->text('desc')->nullable();
            $table->boolean('recurring')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_events');
    }
};
