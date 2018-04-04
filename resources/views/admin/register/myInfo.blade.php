@extends("admin.layout.main")

@section("content")
<blockquote class="layui-elem-quote news_search">
	<div class="layui-inline">		
    	<div class="layui-inline" style="margin-left: 10px;">
    		<a id="down_resume" class="layui-btn" style="height: 32px;line-height: 32px;">下载简历</a>
    	</div> 			  
	</div>
</blockquote>
	<form class="layui-form layui-form-pane" >
	<input type="hidden" name="uid" value="{{ $admin['id'] }}">
	<div class="layui-form-item">
		<div class="layui-inline">		
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input real_name " lay-verify="required" placeholder="请输入姓名" name="real_name" value="{{ $admin['real_name'] }}" disabled>
			</div>
		</div>
		<div class="layui-inline">
		    <label class="layui-form-label">性别</label>
		    <div class="layui-input-block userSex">
		      	<input  type="radio" name="sex" value="男" title="男" @if($admin['sex']=='男') checked class="sex" @endif>
		      	<input  type="radio" name="sex" value="女" title="女" @if($admin['sex']=='女') checked class="sex" @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    </div>
	    </div>
		<div class="layui-inline">		
			<label class="layui-form-label">出生日期</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input birth" lay-verify="required|date" name="birth" value="{{ date('Y-m-d',$admin['birth']) }}" disabled>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">民族</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input nation" lay-verify="required" name="nation" value="{{ $nation }}" disabled>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label">籍贯</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input native_place" lay-verify="required" placeholder="请输入籍贯" name="native_place" value="{{ $admin['native_place'] }}" disabled>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label">出生地</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input native_heath" lay-verify="required" placeholder="请输入出生地" name="native_heath" value="{{ $admin['native_heath'] }}" disabled>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">政治面貌</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input political_outlook" lay-verify="required" name="political_outlook" value="{{ $political_outlook[$admin['political_outlook']] }}" disabled>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label">入党时间</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input birth join_party_time" lay-verify="required|date"  name="join_party_time" value="{{ date('Y-m-d',$admin['join_party_time']) }}" disabled>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="参加工作时间">参加工作时间</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input birth join_work_time" lay-verify="required|date" name="join_work_time" value="{{ date('Y-m-d',$admin['join_party_time']) }}" disabled>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">		
			<label class="layui-form-label">身份证号码</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input id_number" lay-verify="required|id_number" placeholder="请输入身份证号码" name="id_number" value="{{ $admin['id_number'] }}" disabled>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="进入检察院工作日期">进入检察院工作日期</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input birth join_procuratorate_time" lay-verify="required|date" name="join_procuratorate_time" value="{{ date('Y-m-d',$admin['join_procuratorate_time']) }}" disabled>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="进入技术部门工作日期">进入技术部门工作日期</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input birth join_technical_department_time" lay-verify="required|date" name="join_technical_department_time" value="{{ date('Y-m-d',$admin['join_technical_department_time']) }}" disabled>
			</div>
		</div>
	</div>
	
	<div class="layui-form-item">
		<div class="layui-inline">
		    <label class="layui-form-label">是否在岗</label>
		    <div class="layui-input-block if_work">
		      	<input type="radio" name="if_work" value="1" title="是" @if($admin['if_work']=='1') checked class="if_work" @endif>
		      	<input type="radio" name="if_work" value="2" title="否" @if($admin['if_work']=='2') checked class="if_work" @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    </div>
	    </div>
	    <div class="layui-inline">
			<label class="layui-form-label">学历</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input education" lay-verify="required" name="education" value="{{ $education[$admin['education']] }}" disabled>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">学位</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input academic_degree" lay-verify="required" name="academic_degree" value="{{ $academic_degree[$admin['academic_degree']] }}" disabled>
			</div>
		</div>
	</div>
	<div class="layui-form-item">	
		<div class="layui-inline">		
			<label class="layui-form-label" title="学历毕业院校及专业">学历毕业院校及专业</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input major_school" lay-verify="required" placeholder="请输入学历毕业院校及专业" name="major_school" value="{{ $admin['major_school'] }}" disabled>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="学位毕业院校及专业">学位毕业院校及专业</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input major_degree_school" lay-verify="required" placeholder="请输入学位毕业院校及专业" name="major_degree_school" value="{{ $admin['major_degree_school'] }}" disabled>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="最终学历毕业时间">最终学历毕业时间</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input birth get_education_time" lay-verify="required|date" name="get_education_time" value="{{ date('Y-m-d',$admin['get_education_time']) }}" disabled>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">		
			<label class="layui-form-label" title="获得学位时间">获得学位时间</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input birth get_academic_degree_time" lay-verify="required|date" name="get_academic_degree_time" value="{{ date('Y-m-d',$admin['get_academic_degree_time']) }}" disabled>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">检察官员额</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input procurator" lay-verify="required" name="procurator" value="{{ $procurator[$admin['procurator']] }}" disabled>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">行政职务</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input administrative_duties" lay-verify="required" name="administrative_duties" value="{{ $administrative_duties[$admin['administrative_duties']] }}" disabled>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">行政级别</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input administrative_level" lay-verify="required" name="administrative_level" value="{{ $administrative_level[$admin['administrative_level']] }}" disabled>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label" title="专业技师职称">专业技师职称</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input technician_title" lay-verify="required" name="technician_title" value="{{ $technician_title[$admin['technician_title']] }}" disabled>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">我的头像</label>
			<div class="layui-input-inline">
				<img src="{{ $admin['face'] }}">
			</div>
	</div>	
	<div class="layui-form-item">
		<label class="layui-form-label">简历</label>
		<div class="layui-input-block">
			<textarea placeholder="请输入简历" class="layui-textarea resume" name="resume">{{ $admin['resume'] }}</textarea>
		</div>
	</div>	
	</form>
@endsection
@section("js")
	<script type="text/javascript" src="/layadmin/modul/register/downresume.js"></script>
@endsection