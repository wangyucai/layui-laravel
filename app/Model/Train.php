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
            if($v['px_end_time']<time()) $v['if_expire'] = 1;
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
    /**
    * 获取报名及上报用户的分页数据
    * @return array
    */
    public function getBmUser(array $param) : array
    {
       $page = $param['page'];
       $limit = $param['limit'];
       $sortfield = $param['sortField'] ?? 'id';
       $order = $param['order'] ?? 'asc';
       $offset = ($page - 1) * $limit;  
       if($param['my_dwjb']<=2){// 超级管理员和省级管理员看到本级报名及下属单位上报
            $param['my_dwdm'] = 520000;
            $where = [['user_notice.city_if_selected',1]];
       }elseif($param['my_dwjb']==3){// 市级管理员看到县级上报和本级上报
            $where = [['user_notice.county_if_selected',1]];
       }else{
            $where = []; 
       } 
       // 获取报名通知的用户
       $notice_id = $param['notice_id'];
       $bm_user = Admin::leftJoin('user_notice', 'user_notice.user_id', '=', 'admins.id')
                             ->where('user_notice.notice_id',$notice_id)
                             ->where('user_notice.enter_dwdm',$param['my_dwdm'])
                             ->where('user_notice.if_enter',1)
                             ->orwhere($where)
                             ->offset($offset)
                             ->limit($limit)
                            ->orderBy($sortfield, $order)
                            ->select('admins.id', 'admins.username','admins.real_name','admins.sex','admins.nation', 'admins.tel', 'admins.company_dwdm','admins.mechanism_id','user_notice.notice_id')
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
         // 获取所有的民族数组
        $nation_arr = Cache::remember('nations', 120, function() {
            return DB::table('nations')->select('nation_bh','nation_name')->get()->pluck('nation_name', 'nation_bh')->toArray();
        });
        foreach ($bm_user as $k => $v) {
            $v['dwqc'] = $dw_arr[$v['company_dwdm']];
            $v['nsjgmc'] = $bm_arr[$v['mechanism_id']];
            $v['nation'] = $nation_arr[$v['nation']];
            $bm_user[$k] = $v;
        }
       $count =  $count = Admin::leftJoin('user_notice', 'user_notice.user_id', '=', 'admins.id')
                                    ->where('user_notice.notice_id',$notice_id)
                                    ->where('user_notice.enter_dwdm',$param['my_dwdm'])
                                    ->where('user_notice.if_enter',1)
                                    ->orwhere($where)
                                    ->count();
       return [
           'count' => $count,
           'data' => $bm_user
       ];
    }
    /**
     * 获取我的培训班分页数据
     * @return array
     */
    public function getMyTrain(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        $offset = ($page - 1) * $limit;
        // 获取培训用户表里我的培训ID
        $my_px_id = DB::table('user_train')->where('user_id',$param['my_id'])->pluck('train_id')->toArray();
        $my_px_id =array_values($my_px_id);
        $query = $this->leftJoin('host_unit', 'host_unit.id', '=', 'trains.zbdw_id');
        $query = $query->whereIn('trains.id',$my_px_id);
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
        // 鉴定业务范围
        $business_arr = Cache::remember('businesses', 120, function() {
            return DB::table('businesses')->select('jdywfw_code','jdywfw_name')->get()->pluck('jdywfw_name', 'jdywfw_code')->toArray();
        });
        // 信息化技术
        $xxhjs_arr = Cache::remember('infor_technology', 120, function() {
            return DB::table('infor_technology')->select('xxhjs_code','xxhjs_name')->get()->pluck('xxhjs_name', 'xxhjs_code')->toArray();
        });
        // 拼接数组
        foreach ($train as $k => $v) {
            if($v['px_end_time']<time()) $v['if_expire'] = 1;
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
