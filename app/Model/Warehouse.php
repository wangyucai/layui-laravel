<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
   	protected $guarded = [];
  	/**
    * 获取仓库分页数据
    * @return array
    */
   	public function getWarehouse(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['warehouses.ckmc', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $warehouses = $this ->where($where)
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get();
        $warehouses= $warehouses->toArray();
        $count = $this->where($where)->count();
        return [
            'count' => $count,
            'data' => $warehouses
        ];
    }
}
