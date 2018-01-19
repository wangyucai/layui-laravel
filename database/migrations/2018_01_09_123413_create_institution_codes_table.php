<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->char('jdjg_dwdm',6)->comment('所属单位代码');
            $table->char('jdjg_code',6)->comment('鉴定机构代码');
            $table->string('jdjg_name',100)->comment('鉴定机构名称');
            $table->char('fj_jdjg_code',6)->comment('父级鉴定机构代码');
            $table->tinyInteger('jdjg_level')->comment('行政级别(省市县)');
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
        Schema::dropIfExists('institution_codes');
    }
}
