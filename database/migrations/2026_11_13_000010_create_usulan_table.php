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
        Schema::dropIfExists('usulan_inpres');

        if (!Schema::hasTable('usulan')) {
            Schema::create('usulan', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('kode_usulan', 50)->nullable();
                $table->integer('kabupaten_kota_id');
                $table->integer('upt_dinas_id')->nullable();
                $table->integer('tematik_kawasan_id')->nullable();
                $table->integer('jenis_penanganan_id')->nullable();
                $table->integer('koridor_id')->nullable();
                $table->integer('ruas_jalan_id')->nullable();
                $table->integer('jembatan_id')->nullable();
                $table->string('nama_pengusul', 150);
                $table->string('nama_kegiatan', 255);
                $table->enum('status_ruas', ['Provinsi', 'Kabupaten/Kota', 'Non Status'])->default('Kabupaten/Kota');
                $table->double('panjang_penanganan')->default(0);
                $table->enum('satuan', ['KM', 'M'])->default('KM');
                $table->bigInteger('alokasi_usulan')->default(0);
                $table->text('keterangan')->nullable();
                $table->enum('status_dokumen_pendukung', ['SIAP', 'TIDAK SIAP', 'TIDAK PERLU'])->default('SIAP');
                $table->string('file_dokumen_pendukung', 255)->nullable();
                $table->boolean('prioritas_pu')->default(false);
                $table->boolean('prioritas_bappeda')->default(false);
                $table->boolean('verifikasi_pu')->default(false);
                $table->boolean('void')->default(false);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                $table->foreign('kabupaten_kota_id', 'fk_usulan_kab_kota')
                    ->references('id')
                    ->on('kabupaten_kota')
                    ->cascadeOnUpdate();

                $table->foreign('upt_dinas_id', 'fk_usulan_upt_dinas')
                    ->references('id')
                    ->on('upt_dinas')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();

                $table->foreign('tematik_kawasan_id', 'fk_usulan_tematik')
                    ->references('id')
                    ->on('tematik_kawasan')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();

                $table->foreign('jenis_penanganan_id', 'fk_usulan_jenis_penanganan')
                    ->references('id')
                    ->on('jenis_penanganan')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();

                $table->foreign('koridor_id', 'fk_usulan_koridor')
                    ->references('id')
                    ->on('koridor')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();

                $table->foreign('ruas_jalan_id', 'fk_usulan_ruas_jalan')
                    ->references('id')
                    ->on('ruas_jalan')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();

                $table->foreign('jembatan_id', 'fk_usulan_jembatan')
                    ->references('id')
                    ->on('jembatan')
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
        Schema::dropIfExists('usulan');
    }
};
