<?php
 // 注册中选择单位的递归 
function select_company($pidname,$idname,$data,$pid='100000',$html='**',$num='1'){
    $arr=array();
    foreach($data as $val){
        if($val[$pidname]==$pid){
            $val['html']=str_repeat($html,$num);
            $val['num']=$num+1;
            $arr[]=$val;
            $arr=array_merge($arr,select_company($pidname,$idname,$data,$val[$idname],$html,$val['num']));
        }
    }
    return $arr;
}
// 政治面貌
function political_outlook(){
    $political_outlook = [
        '01' => '中国共产党党员',
        '02' => '中国共产党预备党员',
        '03' => '中国共产主义青年团团员',
        '04' => '中国国民党革命委员会会员',
        '05' => '中国民主同盟盟员',
        '06' => '中国民主建国会会员',
        '07' => '中国民主促进会会员',
        '08' => '中国农工民主党党员',
        '09' => '中国致公党党员',
        '10' => '九三学社社员',
        '11' => '台湾民主自治同盟员',
        '12' => '无党派人士',
        '13' => '群众',
    ];
    return $political_outlook;
}
// 学历
function education(){
    $education = [
        '1' => '专科',
        '2' => '大学本科',
        '3' => '硕士研究生',
        '4' => '博士研究生',
        '5' => '其它',
    ];
    return $education;
}
// 学位
function academic_degree(){
    $academic_degree = [
        '1' => '学士',
        '2' => '硕士',
        '3' => '博士',
    ];
    return $academic_degree;
}
// 检察官员额
function procurator(){
    $procurator = [
        '01' => '检察辅助人员',
        '02' => '检察官',
        '03' => '检察官助理',
        '04' => '司法行政人员',
        '05' => '其他',
    ];
    return $procurator;
}
// 行政职务
function administrative_duties(){
    $administrative_duties = [
        '01' => '检察长',
        '02' => '副检察长',
        '03' => '处长',
        '04' => '副处长',
        '05' => '科长',
        '06' => '副科长',
        '07' => '其他',
    ];
    return $administrative_duties;
}
// 行政级别
function administrative_level(){
    $administrative_level = [
        '01' => '办事员',
        '02' => '科员',
        '03' => '乡科级副职',
        '04' => '乡科级正职',
        '05' => '县处级副职',
        '06' => '县处级正职',
        '07' => '地厅级副职',
        '08' => '地厅级正职',
        '09' => '事业编制干部',
        '10' => '其他',
    ];
    return $administrative_level;
}
// 专业技师职称
function technician_title(){
    $technician_title = [
        '01' => '无职称',
        '02' => '初级职称',
        '03' => '中级职称',
        '04' => '副高级职称',
        '05' => '正高级职称',
    ];
    return $technician_title;
}

// 提示消息类型
function message_type(){
    $message_type = [
        '1' => '培训信息反馈',
    ];
    return $message_type;
}
