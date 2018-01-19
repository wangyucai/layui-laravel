<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NoticeType extends Model
{
    protected $guarded = [];
  	/**
    * 获取通知类型分页数据
    * @return array
    */
   	public function getNoticeType(array $param) : array
   	{
       $page = $param['page'];
       $limit = $param['limit'];
       $where = $param['cond'] ?? [];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       if ($where) $where = [['notice_type_name','like', '%'.$where.'%']];
       $offset = ($page - 1) * $limit;
       $noticetypes = $this->where($where)
                             ->offset($offset)
                             ->limit($limit)
                             ->orderBy($sortfield, $order)
                             ->get()
                             ->toArray();     
       $count =  $count = $this->where($where)->count();
       return [
           'count' => $count,
           'data' => $noticetypes
       ];
   	}
}
