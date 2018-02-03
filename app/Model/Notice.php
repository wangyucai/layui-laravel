<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Auth;

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
    /**
    * 获取我的通知分页数据
    * @return array
    */
    public function getMyNotice(array $param) : array
    {
       $page = $param['page'];
       $limit = $param['limit'];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       $where = $param['notice_status'] ?? [];
       $offset = ($page - 1) * $limit;
       if ($where==1) $where = [['user_notice.if_read',1]];
       if ($where==0) $where = [['user_notice.if_read',0]];
       if ($where==2) $where = [];
       $uid = $user = Auth::guard('admin')->user()->id;
       $mynotices = $this->where($where)
                             ->leftJoin('user_notice', 'user_notice.notice_id', '=', 'notices.id')
                             ->where('user_notice.user_id',$uid)
                             ->offset($offset)
                             ->limit($limit)
                             ->orderBy('notices.created_at', 'desc')
                             ->select('notices.id','notices.title','notices.type','notices.created_at','user_notice.user_id','user_notice.notice_id','user_notice.if_read')
                             ->get()
                             ->toArray();
        // 获取通知类型的数组
        $notice_types_arr = Cache::remember('notice_types', 120, function() {
            return DB::table('notice_types')->select('notice_type_code','notice_type_name')->get()->pluck('notice_type_name', 'notice_type_code')->toArray();
        });
        foreach ($mynotices as $k => $v) {
            $v['notice_type_name'] = $notice_types_arr[$v['type']];
            $mynotices[$k] = $v;
        }    
       $count =  $count = $this->where($where)->leftJoin('user_notice', 'user_notice.notice_id', '=', 'notices.id')->where('user_notice.user_id',$uid)->count();
       return [
           'count' => $count,
           'data' => $mynotices
       ];
    }
    
  /**
  * 获取我的通知详情
  * @return array
  */
  public function myNoticeDetail(array $param) : array
  {
      $mynotice = $this->leftJoin('user_notice', 'user_notice.notice_id', '=', 'notices.id')
                       ->where('user_notice.user_id',$param['user_id'])
                       ->select('notices.*','user_notice.user_id','user_notice.notice_id','user_notice.if_down','user_notice.if_enter')
                       ->find($param['mynotice'])
                       ->toArray();
      return $mynotice;
  }

  /**
    * 获取通知用户的分页数据
    * @return array
    */
    public function getNoticeUser(array $param) : array
    {

       $page = $param['page'];
       $limit = $param['limit'];
       $where = $param['if_read'] ?? [];
       $where1 = $param['if_down'] ?? [];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       $offset = ($page - 1) * $limit;     

       if ($where or $where==0) $where = [['user_notice.if_read',$where]];
       if ($where1 or $where1==0) $where1 = [['user_notice.if_down',$where1]];
       // 获取发送通知的用户
       $notice_id = $param['notice_id'];
       $notice_user = Admin::leftJoin('user_notice', 'user_notice.user_id', '=', 'admins.id')
                             ->where('user_notice.notice_id',$notice_id)
                             ->where($where)
                             ->where($where1)
                             ->offset($offset)
                             ->limit($limit)
                            ->orderBy($sortfield, $order)
                             ->select('admins.id', 'admins.username','admins.real_name', 'admins.tel', 'admins.company_dwdm','admins.mechanism_id','user_notice.notice_id','user_notice.if_read','user_notice.if_down')
                             ->get()
                             ->toArray();
        // 获取所有的单位代码数组
        $dw_arr = Cache::remember('companies', 120, function() {
            return DB::table('companies')->select('dwdm','dwqc')->get()->pluck('dwqc', 'dwdm')->toArray();
        });
        // 获取所有部门的数组
        $bm_arr = Cache::remember('mechanisms', 120, function() {
            return DB::table('mechanisms')->select('id','nsjgmc')->get()->pluck('nsjgmc', 'id')->toArray();
        });
        foreach ($notice_user as $k => $v) {
            $v['dwqc'] = $dw_arr[$v['company_dwdm']];
            $v['nsjgmc'] = $bm_arr[$v['mechanism_id']];
            $notice_user[$k] = $v;
        }
       $count =  $count = Admin::leftJoin('user_notice', 'user_notice.user_id', '=', 'admins.id')
                                  ->where('user_notice.notice_id',$notice_id)
                                  ->where($where)
                                  ->where($where1)
                                  ->count();
       return [
           'count' => $count,
           'data' => $notice_user
       ];
    }

    /**
    * 获取提示信息列表页
    * @return array
    */
    public function getMyMessage(array $param) : array
    {
       $page = $param['page'];
       $limit = $param['limit'];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       $where = $param['notice_status'] ?? [];
       $offset = ($page - 1) * $limit;
       if ($where==1) $where = [['prompt_messages.if_read',1]];
       if ($where==0) $where = [['prompt_messages.if_read',0]];
       if ($where==2) $where = [];
       $uid = $user = Auth::guard('admin')->user()->id;
       $myMessages = DB::table('prompt_messages')
                             ->where('receiver_id',$uid)
                             ->where($where)
                             ->offset($offset)
                             ->limit($limit)
                             ->orderBy('created_at', 'desc')
                             ->get()
                             ->toArray();
        $message_type = message_type();
        $myMessages = array_map('get_object_vars', $myMessages);//把二维对象也全部转为数组
        foreach ($myMessages as $k => $v) {
            $v['msg_type'] = $message_type[$v['msg_type']];

            $myMessages[$k] = $v;
        }    
       $count =  $count = DB::table('prompt_messages')->where('receiver_id',$uid)->where($where)->count();
       return [
           'count' => $count,
           'data' => $myMessages
       ];
    }
}
