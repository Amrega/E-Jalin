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
        if (!Schema::hasTable('kabupaten_kota')) {
            Schema::create('kabupaten_kota', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('provinsi_id');
                $table->integer('upt_id')->nullable();
                $table->string('nama_kabupaten_kota', 100);
                $table->boolean('void')->default(false);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                $table->foreign('provinsi_id', 'fk_kabupaten_kota_provinsi')
                    ->references('id')
                    ->on('provinsi')
                    ->cascadeOnUpdate();

                $table->foreign('upt_id', 'fk_kabupaten_kota_upt')
                    ->references('id')
                    ->on('upt_dinas')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kabupaten_kota');
    }
};
