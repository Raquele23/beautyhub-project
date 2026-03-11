<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professional_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('weekday'); // 0 = domingo, 1 = segunda, ..., 6 = sábado
            $table->time('open_time');
            $table->time('close_time');
            $table->unsignedSmallInteger('slot_interval'); // em minutos (ex: 30, 60)
            $table->timestamps();

            $table->unique(['professional_id', 'weekday']); // um registro por dia por profissional
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('availabilities');
    }
};
