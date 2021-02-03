<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ovenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("ovense_name");
            $table->enum("pinalty_type", ["Denda", "Penambahan Jam Kerja", "Sanksi Lisan", "Skorsing", "Other"]);
            $table->date("date");
            $table->integer("punishment")->nullable();
            $table->unsignedBigInteger("employee_id");
            $table->foreign("employee_id")->references("id")->on("employees")->onUpdate("CASCADE")->onDelete("CASCADE");
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
        Schema::dropIfExists('ovenses');
    }
}
