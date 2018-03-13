<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetUnitsTable extends Migration
{
    /**
     * Run the migrations.
     * 资产单位表
     * @return void
     */
    public function up()
    {
        Schema::create('asset_units', function (Blueprint $table) {
            $table->increments('id');
            $table->char('zcdw_code',4)->index()->comment('资产单位代码');
            $table->string('zcdw_name',100)->comment('资产单位名称');
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
        Schema::dropIfExists('asset_units');
    }
}
