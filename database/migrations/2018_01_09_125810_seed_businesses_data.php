<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedBusinessesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        $time = \Carbon\Carbon::now()->toDateTimeString();
        $businesses = [
            [
                'jdywfw_code' => 'A0',
                'jdywfw_name' => '法医类',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'A1',
                'jdywfw_name' => '法医病理',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'A2',
                'jdywfw_name' => '法医临床',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'A3',
                'jdywfw_name' => '法医精神病',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'A4',
                'jdywfw_name' => '法医物证',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'A5',
                'jdywfw_name' => '法医毒物',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'B0',
                'jdywfw_name' => '物证类',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'B1',
                'jdywfw_name' => '文物鉴定(文件检验)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'B2',
                'jdywfw_name' => '痕迹鉴定(痕迹检验)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'B3',
                'jdywfw_name' => '微量物证鉴定(理化检验)',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'C0',
                'jdywfw_name' => '电子证据',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'D0',
                'jdywfw_name' => '司法会计',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'E0',
                'jdywfw_name' => '心理测试',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'F1',
                'jdywfw_name' => '录音资料检验',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'F2',
                'jdywfw_name' => '图像资料检验',
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'jdywfw_code' => 'Z',
                'jdywfw_name' => '其他',
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];
        DB::table('businesses')->insert($businesses);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('businesses')->truncate();
    }
}
