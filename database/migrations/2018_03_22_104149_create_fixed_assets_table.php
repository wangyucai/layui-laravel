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
            $table->char('gdzc_dwdm',6)->comment('发布固定资产的用户单位代码');
            $table->char('gdzc_bmdm',4)->comment('发布固定资产的用户部门代码');
            $table->char('gdzcbh',6)->comment('资产编号');
            $table->string('gdzcmc',100)->comment('资产名称');
            $table->string('gdzcpp',100)->comment('资产品牌');
            $table->string('gdzcxh',100)->nullable()->comment('资产型号');
            $table->char('gdzcffbm',2)->comment('发放部门');
            $table->decimal('price', 6, 2)->comment('单价');
            $table->integer('gdzcnums')->comment('持有的数量');
            
            $table->tinyInteger('if_back')->default(0)->comment('是否归还');
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
