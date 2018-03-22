<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceIdentitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_identities', function (Blueprint $table) {
            $table->increments('id');
            $table->char('sbsf_zcbh',6)->comment('设备身份_资产编号');
            $table->integer('sbsf_kcid')->unsigned()->comment('设备身份_库存id');
            $table->char('sbsf_xh',5)->comment('设备身份_序号');
            $table->string('sbsf_bz',200)->comment('设备身份_备注');
            $table->tinyInteger('if_ck')->default(0)->comment('0默认在仓库，1:被领用');
            $table->tinyInteger('if_bf')->default(0)->comment('0默认没报废，1:报废');
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
        Schema::dropIfExists('device_identities');
    }
}
