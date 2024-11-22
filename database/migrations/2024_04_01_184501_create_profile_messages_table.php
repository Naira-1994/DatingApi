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
        Schema::create('profile_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_from');
            $table->unsignedBigInteger('id_to');
            $table->longText('message_text');
            $table->timestamps();

            $table->foreign('id_from')
                ->references('id')
                ->on('profiles')
                ->onDelete('cascade');

            $table->foreign('id_to')
                ->references('id')
                ->on('profiles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_messages');
    }
};
