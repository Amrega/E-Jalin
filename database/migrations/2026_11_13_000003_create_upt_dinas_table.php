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
        if (!Schema::hasTable('upt_dinas')) {
            Schema::create('upt_dinas', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('provinsi_id');
                $table->string('nama_upt', 150);
                $table->boolean('void')->default(false);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                $table->foreign('provinsi_id', 'fk_upt_dinas_provinsi')
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
        Schema::dropIfExists('upt_dinas');
    }
};
