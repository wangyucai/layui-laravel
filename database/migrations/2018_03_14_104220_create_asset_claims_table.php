<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_claims', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ly_ckid')->unsigned()->comment('仓库的id');
            $table->integer('ly_zcid')->unsigned()->comment('资产的id');
            $table->integer('ly_uid')->unsigned()->comment('领用人的id');
            $table->char('ly_dwdm',10)->comment('领用人的单位代码');
            $table->char('ly_bmdm',4)->comment('领用人的部门代码');
            $table->char('ly_nums',5)->comment('领用数量');
            $table->char('ly_gsml',2)->comment('归属门类');
            $table->string('ly_zcyt',200)->comment('资产用途');
            $table->char('lyrq',10)->comment('领用日期');
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
        Schema::dropIfExists('asset_claims');
    }
}
