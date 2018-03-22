<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('zc_dwdm')->unsigned()->comment('发布资产的用户单位代码');
            $table->char('zc_bmdm',4)->comment('发布资产的用户部门代码');
            $table->char('zcbh',6)->comment('资产编号');
            $table->string('zcmc',100)->comment('资产名称');
            $table->string('zcpp',100)->comment('资产品牌');
            $table->string('zcxh',100)->nullable()->comment('资产型号');
            $table->char('zcdw',4)->comment('资产单位代码');
            $table->char('zcxz',4)->comment('资产性质代码');
            $table->string('cd',100)->nullable()->comment('产地');
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
        Schema::dropIfExists('equipment_assets');
    }
}
