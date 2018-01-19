<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Train extends Model
{
    protected $guarded = [];
    /**
     * 获取培训信息分页数据
     * @return array
     */
    public function getTrain(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        if(isset($param['pxrq_start'])){
            $where = strtotime($param['pxrq_start']);
        }else{
            $where = [];
        }
        if(isset($param['pxrq_end'])){
            $where1 = strtotime($param['pxrq_end']);
        }else{
            $where1 = [];
        }
        $where2 = $param['pxfx'] ?? [];
        $where3 = $param['pxbt'] ?? [];
        $where4 = $param['pxdd'] ?? [];
        $where5 = $param['zbdw'] ?? [];

        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['trains.px_start_time', '>=', $where]]; 
        if ($where && $where1) $where1 = [['trains.px_start_time', '<=', $where1]]; 
        if ($where2) $where2 = [['trains.px_fx', 'like', $where2.'%']]; 
        if ($where3) $where3 = [['trains.px_title', 'like', '%'.$where3.'%']];
        if ($where4) $where4 = [['trains.px_place', 'like', '%'.$where4.'%']];
        if ($where5) $where5 = [['trains.zbdw_id', $where5]]; 
        $offset = ($page - 1) * $limit;
        $query = $this->leftJoin('host_unit', 'host_unit.id', '=', 'trains.zbdw_id');
        $query = $query->where($where);
        $query = $query->where($where1);
        $query = $query->where($where2);
        $query = $query->where($where3);
        $query = $query->where($where4);
        $query = $query->where($where5);
        $count = $query->select('trains.*','host_unit.name');
        $train = $count  ->offset($offset)
                         ->limit($limit)
                         ->orderBy($sortfield, $order)
                         ->get()
                         ->toArray();
        // 培训方向
        $px_fx_arr = Cache::remember('train_direction', 120, function() {
            return DB::table('train_direction')->select('pxfx_name','pxfx_code')->get()->pluck('pxfx_name', 'pxfx_code')->toArray();
        });
        // $px_fx_arr =  DB::table('train_direction')->select('pxfx_name','pxfx_code')->get()->pluck('pxfx_name', 'pxfx_code')->toArray();
        // 鉴定业务范围
        $business_arr = Cache::remember('businesses', 120, function() {
            return DB::table('businesses')->select('jdywfw_code','jdywfw_name')->get()->pluck('jdywfw_name', 'jdywfw_code')->toArray();
        });
        // $business_arr =  DB::table('businesses')->select('jdywfw_code','jdywfw_name')->get()->pluck('jdywfw_name', 'jdywfw_code')->toArray();
        // 信息化技术
        $xxhjs_arr = Cache::remember('infor_technology', 120, function() {
            return DB::table('infor_technology')->select('xxhjs_code','xxhjs_name')->get()->pluck('xxhjs_name', 'xxhjs_code')->toArray();
        });
        // $xxhjs_arr = DB::table('infor_technology')->select('xxhjs_code','xxhjs_name')->get()->pluck('xxhjs_name', 'xxhjs_code')->toArray();
        // 拼接数组
        foreach ($train as $k => $v) {
            // 把培训时间转成日期格式
            $v['px_start_time'] = date('Y-m-d',$v['px_start_time']);
            $v['px_end_time'] = date('Y-m-d',$v['px_end_time']);
            // 把培训方向转成数组
            $my_pxfx = explode(',', $v['px_fx']);
            $v['px_fx'] = $px_fx_arr[$my_pxfx['0']];
            if(count($my_pxfx)==2){
                if($my_pxfx['0'] == '03'){
                    $next_name = $business_arr[$my_pxfx['1']];         
                }else{
                    $next_name = $xxhjs_arr[$my_pxfx['1']];
                }
                $v['px_fx'] = $v['px_fx'].'->'.$next_name;
            }
            $train[$k] = $v;
        }
        $count = $count->count();
        return [
            'count' => $count,
            'data' => $train
        ];
    }
}
