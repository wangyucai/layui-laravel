<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserReceive extends Model
{
    //添加表头
    protected $table = 'user_receive';
    protected $guarded = [];
    /**
     * 获取我申领的资产的设备的分页数据
     * @return array
     */
    public function getMyAssetDevice(array $param) : array
    {
        $zc_id = $param['zc_id'];
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['equipment_assets.zcmc', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $myAssetDevices = $this ->where($where)
								 ->where('user_receive.zc_id',$zc_id)
								 ->where('user_receive.user_id',$param['my_id'])
                                 ->leftJoin('equipment_assets', 'equipment_assets.id', '=', 'user_receive.zc_id')
                                 ->select('user_receive.*', 'equipment_assets.zcmc','equipment_assets.zcbh','equipment_assets.zcpp','equipment_assets.zcxh')
                                 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy($sortfield, $order)
                                 ->get();
        $myAssetDevices= $myAssetDevices->toArray();
        foreach ($myAssetDevices as $k => $v) {
            if($v['back_time']){
               $v['back_time'] = date('Y-m-d',$v['back_time']);
            } 
            $myAssetDevices[$k] =  $v;
        }
        $count = $this->where($where)
                      ->where('user_receive.zc_id',$zc_id)
                      ->where('user_receive.user_id',$param['my_id'])
                      ->leftJoin('equipment_assets', 'equipment_assets.id', '=', 'user_receive.zc_id')
                      ->count();
        return [
            'count' => $count,
            'data' => $myAssetDevices
        ];
    }
    /**
     * 管理员获取申领的资产的设备的分页数据
     * @return array
     */
    public function getAllAssetDevice(array $param) : array
    {
        $zc_id = $param['zc_id'];
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['equipment_assets.zcmc', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $myAssetDevices = $this ->where($where)
                                 ->where('user_receive.zc_id',$zc_id)
                                 ->leftJoin('equipment_assets', 'equipment_assets.id', '=', 'user_receive.zc_id')
                                 ->select('user_receive.*', 'equipment_assets.zcmc','equipment_assets.zcbh','equipment_assets.zcpp','equipment_assets.zcxh')
                                 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy($sortfield, $order)
                                 ->get();
        $myAssetDevices= $myAssetDevices->toArray();
        foreach ($myAssetDevices as $k => $v) {
            if($v['rk_time']){
               $v['rk_time'] = date('Y-m-d',$v['rk_time']);
            } 
            $myAssetDevices[$k] =  $v;
        }
        $count = $this->where($where)
                      ->where('user_receive.zc_id',$zc_id)
                      ->leftJoin('equipment_assets', 'equipment_assets.id', '=', 'user_receive.zc_id')
                      ->count();
        return [
            'count' => $count,
            'data' => $myAssetDevices
        ];
    }
}
