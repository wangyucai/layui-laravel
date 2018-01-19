<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedInforTechnologyData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $time = \Carbon\Carbon::now()->toDateTimeString();
        $xxhjs = [
            [
                'xxhjs_code' => 'A0',
                'xxhjs_name' => '操作系统',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'xxhjs_code' => 'B0',
                'xxhjs_name' => '数据库技术',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'xxhjs_code' => 'C0',
                'xxhjs_name' => '网络技术',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'xxhjs_code' => 'D0',
                'xxhjs_name' => '大数据',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'xxhjs_code' => 'E0',
                'xxhjs_name' => '人工智能',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'xxhjs_code' => 'F0',
                'xxhjs_name' => '智能手机',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'xxhjs_code' => 'F1',
                'xxhjs_name' => '移动终端',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'xxhjs_code' => 'Z',
                'xxhjs_name' => '其它',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            
        ];
        DB::table('infor_technology')->insert($xxhjs);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('infor_technology')->truncate();
    }
}
