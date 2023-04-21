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
        Schema::create('book_appointments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('visit_type')->nullable();
            $table->string('appointment_reason')->nullable();
            $table->date('appointment_date')->nullable();
            $table->time('appointment_time')->nullable();
            $table->string('appointment_email')->nullable();
            $table->string('appointment_number')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('book_appointments');
    }
};
