<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->boolean('published')->nullable(false)->default(true);
            $table->integer('pid')->nullable(true)->default(null);
            $table->string('alias')->nullable(false);
            $table->text('content')->nullable(true)->default(null);
            $table->integer('template_id')->nullable(true)->default(null);
            $table->string('meta_title')->nullable(true)->default(null);
            $table->string('meta_keywords')->nullable(true)->default(null);
            $table->text('meta_description')->nullable(true)->default(null);

            $table->foreign('template_id')
                ->references('id')
                ->on('templates')
                ->onDelete('RESTRICT')
                ->onUpdate('CASCADE');

            $table->foreign('pid')
                ->references('id')
                ->on('pages')
                ->onDelete('CASCADE')
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
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign('pages_template_id_foreign');
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign('pages_pid_foreign');
        });

        Schema::dropIfExists('pages');
    }
};
