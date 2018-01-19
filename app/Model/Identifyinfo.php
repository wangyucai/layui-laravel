<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Model\Company;
use App\Model\Admin;

class Identifyinfo extends Model
{
    protected $guarded = [];
    /**
     * 获取我的证书分页数据
     * @return array
     */
    public function getIdentifyInfos(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        // if ($where) $where = [['institution_codes.jdjg_name', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        // 获取我自己的证书列表
        $uid = $user = Auth::guard('admin')->user()->id;
        $identifyinfos = $this	 ->where('admin_id',$uid)
                                 ->leftJoin('institution_codes', 'identifyinfos.jdjg_dwdm', '=', 'institution_codes.jdjg_code')
                                 ->select('identifyinfos.*', 'institution_codes.jdjg_name')
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get()
			                     ->toArray();     
        $count = $this->where('admin_id',$uid)->leftJoin('institution_codes', 'identifyinfos.jdjg_dwdm', '=', 'institution_codes.jdjg_code')->select('identifyinfos.*', 'institution_codes.jdjg_name')->count();
        return [
            'count' => $count,
            'data' => $identifyinfos
        ];
    }

    /**
     * 获取本机构证书分页数据
     * @return array
     */
    public function getMyInscertificates(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        if(isset($param['fzrq_start'])){
            $where = strtotime($param['fzrq_start']);
        }else{
            $where = [];
        }
        if(isset($param['fzrq_end'])){
            $where1 = strtotime($param['fzrq_end']);
        }else{
            $where1 = [];
        }
        $where2 = $param['jdjg_dwdm'] ?? [];
        $where3 = $param['jdywfw_code'] ?? [];
        $where4 = $param['my_dwjb'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['identifyinfos.jdry_fzrq', '>=', $where]]; 
        if ($where && $where1) $where1 = [['identifyinfos.jdry_fzrq', '<=', $where1]]; 
        if ($where2) $where2 = [['identifyinfos.jdjg_dwdm', $where2]]; 
        if ($where3) $where3 = [['identifyinfos.jdywfw_code', $where3]]; 
        $offset = ($page - 1) * $limit;
        // 获取本机构的证书列表
        $my_dwdm = $user = Auth::guard('admin')->user()->company_dwdm;
        // 判断是否包含子机构查询(分别是本机构单位级别为省级和市级的情况)
        if ($where4==2 && $param['jdjg_dwdm']!=520000) $where4 = $children_dwdm = Company::where('sjdm',$param['jdjg_dwdm'])->orwhere('dwdm',$param['jdjg_dwdm'])->pluck('dwdm')->toArray();
        if ($where4==2 && $param['jdjg_dwdm']==520000) $where4 = $children_dwdm = Company::pluck('dwdm')->toArray();
        if ($where4 == 3) $where4 = $children_dwdm = Company::where('sjdm',$my_dwdm)->orwhere('dwdm',$param['jdjg_dwdm'])->pluck('dwdm')->toArray();
        // 查询语句
        $query = $this->leftJoin('institution_codes', 'identifyinfos.jdjg_dwdm', '=', 'institution_codes.jdjg_code');
        // 选择机构和子机构同时存在
        ($where2 && $where4) && $query->where(function ($query) use ($where2,$where4) {
            $query->whereIn('identifyinfos.jdjg_dwdm',$where4)->orwhere($where2);
        });
        (!$where2 && !$where4 && $my_dwdm!=100000) && $query->where('identifyinfos.jdjg_dwdm','=', $my_dwdm);
        $query->where($where);
        $query->where($where1);
        ($where2 && !$where4) && $query->where($where2);
        (!$where2 && $where4) && $query->whereIn('identifyinfos.jdjg_dwdm',$where4);
        $query->where($where3);
        $count = $query->select('identifyinfos.*', 'institution_codes.jdjg_name');
        $identifyinfos = $count ->offset($offset)
                                ->limit($limit)
                                ->orderBy($sortfield, $order)
                                ->get()
                                ->toArray();
        $count = $count->count();        
        return [
            'count' => $count,
            'data' => $identifyinfos
        ];
    }

    
    /**
     * 获取各级机构鉴定证书分页数据
     * @return array
     */
    public function getAllLevelInscertificates(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['jdzs_level'] ?? [];
        $offset = ($page - 1) * $limit;
        // 获取单位级别下的证书
        $where && $where = Company::where('dwjb',$where)->pluck('dwdm')->toArray();
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        // 查询语句
        $query = $this->leftJoin('institution_codes', 'identifyinfos.jdjg_dwdm', '=', 'institution_codes.jdjg_code');
        $where && $query->whereIn('identifyinfos.jdjg_dwdm',$where);
        $count = $query->select('identifyinfos.*', 'institution_codes.jdjg_name');
        $identifyinfos = $count ->offset($offset)
                                ->limit($limit)
                                ->orderBy($sortfield, $order)
                                ->get()
                                ->toArray();
        $count = $count->count();        
        return [
            'count' => $count,
            'data' => $identifyinfos
        ];
    }

