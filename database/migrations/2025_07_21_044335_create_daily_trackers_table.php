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
Schema::create('daily_trackers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('request_id')->constrained()->onDelete('cascade');
    $table->date('start_date');
    $table->integer('max_days')->default(31);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_trackers');
    }
};
