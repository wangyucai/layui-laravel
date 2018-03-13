<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AssetUnit extends Model
{
    protected $guarded = [];
  /**
    * 获取资产单位代码分页数据
    * @return array
    */
   public function getAssetUnit(array $param) : array
   {
       $page = $param['page'];
       $limit = $param['limit'];
       $where = $param['cond'] ?? [];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       if ($where) $where = [['zcdw_name','like', '%'.$where.'%']];
       $offset = ($page - 1) * $limit;
       $assetUnits = $this->where($where)
                             ->offset($offset)
                             ->limit($limit)
                             ->orderBy($sortfield, $order)
                             ->get()
                             ->toArray();     
       $count =  $count = $this->where($where)->count();
       return [
           'count' => $count,
           'data' => $assetUnits
       ];
   }
}
