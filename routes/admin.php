<?php

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
    Route::get('login', 'LoginController@index');
    Route::post('login', 'LoginController@signIn');
    Route::get('captcha/{rcode?}', 'LoginController@captcha');
    Route::get('logout', 'LoginController@logout');
    // 注册
    Route::get('register', 'RegisterController@index');
    Route::post('register', 'RegisterController@register');

    Route::group(['middleware' => 'auth:admin'], function () {
        
        Route::group(['middleware' => 'logs'], function () {
        
        Route::get('/', 'IndexController@index');
        Route::get('index', 'IndexController@index');
        Route::get('menu', 'IndexController@getMenu');
        Route::get('forbidden', 'IndexController@forbidden');
        Route::get('main', 'IndexController@main');
        /*
         * 权限管理模块
        */
        Route::group(['middleware' => 'rbac'], function () {
            Route::get('user/password/edit', 'IndexController@editPassword');
            Route::put('user/password', 'IndexController@editPassword');
            // 管理员管理
            Route::get('usersList', 'RuleController@adminsPage');
            Route::get('users', 'RuleController@getAdmins');
            Route::get('user/create', 'RuleController@addAdmin');
            Route::post('user', 'RuleController@addAdmin');
            Route::get('user/{id}/edit', 'RuleController@editAdmin');
            Route::put('user', 'RuleController@editAdmin');
            Route::patch('user', 'RuleController@activeAdmin');
            Route::delete('user', 'RuleController@delAdmin');
            // 权限管理
            Route::get('rules', 'RuleController@rules');
            Route::get('rule/create', 'RuleController@addRule');
            Route::post('rule', 'RuleController@addRule');
            Route::get('rule/{id}/edit', 'RuleController@editRule');
            Route::put('rule', 'RuleController@editRule');
            Route::delete('rule', 'RuleController@deleteRule');
            Route::patch('rule', 'RuleController@editRuleStatus');
            // 角色管理
            Route::get('role/create', 'RuleController@addRole');
            Route::post('role', 'RuleController@addRole');
            Route::get('role/{id}/edit', 'RuleController@editRole');
            Route::put('role', 'RuleController@editRole');
            Route::delete('role', 'RuleController@deleteRole');
            Route::get('roles', 'RuleController@roles');
            // 权限配置
            Route::get('role/{role_id}/rules', 'RuleController@setRules');
            Route::put('role/{role_id}/rules', 'RuleController@storeRules');

            /*
             * 单位部门管理
            */
            // 单位列表
            Route::get('company', 'CompanyController@companyList');
            Route::get('companies', 'CompanyController@getCompanies');
            Route::get('company/create', 'CompanyController@addCompany');
            Route::post('company', 'CompanyController@addCompany');
            Route::get('company/{id}/edit', 'CompanyController@editCompany');
            Route::put('company', 'CompanyController@editCompany');
            Route::delete('company', 'CompanyController@delCompany');
            // 内设机构代码管理
            Route::get('mechanismcode', 'MechanismCodeController@mechanismCodesList');
            Route::get('mechanismcodes', 'MechanismCodeController@getMechanismCodes');
            Route::get('mechanismcode/create', 'MechanismCodeController@addMechanismCode');
            Route::post('mechanismcode', 'MechanismCodeController@addMechanismCode');
            Route::get('mechanismcode/{id}/edit', 'MechanismCodeController@editMechanismCode');
            Route::put('mechanismcode', 'MechanismCodeController@editMechanismCode');
            Route::delete('mechanismcode', 'MechanismCodeController@delMechanismCode');
            // 本单位的部门(内设机构)
            Route::get('mymechanismcode', 'MechanismCodeController@myMechanismCodesList');
            Route::get('mymechanismcodes', 'MechanismCodeController@getMyMechanismCodes');
            Route::get('mymechanismcode/create', 'MechanismCodeController@addMyMechanismCode');
            Route::post('mymechanismcode', 'MechanismCodeController@addMyMechanismCode');
            Route::get('mymechanismcode/{id}/edit', 'MechanismCodeController@editMyMechanismCode');
            Route::put('mymechanismcode', 'MechanismCodeController@editMyMechanismCode');
            Route::delete('mymechanismcode', 'MechanismCodeController@delMyMechanismCode');
            
            /*
             * 人员管理系统
            */
            // 注册用户管理
            Route::get('registeruser', 'RegisterController@registerList');
            Route::get('registerusers', 'RegisterController@getRegisterUsers');
            Route::get('registeruser/{id}/edit', 'RegisterController@editRegisterUser')->middleware('edituserbutton');
            Route::put('registeruser', 'RegisterController@editRegisterUser');
            Route::patch('registeruser', 'RegisterController@activeRegisterUser');
            // Route::delete('registeruser', 'RegisterController@delAdmin');     
            // 完善人事信息用户管理
            Route::get('completeinfouser', 'RegisterController@completeInfoUserList');
            Route::get('completeinfousers', 'RegisterController@getCompleteInfoUsers');
            Route::get('completeinfouser/{id}/edit', 'Registercontroller@editCompleteInfoUser')->middleware('edituserbutton');
            Route::put('completeinfouser', 'RegisterController@editCompleteInfoUser');
            Route::patch('completeinfouser', 'RegisterController@activeCompleteInfoUser');
            // Route::delete('completeinfouser', 'RegisterController@delAdmin');
            /*
             * 机构管理系统
            */
            // 司法鉴定机构代码列表
            Route::get('institutioncode', 'InstitutionCodeController@institutionCodesList');
            Route::get('institutioncodes', 'InstitutionCodeController@getInstitutionCodes');
            Route::get('institutioncode/create', 'InstitutionCodeController@addInstitutionCode');
            Route::post('institutioncode', 'InstitutionCodeController@addInstitutionCode');
            Route::get('institutioncode/{id}/edit', 'InstitutionCodeController@editInstitutionCodes');
            Route::put('institutioncode', 'InstitutionCodeController@editInstitutionCodes');
            Route::delete('institutioncode', 'InstitutionCodeController@delInstitutionCodes');     
            // 司法鉴定业务范围管理
            Route::get('business', 'BusinessController@businessesList');
            Route::get('businesses', 'BusinessController@getBusinesses');
            Route::get('business/create', 'BusinessController@addBusiness');
            Route::post('business', 'BusinessController@addBusiness');
            Route::get('business/{id}/edit', 'BusinessController@editBusiness');
            Route::put('business', 'BusinessController@editBusiness');
            Route::delete('business', 'BusinessController@delBusiness');

            // 司法鉴定机构证书
            Route::get('inscertificate', 'InscertificateController@inscertificatesList');
            Route::get('inscertificates', 'InscertificateController@getInscertificates');
            Route::get('inscertificate/create', 'InscertificateController@addInscertificate');
            Route::post('inscertificate', 'InscertificateController@addInscertificate');
            Route::get('inscertificate/{id}/edit', 'InscertificateController@editInscertificate');
            Route::put('inscertificate', 'InscertificateController@editInscertificate');
            Route::patch('inscertificate', 'InscertificateController@activeInscertificate');
            Route::delete('inscertificate', 'InscertificateController@delInscertificate');
            
            // 本机构证书查询列表
            Route::get('myinscertificate', 'IdentifyinfoController@myInscertificateList');
            Route::get('myinscertificates', 'IdentifyinfoController@getMyInscertificates');

            // 各级鉴定机构证书查询统计列表
            Route::get('alllevelinscertificate', 'IdentifyinfoController@allLevelInscertificateList');
            Route::get('alllevelinscertificates', 'IdentifyinfoController@getAllLevelInscertificates');
            // 鉴定人员统计查询
            Route::get('appraiserstatistic', 'IdentifyinfoController@appraiserStatisticList');
            Route::get('appraiserstatistics', 'IdentifyinfoController@getAppraiserStatistics');
            Route::get('appraiserstatistics/look/{id}', 'IdentifyinfoController@lookAppraiserStatistics');
            Route::get('appraiserstatistics/looks', 'IdentifyinfoController@getLookAppraiserStatistics');

            /*
             * 培训模块管理
            */
            // 培训信息列表
            Route::get('trainmodule', 'TrainController@trainList');
            Route::get('trainmodules', 'TrainController@getTrain');
            Route::get('trainmodule/create', 'TrainController@addTrain');
            Route::post('trainmodule', 'TrainController@addTrain');
            Route::get('trainmodule/{id}/edit', 'TrainController@editTrain');
            Route::put('trainmodule', 'TrainController@editTrain');
            Route::delete('trainmodule', 'TrainController@delTrain');  
            // 信息化技术代码表
            Route::get('infortech', 'InforTechnologyController@infortechList');
            Route::get('infortechs', 'InforTechnologyController@getInfortech');
            Route::get('infortech/create', 'InforTechnologyController@addInfortech');
            Route::post('infortech', 'InforTechnologyController@addInfortech');
            Route::get('infortech/{id}/edit', 'InforTechnologyController@editInfortech');
            Route::put('infortech', 'InforTechnologyController@editInfortech');
            Route::delete('infortech', 'InforTechnologyController@delInfortech');
            // 培训方向代码表
            Route::get('traindirection', 'TrainDirectionController@trainDirectionList');
            Route::get('traindirections', 'TrainDirectionController@getTrainDirection');
            Route::get('traindirection/create', 'TrainDirectionController@addTrainDirection');
            Route::post('traindirection', 'TrainDirectionController@addTrainDirection');
            Route::get('traindirection/{id}/edit', 'TrainDirectionController@editTrainDirection');
            Route::put('traindirection', 'TrainDirectionController@editTrainDirection');
            Route::delete('traindirection', 'TrainDirectionController@delTrainDirection');

            /*
             * 通知管理
            */
            // 通知类型表
            Route::get('noticetype', 'NoticeTypeController@noticeTypeList');
            Route::get('noticetypes', 'NoticeTypeController@getNoticeType');
            Route::get('noticetype/create', 'NoticeTypeController@addNoticeType');
            Route::post('noticetype', 'NoticeTypeController@addNoticeType');
            Route::get('noticetype/{id}/edit', 'NoticeTypeController@editNoticeType');
            Route::put('noticetype', 'NoticeTypeController@editNoticeType');
            Route::delete('noticetype', 'NoticeTypeController@delNoticeType');
            // 通知列表
            Route::get('notice', 'NoticeController@noticeList');
            Route::get('notices', 'NoticeController@getNotice');
            Route::get('notice/create', 'NoticeController@addNotice');
            Route::post('notice', 'NoticeController@addNotice');
            Route::post('notice/upload', 'NoticeController@uploadAttachment');
            Route::get('notice/{id}/edit', 'NoticeController@editNotice');
            Route::put('notice', 'NoticeController@editNotice');
            Route::delete('notice', 'NoticeController@delNotice');
            Route::get('notice/{id}/user','NoticeController@noticeUserList');
            Route::get('notice/users','NoticeController@getNoticeUser');
            /*
             * 内部邮件管理
            */
             // 通知列表
            Route::get('email', 'EmailController@noticeList');
            Route::get('emails', 'NoticeController@getNotice');
            Route::get('email/create', 'NoticeController@addNotice');
            Route::post('email', 'NoticeController@addNotice');
            Route::post('email/upload', 'NoticeController@uploadAttachment');
            Route::get('email/{id}/edit', 'NoticeController@editNotice');
            Route::put('email', 'NoticeController@editNotice');
            Route::delete('email', 'NoticeController@delNotice');
        });
        });
    });
    // 完善人事信息
    Route::get('completeuserinfo', 'RegisterController@completeUserInfo')->middleware('checkifregister');
    Route::post('completeuserinfo', 'RegisterController@completeUserInfo')->middleware('checkifregister');
    Route::post('completeuserinfo/upload', 'RegisterController@uploadFace')->middleware('checkifregister');

    // 完善鉴定信息
    Route::get('completeidentifyinfolist', 'IdentifyinfoController@IdentifyInfoList')->middleware('checkifregister');
    Route::get('completeidentifyinfos', 'IdentifyinfoController@getIdentifyInfos');
    Route::get('completeidentifyinfo', 'IdentifyinfoController@completeIdentifyInfo');
    Route::post('completeidentifyinfo', 'IdentifyinfoController@completeIdentifyInfo');
    Route::post('myidentifynum', 'IdentifyinfoController@myIdentifyNum');
    Route::post('completeidentifyinfo/upload', 'IdentifyinfoController@uploadMyzs');
    Route::get('completeidentifyinfo/{id}/edit', 'IdentifyinfoController@editIdentifyInfo');
    Route::put('completeidentifyinfo', 'IdentifyinfoController@editIdentifyInfo');
    Route::delete('completeidentifyinfo', 'IdentifyinfoController@delIdentifyInfo');
    Route::get('lookmyidentifyinfo/{id}', 'IdentifyinfoController@lookMyIdentifyInfo');
    // 我的通知列表
    Route::get('mynotice', 'NoticeController@myNoticeList')->middleware('checkifregister');
    Route::get('mynotices', 'NoticeController@getMyNotice');
    Route::get('mynotices/{mynotice}/show', 'NoticeController@myNoticeShow');
    Route::post('readmynotice', 'NoticeController@readMyNotice');
    Route::post('downattachment', 'NoticeController@downAttachment');
});