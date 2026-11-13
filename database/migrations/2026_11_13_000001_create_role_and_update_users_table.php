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
        if (!Schema::hasTable('role')) {
            Schema::create('role', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('nama_role', 100);
                $table->boolean('void')->default(false);
                $table->timestamp('created_at')->nullable()->useCurrent();
                $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->integer('role_id')->nullable()->after('email');
                $table->foreign('role_id', 'fk_users_role')
                    ->references('id')
                    ->on('role')
                    ->cascadeOnUpdate();
            }
            if (!Schema::hasColumn('users', 'void')) {
                $table->boolean('void')->default(false)->after('role_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign('fk_users_role');
                $table->dropColumn('role_id');
            }
            if (Schema::hasColumn('users', 'void')) {
                $table->dropColumn('void');
            }
        });

        Schema::dropIfExists('role');
    }
};
