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
        Schema::create('predictions', function (Blueprint $table) {
            $table->id(); $table->foreignId("user_id")->constrained()->cascadeOnDelete(); $table->decimal("predicted_exam_score", 5, 2); $table->string("academic_risk"); $table->integer("cluster"); $table->string("cluster_label"); $table->dateTime("prediction_date"); $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
