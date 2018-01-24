<?php
return [
	// 权限管理--权限操作
	'/admin/rule.post'   		=> '添加权限',
	'/admin/rule.put'    		=> '编辑权限',
    '/admin/rule.del'  	 		=> '删除权限',
    '/admin/rule.patch'  		=> '配置角色权限',
    // 权限管理--系统管理员操作
    'admin/user.post'  	 		=> '添加管理员',
    'admin/user/password.put'  	=> '修改密码',
    'admin/user.put' 	 		=> '编辑管理员',
    'admin/user.patch'	 		=> '启用或禁用管理员',
    'admin/user.del'  	 		=> '删除管理员',
    // 权限管理--角色操作
    'admin/role.post'  	 		=> '添加角色',
    'admin/role.put' 	 		=> '编辑角色',
    'admin/role.del'  	 		=> '删除角色',
    // 通知管理--通知列表
    'admin/notice.post'  		=> '添加通知',
    'admin/notice/upload.post'  => '上传通知附件',
    'admin/notice.put' 	 		=> '编辑通知',
    'admin/notice.del'   		=> '删除通知',
    // 通知管理--通知类型列表
    'admin/noticetype.post'  	=> '添加通知类型',
    'admin/noticetype.put' 	 	=> '编辑通知类型',
    'admin/noticetype.del'   	=> '删除通知类型',
    // 通知管理--我的通知
    'admin/readmynotice.post'  	=> '阅读一条通知',
    'admin/downattachment.post' => '下载通知附件',
    // 单位部门管理--单位列表
    'admin/company.post'  		=> '添加单位',
    'admin/company.put' 	 	=> '编辑单位',
    'admin/company.del'   		=> '删除单位',
    // 单位部门管理--内设机构代码管理
    'admin/mechanismcode.post'  => '添加内设机构代码',
    'admin/mechanismcode.put' 	=> '编辑内设机构代码',
    'admin/mechanismcode.del'   => '删除内设机构代码',
    // 单位部门管理--本单位的部门
    'admin/mymechanismcode.post'  => '添加内设机构',
    'admin/mymechanismcode.put'   => '编辑内设机构',
    'admin/mymechanismcode.del'   => '删除内设机构',
    // 人员管理系统--
];