<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionCarModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profession_car_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('zsmc',100)->comment('证书名称');
            $table->char('zsbh',15)->comment('证书编号');
            $table->string('ywlb',200)->comment('业务类别');
            $table->tinyInteger('fzjg')->comment('发证机关.0:最高人民检察院,1:贵州省人民检察院');
            $table->tinyInteger('zsyxq')->comment('证书有效期n年');
            $table->char('my_dwdm',10)->comment('发布者的单位代码');       
            $table->tinyInteger('bz')->comment('0:无效,1:有效');
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
        Schema::dropIfExists('profession_car_modules');
    }
}
