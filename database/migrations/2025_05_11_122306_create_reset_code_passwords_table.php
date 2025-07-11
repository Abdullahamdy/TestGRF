<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reset_code_passwords', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('verification_code')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('attempts')->default(0);
            $table->boolean('blocked')->default(false);
            $table->timestamp('blocked_until')->nullable();
            $table->boolean('account_locked')->default(false);
            $table->timestamp('account_locked_until')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reset_code_passwords');
    }
};
