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

        // Make each friendship unique (1 row per 1 friendship)
        DB::statement('ALTER TABLE friendships ADD COLUMN friend1 VARCHAR(50) AS (LEAST(request_sender, request_receiver)), 
        ADD COLUMN friend2 VARCHAR(50) AS (GREATEST(request_sender, request_receiver));');
        DB::statement('CREATE UNIQUE INDEX unique_friendship ON friendships (friend1, friend2);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('friendships');
    }
};
