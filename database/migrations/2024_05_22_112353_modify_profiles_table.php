<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->dropColumn('age');
            $table->string('birthday');
            $table->dropColumn('last_online');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->string('last_online')->nullable()->default(DB::raw('extract(epoch from now())::bigint::text'));
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
            $table->integer('age');
            $table->dropColumn('birthday');
            $table->dropColumn('last_online');
        });

        Schema::table('profiles', function (Blueprint $table) {
            $table->timestamp('last_online')->nullable();
        });

        DB::table('profiles')->update([
            'last_online' => DB::raw('to_timestamp(last_online::bigint)')
        ]);
    }
};
