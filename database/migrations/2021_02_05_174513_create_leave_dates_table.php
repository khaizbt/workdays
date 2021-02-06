<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_dates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('leave_id');
            $table->date("date");
            $table->timestamps();
            $table->foreign("leave_id")->references("id")->on("holidays")->onDelete("CASCADE")->onUpdate("CASCADE");
        });

        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn("date_start");
            $table->dropColumn("date_end");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_dates');
    }
}
