<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MechanismCode extends Model
{
    protected $guarded = [];
  /**
    * 获取内设机构代码分页数据
    * @return array
    */
   public function getMechanismCodes(array $param) : array
   {
       $page = $param['page'];
       $limit = $param['limit'];
       $where = $param['cond'] ?? [];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       if ($where) $where = [['code_name','like', '%'.$where.'%']];
       $offset = ($page - 1) * $limit;
       $mechanismCodes = $this->where($where)
                             ->offset($offset)
                             ->limit($limit)
                             ->orderBy($sortfield, $order)
                             ->get()
                             ->toArray();     
       $count =  $count = $this->where($where)->count();
       return [
           'count' => $count,
           'data' => $mechanismCodes
       ];
   }

}
