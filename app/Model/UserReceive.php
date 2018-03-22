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
								 ->where('zc_id',$zc_id)
								 ->where('user_id',$param['my_id'])
                                 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy($sortfield, $order)
                                 ->get();
        $myAssetDevices= $myAssetDevices->toArray();
        $count = $this->where($where)->where('zc_id',$zc_id)->where('user_id',$param['my_id'])->count();
        return [
            'count' => $count,
            'data' => $myAssetDevices
        ];
    }
}
