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
        if (!Schema::hasTable('usulan_aspal')) {
            Schema::create('usulan_aspal', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('kabupaten_kota', 150);
                $table->text('keterangan_urgensi');
                $table->integer('pengusul_id');
                $table->integer('jenis_pengusul_id');
                $table->string('upload_surat_permohonan', 255)->nullable();
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                $table->foreign('pengusul_id', 'fk_usulan_aspal_pengusul')
                    ->references('id')
                    ->on('pengusul')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->foreign('jenis_pengusul_id', 'fk_usulan_aspal_jenis_pengusul')
                    ->references('id')
                    ->on('jenis_pengusul')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_aspal');
    }
};
