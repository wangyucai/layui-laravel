<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_bids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('my_name',30)->comment('姓名');
            $table->char('my_sfzh',18)->comment('身份证号');
            $table->string('zsmc',100)->comment('证书名称');
            $table->char('zsbh',15)->comment('证书编号');
            $table->char('my_dwdm',6)->comment('所在单位');
            $table->char('my_bm',4)->comment('所在部门');
            $table->string('ywlb',200)->comment('业务类别');
            $table->tinyInteger('fzjg')->comment('发证机关.0:最高人民检察院,1:贵州省人民检察院');
            $table->tinyInteger('zsyxq')->comment('证书有效期n年');   
            $table->tinyInteger('county_if_check')->default('0')->comment('县管理员是否上报');
            $table->tinyInteger('city_if_check')->default('0')->comment('市管理员是否上报');
            $table->tinyInteger('if_check')->default('0')->comment('0:未申请,1:已申请,2:审核通过,3:审核未通过');
            $table->tinyInteger('bz')->comment('0:无效,1:有效');
            $table->char('check_time',10)->nullable()->comment('审核时间,从这个时间证书生效');
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
        Schema::dropIfExists('certificate_bids');
    }
}
