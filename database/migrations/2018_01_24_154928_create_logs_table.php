<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('操作用户id');
            $table->string('user_name',30)->comment('操作用户名');
            $table->string('operator',30)->nullable()->comment('操作用户真实姓名');
            $table->string('method',30)->comment('方法名');
            $table->string('routes',100)->comment('路由地址');
            $table->string('record',100)->comment('操作记录');
            $table->string('ip',100)->comment('ip');
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
        Schema::dropIfExists('logs');
    }
}
