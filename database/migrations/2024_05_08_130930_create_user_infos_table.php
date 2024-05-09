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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('chat_id');
            $table->float('weight')->nullable();
            $table->float('goal_weight')->nullable();
            $table->integer('tall')->nullable();
            $table->integer('age')->nullable();
            $table->boolean('gender')->nullable();
            $table->unsignedBigInteger('activity_type_id')->nullable();
            $table->integer('daily_spend_calories')->nullable();
            $table->integer('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_infos');
    }
};
