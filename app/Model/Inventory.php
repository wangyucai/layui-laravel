<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $guarded = [];
    /**
     * 获取领用资产的分页数据
     * @return array
     */
    public function getAssetClaim(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['equipment_assets.zcmc', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $equipmentAssets = $this->where('inventories.if_check',1)
                                 ->leftJoin('equipment_assets', 'equipment_assets.id', '=', 'inventories.kc_zcid')
                                 ->where($where)
                                 ->where('equipment_assets.zc_dwdm',$param['my_dwdm'])
                                 ->where('equipment_assets.zc_bmdm',$param['my_bmdm'])
                                 ->select('equipment_assets.zcbh','equipment_assets.zcmc','equipment_assets.zcpp','equipment_assets.zcxh', 'inventories.id','inventories.kc_nums','inventories.kc_zcid','inventories.kc_rkrq','inventories.kc_uid','inventories.kc_uid','inventories.kc_qryj')
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get();
        // 取入依据
        $qryj_arr = qryj();                     
        foreach ($equipmentAssets as $k => $v) {
           $v->kc_qryj = $qryj_arr[$v->kc_qryj];
           $v->kc_rkrq = date('Y-m-d H:i:s',$v->kc_rkrq);
           $v->kc_uid = Admin::where('id',$v->kc_uid)->value('real_name');
        }
        $equipmentAssets= $equipmentAssets->toArray();
        $count = $this->where('inventories.if_check',1)->leftJoin('equipment_assets', 'equipment_assets.id', '=', 'inventories.kc_zcid')
                                 ->where($where)
                                 ->where('equipment_assets.zc_dwdm',$param['my_dwdm'])
                                 ->where('equipment_assets.zc_bmdm',$param['my_bmdm'])->count();
        return [
            'count' => $count,
            'data' => $equipmentAssets
        ];
    }
    /**
     * 获取入库资产的分页数据
     * @return array
     */
    public function getInboundAsset(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['zcmc'] ?? [];
        $where3 = $param['jsr'] ?? [];
        $where4 = $param['danwei'] ?? [];
        $where5 = $param['my_dwjb'] ?? [];
        $where6 = $param['province_level'] ?? [];
        $where7 = $param['if_check'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['equipment_assets.zcmc', 'like', $where.'%']];
        if(isset($param['rkrq_start'])){
            $where1 = strtotime($param['rkrq_start']);
        }else{
            $where1 = [];
        }
        if(isset($param['rkrq_end'])){
            $where2 = strtotime($param['rkrq_end']);
        }else{
            $where2 = [];
        }
        if ($where3) $where3 = [['inventories.kc_username', 'like', $where3.'%']];
        if ($where1) $where1 = [['inventories.kc_rkrq', '>=', $where1]]; 
        if ($where1 && $where2) $where2 = [['inventories.kc_rkrq', '<=', $where2]]; 
        if ($where4) $where4  = [['inventories.kc_dwdm', $where4]];
        // 判断是否包含下辖单位查询(分别是本单位级别为省级和市级的情况)
        if ($where5==2 && $param['danwei']!=520000) $where5 = $children_dwdm = Company::where('sjdm',$param['danwei'])->orwhere('dwdm',$param['danwei'])->pluck('dwdm')->toArray();
        if ($where5==2 && $param['danwei']==520000) $where5 = $children_dwdm = Company::pluck('dwdm')->toArray();
        if ($where5 == 3) $where5 = $children_dwdm = Company::where('sjdm',$param['my_dwdm'])->orwhere('dwdm',$param['my_dwdm'])->pluck('dwdm')->toArray();
        if ($where6 == 2) $where6 = Company::whereIn('dwjb',['2','3'])->pluck('dwdm')->toArray();
        if ($where6 == 3) $where6 = Company::where('dwjb',3)->pluck('dwdm')->toArray();
        if ($where7==0 || $where7==1 || $where7==2) $where7 = [['inventories.if_check', $where7]];
        $offset = ($page - 1) * $limit;
        $query = $this->leftJoin('equipment_assets', 'equipment_assets.id', '=', 'inventories.kc_zcid')
                                 ->where($where)
                                 ->where($where1)
                                 ->where($where2)
                                 ->where($where3);
                                 // ->where('equipment_assets.zc_dwdm',$param['my_dwdm']);
        // 选择单位和子单位同时存在
        ($where4 && $where5) && $query->where(function ($query) use ($where4,$where5) {
            $query->whereIn('inventories.kc_dwdm',$where5)->orwhere($where4);
        });
        (!$where4 && !$where5 && !$where6 && $param['my_dwdm']!=100000) && $query->where('inventories.kc_dwdm','=', $param['my_dwdm']);
        ($where4 && !$where5) && $query->where($where4);
        (!$where4 && $where5) && $query->whereIn('inventories.kc_dwdm',$where5);
        ($where6) && $query->whereIn('inventories.kc_dwdm',$where6);
        ($where7) && $query->where($where7);
        $count = $query->select('equipment_assets.zcbh','equipment_assets.zcmc','equipment_assets.zcpp','equipment_assets.zcxh', 'inventories.id','inventories.kc_nums','inventories.kc_zcid','inventories.kc_rkrq','inventories.kc_dwdm','inventories.kc_uid','inventories.kc_uid','inventories.kc_qryj','inventories.kc_ynums','inventories.if_check','inventories.kc_username');

         $equipmentAssets = $count->offset($offset)
                                  ->limit($limit)
                                  ->orderBy($sortfield, $order)
                                  ->get();
        // 取入依据
        $qryj_arr = qryj();                     
        foreach ($equipmentAssets as $k => $v) {
           $v->kc_qryj = $qryj_arr[$v->kc_qryj];
           $v->kc_rkrq = date('Y-m-d',$v->kc_rkrq);
           $v->bf_nums = DeviceIdentity::where('sbsf_kcid',$v->id)->where('if_bf',1)->count();
           $v->my_dwdm = $param['my_dwdm'];
        }
        $equipmentAssets= $equipmentAssets->toArray();
        $count = $count->count();
        return [
            'count' => $count,
            'data' => $equipmentAssets
        ];
    }
}
