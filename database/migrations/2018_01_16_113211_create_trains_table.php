<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->unsigned()->comment('用户id');
            $table->char('px_dwdm',10)->comment('发布培训人的单位代码');
            $table->string('px_title',200)->comment('培训标题');
            $table->string('px_place',200)->comment('培训地点');
            $table->char('px_start_time',10)->comment('培训开始时间');
            $table->char('px_end_time',10)->comment('培训结束时间');
            $table->tinyInteger('if_expire')->default(0)->comment('是否过期');
            $table->char('px_time',32)->default(0)->comment('培训时长');
            $table->integer('zbdw_id')->unsigned()->comment('主板单位id');
            $table->string('px_fx',50)->comment('培训方向');
            $table->char('px_renshu',10)->comment('培训人数');
            $table->text('px_notice_dw')->nullable()->comment('培训通知单位');
            $table->tinyInteger('if_notice')->default(0)->comment('该培训是否发送过通知');     
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
        Schema::dropIfExists('trains');
    }
}
