<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InstitutionCode extends Model
{
	protected $guarded = [];
    /**
     * 获取司法鉴定机构代码分页数据
     * @return array
     */
    public function getInstitutionCodes(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['jdjg_level', $where]];
        $offset = ($page - 1) * $limit;
        $institutioncodes = $this->where($where)
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get()
			                     ->toArray();     
        $count =  $count = $this->where($where)->count();
        return [
            'count' => $count,
            'data' => $institutioncodes
        ];
    }

}
