<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('pid')->default(NULL)->nullable();
            $table->string('name');
            $table->boolean('is_admin')->default(false);

            $table->foreign('pid')
                ->references('id')
                ->on('roles')
                ->onDelete('SET NULL')
                ->onUpdate('CASCADE');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('role_id')->nullable(false);
            $table->string('password');
            $table->string('phone');
            $table->string('lang')->default('ru_RU');
            $table->string('timezone')->default('Europe/Moscow');
            $table->rememberToken();

            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('RESTRICT')
                ->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign('roles_pid_foreign');
        });

        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
