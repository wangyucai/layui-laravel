<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInscertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscertificates', function (Blueprint $table) {
            $table->increments('id');
            $table->char('jdjg_dm',3)->comment('司法鉴定机构代码');
            $table->char('zsbh',10)->comment('证书编号');
            $table->string('ssdwqc',100)->comment('所属单位全称');
            $table->string('jdjg_fzr',50)->comment('鉴定机构负责人');
            $table->string('jdjg_ywfw',200)->comment('鉴定机构业务范围');
            $table->string('fzdw',100)->comment('发证单位');
            $table->char('fzrq',10)->comment('发证日期');
            $table->string('zgsh_yxqz',1000)->comment('资格审核有效期至');
            $table->tinyInteger('if_jh')->default(0)->comment('机构是否激活代码');
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
        Schema::dropIfExists('inscertificates');
    }
}
