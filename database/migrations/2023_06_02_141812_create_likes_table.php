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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->string('user', 50);
            $table->foreign('user')->references('username')->on('users')->onDelete('cascade');
            $table->foreignId('post');
            $table->foreign('post')->references('id')->on('posts')->onDelete('cascade');
            $table->unique(['user', 'post']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
