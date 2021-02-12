<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryCutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_cuts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->string("cuts_name");
            $table->text("notes")->nullable();
            $table->string("image")->nullable();
            $table->integer("value");
            $table->tinyInteger("status")->default(0);
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
        Schema::dropIfExists('salary_cuts');
    }
}
