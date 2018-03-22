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
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['equipment_assets.zcmc', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $equipmentAssets = $this->leftJoin('equipment_assets', 'equipment_assets.id', '=', 'inventories.kc_zcid')
                                 ->where($where)
                                 ->where('equipment_assets.zc_dwdm',$param['my_dwdm'])
                                 ->where('equipment_assets.zc_bmdm',$param['my_bmdm'])
                                 ->select('equipment_assets.zcbh','equipment_assets.zcmc','equipment_assets.zcpp','equipment_assets.zcxh', 'inventories.id','inventories.kc_nums','inventories.kc_zcid','inventories.kc_rkrq','inventories.kc_uid','inventories.kc_uid','inventories.kc_qryj','inventories.if_check')
                                 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy($sortfield, $order)
                                 ->get();
        // 取入依据
        $qryj_arr = qryj();                     
        foreach ($equipmentAssets as $k => $v) {
           $v->kc_qryj = $qryj_arr[$v->kc_qryj];
           $v->kc_rkrq = date('Y-m-d H:i:s',$v->kc_rkrq);
           $v->kc_name = Admin::where('id',$v->kc_uid)->value('real_name');
        }
        $equipmentAssets= $equipmentAssets->toArray();
        $count = $this->leftJoin('equipment_assets', 'equipment_assets.id', '=', 'inventories.kc_zcid')
                                 ->where($where)
                                 ->where('equipment_assets.zc_dwdm',$param['my_dwdm'])
                                 ->where('equipment_assets.zc_bmdm',$param['my_bmdm'])->count();
        return [
            'count' => $count,
            'data' => $equipmentAssets
        ];
    }
}
