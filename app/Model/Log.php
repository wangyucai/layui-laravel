<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
 	protected $guarded = [];
 	/**
    * 获取日志分页数据
    * @return array
    */
   	public function getLogs(array $param) : array
   	{
       $page = $param['page'];
       $limit = $param['limit'];
       $where = $param['cond'] ?? [];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       if ($where) $where = [['user_name','like', '%'.$where.'%']];
       $offset = ($page - 1) * $limit;
       $logs = $this->where($where)
                             ->offset($offset)
                             ->limit($limit)
                             ->orderBy($sortfield, $order)
                             ->get()
                             ->toArray();     
       $count =  $count = $this->where($where)->count();
       return [
           'count' => $count,
           'data' => $logs
       ];
   	}
}
