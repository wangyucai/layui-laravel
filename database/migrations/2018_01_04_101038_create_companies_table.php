<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->char('dwdm',6)->index()->comment('单位代码');
            $table->string('dwjc',50)->comment('单位简称');
            $table->string('dwqc',100)->comment('单位全称');
            $table->string('dwsx',30)->nullable()->comment('单位缩写');
            $table->tinyInteger('dwjb')->default(0)->comment('单位级别');
            $table->char('sjdm',6)->comment('上级单位代码');
            $table->char('sjxs',4)->comment('上级缩写');
            $table->string('dwdz',200)->nullable()->comment('单位地址');
            $table->char('yzbm',6)->nullable()->comment('邮政编码');
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
        Schema::dropIfExists('companies');
    }
}
