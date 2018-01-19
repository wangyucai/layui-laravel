<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedTrainDirectionData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $time = \Carbon\Carbon::now()->toDateTimeString();
        $pxfx = [
            [
                'pxfx_code' => '01',
                'pxfx_name' => '行政管理',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'pxfx_code' => '02',
                'pxfx_name' => '综合事务',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'pxfx_code' => '03',
                'pxfx_name' => '检查技术',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'pxfx_code' => '04',
                'pxfx_name' => '信息化技术',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'pxfx_code' => '05',
                'pxfx_name' => '检查业务',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'pxfx_code' => '06',
                'pxfx_name' => '其它培训',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            
            
        ];
        DB::table('train_direction')->insert($pxfx);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('train_direction')->truncate();
    }
}
