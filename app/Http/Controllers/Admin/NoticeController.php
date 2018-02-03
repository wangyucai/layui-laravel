<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Notice;
use App\Model\NoticeType;
use App\Model\Company;
use App\Model\Train;
use App\Service\NoticeService;
use Auth;

class NoticeController extends Controller
{
    /**
     * 通知列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function noticeList()
    {
        return view('admin.notices.noticeList');
    }
    /**
     * 获取通知分页数据
     * @param Request $request
     * @param Notice $notice
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotice(Request $request, Notice $notice)
    {
        $data = $request->all();
        $res = $notice->getNotice($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加通知
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addNotice(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
            ]);
            $data = $request->all();
            $data['from_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
            $noticeService = new NoticeService();
            $re = $noticeService->addNotice($data);
            if (!$re) return ajaxError($noticeService->getError(), $noticeService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            // 发送培训预备通知
            $px_info="";
            if($request->px_id){
                $px_info = Train::leftJoin('host_unit', 'host_unit.id', '=', 'trains.zbdw_id')              ->where('trains.id', $request->px_id)
                                  ->select('trains.*','host_unit.name')
                                  ->first();
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
                $pxfx_arr = explode(',', $px_info->px_fx);
                $px_info->px_fx =  $px_fx_arr[$pxfx_arr['0']];
                if(count($pxfx_arr)==2){
                    if($pxfx_arr['0'] == '03'){
                        $next_name = $business_arr[$pxfx_arr['1']];         
                    }else{
                        $next_name = $xxhjs_arr[$pxfx_arr['1']];
                    }
                    $px_info->px_fx = $px_info->px_fx.'->'.$next_name;
                }
            }
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
            $noticetypes = NoticeType::all()->toArray();
            return view('admin.notices.addNotice',[
                    'companies' => json_encode($companies), 'noticetypes' => $noticetypes, 'px_info' => $px_info]);
        }
    }
    // 上传通知附件
    public function uploadAttachment(Request $request,ImageUploadHandler $uploader)
    {
        $uid = $user = Auth::guard('admin')->user()->id;
        if ($request->file) {
            $result = $uploader->save($request->file, 'noticeAttachment', $uid);
            if ($result) {
                return [
                    'status' => 1,
                    'attachment' => $result['path'],
                ];
            }
        }
    }
    /**
     * 编辑通知
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editNotice(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $data = $request->all();
            $data['from_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
            $noticeService = new NoticeService();
            $re = $noticeService->editNotice($data);
            if (!$re) return ajaxError($noticeService->getError(), $noticeService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            $notice = Notice::find($request->id)->toArray();
            $notice_dwdm_has = unserialize($notice['notice_dwdm']);
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
            foreach ($companies as $k => $company) {
                if(in_array($company['dwdm'],$notice_dwdm_has)){
                    $company['checked'] = true;
                }
                $companies[$k] = $company;
            }
            $noticetypes = NoticeType::all()->toArray();
            return view('admin.notices.editNotice',[
                    'companies' => json_encode($companies), 'noticetypes' => $noticetypes, 'notice' => $notice]);
        }
    }
    /**
     * 删除通知
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delNotice(Request $request)
    {
        $noticeService = new NoticeService();
        $re = $noticeService->delNotice($request->id);
        if (!$re) return ajaxError($noticeService->getError(), $noticeService->getHttpCode());
        return ajaxSuccess();
    }

    /**
     * 我的通知列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myNoticeList()
    {
        return view('admin.notices.myNoticeList');
    }
    /**
     * 获取我的通知分页数据
     * @param Request $request
     * @param Notice $notice
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyNotice(Request $request, Notice $notice)
    {
        $data = $request->all();
        $res = $notice->getMyNotice($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    
    /*把我的通知标记为已读*/
    public function readMyNotice(Request $request)
    {
        $noticeService = new NoticeService();
        $re = $noticeService->readMyNotice($request->all());
        if (!$re) return ajaxError($noticeService->getError(), $noticeService->getHttpCode());
        return ajaxSuccess();
    }
    /**
     * 查看通知详情
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myNoticeShow(Notice $notice,$mynotice)
    {   
        $enter_dwdm = Auth::guard('admin')->user()->company_dwdm;
        $user_id = Auth::guard('admin')->user()->id;
        $param['user_id'] = $user_id;
        $param['mynotice'] = $mynotice;
        $mynoticedetail = $notice->myNoticeDetail($param);
        return view('admin.notices.myNoticeShow',compact('mynoticedetail','enter_dwdm'));
    }
    /*下载附件*/
    public function downAttachment(Request $request)
    {
        $noticeService = new NoticeService();
        $re = $noticeService->downAttachment($request->all());
        if (!$re) return ajaxError($noticeService->getError(), $noticeService->getHttpCode());
        return ajaxSuccess();
    }

    /**
     * 通知的用户列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function noticeUserList($id)
    {
        return view('admin.notices.noticeUserList',compact('id'));
    }
    /**
     * 获取通知的用户分页数据
     * @param Request $request
     * @param Notice $notice
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNoticeUser(Request $request, Notice $notice)
    {
        $data = $request->all();
        $res = $notice->getNoticeUser($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 报名培训
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trainEnter(Request $request)
    {
        $noticeService = new NoticeService();
        $re = $noticeService->trainEnter($request->all());
        if (!$re) return ajaxError($noticeService->getError(), $noticeService->getHttpCode());
        return ajaxSuccess();
    }

    /**
     * 我的提示信息列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myMessageList()
    {
        return view('admin.notices.myMessageList');
    }
    /**
     * 获取我的提示信息分页数据
     * @param Request $request
     * @param Notice $notice
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyMessage(Request $request, Notice $notice)
    {
        $data = $request->all();
        $res = $notice->getMyMessage($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /*把我的提示消息标记为已读*/
    public function readMyMessage(Request $request)
    {
        $noticeService = new NoticeService();
        $re = $noticeService->readMyMessage($request->all());
        if (!$re) return ajaxError($noticeService->getError(), $noticeService->getHttpCode());
        return ajaxSuccess();
    }
}
