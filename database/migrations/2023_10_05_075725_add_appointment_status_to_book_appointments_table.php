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
        Schema::table('book_appointments', function (Blueprint $table) {
            $table->enum('appointment_status', ['true', 'false'])->default('false')->comment('true:clode, false:open')->after('appointment_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('book_appointments', function (Blueprint $table) {
            $table->dropColumn('appointment_status');
        });
    }
};
