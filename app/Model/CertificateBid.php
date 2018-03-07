<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ProfessionCarModule;

class CertificateBid extends Model
{
    protected $guarded = [];
    /**
     * 获取需申办的职业资格证书模板分页数据
     * @return array
     */
    public function getCarBid(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['profession_car_modules.zsmc', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $professioncarmodules = ProfessionCarModule::where($where)
        						 ->where('profession_car_modules.bz',1)
                                 ->leftJoin('profession_car_codes', 'profession_car_modules.zsmc', '=', 'profession_car_codes.car_code')
                                 ->select('profession_car_modules.*', 'profession_car_codes.car_name')
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get();
        // 发证机构
        $fzjg_arr = fzjg();                       
        foreach ($professioncarmodules as $k => $v) {
            $v->fzjg = $fzjg_arr[$v->fzjg];
            $v->zsyxq = $v->zsyxq.'年';
        }
        $professioncarmodules= $professioncarmodules->toArray();
        $count = ProfessionCarModule::where($where)->where('profession_car_modules.bz',1)->count();
        return [
            'count' => $count,
            'data' => $professioncarmodules
        ];
    }
    /**
     * 获取已经申办的职业资格证书分页数据
     * @return array
     */
    public function getCertificate(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['zsmc'] ?? [];
        $where3 = $param['check_status'] ?? [];
        $where4 = $param['county_if_check'] ?? [];
        $where5 = $param['city_if_check'] ?? [];
        if(isset($param['start'])){
            $where1 = $param['start'];
        }else{
            $where1 = [];
        }
        if(isset($param['end'])){
            $where2 = $param['end'];
        }else{
            $where2 = [];
        }
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where1) $where1 = [['certificate_bids.created_at', '>=', $where1]]; 
        if ($where1 && $where2) $where2 = [['certificate_bids.created_at', '<=', $where2]];
        if ($where) $where = [['certificate_bids.zsmc', $where]];
        if ($where3) $where3 = [['certificate_bids.if_check', $where3]];
        if ($where4==0 || $where4==1) $where4 = [['certificate_bids.county_if_check', $where4]];
        if ($where5==0 || $where5==1) $where5 = [['certificate_bids.city_if_check', $where5]];
        if($param['dwdm']==100000) $param['dwdm']=520000;//超级管理员和省级管理员一样权限
        $offset = ($page - 1) * $limit;
        $query = $this->leftJoin('profession_car_codes', 'certificate_bids.zsmc', '=', 'profession_car_codes.car_code');
        if(!$where && !$where1 && !$where2 && !$where3 && empty($where4) && empty($where5)) $query->where('certificate_bids.my_dwdm',$param['dwdm'])->orwhere('certificate_bids.city_if_check',1);
        $count = $query->where('certificate_bids.bz',1);
        if(($where || $where || $where2 || $where3) &&  $param['dwdm']==520000) $count->where($where)->where($where1)->where($where2)->where($where3);
        if(($where || $where || $where2 || $where3 || $where4 || $where5) &&  $param['dwdm']!=520000) $count->where($where)->where($where1)->where($where2)->where($where3)->where($where4)->where($where5)->where('certificate_bids.my_dwdm',$param['dwdm']);
        $certificate_bids = $count->select('certificate_bids.*', 'profession_car_codes.car_name')
                                  ->offset($offset)
                                  ->limit($limit)
                                  ->orderBy($sortfield, $order)
                                  ->get();
        // 发证机构
        $fzjg_arr = fzjg();                       
        foreach ($certificate_bids as $k => $v) {
            $v->fzjg = $fzjg_arr[$v->fzjg];
            $v->zsyxq = $v->zsyxq.'年';
        }
        $certificate_bids= $certificate_bids->toArray();
        $count = $count->count();
        return [
            'count' => $count,
            'data' => $certificate_bids
        ];
    }
}
