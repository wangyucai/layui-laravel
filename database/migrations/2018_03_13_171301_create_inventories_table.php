<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kc_ckid')->unsigned()->comment('仓库的id');
            $table->integer('kc_zcid')->unsigned()->comment('资产的id');
            $table->integer('kc_uid')->unsigned()->comment('入库人的id');
            $table->char('kc_nums',5)->comment('库存数量');
            $table->char('kc_dwdm',10)->comment('入库者的单位代码');
            $table->char('kc_bmdm',4)->comment('入库者的部门代码');
            $table->char('kc_zczk',2)->comment('资产状况');
            $table->char('kc_qryj',2)->comment('取入依据');
            $table->char('kc_rkrq',10)->comment('入库日期');
            $table->char('kc_ckrq',10)->comment('出库日期');
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
        Schema::dropIfExists('inventories');
    }
}
