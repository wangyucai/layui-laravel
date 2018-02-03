<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTelHmAndCompanyIdAndMechanismIdToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('tel_hm',30)->nullable()->comment('电话号码');
            $table->integer('company_id')->unsigned()->comment('我的单位id');
            $table->integer('mechanism_id')->unsigned()->comment('我的单位下部门的id');
            $table->char('mechanism_code',4)->comment('我的单位下部门的代码');
            $table->string('real_name',30)->nullable()->comment('真实姓名');
            $table->tinyInteger('dwjb')->nullable()->comment('单位级别');
            $table->char('sex',4)->nullable()->comment('性别');
            $table->char('birth',10)->nullable()->comment('出生年月');
            $table->char('nation',2)->nullable()->comment('民族');
            $table->string('native_place',200)->nullable()->comment('籍贯');
            $table->string('native_heath',200)->nullable()->comment('出生地');
            $table->string('political_outlook',100)->nullable()->comment('政治面貌');
            $table->char('join_party_time',10)->nullable()->comment('入党时间');
            $table->char('join_work_time',10)->nullable()->comment('参加工作时间');
            $table->char('id_number',18)->nullable()->comment('身份证号');
            $table->char('join_procuratorate_time',10)->nullable()->comment('进入检察院工作日期');
            $table->char('join_technical_department_time',10)->nullable()->comment('进入技术部门工作日期');
            $table->string('education',100)->nullable()->comment('学历');
            $table->string('academic_degree',100)->nullable()->comment('学位');
            $table->string('major_school',200)->nullable()->comment('学历毕业院校及专业');
            $table->string('major_degree_school',200)->nullable()->comment('学位毕业院校及专业');
            $table->char('get_education_time',10)->nullable()->comment('最终学历毕业时间');
            $table->char('get_academic_degree_time',10)->nullable()->comment('获得学位时间');
            $table->string('procurator',100)->nullable()->comment('检察官员额');
            $table->string('administrative_duties',100)->nullable()->comment('行政职务');
            $table->string('technician_title',100)->nullable()->comment('专业技师职称');
            $table->text('resume')->nullable()->comment('简历');
            $table->string('face',200)->nullable()->comment('头像');
            $table->integer('if_work')->unsigned()->default(0)->comment('是否在岗');
            $table->integer('register_if_check')->unsigned()->default(0)->comment('注册信息是否审核');
            $table->integer('perinfor_if_check')->unsigned()->default(0)->comment('完善信息是否审核');
            $table->tinyInteger('no_notice_count')->default(0)->comment('未读通知数量');
            $table->tinyInteger('no_email_count')->default(0)->comment('未读邮件数量');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('tel_hm');
            $table->dropColumn('company_id');
            $table->dropColumn('mechanism_id');
            $table->dropColumn('mechanism_code');
            $table->dropColumn('real_name');
            $table->dropColumn('dwjb');
            $table->dropColumn('sex');
            $table->dropColumn('birth');
            $table->dropColumn('nation');
            $table->dropColumn('native_place');
            $table->dropColumn('native_heath');
            $table->dropColumn('political_outlook');
            $table->dropColumn('join_party_time');
            $table->dropColumn('join_work_time');
            $table->dropColumn('id_number');
            $table->dropColumn('join_procuratorate_time');
            $table->dropColumn('join_technical_department_time');
            $table->dropColumn('education');
            $table->dropColumn('academic_degree');
            $table->dropColumn('major_school');
            $table->dropColumn('major_degree_school');
            $table->dropColumn('get_education_time');
            $table->dropColumn('get_academic_degree_time');
            $table->dropColumn('procurator');
            $table->dropColumn('administrative_duties');
            $table->dropColumn('technician_title');
            $table->dropColumn('resume');
            $table->dropColumn('face');
            $table->dropColumn('if_work');
            $table->dropColumn('register_if_check');
            $table->dropColumn('perinfor_if_check');
            $table->dropColumn('no_notice_count');
            $table->dropColumn('no_email_count');
        });
    }
}
