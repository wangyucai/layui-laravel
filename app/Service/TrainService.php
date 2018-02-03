<?php

namespace App\Service;


use App\Common\Enum\HttpCode;
use App\Model\Train;
use App\Model\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TrainService extends BaseService
{
    /**
     * 添加培训信息
     * @param $data
     * @return array|bool
     */
    public function addTrain(array $data) : bool
    {
        unset($data['token']);
        if($data['px_end_time']<time()) $data['if_expire'] = 1;
        $data['px_time'] = $data['px_end_time'] - $data['px_start_time'];
        $data['px_start_time'] = strtotime($data['px_start_time']);
        $data['px_end_time'] = strtotime($data['px_end_time']);
        $data['px_notice_dw'] = serialize(explode(',', $data['px_notice_dw']));
        $data['uid']          = Auth::guard('admin')->user()->id;
        $data['px_dwdm']      = Auth::guard('admin')->user()->company_dwdm;
        // 查询主办单位表
        $dw = DB::table('host_unit')->where('name', '=', $data['zbdw_id'])->first();
        if (empty($dw)) {
            $zbdw['name'] = $data['zbdw_id'];
            $data['zbdw_id'] = DB::table('host_unit')->insertGetId($zbdw);
        } else {
            $data['zbdw_id'] = $dw->id;
        }
        DB::beginTransaction();
        $train = Train::create($data);
        // 非队列实现
        $this->sendTrains($train);
        if (!$train) {
            $this->error = '添加失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 发送培训给指定用户
     * @param $data
     * @return array|bool
     */
    public function sendTrains($train)
    {
        // 获取培训发送给哪个单位
        $px_notice_dw = $train->px_notice_dw;
        $px_notice_dw_arr = unserialize($px_notice_dw);
        // 通知用户
        $users = Admin::whereIn('company_dwdm',$px_notice_dw_arr)->where('tel_hm','!=','')->get();
        foreach($users as $user){
            $user->addTrain($train);
        }
    }

    /**
     * 编辑培训信息
     * @param $data
     * @return bool
     */
    public function editTrain(array $data) : bool
    {
        $train = Train::find($data['px_id']);
        $px_notice_dw_arr = unserialize($train->px_notice_dw);
        $px_start_time = strtotime($data['px_start_time']);
        $px_end_time = strtotime($data['px_end_time']);
        if($px_end_time<time()) $data['if_expire'] = 1;
            $data['if_expire'] = 0;
        $data['px_time'] = $px_end_time - $px_start_time;
        // 手动开启事务
        DB::beginTransaction();
        $train->px_title         = $data['px_title'];
        $train->px_place         = $data['px_place'];
        $train->px_start_time    = $px_start_time;
        $train->px_end_time      = $px_end_time;
        $train->if_expire        = $data['if_expire'];
        $train->px_time          = $data['px_time'];
        $train->px_fx            = $data['px_fx'];
        $train->px_renshu        = $data['px_renshu'];
        $train->px_notice_dw     = serialize(explode(',', $data['px_notice_dw']));
        // 查询主办单位表
        $dw = DB::table('host_unit')->where('name', '=', $data['zbdw_id'])->first();
        if (empty($dw)) {
            $zbdw['name'] = $data['zbdw_id'];
            $train->zbdw_id = DB::table('host_unit')->insertGetId($zbdw);
        } else {
            $train->zbdw_id = $dw->id;
        }
        $this->delTrains($train,$px_notice_dw_arr);
        $this->sendTrains($train);
        $re = $train->save();
        if ($re === false) {
            $this->error = '修改失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 删除指定用户的培训
     * @param $data
     * @return array|bool
     */
    public function delTrains($train, $px_notice_dw_arr)
    {
        // 通知用户
        $users = Admin::whereIn('company_dwdm',$px_notice_dw_arr)->where('tel_hm','!=','')->get();
        foreach($users as $user){
            $user->deleteTrain($train);
        }
    }
    /**
     * 删除培训信息
     * @param $data
     * @return bool
     */
    public function delTrain(int $trainId) : bool
    {
        $train = Train::find($trainId);
        $px_notice_dw_arr = unserialize($train->px_notice_dw);
        $this->delTrains($train,$px_notice_dw_arr);
        if (!$train) {
            $this->error = '该培训信息不存在';
            $this->httpCode = HttpCode::GONE;
            return false;
        }
        DB::beginTransaction();
        $re = $train->delete();
        if ($re === false) {
            $this->error = '删除失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 上报已选用户信息
     * @param $data
     * @return bool
     */
    public function selectBmUser(array $data) : bool
    {
        DB::beginTransaction();
        foreach ($data as $k => $v) {
            if($v['my_dwjb']==2){
                $update_arr = ['province_if_selected'=>1,'province_dwdm'=>520000];
            }elseif($v['my_dwjb']==3){
                $update_arr = ['city_if_selected'=>1,'city_dwdm'=>$v['my_dwdm']];
            }else{
                $update_arr = ['county_if_selected'=>1,'county_dwdm'=>$v['my_dwdm']];
            }
            $res = DB::table('user_notice')
                        ->where([['user_id', '=', $v['id']],['notice_id', '=', $v['notice_id']]])
                        ->first();
            if($res->county_if_selected==1 and $v['my_dwjb']==4){
                $this->error = $v['real_name'].'已被上报，无需选择';
                $this->httpCode = HttpCode::NOT_FOUND;
                return false;
            }
            if($res->city_if_selected==1 and $v['my_dwjb']==3){
                $this->error = $v['real_name'].'已被上报，无需选择';
                $this->httpCode = HttpCode::NOT_FOUND;
                return false;
            }
            // if($res->province_if_selected==1 and $v['my_dwjb']==2){
            //     $this->error = $v['real_name'].'已被上报，无需选择';
            //     $this->httpCode = HttpCode::NOT_FOUND;
            //     return false;
            // }
            $re = DB::table('user_notice')->where('user_id', $v['id'])->where('notice_id',$v['notice_id'])->update($update_arr);
        }
        if ($re === false) {
            $this->error = '更新失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
    /**
     * 反馈信息给已选用户的信息
     * @param $data
     * @return bool
     */
    public function MessageFeedback(array $data) : bool
    {
        $my_id = Auth::guard('admin')->user()->id;
        $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
        $time = \Carbon\Carbon::now()->toDateTimeString();
        DB::beginTransaction();
        foreach ($data as $k => $v) {
            $has = DB::table('prompt_messages')
                        ->where([['receiver_id', '=', $v['id']],['msg_type', '=', '1'],['notice_id', '=', $v['notice_id']]])
                        ->count();
            if($has){
                $this->error = $v['real_name'].'已成功反馈信息，无需选择';
                $this->httpCode = HttpCode::NOT_FOUND;
                return false;
            }
            $msg_arr = ['receiver_id' => $v['id'], 'sender_id' => $my_id, 
                        'sender_dwdm' => $my_dwdm, 'msg_type' => 1, 
                        'msg_content' => $v['feedback_msg'], 'notice_id' => $v['notice_id'],
                        'created_at' => $time, 'updated_at' => $time];
            $re = DB::table('prompt_messages')->insert($msg_arr);
        }
        if ($re === false) {
            $this->error = '信息反馈失败';
            $this->httpCode = HttpCode::BAD_REQUEST;
            DB::rollBack();
            return false;
        }
        DB::commit();
        return true;
    }
}