<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->unsignedSmallInteger('preparation_time_minutes')->default(15)->after('auto_complete');
        });

        $professionals = DB::table('professionals')->select('id')->get();

        foreach ($professionals as $professional) {
            $slotInterval = DB::table('availabilities')
                ->where('professional_id', $professional->id)
                ->orderBy('weekday')
                ->value('slot_interval');

            DB::table('professionals')
                ->where('id', $professional->id)
                ->update([
                    'preparation_time_minutes' => $slotInterval ? (int) $slotInterval : 15,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('professionals', function (Blueprint $table) {
            $table->dropColumn('preparation_time_minutes');
        });
    }
};
