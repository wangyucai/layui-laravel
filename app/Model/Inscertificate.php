<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Inscertificate extends Model
{
    protected $guarded = [];
    /**
     * 获取司法鉴定机构证书分页数据
     * @return array
     */
    public function getInscertificates(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['institution_codes.jdjg_name', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $where1 =  $param['dwdm']!=100000 ? $param['dwdm'] : [];
        if ($where1) $where1 = [['inscertificates.jdjg_dm', $where1]];
        $inscertificates = $this ->where($where)
                                 ->where($where1)
                                 ->leftJoin('institution_codes', 'inscertificates.jdjg_dm', '=', 'institution_codes.jdjg_dwdm')
                                 ->select('inscertificates.*', 'institution_codes.jdjg_name')
			                     ->offset($offset)
			                     ->limit($limit)
			                     ->orderBy($sortfield, $order)
			                     ->get()
			                     ->toArray();     
        $count = $this->where($where)->leftJoin('institution_codes', 'inscertificates.jdjg_dm', '=', 'institution_codes.jdjg_code')->select('inscertificates.*', 'institution_codes.jdjg_name')->count();
        return [
            'count' => $count,
            'data' => $inscertificates
        ];
    }
}
