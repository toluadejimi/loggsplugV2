<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('api_key', 64)->unique();
            $table->decimal('admin_discount_percent', 5, 2)->default(0)->comment('Platform cut % off base price');
            $table->tinyInteger('status')->default(1)->comment('1=active, 0=suspended');
            $table->string('business_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->timestamp('api_key_revoked_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resellers');
    }
};
