<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProfessionCarModule extends Model
{
    protected $guarded = [];
    /**
     * 获取职业资格证书模板分页数据
     * @return array
     */
    public function getCarModule(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['profession_car_modules.zsmc', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $professioncarmodules = $this ->where($where)
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get()
			                     ->toArray();     
        $count = $this->where($where)->count();
        return [
            'count' => $count,
            'data' => $professioncarmodules
        ];
    }
}

