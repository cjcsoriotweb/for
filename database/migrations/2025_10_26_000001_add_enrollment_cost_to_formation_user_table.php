<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formation_user', function (Blueprint $table) {
            $table->integer('enrollment_cost')->nullable()->after('max_score_total');
        });

        if (DB::getDriverName() === 'sqlite') {
            $formationCosts = DB::table('formations')
                ->select(['id', 'money_amount'])
                ->get()
                ->keyBy('id');

            DB::table('formation_user')
                ->orderBy('id')
                ->chunkById(100, function ($rows) use ($formationCosts) {
                    foreach ($rows as $row) {
                        $cost = $row->enrollment_cost;

                        if ($cost === null && isset($formationCosts[$row->formation_id])) {
                            $cost = $formationCosts[$row->formation_id]->money_amount;
                        }

                        DB::table('formation_user')
                            ->where('id', $row->id)
                            ->update(['enrollment_cost' => $cost]);
                    }
                });
        } else {
            DB::statement('UPDATE formation_user fu JOIN formations f ON fu.formation_id = f.id SET fu.enrollment_cost = COALESCE(fu.enrollment_cost, f.money_amount)');
        }
    }

    public function down(): void
    {
        Schema::table('formation_user', function (Blueprint $table) {
            $table->dropColumn('enrollment_cost');
        });
    }
};
