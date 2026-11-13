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
        if (!Schema::hasTable('ruas_jalan')) {
            Schema::create('ruas_jalan', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('kabupaten_kota_id');
                $table->integer('koridor_id')->nullable();
                $table->string('nama_ruas', 255);
                $table->string('nomor_ruas', 100)->nullable();
                $table->double('panjang_ruas')->nullable();
                $table->enum('status_jalan', ['Kabupaten/Kota', 'Non Status'])->default('Kabupaten/Kota');
                $table->string('file_kml_ruas', 255)->nullable();
                $table->boolean('void')->default(false);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                $table->foreign('kabupaten_kota_id', 'fk_ruas_kabupaten_kota')
                    ->references('id')
                    ->on('kabupaten_kota')
                    ->cascadeOnUpdate();

                $table->foreign('koridor_id', 'fk_ruas_koridor')
                    ->references('id')
                    ->on('koridor')
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
        Schema::dropIfExists('ruas_jalan');
    }
};
