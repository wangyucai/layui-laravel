<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{
     protected $guarded = [];
    /**
     * 获取我的固定资产分页数据
     * @return array
     */
    public function getMyFixedAsset(array $param) : array
    {

        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['fixed_assets.gdzc_mc', 'like', '%'.$where.'%']];
        if(isset($param['lqrq_start'])){
            $where1 = strtotime($param['lqrq_start']);
        }else{
            $where1 = [];
        }
        if(isset($param['lqrq_end'])){
            $where2 = strtotime($param['lqrq_end']);
        }else{
            $where2 = [];
        }
        $offset = ($page - 1) * $limit;
        if ($where1) $where1 = [['fixed_assets.gdzc_lqrq', '>=', $where1]]; 
        if ($where1 && $where2) $where2 = [['fixed_assets.gdzc_lqrq', '<=', $where2]]; 
        $fixed_assets = $this ->where($where)
                                 ->where($where1)
                                 ->where($where2)
                                 ->where('fixed_assets.gdzc_uid',$param['gdzc_uid'])
                                 ->where('fixed_assets.gdzc_dwdm',$param['gdzc_dwdm'])
                                 ->where('fixed_assets.gdzc_bmdm',$param['gdzc_bmdm'])
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get();
        // 资产性质
        $ffbm_arr = ffbm();                      
        foreach ($fixed_assets as $k => $v) {
            $v->gdzc_ffbm = $ffbm_arr[$v->gdzc_ffbm];
            $v->gdzc_bh = 'GZJCYZC+'.$v->gdzc_bh;
            $v->gdzc_lqrq = date('Y-m-d',$v->gdzc_lqrq);
            if($v->gdzc_ghrq){
                $v->gdzc_ghrq = date('Y-m-d',$v->gdzc_ghrq);
            }  
        }
        $fixed_assets= $fixed_assets->toArray();
        $count = $this->where($where)
                       ->where($where1)
                       ->where($where2)
        			   ->where('fixed_assets.gdzc_uid',$param['gdzc_uid'])
                       ->where('fixed_assets.gdzc_dwdm',$param['gdzc_dwdm'])
                       ->where('fixed_assets.gdzc_bmdm',$param['gdzc_bmdm'])->count();
        return [
            'count' => $count,
            'data' => $fixed_assets
        ];
    }
    /**
     * 获取固定资产管理分页数据
     * @return array
     */
    public function getFixedAsset(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $where3 = $param['real_name'] ?? [];
        $where4 = $param['gdzc_bmdm'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['fixed_assets.gdzc_mc', 'like', $where.'%']];
        if ($where3) $where3 = [['admins.real_name', 'like', $where3.'%']];
        if ($where4) $where4 = [['fixed_assets.gdzc_bmdm', $where4]];
        if(isset($param['lqrq_start'])){
            $where1 = strtotime($param['lqrq_start']);
        }else{
            $where1 = [];
        }
        if(isset($param['lqrq_end'])){
            $where2 = strtotime($param['lqrq_end']);
        }else{
            $where2 = [];
        }
        $offset = ($page - 1) * $limit;
        if ($where1) $where1 = [['fixed_assets.gdzc_lqrq', '>=', $where1]]; 
        if ($where1 && $where2) $where2 = [['fixed_assets.gdzc_lqrq', '<=', $where2]]; 
        $fixed_assets = $this ->where($where)
                                 ->where($where1)
                                 ->where($where2)
                                 ->where($where4)
                                 ->where('fixed_assets.gdzc_dwdm',$param['gdzc_dwdm'])
                                 // ->where('fixed_assets.gdzc_bmdm',$param['gdzc_bmdm'])
                                 ->leftJoin('admins', 'admins.id', '=', 'fixed_assets.gdzc_uid')
                                 ->where($where3)
                                 ->select('fixed_assets.*', 'admins.real_name')
                                 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy($sortfield, $order)
                                 ->get();
        // 资产性质
        $ffbm_arr = ffbm();                      
        foreach ($fixed_assets as $k => $v) {
            $v->gdzc_ffbm = $ffbm_arr[$v->gdzc_ffbm];
            $v->gdzc_bh = 'GZJCYZC+'.$v->gdzc_bh;
            $v->gdzc_lqrq = date('Y-m-d',$v->gdzc_lqrq);
        }
        $fixed_assets= $fixed_assets->toArray();
        $count = $this->where($where)
                       ->where($where1)
                       ->where($where2)
                       ->where($where4)
                       ->where('fixed_assets.gdzc_dwdm',$param['gdzc_dwdm'])
                       // ->where('fixed_assets.gdzc_bmdm',$param['gdzc_bmdm'])
                       ->leftJoin('admins', 'admins.id', '=', 'fixed_assets.gdzc_uid')
                       ->where($where3)
                       ->count();
        return [
            'count' => $count,
            'data' => $fixed_assets
        ];
    }
}
