<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('resellers') || Schema::hasColumn('resellers', 'api_site_url')) {
            return;
        }

        Schema::table('resellers', function (Blueprint $table) {
            $table->string('api_site_url', 500)->nullable();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('resellers') || ! Schema::hasColumn('resellers', 'api_site_url')) {
            return;
        }

        Schema::table('resellers', function (Blueprint $table) {
            $table->dropColumn('api_site_url');
        });
    }
};
