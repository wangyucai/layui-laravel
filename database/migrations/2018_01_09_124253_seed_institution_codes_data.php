<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedInstitutionCodesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $institution_codes = [
            [
                'jdjg_dwdm' => '520000',
                'jdjg_code' => '01',
                'jdjg_name' => '贵州省人民检察院司法鉴定中心',
                'fj_jdjg_code' => 0,
                'jdjg_level' => 1,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ],
            [
                'jdjg_dwdm' => '520100',
                'jdjg_code' => '02',
                'jdjg_name' => '贵州省贵阳市人民检察院司法鉴定中心',
                'fj_jdjg_code' => '520000',
                'jdjg_level' => 2,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ],
            [
                'jdjg_dwdm' => '520300',
                'jdjg_code' => '03',
                'jdjg_name' => '贵州省遵义市人民检察院司法鉴定中心',
                'fj_jdjg_code' => '520000',
                'jdjg_level' => 2,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ],
            [
                'jdjg_dwdm' => '520200',
                'jdjg_code' => '04',
                'jdjg_name' => '贵州省六盘水市人民检察院司法鉴定中心',
                'fj_jdjg_code' => '520000',
                'jdjg_level' => 2,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ],
            [
                'jdjg_dwdm' => '520400',
                'jdjg_code' => '05',
                'jdjg_name' => '贵州省安顺市人民检察院司法鉴定中心',
                'fj_jdjg_code' => '520000',
                'jdjg_level' => 2,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ],
            [
                'jdjg_dwdm' => '522400',
                'jdjg_code' => '06',
                'jdjg_name' => '贵州省毕节市人民检察院司法鉴定中心',
                'fj_jdjg_code' => '520000',
                'jdjg_level' => 2,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ],
            [
                'jdjg_dwdm' => '522200',
                'jdjg_code' => '07',
                'jdjg_name' => '贵州省铜仁市人民检察院司法鉴定中心',
                'fj_jdjg_code' => '520000',
                'jdjg_level' => 2,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ],
            [
                'jdjg_dwdm' => '522600',
                'jdjg_code' => '08',
                'jdjg_name' => '贵州省黔东南苗族侗族自治州人民检察院司法鉴定中心',
                'fj_jdjg_code' => '520000',
                'jdjg_level' => 2,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ],
            [
                'jdjg_dwdm' => '522700',
                'jdjg_code' => '09',
                'jdjg_name' => '贵州省黔南布依族苗族自治州人民检察院司法鉴定中心',
                'fj_jdjg_code' => '520000',
                'jdjg_level' => 2,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ],
            [
                'jdjg_dwdm' => '522300',
                'jdjg_code' => '10',
                'jdjg_name' => '贵州省黔西南布依族苗族自治州人民检察院司法鉴定中心',
                'fj_jdjg_code' => '520000',
                'jdjg_level' => 2,
                'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
            ],
        ];
        DB::table('institution_codes')->insert($institution_codes);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('institution_codes')->truncate();
    }
}
