<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixedAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gdzc_uid')->comment('用户id');
            $table->char('gdzc_dwdm',6)->comment('发布固定资产的用户单位代码');
            $table->char('gdzc_bmdm',4)->comment('发布固定资产的用户部门代码');
            $table->char('gdzc_bh',6)->comment('资产编号');
            $table->string('gdzc_mc',100)->comment('资产名称');
            $table->string('gdzc_pp',100)->comment('资产品牌');
            $table->string('gdzc_xh',100)->nullable()->comment('资产型号');
            $table->char('gdzc_ffbm',2)->comment('发放部门');
            $table->decimal('price', 6, 2)->comment('单价');
            $table->integer('gdzc_nums')->comment('持有的数量');
            $table->char('gdzc_lqrq',10)->comment('领取日期');
            $table->char('gdzc_ghrq',10)->nullable()->comment('归还日期');
            $table->text('gdzc_bz')->nullable()->comment('备注');
            $table->tinyInteger('if_back')->default(0)->comment('是否归还');
            $table->string('gdzc_pic',200)->nullable()->comment('图片路径');
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
        Schema::dropIfExists('fixed_assets');
    }
}
