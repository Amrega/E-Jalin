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
            if (!Schema::hasColumn('usulan_aspal', 'qty_drum')) {
                $table->integer('qty_drum')->default(0)->after('kabupaten_kota');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usulan_aspal', function (Blueprint $table) {
            if (Schema::hasColumn('usulan_aspal', 'qty_drum')) {
                $table->dropColumn('qty_drum');
            }
        });
    }
};
