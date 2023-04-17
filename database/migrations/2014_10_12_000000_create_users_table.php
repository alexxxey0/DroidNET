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
        Schema::create('users', function (Blueprint $table) {
            $table->string('username', 50)->primary();
            $table->string('first_name', 20);
            $table->string('last_name', 20);
            $table->string('password', 64);
            $table->string('email', 50)->unique();
            $table->text('about_me')->nullable();
            $table->string('role', 20)->default('user');
            $table->string('image')->nullable();
            $table->timestamp('registered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
