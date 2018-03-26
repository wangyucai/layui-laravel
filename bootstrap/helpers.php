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
 /* 返回距离现在时间的格式，如1秒前,1分前,1小时前,1天前,1周前*
   * 话说$time是一个时间戳
  */
 function maktimes($time)
  {
   $t= $time-time();
      $f=array(
        '31536000'=> '年',
        '2592000' => '个月',
        '604800'  => '星期',
        '86400'   => '天',
        '3600'    => '小时',
        '60'      => '分钟',
        '1'       => '秒'
    );
    foreach ($f as $k=>$v){        
        if (0 !=$c=floor($t/(int)$k)){
            return $c.$v;
        }
    }
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
        '2' => '职业资格证书申报的审核信息反馈',
        '3' => '资产申领审核的信息反馈',
        '4' => '资产入库审核的信息反馈',
        '5' => '归还申领资产的信息反馈',
    ];
    return $message_type;
}
// 发证机构
function fzjg(){
    $fzjg = [
        '0' => '最高人民检察院',
        '1' => '贵州省人民检察院',
    ];
    return $fzjg;
}

// 资产性质
function zcxz(){
    $zcxz = [
        '01' => '普通资产',
        '02' => '危险品',
        '03' => '化学药品',
        '04' => '贵重物品',
        '05' => '消耗品',
        '06' => '其它',
    ];
    return $zcxz;
}
// 资产状况
function zczk(){
    $zczk = [
        '01' => '新购',
        '02' => '返还',
        '03' => '报废',
        '04' => '修理',
        '05' => '过期',
        '06' => '其它',
    ];
    return $zczk;
}
// 取入依据
function qryj(){
    $qryj = [
        '01' => '领导批示',
        '02' => '采购合同',
        '03' => '验收清单',
        '04' => '部门领用',
        '05' => '领用返还',
        '06' => '报废返还',
        '07' => '其它',
    ];
    return $qryj;
}
// 归属门类表
function gsml(){
    $gsml = [
        '01' => '办公',
        '02' => '法医',
        '03' => '司法会计',
        '04' => '电子数据',
        '05' => '文件检验',
        '06' => '实验室共用',
        '07' => '技术处共用',
        '08' => '其它',
    ];
    return $gsml;
}
// 固定资产的发放部门
function ffbm(){
    $ffbm = [
        '01' => '计财部门',
        '02' => '院办公室部门',
        '03' => '机关服务部门',
    ];
    return $ffbm;
}