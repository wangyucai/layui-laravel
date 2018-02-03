<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromptMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prompt_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('receiver_id')->unsigned()->comment('接受提示消息用户ID');
            $table->integer('sender_id')->unsigned()->comment('反馈者用户ID');
            $table->char('sender_dwdm',10)->comment('反馈者用户单位代码');
            $table->tinyInteger('msg_type')->default(0)->comment('信息反馈类型');
            $table->text('msg_content')->comment('反馈内容');
            $table->integer('notice_id')->unsigned()->comment('通知ID');
            $table->tinyInteger('if_read')->default(0)->comment('是否已读');
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
        Schema::dropIfExists('prompt_messages');
    }
}
