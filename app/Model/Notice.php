<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $guarded = [];
  	/**
    * 获取通知分页数据
    * @return array
    */
   	public function getNotice(array $param) : array
   	{
       $page = $param['page'];
       $limit = $param['limit'];
       $where = $param['cond'] ?? [];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       if ($where) $where = [['notices.title','like', '%'.$where.'%']];
       $offset = ($page - 1) * $limit;
       $notices = $this->where($where)
                             ->leftJoin('notice_types', 'notice_types.notice_type_code', '=', 'notices.type')
                             ->offset($offset)
                             ->limit($limit)
                             ->orderBy($sortfield, $order)
                             ->select('notices.id','notices.title','notice_types.notice_type_name','notices.from_dw','notices.if_expire','notices.notice_yxq')
                             ->get()
                             ->toArray();    
        foreach ($notices as $k => $notice) {
           if($notice['notice_yxq']<time())  $notice['if_expire'] = 1;
           $notice['notice_yxq'] = date('Y-m-d',$notice['notice_yxq']);
           $notices[$k] = $notice;
        } 
       $count =  $count = $this->where($where)->leftJoin('notice_types', 'notice_types.notice_type_code', '=', 'notices.type')->count();
       return [
           'count' => $count,
           'data' => $notices
       ];
   	}
}
