<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeviceIdentity extends Model
{
    protected $guarded = [];
    /**
     * 获取设备身份的分页数据
     * @return array
     */
    public function getDeviceIdentity(array $param) : array
    {
        $kc_id = $param['kc_id'];
        $page = $param['page'];
        $limit = $param['limit'];
        if(isset($param['if_ck'])) {
        	$where = [['device_identities.if_ck', $param['if_ck']]];
        }else{
        	$where = [];	
        }
        $where2 = [['device_identities.sbsf_kcid', $kc_id]];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        $offset = ($page - 1) * $limit;
        $deviceIdentities = $this->where($where)
                                 ->where($where2)
                                 ->leftJoin('inventories', 'inventories.id', '=', 'device_identities.sbsf_kcid')
                                 ->leftJoin('equipment_assets', 'inventories.kc_zcid', '=', 'equipment_assets.id')
                                 ->select('device_identities.*', 'equipment_assets.bfnx','equipment_assets.zcmc','equipment_assets.zcpp','equipment_assets.zcxh','inventories.kc_rkrq')
        						 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy($sortfield, $order)
                                 ->get();
        foreach ($deviceIdentities as $k => $v) {
        	$v->sbsf_zcbh = 'GZJCYJSC+'.$v->sbsf_zcbh;
            $v->bfrq = [($v->kc_rkrq)+365*24*60*60*($v->bfnx)];  
             
            $v->bf_time = ($v->bfrq[0])-time();

            if($v->bf_time>0){
                $v->bf = '剩余'.maktimes($v->bfrq[0]);
            }else{
                $v->bf = '超出'.maktimes($v->bfrq[0]);
            }
        } 
        $deviceIdentities= $deviceIdentities->toArray();
        $count = $this->where($where)
                      ->where($where2)
                      ->leftJoin('inventories', 'inventories.id', '=', 'device_identities.sbsf_kcid')
                      ->leftJoin('equipment_assets', 'inventories.kc_zcid', '=', 'equipment_assets.id')
                      ->count();
        return [
            'count' => $count,
            'data' => $deviceIdentities
        ];
    }
}
