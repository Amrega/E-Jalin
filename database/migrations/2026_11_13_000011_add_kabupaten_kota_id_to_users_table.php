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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'kabupaten_kota_id')) {
                $table->integer('kabupaten_kota_id')->nullable()->after('role_id');
                $table->foreign('kabupaten_kota_id', 'fk_users_kabupaten_kota')
                    ->references('id')
                    ->on('kabupaten_kota')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }

            if (!Schema::hasColumn('users', 'upt_dinas_id')) {
                $table->integer('upt_dinas_id')->nullable()->after('kabupaten_kota_id');
                $table->foreign('upt_dinas_id', 'fk_users_upt_dinas')
                    ->references('id')
                    ->on('upt_dinas')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'upt_dinas_id')) {
                $table->dropForeign('fk_users_upt_dinas');
                $table->dropColumn('upt_dinas_id');
            }
            if (Schema::hasColumn('users', 'kabupaten_kota_id')) {
                $table->dropForeign('fk_users_kabupaten_kota');
                $table->dropColumn('kabupaten_kota_id');
            }
        });
    }
};
