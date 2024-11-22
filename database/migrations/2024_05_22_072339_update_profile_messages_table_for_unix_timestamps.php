<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profile_messages', function (Blueprint $table) {
            $table->string('created_at_timestamp')->nullable()->after('message_text');
            $table->string('updated_at_timestamp')->nullable()->after('created_at_timestamp');

            $table->dropTimestamps();
        });
    }

    public function down(): void
    {
        Schema::table('profile_messages', function (Blueprint $table) {
            $table->timestamps();

            $table->dropColumn('created_at_timestamp');
            $table->dropColumn('updated_at_timestamp');
        });
    }
};
