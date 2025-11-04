<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsermaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usermaster', function (Blueprint $table) {
            $table->bigIncrements('userId');
			$table->string('username');
			$table->string('password');
			$table->string('user_type');
			$table->string('contact');
			$table->string('address');
			$table->integer('delflag');
			$table->dateTime('ModifyDate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usermaster');
    }
}
