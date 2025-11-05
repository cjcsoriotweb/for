<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('formations', 'money_amount')) {
            Schema::table('formations', function (Blueprint $table) {
                $table->dropColumn('money_amount');
            });
        }

        if (Schema::hasColumn('teams', 'money')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->dropColumn('money');
            });
        }

        if (Schema::hasColumn('formation_user', 'enrollment_cost')) {
            Schema::table('formation_user', function (Blueprint $table) {
                $table->dropColumn('enrollment_cost');
            });
        }

        Schema::dropIfExists('team_credit_transactions');
        Schema::dropIfExists('payments');
    }

    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            if (! Schema::hasColumn('formations', 'money_amount')) {
                $table->integer('money_amount')->default(0)->after('level');
            }
        });

        Schema::table('teams', function (Blueprint $table) {
            if (! Schema::hasColumn('teams', 'money')) {
                $table->integer('money')->default(0)->after('personal_team');
            }
        });

        Schema::table('formation_user', function (Blueprint $table) {
            if (! Schema::hasColumn('formation_user', 'enrollment_cost')) {
                $table->integer('enrollment_cost')->nullable()->after('max_score_total');
            }
        });

        if (! Schema::hasTable('team_credit_transactions')) {
            Schema::create('team_credit_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('team_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->integer('amount');
                $table->string('reason');
                $table->timestamps();
            });
        }

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
