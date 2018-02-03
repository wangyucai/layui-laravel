<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Informatization extends Model
{
    protected $guarded = [];
    /**
     * 获取我的证书分页数据
     * @return array
     */
    public function getinformatization(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        $offset = ($page - 1) * $limit;
        // 获取我自己的证书列表
        $uid = $user = Auth::guard('admin')->user()->id;
        $informatizations = $this->where('user_myid',$uid)
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get();
        // $informatizations = array_map('get_object_vars', $informatizations);
        foreach ($informatizations as $k => $v) {
            $v->info_fzrq = date('Y-m-d',$v->info_fzrq);
        }
        $informatizations= $informatizations->toArray();
        $count = $this->where('user_myid',$uid)->count();
        return [
            'count' => $count,
            'data' => $informatizations
        ];
    }
    /**
     * 获取所有证书分页数据
     * @return array
     */
    public function getAllInformatizations(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['danwei'] ?? [];
        $where1 = $param['my_dwjb'] ?? [];
        $where2 = $param['info_myname'] ?? [];
        $where3 = $param['info_zsmc'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        $offset = ($page - 1) * $limit;
        if ($where) $where    = [['info_mydwdm', $where]];
        if ($where2) $where2  = [['info_myname', 'like', '%'.$where2.'%']];
        if ($where3) $where3  = [['info_zsmc', 'like', '%'.$where3.'%']];
        // 获取本单位用户拥有信息化资格证书列表
        $my_dwdm = $user = Auth::guard('admin')->user()->company_dwdm;
        // 判断是否包含下辖单位查询(分别是本单位级别为省级和市级的情况)
        if ($where1==2 && $param['danwei']!=520000) $where1 = $children_dwdm = Company::where('sjdm',$param['danwei'])->orwhere('dwdm',$param['danwei'])->pluck('dwdm')->toArray();
        if ($where1==2 && $param['danwei']==520000) $where1 = $children_dwdm = Company::pluck('dwdm')->toArray();
        if ($where1 == 3) $where1 = $children_dwdm = Company::where('sjdm',$my_dwdm)->orwhere('dwdm',$my_dwdm)->pluck('dwdm')->toArray();
        $query = $this->where($where2)->where($where3);
         // 选择单位和子单位同时存在
        ($where && $where1) && $query->where(function ($query) use ($where,$where1) {
            $query->whereIn('info_mydwdm',$where1)->orwhere($where);
        });
        (!$where && !$where1 && $my_dwdm!=100000) && $query->where('info_mydwdm','=', $my_dwdm);
        ($where && !$where1) && $query->where($where);
        (!$where && $where1) && $query->whereIn('info_mydwdm',$where1);
        $count = $query;
        $informatizations = $count->offset($offset)
                                  ->limit($limit)
                                  ->orderBy($sortfield, $order)
                                  ->get();
        // $informatizations = array_map('get_object_vars', $informatizations);
        foreach ($informatizations as $k => $v) {
            $v->info_fzrq = date('Y-m-d',$v->info_fzrq);
        }
        $informatizations= $informatizations->toArray();
        $count = $count->count();
        return [
            'count' => $count,
            'data' => $informatizations
        ];
    }
}
