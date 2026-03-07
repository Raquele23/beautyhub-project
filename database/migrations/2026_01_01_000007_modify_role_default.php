<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // alter default so that new users are professionals by default
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['client', 'professional'])->default('professional')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['client', 'professional'])->default('client')->change();
        });
    }
};
