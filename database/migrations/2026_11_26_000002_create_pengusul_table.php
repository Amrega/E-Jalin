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
        if (!Schema::hasTable('pengusul')) {
            Schema::create('pengusul', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('nama_pengusul', 255);
                $table->integer('jenis_pengusul_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();

                $table->foreign('jenis_pengusul_id', 'fk_pengusul_jenis_pengusul')
                    ->references('id')
                    ->on('jenis_pengusul')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->foreign('user_id', 'fk_pengusul_user')
                    ->references('id')
                    ->on('users')
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
        Schema::dropIfExists('pengusul');
    }
};
