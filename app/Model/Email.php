<?php

namespace App\Model;
use Auth;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $guarded = [];
  	/**
    * 获取内部邮件分页数据
    * @return array
    */
   	public function getEmail(array $param) : array
   	{
       $page = $param['page'];
       $limit = $param['limit'];
       $where = $param['cond'] ?? [];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       if ($where) $where = [['emails.email_theme','like', '%'.$where.'%']];
       $offset = ($page - 1) * $limit;
       $emails = $this->where($where)
                      ->leftJoin('admins', 'admins.id', '=', 'emails.email_sender')
                      ->offset($offset)
                      ->limit($limit)
                      ->orderBy($sortfield, $order)
                      ->select('emails.id','emails.email_theme','emails.email_content','admins.username','admins.real_name')
                      ->get()
                      ->toArray();    
       $count =  $count = $this->where($where)->leftJoin('admins', 'admins.id', '=', 'emails.email_sender')->count();
       return [
           'count' => $count,
           'data' => $emails
       ];
   	}
    /**
    * 获取我的邮件分页数据
    * @return array
    */
    public function getMyEmail(array $param) : array
    {
       $page = $param['page'];
       $limit = $param['limit'];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       $where = $param['email_status'] ?? [];
       $offset = ($page - 1) * $limit;
       if ($where==1) $where = [['user_email.if_read',1]];
       if ($where==0) $where = [['user_email.if_read',0]];
       if ($where==2) $where = [];
       $uid = $user = Auth::guard('admin')->user()->id;
       $myemails = $this->where($where)
                             ->leftJoin('user_email', 'user_email.email_id', '=', 'emails.id')
                             ->leftJoin('admins', 'admins.id', '=', 'user_email.user_id')
                             ->where('user_email.user_id',$uid)
                             ->offset($offset)
                             ->limit($limit)
                             ->orderBy('emails.created_at', 'desc')
                             ->select('emails.id','emails.email_theme','emails.created_at','user_email.user_id','user_email.email_id','user_email.if_read','admins.username')
                             ->get()
                             ->toArray();
       $count =  $count = $this->where($where)->leftJoin('user_email', 'user_email.email_id', '=', 'emails.id')->leftJoin('admins', 'admins.id', '=', 'user_email.user_id')->where('user_email.user_id',$uid)->count();
       return [
           'count' => $count,
           'data' => $myemails
       ];
    }
    /**
    * 获取我的邮件详情
    * @return array
    */
    public function myEmailDetail(int $emailId) : array
    {
        $myemail = $this->leftJoin('user_email', 'user_email.email_id', '=', 'emails.id')
                         ->select('emails.*','user_email.user_id','user_email.email_id','user_email.if_down')
                         ->find($emailId)
                         ->toArray();
        return $myemail;
    }
}
