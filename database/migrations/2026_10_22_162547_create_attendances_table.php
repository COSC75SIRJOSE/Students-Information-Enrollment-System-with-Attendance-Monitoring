<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();

            $table->date('attendance_date');

            $table->enum('status', [
                'present',
                'absent',
                'late',
                'excused'
            ])->default('present');

            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->string('remarks', 500)->nullable();

            $table->timestamps();

            $table->unique([
                'student_id',
                'attendance_date'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};