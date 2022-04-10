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
        Schema::create('template_js', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('template_id')->nullable(false);
            $table->integer('js_id')->nullable(false);
            $table->integer('view_pos')->nullable(false)->default(0);
            $table->integer('block_pos')->nullable(false)->default(0);

            $table->foreign('template_id')
                ->references('id')
                ->on('templates')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');

            $table->foreign('js_id')
                ->references('id')
                ->on('js')
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
        Schema::table('template_js', function (Blueprint $table) {
            $table->dropForeign('template_js_template_id_foreign');
        });

        Schema::table('template_js', function (Blueprint $table) {
            $table->dropForeign('template_js_js_id_foreign');
        });

        Schema::dropIfExists('template_js');
    }
};
