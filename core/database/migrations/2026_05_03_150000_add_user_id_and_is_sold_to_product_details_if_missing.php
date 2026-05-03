<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Older installs may lack columns that exist in the canonical schema.
     */
    public function up(): void
    {
        if (! Schema::hasTable('product_details')) {
            return;
        }

        if (! Schema::hasColumn('product_details', 'user_id')) {
            Schema::table('product_details', function (Blueprint $table) {
                $table->unsignedInteger('user_id')->default(0)->after('product_id');
            });
        }

        if (! Schema::hasColumn('product_details', 'is_sold')) {
            Schema::table('product_details', function (Blueprint $table) {
                $table->boolean('is_sold')->default(0);
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('product_details')) {
            return;
        }

        if (Schema::hasColumn('product_details', 'is_sold')) {
            Schema::table('product_details', function (Blueprint $table) {
                $table->dropColumn('is_sold');
            });
        }

        if (Schema::hasColumn('product_details', 'user_id')) {
            Schema::table('product_details', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
};
