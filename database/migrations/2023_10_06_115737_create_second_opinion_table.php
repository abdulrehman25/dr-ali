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
        Schema::create('second_opinion', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->index()->nullable();           
            $table->json('type_of_scan')->nullable();
            $table->json('what_part_of_body')->nullable();
            $table->string('scan')->nullable();
            $table->string('report')->nullable();
            $table->text('comment')->nullable();
            $table->string('selected_package')->nullable();
            $table->enum('appointment_status', ['true', 'false'])->default('false')->comment('true:close, false:open');        
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('second_opinion');
    }
};
