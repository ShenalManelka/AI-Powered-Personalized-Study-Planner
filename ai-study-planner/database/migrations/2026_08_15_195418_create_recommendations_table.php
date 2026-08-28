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
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id(); $table->foreignId("user_id")->constrained()->cascadeOnDelete(); $table->foreignId("prediction_id")->nullable()->constrained()->nullOnDelete(); $table->enum("priority", ["low", "medium", "high"])->default("medium"); $table->text("recommendation_text"); $table->boolean("is_completed")->default(false); $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
