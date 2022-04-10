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
        Schema::create('mail_messages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->integer('status')->nullable(false)->default(1);
            $table->string('recipient')->nullable(false);
            $table->string('subject')->nullable(true)->default(null);
            $table->text('message')->nullable(false);
            $table->timestamp('send_time')->nullable(true)->default(null);
            $table->text('send_errors')->nullable(true)->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_messages');
    }
};
