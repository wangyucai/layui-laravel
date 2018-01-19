<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $guarded = [];
    /**
     * 获取司法鉴定业务范围分页数据
     * @return array
     */
    public function getBusinesses(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['jdywfw_name', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $businesses = $this->where($where)
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get()
			                     ->toArray();     
        $count =  $count = $this->where($where)->count();
        return [
            'count' => $count,
            'data' => $businesses
        ];
    }
}
