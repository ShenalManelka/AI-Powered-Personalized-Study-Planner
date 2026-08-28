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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id(); $table->foreignId("user_id")->constrained()->cascadeOnDelete(); $table->foreignId("subject_id")->constrained()->cascadeOnDelete(); $table->string("title"); $table->text("description")->nullable(); $table->dateTime("deadline"); $table->enum("priority", ["low", "medium", "high"])->default("medium"); $table->enum("status", ["pending", "in_progress", "completed"])->default("pending"); $table->decimal("estimated_hours", 5, 2)->nullable(); $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
