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
        Route::get('nocomplete', 'IndexController@noComplete');
        Route::get('nocheck', 'IndexController@noCheck');
        /*
         * 权限管理模块
        */
        Route::group(['middleware' => 'rbac'], function () {
            Route::get('user/password/edit', 'IndexController@editPassword');
            Route::put('user/password', 'IndexController@editPassword');
            // 管理员管理
            Route::get('userslist', 'RuleController@adminsPage');
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
            // 信息化信息统计查询
            Route::get('allinformatization', 'InformatizationController@allInformatizationList');
            Route::get('allinformatizations', 'InformatizationController@getAllInformatizations');
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
            Route::get('trainmodule/{id}/bmuser', 'TrainController@bmUserList');
            Route::get('trainmodule/bmusers', 'TrainController@getBmUser');
            Route::post('trainmodule/bmusers', 'TrainController@selectBmUser');
            Route::post('trainmodule/feedback', 'TrainController@MessageFeedback');
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
            // 内部邮件列表
            Route::get('email', 'EmailController@emailList');
            Route::get('emails', 'EmailController@getEmail');
            Route::get('email/create', 'EmailController@addEmail');
            Route::post('email', 'EmailController@addEmail');
            Route::post('emailer', 'EmailController@getEmailer');
            Route::post('email/upload', 'EmailController@uploadAttachment');
            Route::get('email/{id}/edit', 'EmailController@editEmail');
            Route::put('email', 'EmailController@editEmail');
            Route::delete('email', 'EmailController@delEmail');

            // 日志管理
            Route::get('log', 'LogController@logList');
            Route::get('logs', 'LogController@getLogs');
            /*
             * 职业资格证书办理模块
            */
            // 职业资格证书代码表
            Route::get('professioncarcode', 'ProfessionCarCodeController@carCodeList');
            Route::get('professioncarcodes', 'ProfessionCarCodeController@getCarCode');
            Route::get('professioncarcode/create', 'ProfessionCarCodeController@addCarCode');
            Route::post('professioncarcode', 'ProfessionCarCodeController@addCarCode');
            Route::get('professioncarcode/{id}/edit', 'ProfessionCarCodeController@editCarCode');
            Route::put('professioncarcode', 'ProfessionCarCodeController@editCarCode');
            Route::delete('professioncarcode', 'ProfessionCarCodeController@delCarCode');
            // 职业资格证书模板
            Route::get('professioncarmodule', 'ProfessionCarModuleController@carModuleList');
            Route::get('professioncarmodules', 'ProfessionCarModuleController@getCarModule');
            Route::get('professioncarmodule/create', 'ProfessionCarModuleController@addCarModule');
            Route::post('professioncarmodule', 'ProfessionCarModuleController@addCarModule');
            Route::get('professioncarmodule/{id}/edit', 'ProfessionCarModuleController@editCarModule');
            Route::put('professioncarmodule', 'ProfessionCarModuleController@editCarModule');
            Route::delete('professioncarmodule', 'ProfessionCarModuleController@delCarModule');
            // 管理申办的职业资格证书
            Route::get('managecertificate', 'CertificateBidController@certificateList');
            Route::get('managecertificates', 'CertificateBidController@getCertificate');
            Route::get('managecertificate/{id}/edit', 'CertificateBidController@editCertificate');
            Route::put('managecertificate', 'CertificateBidController@editCertificate');
            Route::post('managecertificate/reporting', 'CertificateBidController@reportingCertificate');
            Route::post('managecertificate/check', 'CertificateBidController@checkCertificate');
            Route::delete('managecertificate', 'CertificateBidController@delCertificate');
        });
        });
    });
    
        // 完善人事信息
        Route::get('completeuserinfo', 'RegisterController@completeUserInfo')->middleware('checkifregister');
        Route::post('completeuserinfo', 'RegisterController@completeUserInfo')->middleware('checkifregister');
        Route::post('completeuserinfo/upload', 'RegisterController@uploadFace')->middleware('checkifregister');

    // 进行下面操作前需要先完善人事信息 
    Route::group(['middleware' => 'ifcompleteinfo'], function () {
        // 完善信息化资格证书信息
        Route::get('informatization', 'InformatizationController@informatizationList')->middleware('checkifregister');
        Route::get('informatizations', 'InformatizationController@getinformatization');
        Route::get('informatization/create', 'InformatizationController@addInformatization');
        Route::post('informatization', 'InformatizationController@addInformatization');
        Route::post('myinforcarnum', 'InformatizationController@myInforCarNum');
        Route::post('informatization/upload', 'InformatizationController@uploadMyInforCar');
        Route::get('informatization/{id}/edit', 'InformatizationController@editInformatization');
        Route::put('informatization', 'InformatizationController@editInformatization');
        Route::delete('informatization', 'InformatizationController@delInformatization');
        Route::get('lookmyinformatization/{id}', 'InformatizationController@lookMyInformatization');
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
        Route::post('trainnotice/enter', 'NoticeController@trainEnter');
        // 我的邮件列表
        Route::get('myemail', 'EmailController@myEmailList')->middleware('checkifregister');
        Route::get('myemails', 'EmailController@getMyEmail');
        Route::get('myemails/{myemail}/show', 'EmailController@myEmailShow');
        Route::post('readmyemail', 'EmailController@readMyEmail');
        // 我的培训班列表
        Route::get('mytrainmodule', 'TrainController@myTrainList')->middleware('checkifregister');
        Route::get('mytrainmodules', 'TrainController@getMyTrain');
        // 我的提示信息
        Route::get('mymessage', 'NoticeController@myMessageList')->middleware('checkifregister');
        Route::get('mymessages', 'NoticeController@getMyMessage');
        Route::post('readmymessage', 'NoticeController@readMyMessage');
        // 职业证书申办功能
        Route::get('certificatebid', 'CertificateBidController@carBidList')->middleware('checkifregister');
        Route::get('certificatebids', 'CertificateBidController@getCarBid');
        Route::get('certificatebid/{id}/create', 'CertificateBidController@addCarBid');
        Route::post('certificatebid', 'CertificateBidController@addCarBid');
    });
});