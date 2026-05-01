<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('alumni_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['job', 'internship', 'freelance'])->default('job');
            $table->string('title');
            $table->string('company');
            $table->string('location');
            $table->enum('work_type', ['onsite', 'remote', 'hybrid'])->default('onsite');
            $table->text('description');
            $table->string('requirements')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('contact_info');
            $table->date('deadline')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('alumni_jobs'); }
};
