<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->foreignId('client_id')->nullable()->change();

            $table->string('client_name')->nullable()->after('client_id');
            $table->string('client_email')->nullable()->after('client_name');
            $table->string('client_phone', 20)->nullable()->after('client_email');

            $table->foreign('client_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn(['client_name', 'client_email', 'client_phone']);

            $table->foreignId('client_id')->nullable(false)->change();
            $table->foreign('client_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
