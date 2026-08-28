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
        Schema::create('study_plan_items', function (Blueprint $table) {
            $table->id(); $table->foreignId("study_plan_id")->constrained()->cascadeOnDelete(); $table->foreignId("subject_id")->nullable()->constrained()->nullOnDelete(); $table->foreignId("assignment_id")->nullable()->constrained()->nullOnDelete(); $table->string("title"); $table->date("study_date"); $table->time("start_time"); $table->time("end_time"); $table->integer("duration_minutes"); $table->enum("status", ["pending", "completed", "missed"])->default("pending"); $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_plan_items');
    }
};