    /**
     * 获取鉴定人员分页数据
     * @return array
     */
    public function getAppraiserStatistics(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['jdzs_level'] ?? [];
        $offset = ($page - 1) * $limit;
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        // 查询条件
        $where1 = $param['jdjg_dwdm'] ?? [];
        $where2 = $param['jdywfw_code'] ?? [];
        $where3 = $param['my_dwjb'] ?? [];
        $where4 = $param['province_level'] ?? [];
        if ($where1) $where1 = [['identifyinfos.jdjg_dwdm', $where1]]; 
        if ($where2) $where2 = [['identifyinfos.jdywfw_code', $where2]]; 
        // 获取本机构的证书列表
        $my_dwdm = $user = Auth::guard('admin')->user()->company_dwdm;
        // 判断是否包含子机构查询(分别是本机构单位级别为省级和市级的情况)
        if ($where3==2 && $param['jdjg_dwdm']!=520000) $where3 = $children_dwdm = Company::where('sjdm',$param['jdjg_dwdm'])->orwhere('dwdm',$param['jdjg_dwdm'])->pluck('dwdm')->toArray();
        if ($where3==2 && $param['jdjg_dwdm']==520000) $where3 = $children_dwdm = Company::pluck('dwdm')->toArray();
        if ($where3 == 3) $where3 = $children_dwdm = Company::where('sjdm',$my_dwdm)->orwhere('dwdm',$my_dwdm)->pluck('dwdm')->toArray();
        if ($where4) $where4 = Company::where('dwjb',$where4)->pluck('dwdm')->toArray();
        // dd($where4);
        // 获取所有鉴定人员的uid
        $query = $this->distinct();
        // 选择机构和子机构同时存在
        ($where1 && $where3) && $query->where(function ($query) use ($where1,$where3) {
            $query->whereIn('jdjg_dwdm',$where3)->orwhere($where1);
        });
        (!$where1 && !$where3 && !$where4 && $my_dwdm!=100000) && $query->where('jdjg_dwdm','=', $my_dwdm);
        $query->where($where2);
        ($where1 && !$where3) && $query->where($where1);
        (!$where1 && $where3) && $query->whereIn('jdjg_dwdm',$where3);
        $where4 && $query->whereIn('jdjg_dwdm',$where4);
        $jdry_uid = $query->pluck('admin_id');
        // 查询语句
        $query = Admin::leftJoin('mechanisms', 'mechanisms.id', '=', 'admins.mechanism_id')
                        ->leftJoin('companies', 'mechanisms.company_dwdm', '=', 'companies.dwdm');
        $query->whereIn('admins.id',$jdry_uid);
        $count = $query->select('admins.id','admins.username', 'admins.real_name','admins.sex', 'admins.tel','mechanisms.nsjgmc','companies.dwqc');
        $identifyinfos = $count ->offset($offset)
                                ->limit($limit)
                                ->orderBy($sortfield, $order)
                                ->get()
                                ->toArray();
        $count = $count->count();        
        return [
            'count' => $count,
            'data' => $identifyinfos
        ];
    }

    /**
     * 获取查看的鉴定人员所得证书分页数据
     * @return array
     */
    public function getLookAppraiserStatistics(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        // if ($where) $where = [['institution_codes.jdjg_name', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $jdry_id = $param['jdry_id'] ?? [];
        // 获取我自己的证书列表
        $uid = $user = Auth::guard('admin')->user()->id;
        $identifyinfos = $this   ->where('admin_id',$jdry_id)
                                 ->leftJoin('institution_codes', 'identifyinfos.jdjg_dwdm', '=', 'institution_codes.jdjg_code')
                                 ->select('identifyinfos.*', 'institution_codes.jdjg_name')
                                 ->offset($offset)
                                 ->limit($limit)
                                 ->orderBy($sortfield, $order)
                                 ->get()
                                 ->toArray();     
        $count = $this->where('admin_id',$uid)->leftJoin('institution_codes', 'identifyinfos.jdjg_dwdm', '=', 'institution_codes.jdjg_code')->select('identifyinfos.*', 'institution_codes.jdjg_name')->count();
        return [
            'count' => $count,
            'data' => $identifyinfos
        ];
    }
}
