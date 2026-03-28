<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availability_breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('availability_id')->constrained()->cascadeOnDelete();
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->index(['availability_id', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availability_breaks');
    }
};
