<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->text('email_receivers')->comment('邮件接收者');
            $table->integer('email_sender')->unsigned()->comment('邮件发送者ID');
            $table->string('email_theme',100)->comment('邮件主题');
            $table->text('email_content')->comment('邮件内容');
            $table->text('email_pics')->nullable()->comment('邮件图片地址');
            $table->text('email_attachments')->nullable()->comment('邮件附件地址');
            $table->timestamps();
        });
        // 关系表
        Schema::create('user_email', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->integer('email_id')->default(0);
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
        Schema::dropIfExists('emails');
        Schema::dropIfExists('user_email');
    }
}
