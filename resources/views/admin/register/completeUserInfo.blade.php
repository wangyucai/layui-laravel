@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
	<input type="hidden" name="_token" class="tag_token" value="<?php echo csrf_token(); ?>">
	<input type="hidden" name="dwjb" value="{{ $dwjb }}">
	<input type="hidden" name="uid" value="{{$uid}}">
	<div class="layui-form-item">
		<div class="layui-inline">		
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input real_name" lay-verify="required" placeholder="请输入姓名" name="real_name">
			</div>
		</div>
		<div class="layui-inline">
		    <label class="layui-form-label">性别</label>
		    <div class="layui-input-block userSex">
		      	<input type="radio" name="sex" value="男" title="男" checked>
		      	<input type="radio" name="sex" value="女" title="女">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    </div>
	    </div>
		<div class="layui-inline">		
			<label class="layui-form-label">出生日期</label>
			<div class="layui-input-inline">
				<input type="text" id="birth" class="layui-input birth" lay-verify="required|date" name="birth">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">民族</label>
			<div class="layui-input-inline">
				<select name="nation" class="nation" lay-filter="nation">
			        <option value="">请选择民族</option>
			        @foreach($nations as $nation)
			        <option value="{{ $nation->id }}">{{ $nation->nation_name }}</option>
			        @endforeach  
			    </select>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label">籍贯</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input native_place" lay-verify="required" placeholder="请输入籍贯" name="native_place">
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label">出生地</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input native_heath" lay-verify="required" placeholder="请输入出生地" name="native_heath">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">政治面貌</label>
			<div class="layui-input-inline">
				<select name="political_outlook" class="political_outlook" lay-filter="zzmm" lay-verify="required|zzmm">	
			        <option value="">请选择政治面貌</option>
			        @foreach($political_outlook as $k=>$v)
			        <option value="{{ $k }}">{{ $v }}</option>
			        @endforeach  
			    </select>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label">入党时间</label>
			<div class="layui-input-inline">
				<input type="text" id="join_party_time" class="layui-input join_party_time" lay-verify="required|date"  name="join_party_time">
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="参加工作时间">参加工作时间</label>
			<div class="layui-input-inline">
				<input type="text" id="join_work_time" class="layui-input join_work_time" lay-verify="required|date" name="join_work_time">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">		
			<label class="layui-form-label">身份证号码</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input id_number" lay-verify="required|id_number" placeholder="请输入身份证号码" name="id_number">
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="进入检察院工作日期">进入检察院工作日期</label>
			<div class="layui-input-inline">
				<input type="text" id="join_procuratorate_time" class="layui-input join_procuratorate_time" lay-verify="required|date" name="join_procuratorate_time">
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="进入技术部门工作日期">进入技术部门工作日期</label>
			<div class="layui-input-inline">
				<input type="text" id="join_technical_department_time" class="layui-input join_technical_department_time" lay-verify="required|date" name="join_technical_department_time">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">		
			<label class="layui-form-label">头像上传</label>
			<div class="layui-input-inline">
				<button type="button" class="layui-btn" id="face" name="face">
				  <i class="layui-icon">&#xe67c;</i>上传头像
				</button>
			</div>
		</div>
		<div class="layui-inline">
		    <label class="layui-form-label">是否在岗</label>
		    <div class="layui-input-block if_work">
		      	<input type="radio" name="if_work" value="1" title="是" checked>
		      	<input type="radio" name="if_work" value="2" title="否">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		    </div>
	    </div>
	    <div class="layui-inline">
			<label class="layui-form-label">学历</label>
			<div class="layui-input-inline">
				<select name="education" class="education" lay-filter="education">
			        <option value="">请选择学历</option>
			        @foreach($education as $k=>$v)
			        <option value="{{ $k }}">{{ $v }}</option>
			        @endforeach  
			    </select>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">学位</label>
			<div class="layui-input-inline">
				<select name="academic_degree" class="academic_degree" lay-filter="academic_degree">
			        <option value="">请选择学位</option>
			        @foreach($academic_degree as $k=>$v)
			        <option value="{{ $k }}">{{ $v }}</option>
			        @endforeach 
			    </select>
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="学历毕业院校及专业">学历毕业院校及专业</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input major_school" lay-verify="required" placeholder="请输入学历毕业院校及专业" name="major_school">
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="学位毕业院校及专业">学位毕业院校及专业</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input major_degree_school" lay-verify="required" placeholder="请输入学位毕业院校及专业" name="major_degree_school">
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">		
			<label class="layui-form-label" title="最终学历毕业时间">最终学历毕业时间</label>
			<div class="layui-input-inline">
				<input type="text" id="get_education_time" class="layui-input get_education_time" lay-verify="required|date" name="get_education_time">
			</div>
		</div>
		<div class="layui-inline">		
			<label class="layui-form-label" title="获得学位时间">获得学位时间</label>
			<div class="layui-input-inline">
				<input type="text" id="get_academic_degree_time" class="layui-input get_academic_degree_time" lay-verify="required|date" name="get_academic_degree_time">
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">检察官员额</label>
			<div class="layui-input-inline">
				<select name="procurator" class="procurator" lay-filter="procurator">
			        <option value="">请选择检察官员额</option>
			        @foreach($procurator as $k=>$v)
			        <option value="{{ $k }}">{{ $v }}</option>
			        @endforeach 
			    </select>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-inline">
			<label class="layui-form-label">行政职务</label>
			<div class="layui-input-inline">
				<select name="administrative_duties" class="administrative_duties" lay-filter="administrative_duties">
			        <option value="">请选择行政职务</option>
			        @foreach($administrative_duties as $k=>$v)
			        <option value="{{ $k }}">{{ $v }}</option>
			        @endforeach
			    </select>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label">行政级别</label>
			<div class="layui-input-inline">
				<select name="administrative_level" class="administrative_level" lay-filter="administrative_level">
			        <option value="">请选择行政级别</option>
			        @foreach($administrative_level as $k=>$v)
			        <option value="{{ $k }}">{{ $v }}</option>
			        @endforeach
			    </select>
			</div>
		</div>
		<div class="layui-inline">
			<label class="layui-form-label" title="专业技师职称">专业技师职称</label>
			<div class="layui-input-inline">
				<select name="technician_title" class="technician_title" lay-filter="technician_title">
			        <option value="">请选择专业技师职称</option>
			        @foreach($technician_title as $k=>$v)
			        <option value="{{ $k }}">{{ $v }}</option>
			        @endforeach
			    </select>
			</div>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">简历</label>
		<div class="layui-input-block">
			<textarea placeholder="请输入简历" class="layui-textarea" name="resume"></textarea>
		</div>
	</div>	
	<div class="layui-form-item">
		<div class="layui-input-block">
			<button class="layui-btn" type="button" lay-submit lay-filter="completeuser">立即提交
			<button type="reset" class="layui-btn layui-btn-primary">重置</button>
	    </div>
	</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/register/completeUserInfo.js"></script>
@endsection
