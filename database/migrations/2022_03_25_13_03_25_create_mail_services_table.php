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
        Schema::create('mail_services', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->boolean('is_active')->nullable(false)->default(false);
            $table->string('smtp_server')->nullable(false);
            $table->integer('smtp_port')->nullable(false);
            $table->string('smtp_user')->nullable(false);
            $table->string('smtp_password')->nullable(false);
            $table->string('smtp_security')->nullable(true)->default(null);
            $table->string('sender_name')->nullable(true)->default(null);
            $table->string('sender_email')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_services');
    }
};
