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
        Schema::create('calc_ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->integer('status')->default(0)->comment('0 - yangi ochilgan , 1 - suhbat boshlangan , 2 - suhbat yakunlangan');
            $table->text('image');
            $table->text('titles')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calc_ai_conversations');
    }
};
