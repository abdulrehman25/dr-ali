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
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();;
            $table->string('last_name')->nullable();;
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->json('type_of_scan')->nullable();
            $table->json('what_part_of_body')->nullable();
            $table->string('scan')->nullable();
            $table->string('report')->nullable();
            $table->text('comment')->nullable();
            $table->string('selected_package')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
