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
        if (!Schema::hasTable('jembatan')) {
            Schema::create('jembatan', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('ruas_jalan_id');
                $table->string('nama_jembatan', 255);
                $table->string('nomor_jembatan', 100)->nullable();
                $table->double('panjang_jembatan')->nullable();
                $table->enum('status_jembatan', ['Kabupaten/Kota', 'Non Status'])->default('Kabupaten/Kota');
                $table->string('file_kml_jembatan', 255)->nullable();
                $table->boolean('void')->default(false);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                $table->foreign('ruas_jalan_id', 'fk_jembatan_ruas_jalan')
                    ->references('id')
                    ->on('ruas_jalan')
                    ->cascadeOnUpdate();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jembatan');
    }
};
