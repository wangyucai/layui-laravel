<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIfEnterAndEnterDwdmAndIfSelectAndSelecterDwdmToUserNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_notice', function (Blueprint $table) {
            $table->tinyInteger('if_enter')->default(0)->comment('是否报名');
            $table->char('enter_dwdm',10)->nullable()->comment('报名者的单位代码');
            $table->tinyInteger('county_if_selected')->default(0)->comment('是否被县级管理员上报');
            $table->char('county_dwdm',10)->nullable()->comment('县上报者的单位代码');
            $table->tinyInteger('city_if_selected')->default(0)->comment('是否被市级管理员上报');
            $table->char('city_dwdm',10)->nullable()->comment('市上报者的单位代码');
            $table->tinyInteger('province_if_selected')->default(0)->comment('是否被省级管理员上报');
            $table->char('province_dwdm',10)->nullable()->comment('省上报者的单位代码');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_notice', function (Blueprint $table) {
            $table->dropColumn('if_enter');
            $table->dropColumn('enter_dwdm');
            $table->dropColumn('county_if_selected');
            $table->dropColumn('county_dwdm');
            $table->dropColumn('city_if_selected');
            $table->dropColumn('city_dwdm');
            $table->dropColumn('province_if_selected');
            $table->dropColumn('province_dwdm');
        });
    }
}
