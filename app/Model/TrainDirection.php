<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TrainDirection extends Model
{
    //添加表头
    protected $table = 'train_direction';
    protected $guarded = [];
  	/**
    * 获取内设机构代码分页数据
    * @return array
    */
   	public function getTrainDirection(array $param) : array
   	{
       $page = $param['page'];
       $limit = $param['limit'];
       $where = $param['cond'] ?? [];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       if ($where) $where = [['pxfx_name','like', '%'.$where.'%']];
       $offset = ($page - 1) * $limit;
       $traindirections = $this->where($where)
                             ->offset($offset)
                             ->limit($limit)
                             ->orderBy($sortfield, $order)
                             ->get()
                             ->toArray();     
       $count =  $count = $this->where($where)->count();
       return [
           'count' => $count,
           'data' => $traindirections
       ];
   	}
}
