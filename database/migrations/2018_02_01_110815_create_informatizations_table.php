<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInformatizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informatizations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_myid')->unsigned()->comment('外键-用户id');
            $table->char('info_mydwdm',10)->comment('用户单位代码');          
            $table->string('info_myname',50)->comment('用户姓名');          
            $table->char('info_zsbh',30)->comment('信息化资格证书编号');
            $table->string('info_zsmc',100)->comment('信息化资格证书名称');
            $table->string('info_bzjg',50)->comment('信息化资格证书颁证机构');
            $table->char('info_fzrq',10)->comment('发证日期');
            $table->text('info_zspath')->comment('上传证书扫描件路径');
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
        Schema::dropIfExists('informatizations');
    }
}
