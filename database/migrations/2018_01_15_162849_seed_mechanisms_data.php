<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Model\Company;
use App\Model\MechanismCode;

class SeedMechanismsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        $mechanisms = [];
        $nsjgmc = [
            '1'=> '检查技术部门',
            '2'=> '信息化术部门',
            '3'=> '公诉部门',
            '4'=> '侦查监督部门',
            '5'=> '刑事执行检查部门',
            '6'=> '生态环境保护部门',
            '7'=> '预防部门',
            '8'=> '民政部门',
            '9'=> '案件管理部门',
            '10'=> '未检部门',
            '11'=> '控告部门',
            '12'=> '申诉部门',
            '13'=> '反贪部门',
            '14'=> '反渎职侵权部门',
        ];
        $Companies = Company::where('id','!=',1)->get();
        $nsjgs = MechanismCode::all();
        foreach ($Companies as $company) {
            foreach ($nsjgs as $nsjg) {
               $mechanisms[] = [
                    'company_dwdm' => $company->dwdm,
                    'mechanism_code' => $nsjg->code,
                    'nsjgmc' => $nsjg->code_name,
                    'nsjgxzjb' => $company->dwjb,
                    'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
                    'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
                ];
            }
        }
        DB::table('mechanisms')->insert($mechanisms);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('mechanisms')->truncate();
    }
}
