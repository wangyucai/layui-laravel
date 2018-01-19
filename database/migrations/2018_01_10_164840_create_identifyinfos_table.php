<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdentifyinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('identifyinfos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned()->comment('外键-用户id');
            $table->string('jdry_name',50)->comment('鉴定人员姓名');
            $table->char('jdry_zsbh',10)->comment('鉴定人员证书编号');
            $table->char('jdywfw_code',10)->comment('鉴定业务范围代码');
            $table->string('jdry_fzdw',100)->comment('发证单位');
            $table->char('jdry_fzrq',10)->comment('发证日期');
            $table->string('jdry_yxrq',500)->comment('资格审核延续记录最后日期');
            $table->char('jdjg_dwdm',10)->comment('所在鉴定机构单位代码');
            $table->string('jdry_zspath',200)->comment('上传的证书路径');
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
        Schema::dropIfExists('identifyinfos');
    }
}
