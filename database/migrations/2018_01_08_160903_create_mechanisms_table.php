<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMechanismsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mechanisms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_dwdm')->unsigned()->comment('本部门的单位代码');
            $table->char('mechanism_code','4')->comment('内设机构代码');
            $table->string('nsjgmc',50)->comment('内设机构名称');
            $table->tinyInteger('nsjgxzjb')->comment('内设机构行政级别');
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
        Schema::drop('mechanisms');
    }
}
