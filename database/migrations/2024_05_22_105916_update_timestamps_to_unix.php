<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'profiles',
            'user_profile_actions',
            'users',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('created_at_timestamp')->nullable();
                $table->string('updated_at_timestamp')->nullable();

                $table->dropColumn(['created_at', 'updated_at']);
            });
        }

    }

    public function down(): void
    {
        $tables = [
            'profiles',
            'user_profile_actions',
            'users',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->timestamps();

                $table->dropColumn(['created_at_timestamp', 'updated_at_timestamp']);
            });
        }
    }
};
