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
        Schema::table('usulan_aspal', function (Blueprint $table) {
            if (!Schema::hasColumn('usulan_aspal', 'tahun')) {
                $table->integer('tahun')->default((int) date('Y'))->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usulan_aspal', function (Blueprint $table) {
            if (Schema::hasColumn('usulan_aspal', 'tahun')) {
                $table->dropColumn('tahun');
            }
        });
    }
};
