<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'reported_at')) {
                $table->timestamp('reported_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'report_reason')) {
                $table->text('report_reason')->nullable()->after('reported_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['reported_at', 'report_reason']);
        });
    }
};
