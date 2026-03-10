<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'product_id')) {
                $table->unsignedBigInteger('product_id')->default(0)->after('deposit_id');
            }
            if (!Schema::hasColumn('orders', 'reseller_id')) {
                $table->unsignedBigInteger('reseller_id')->nullable()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['reseller_id']);
        });
    }
};
