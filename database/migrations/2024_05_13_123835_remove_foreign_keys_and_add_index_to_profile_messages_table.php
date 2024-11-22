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
        Schema::table('profile_messages', function (Blueprint $table) {
            $table->dropForeign(['id_from']);
            $table->dropForeign(['id_to']);

            $table->index('id_to');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_messages', function (Blueprint $table) {
            $table->foreign('id_from')
                ->references('id')
                ->on('profiles')
                ->onDelete('cascade');

            $table->foreign('id_to')
                ->references('id')
                ->on('profiles')
                ->onDelete('cascade');

            $table->dropIndex(['id_to']);

        });
    }
};
