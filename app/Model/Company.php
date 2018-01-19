<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];
   	// Company-Mechanism:One-Many
   	public function mechanisms()
   	{
   		return $this->hasMany(Mechanism::class);
   	}

      /**
        * 获取单位分页数据
        * @return array
        */
       public function getCompanies(array $param) : array
       {
           $page = $param['page'];
           $limit = $param['limit'];
           $where = $param['cond'] ?? [];
           $where1 = $param['my_dwdm']!=100000 ? $param['my_dwdm'] : [];
           $sortfield = $param['sortField'] ?? 'id';
           $order = $param['order'] ?? 'asc';
           if ($where) $where = [['dwjb', $where]];
           if ($where1) $where1 = [['dwdm', $where1]];
           $offset = ($page - 1) * $limit;
           $companies = $this->where($where)
                                 ->where($where1)
                                 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy($sortfield, $order)
                                 ->get()
                                 ->toArray();     
           $count =  $count = $this->where($where)->count();
           return [
               'count' => $count,
               'data' => $companies
           ];
       }

}
