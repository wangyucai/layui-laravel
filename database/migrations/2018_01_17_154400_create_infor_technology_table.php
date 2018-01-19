<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInforTechnologyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infor_technology', function (Blueprint $table) {
            $table->increments('id');
            $table->char('xxhjs_code',4)->comment('信息化技术代码');
            $table->string('xxhjs_name',50)->comment('信息化技术名称');
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
        Schema::dropIfExists('infor_technology');
    }
}
