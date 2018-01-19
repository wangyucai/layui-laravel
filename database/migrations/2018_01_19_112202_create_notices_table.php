<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',50)->comment('通知标题');
            $table->text('notice_dwdm')->comment('通知单位');
            $table->text('content')->comment('通知内容');
            $table->text('content2')->nullable()->comment('培训模块通知内容');
            $table->string('attachment',500)->nullable()->comment('通知的附件地址');
            $table->char('notice_yxq',10)->comment('通知的有效期');
            $table->tinyInteger('if_expire')->default(0)->comment('默认未过期，1代表过期');       
            $table->string('from_dw',100)->comment('发布通知的单位');
            $table->tinyInteger('type')->comment('通知类型');
            $table->integer('px_id')->unsigned()->comment('如果是培训通知就有培训id');
            $table->char('from_dwdm',10)->comment('发送通知人的单位代码');
            $table->timestamps();
        });
        // 关系表
        Schema::create('user_notice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->integer('notice_id')->default(0);
            $table->tinyInteger('if_read')->default(0)->comment('是否阅读');
            $table->tinyInteger('if_down')->default(0)->comment('是否下载');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notices');
        Schema::dropIfExists('user_notice');
    }
}
