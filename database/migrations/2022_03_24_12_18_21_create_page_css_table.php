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
        Schema::create('page_csses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('page_id')->nullable(false);
            $table->integer('css_id')->nullable(false);
            $table->integer('view_pos')->nullable(false)->default(0);
            $table->integer('block_pos')->nullable(false)->default(0);

            $table->foreign('page_id')
                ->references('id')
                ->on('pages')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->foreign('css_id')
                ->references('id')
                ->on('csses')
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
        Schema::table('page_csses', function (Blueprint $table) {
            $table->dropForeign('page_csses_page_id_foreign');
        });

        Schema::table('page_csses', function (Blueprint $table) {
            $table->dropForeign('page_csses_css_id_foreign');
        });

        Schema::dropIfExists('page_csses');
    }
};
