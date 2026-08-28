<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id(); $table->foreignId("user_id")->constrained()->cascadeOnDelete(); $table->decimal("study_hours", 5, 2)->default(0); $table->decimal("attendance", 5, 2)->default(0); $table->decimal("sleep_hours", 5, 2)->default(0); $table->decimal("internet_usage", 5, 2)->default(0); $table->integer("assignments_completed")->default(0); $table->decimal("previous_score", 5, 2)->nullable(); $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
