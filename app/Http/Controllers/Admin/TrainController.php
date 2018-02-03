<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Model\Train;
use App\Model\Company;
use App\Model\Notice;
use App\Service\TrainService;
use Auth;

class TrainController extends Controller
{
    /**
     * 培训信息列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trainList()
    {
        $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
        //获取培训方向信息
        $fx_data = DB::table('train_direction')->get();
        //获取主办单位
        $zhuban = DB::table('host_unit')->get();
        return view('admin.trains.trainList', compact('my_dwdm', 'fx_data', 'zhuban'));
    }
    /**
     * 获取培训信息分页数据
     * @param Request $request
     * @param Train $train
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrain(Request $request, Train $train)
    {
        $data = $request->all();
        $res = $train->getTrain($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加培训信息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addTrain(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
            
            ]);
            $trainService = new TrainService();
            $re = $trainService->addTrain($request->all());
            if (!$re) return ajaxError($trainService->getError(), $trainService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            //获取培训方向信息
            $fx_data = DB::table('train_direction')->get();
            //获取鉴定门类
            $jd_data = DB::table('businesses')->get();
            //信息化技术
            $xinxi_data = DB::table('infor_technology')->get();
            //获取主办单位
            $zhuban = DB::table('host_unit')->get();
            // 获取通知单位
            $my_dwjb = Auth::guard('admin')->user()->dwjb;
            $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
            if($my_dwjb == 2){//省级
                $companies = Company::where('dwdm','!=','100000')->select('dwdm','sjdm','dwqc')->get()->toArray();
            }elseif($my_dwjb == 3){// 市级
                $companies = Company::where('dwdm',$my_dwdm)->orwhere('sjdm',$my_dwdm)->select('dwdm','sjdm','dwqc')->get()->toArray();
            }else{
                $companies = Company::where('dwdm',$my_dwdm)->select('dwdm','sjdm','dwqc')->get()->toArray();
            }
            return view('admin.trains.addTrain', 
                [
                    'companies' => json_encode($companies), 'fx_data' => $fx_data, 
                    'jd_data' => $jd_data, 'xinxi_data' => $xinxi_data, 'zhuban' => $zhuban,
                ]);
        }
    }
    
    /**
     * 编辑培训信息
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editTrain(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $trainService = new TrainService();
            $re = $trainService->editTrain($request->all());
            if (!$re) return ajaxError($trainService->getError(), $trainService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            //获取培训方向信息
            $fx_data = DB::table('train_direction')->get();
            //获取鉴定门类
            $jd_data = DB::table('businesses')->get();
            //信息化技术
            $xinxi_data = DB::table('infor_technology')->get();
            //获取主办单位
            $zhuban = DB::table('host_unit')->get();
            // 获取该培训信息
            $trains = Train::leftJoin('host_unit', 'host_unit.id', '=', 'trains.zbdw_id')->where('trains.id',$request->id)->select('trains.*','host_unit.name')->first()->toArray();
            // 已选中的通知单位
            $notice_dw_has = unserialize($trains['px_notice_dw']);
            // 获取通知单位
            // 获取管理员单位级别
            $my_dwjb = Auth::guard('admin')->user()->dwjb;
            $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
            if($my_dwjb == 2){//省级
                $companies = Company::where('dwdm','!=','100000')->select('dwdm','sjdm','dwqc')->get()->toArray();
            }elseif($my_dwjb == 3){// 市级
                $companies = Company::where('dwdm',$my_dwdm)->orwhere('sjdm',$my_dwdm)->select('dwdm','sjdm','dwqc')->get()->toArray();
            }else{
                $companies = Company::where('dwdm',$my_dwdm)->select('dwdm','sjdm','dwqc')->get()->toArray();
            }
            
            foreach ($companies as $k => $company) {
                if(in_array($company['dwdm'],$notice_dw_has)){
                    $company['checked'] = true;
                }
                $companies[$k] = $company;
            }
            return view('admin.trains.editTrain', 
                [
                    'companies' => json_encode($companies), 'fx_data' => $fx_data, 
                    'jd_data' => $jd_data, 'xinxi_data' => $xinxi_data, 
                    'zhuban' => $zhuban, 'trains' => $trains
                ]);
        }
    }
    /**
     * 删除培训信息
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delTrain(Request $request)
    {
        $trainService = new TrainService();
        $re = $trainService->delTrain($request->id);
        if (!$re) return ajaxError($trainService->getError(), $trainService->getHttpCode());
        return ajaxSuccess();
    }
    /**
     * 查看报名用户列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bmUserList($trainID)
    {
        $my_dwjb = Auth::guard('admin')->user()->dwjb;
        $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
        // 获取该培训的通知
        $notice_id = Notice::where('px_id',$trainID)->value('id');
        // 获取培训的发起者单位代码及级别
        $send_px_dwdm = Train::where('id',$trainID)->value('px_dwdm');
        $send_px_dwjb = Company::where('dwdm',$send_px_dwdm)->value('dwjb');
        if($send_px_dwjb==1) $send_px_dwjb =2;// 默认超级管理员单位级别为2
        return view('admin.trains.bmUserList',compact('notice_id','my_dwjb','my_dwdm','send_px_dwdm','send_px_dwjb'));
    }
    /**
     * 获取培训信息分页数据
     * @param Request $request
     * @param Train $train
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBmUser(Request $request, Train $train)
    {
        $data = $request->all();
        $res = $train->getBmUser($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    
    /**
     * 上报已选用户信息
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function selectBmUser(Request $request)
    {
        $trainService = new TrainService();
        $re = $trainService->selectBmUser($request->all());
        if (!$re) return ajaxError($trainService->getError(), $trainService->getHttpCode());
        return ajaxSuccess();
    }
    /**
     * 反馈信息给已选用户的信息
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function MessageFeedback(Request $request)
    {      
        $data = $request->all();
        $trainService = new TrainService();
        $re = $trainService->MessageFeedback($data);
        if (!$re) return ajaxError($trainService->getError(), $trainService->getHttpCode());
        return ajaxSuccess();
    }

    /**
     * 我的培训班列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myTrainList()
    {
        $my_id = Auth::guard('admin')->user()->id;
        return view('admin.trains.myTrainList', compact('my_id'));
    }
    /**
     * 获取我的培训班分页数据
     * @param Request $request
     * @param Train $train
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyTrain(Request $request, Train $train)
    {
        $data = $request->all();
        $res = $train->getMyTrain($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
}
