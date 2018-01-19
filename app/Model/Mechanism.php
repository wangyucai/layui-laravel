<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Model\Company;
use App\Model\Admin;

class Mechanism extends Model
{
    protected $guarded = [];
    //Mechanism-Comoany:One-One
    public function companies()
    {
    	return $this->hasOne(Company::class);
    }
    // Mechanism-MechanismCode:One-One
    public function mechanism_codes()
    {
    	return $this->hasOne(MechanismCode::class);
    }

    /**
    * 获取本单位内设机构分页数据
    * @return array
    */
    public function getMyMechanismCodes(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['nsjgmc','like', '%'.$where.'%']];
        $offset = ($page - 1) * $limit;
        // 获取本单位的单位代码
        $my_dwdm = $user = Auth::guard('admin')->user()->company_dwdm;
        $mechanism = $this->where('company_dwdm',$my_dwdm)
                             ->where($where)
                             ->offset($offset)
                             ->limit($limit)
                             ->orderBy($sortfield, $order)
                             ->get()
                             ->toArray();     
       $count =  $count = $this->where('company_dwdm',$my_dwdm)->where($where)->count();
       return [
           'count' => $count,
           'data' => $mechanism
       ];
    }
}
