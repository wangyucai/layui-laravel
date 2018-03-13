<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EquipmentAsset extends Model
{
    protected $guarded = [];
    /**
     * 获取装备资产分页数据
     * @return array
     */
    public function getEquipmentAsset(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['equipment_assets.zcmc', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $equipmentAssets = $this ->where($where)
                                 ->leftJoin('asset_units', 'equipment_assets.zcdw', '=', 'asset_units.zcdw_code')
                                 ->select('equipment_assets.*', 'asset_units.zcdw_name')
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get();
        // 资产性质
        $zcxz_arr = zcxz();                       
        foreach ($equipmentAssets as $k => $v) {
            $v->zcxz = $zcxz_arr[$v->zcxz];
            $v->zcbh = 'GZJCYJSC+'.$v->zcbh;
        }
        $equipmentAssets= $equipmentAssets->toArray();
        $count = $this->where($where)->count();
        return [
            'count' => $count,
            'data' => $equipmentAssets
        ];
    }
}
