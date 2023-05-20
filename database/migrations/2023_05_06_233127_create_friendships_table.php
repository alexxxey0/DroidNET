<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('friendships', function (Blueprint $table) {
            $table->id();
            $table->string('request_sender');
            $table->foreign('request_sender')->references('username')->on('users')->onDelete('cascade');
            $table->string('request_receiver');
            $table->foreign('request_receiver')->references('username')->on('users')->onDelete('cascade');
            $table->string('status', 20);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friendships');
    }
};
