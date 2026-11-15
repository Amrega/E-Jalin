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
        if (!Schema::hasTable('koridor')) {
            Schema::create('koridor', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('provinsi_id');
                $table->string('nama_koridor', 255);
                $table->string('nomor_koridor', 100)->nullable();
                $table->double('panjang_koridor')->nullable();
                $table->string('file_kml_koridor', 255)->nullable();
                $table->boolean('void')->default(false);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                $table->foreign('provinsi_id', 'fk_koridor_provinsi')
                    ->references('id')
                    ->on('provinsi')
                    ->cascadeOnUpdate();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koridor');
    }
};
