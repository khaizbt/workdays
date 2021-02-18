<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("leave_name");
            $table->unsignedBigInteger("employee_id");
            $table->tinyInteger("status")->default(1);
            $table->integer("charge")->nullable(); //Jika ada denda Cuti
            $table->date("date_start")->nullable();
            $table->date("date_end")->nullable();
            $table->tinyInteger("is_approved")->default(0);
            $table->timestamps();
            $table->foreign("employee_id")->references("id")->on("employees")->onUpdate("CASCADE")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('holidays');
    }
}
