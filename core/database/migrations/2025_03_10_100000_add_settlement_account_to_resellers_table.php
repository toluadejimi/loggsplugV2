<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('resellers') || Schema::hasColumn('resellers', 'settlement_account')) {
            return;
        }

        Schema::table('resellers', function (Blueprint $table) {
            $table->text('settlement_account')->nullable()->after('contact_email');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('resellers') || ! Schema::hasColumn('resellers', 'settlement_account')) {
            return;
        }

        Schema::table('resellers', function (Blueprint $table) {
            $table->dropColumn('settlement_account');
        });
    }
};
