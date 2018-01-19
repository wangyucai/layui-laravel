<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCompaniesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = [
            [
                'dwdm' => '100000',
                'dwjc' => '高检院',
                'dwqc' => '最高人民检察院',
                'dwsx' => '高检',
                'dwjb' => '1',
                'sjdm' => '000000',
                'sjxs' => 'zgj',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520000',
                'dwjc' => '贵州省院',
                'dwqc' => '贵州省人民检察院',
                'dwsx' => '黔检',
                'dwjb' => '2',
                'sjdm' => '100000',
                'sjxs' => 'gj',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520100',
                'dwjc' => '贵阳市院',
                'dwqc' => '贵阳市人民检察院',
                'dwsx' => '',
                'dwjb' => '3',
                'sjdm' => '520000',
                'sjxs' => 'gz',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520102',
                'dwjc' => '贵阳市南明区院',
                'dwqc' => '贵阳市南明区人民检察院',
                'dwsx' => '',
                'dwjb' => '4',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520103',
                'dwjc' => '贵阳市云岩区院',
                'dwqc' => '贵阳市云岩区人民检察院',
                'dwsx' => '',
                'dwjb' => '4',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520111',
                'dwjc' => '贵阳市花溪区院',
                'dwqc' => '贵阳市花溪区人民检察院',
                'dwsx' => '',
                'dwjb' => '4',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520112',
                'dwjc' => '贵阳市乌当区院',
                'dwqc' => '贵阳市乌当区人民检察院',
                'dwsx' => '',
                'dwjb' => '4',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520113',
                'dwjc' => '贵阳市白云区院',
                'dwqc' => '贵阳市白云区人民检察院',
                'dwsx' => '',
                'dwjb' => '4',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520114',
                'dwjc' => '贵阳市小河区院',
                'dwqc' => '贵阳市小河区人民检察院',
                'dwsx' => '',
                'dwjb' => '4',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520121',
                'dwjc' => '贵州省开阳县院',
                'dwqc' => '开阳县人民检察院',
                'dwsx' => '',
                'dwjb' => '4',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520122',
                'dwjc' => '贵州省息峰县院',
                'dwqc' => '息烽县人民检察院',
                'dwsx' => '',
                'dwjb' => '3',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520123',
                'dwjc' => '贵州省修文县院',
                'dwqc' => '修文县人民检察院',
                'dwsx' => '',
                'dwjb' => '4',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520181',
                'dwjc' => '贵州省清镇市院',
                'dwqc' => '清镇市人民检察院',
                'dwsx' => '',
                'dwjb' => '4',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            [
                'dwdm' => '520192',
                'dwjc' => '贵阳市筑城院',
                'dwqc' => '贵阳市筑城地区人民检察院',
                'dwsx' => '',
                'dwjb' => '4',
                'sjdm' => '520100',
                'sjxs' => 'gy',
                'dwdz' => '',
                'yzbm' => '',
            ],
            
        ];
        DB::table('companies')->insert($companies);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 清空单位表的数据
        DB::table('companies')->truncate();
    }
}
