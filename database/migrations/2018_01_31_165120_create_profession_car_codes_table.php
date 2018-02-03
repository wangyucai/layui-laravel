<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionCarCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profession_car_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->char('car_code',4)->index()->comment('职业资格证书代码');
            $table->string('car_name',100)->comment('职业资格证书代码名称');
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
        Schema::dropIfExists('profession_car_codes');
    }
}
