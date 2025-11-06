<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove money_amount from formations table (no longer used)
        if (Schema::hasColumn('formations', 'money_amount')) {
            Schema::table('formations', function (Blueprint $table) {
                $table->dropColumn('money_amount');
            });
        }

        // Remove enrollment_cost from formation_user table (no longer used)
        if (Schema::hasColumn('formation_user', 'enrollment_cost')) {
            Schema::table('formation_user', function (Blueprint $table) {
                $table->dropColumn('enrollment_cost');
            });
        }

        // Drop payments table (no longer used)
        Schema::dropIfExists('payments');

        // NOTE: teams.money and team_credit_transactions table are still in use
        // and should NOT be dropped
    }

    public function down(): void
    {
        // Restore money_amount to formations table
        Schema::table('formations', function (Blueprint $table) {
            if (! Schema::hasColumn('formations', 'money_amount')) {
                $table->integer('money_amount')->default(0)->after('level');
            }
        });

        // Restore enrollment_cost to formation_user table
        Schema::table('formation_user', function (Blueprint $table) {
            if (! Schema::hasColumn('formation_user', 'enrollment_cost')) {
                $table->integer('enrollment_cost')->nullable()->after('max_score_total');
            }
        });

        // Restore payments table
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('team_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('provider')->default('stripe');
                $table->string('provider_session_id')->unique();
                $table->string('provider_payment_intent_id')->nullable();
                $table->string('currency', 8)->default('eur');
                $table->unsignedBigInteger('amount')->nullable();
                $table->string('status')->default('pending');
                $table->timestamp('paid_at')->nullable();
                $table->json('provider_payload')->nullable();
                $table->timestamps();
            });
        }
    }
};
