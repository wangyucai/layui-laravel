<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Identifyinfo;
use App\Service\IdentifyinfoService;
use App\Model\InstitutionCode;
use App\Model\Company;
use App\Model\Business;
use Auth;

class IdentifyinfoController extends Controller
{
	/**
     * 完善鉴定人员信息证书列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function IdentifyInfoList()
    {
        return view('admin.identifyinfos.IdentifyInfoList');
    }
    /**
     * 获取鉴定人员信息证书分页数据
     * @param Request $request
     * @param Identifyinfo $identifyinfo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdentifyInfos(Request $request, Identifyinfo $identifyinfo)
    {
        $data = $request->all();
        $res = $identifyinfo->getIdentifyInfos($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加我的证书时判断我的证书是否超过五张
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myIdentifyNum(Request $request)
    {
    	// 获取登录人员的id
        $my_id = Auth::guard('admin')->user()->id;
        // 获取我的证书的数量
        $myidentifynum = Identifyinfo::where('admin_id',$my_id)->count(); 
        if($myidentifynum>=5){
            return ['msg' => "每人最多只能添加五张证书"];
        }else{
            return ['msg' => 1];
        }
    }
	/**
     * 完善鉴定人员信息(添加我的证书)
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function completeIdentifyInfo(Request $request)
    {
    	if($request->isMethod('post')){
    		$this->validate($request, [
                // 'resume' => 'required',        
            ]);
            $data = $request->all();
            $data['user_id'] = Auth::guard('admin')->user()->id;
            $identifyinfoService = new IdentifyinfoService();
            $re = $identifyinfoService->completeIdentifyInfo($data);
            if (!$re) return ajaxError($identifyinfoService->getError(), $identifyinfoService->getHttpCode());
            return ajaxSuccess();
    	}else{
    		// 获取所有鉴定机构
        	$institutioncodes = InstitutionCode::all();
        	$institutioncodes = select_company('fj_jdjg_code', 'jdjg_code', $institutioncodes, '520000', '=', '1');
        	// 获取登录人员的姓名
        	$my_name = Auth::guard('admin')->user()->real_name;
        	// 获取登录人员的id
        	$my_id = Auth::guard('admin')->user()->id;
            // 获取鉴定业务范围
            $businesses = Business::all();
            return view('admin.identifyinfos.completeidentifyinfo', compact('my_name', 'institutioncodes', 'businesses', 'my_id'));
    	}
    }
    // 上传我的证书
    public function uploadMyzs(Request $request,ImageUploadHandler $uploader)
    {
        $uid = $user = Auth::guard('admin')->user()->id;
        if ($request->file) {
            $result = $uploader->save($request->file, 'myCertificate', $uid, 362);
            if ($result) {
                return [
                    'status' => 1,
                    'jdry_zspath' => $result['path'],
                ];
            }
        }
    }
    /**
     * 编辑我的鉴定人员信息证书
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editIdentifyInfo(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $identifyinfoService = new IdentifyinfoService();
            $re = $identifyinfoService->editIdentifyInfo($request->all());
            if (!$re) return ajaxError($identifyinfoService->getError(), $identifyinfoService->getHttpCode());
            return ajaxSuccess();
        } else {
            // 获取所有鉴定机构
            $institutioncodes = InstitutionCode::all();
            $institutioncodes = select_company('fj_jdjg_code', 'jdjg_code', $institutioncodes, '520000', '=', '1');
            // 获取鉴定业务范围
            $businesses = Business::all();
            // 获取我的证书信息
            $myidentify = Identifyinfo::find($request->id)->toArray();
            return view('admin.identifyinfos.editIdentifyInfo',compact('institutioncodes', 'businesses', 'myidentify'));
        }
    }
    /**
     * 删除我的鉴定人员信息证书
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delIdentifyInfo(Request $request)
    {
        $identifyinfoService = new IdentifyinfoService();
        $re = $identifyinfoService->delIdentifyInfo($request->id);
        if (!$re) return ajaxError($identifyinfoService->getError(), $identifyinfoService->getHttpCode());
        return ajaxSuccess();
    }
    /**
     * 本机构鉴定证书列表页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myInscertificateList(Request $request)
    {
        // 获取所有鉴定机构
        $institutioncodes = InstitutionCode::all();
        $institutioncodes = select_company('fj_jdjg_code', 'jdjg_code', $institutioncodes, '520000', '=', '1');
        // 获取鉴定业务范围
        $businesses = Business::all();
        // 获取我的用户单位级别
        $my_dwjb = $user = Auth::guard('admin')->user()->dwjb;
        return view('admin.identifyinfos.myInscertificateList', compact('institutioncodes', 'businesses','my_dwjb'));       
    }
    /**
     * 获取本机构鉴定证书分页数据
     * @param Request $request
     * @param Identifyinfo $identifyinfo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyInscertificates(Request $request, Identifyinfo $identifyinfo)
    {
        $data = $request->all();
        $res = $identifyinfo->getMyInscertificates($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    
    /**
     * 查看此鉴定证书详情
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lookMyIdentifyInfo(Request $request)
    {
        // 获取我的证书信息
        $myidentify = Identifyinfo::find($request->id)->toArray();
        // 获取鉴定机构
        $jdjg_name = InstitutionCode::where('jdjg_dwdm',$myidentify['jdjg_dwdm'])->value('jdjg_name');
        // 获取鉴定业务范围
        $jdywfw_name = Business::where('jdywfw_code',$myidentify['jdywfw_code'])->value('jdywfw_name');
        return view('admin.identifyinfos.lookMyIdentifyInfo',compact('myidentify', 'jdjg_name', 'jdywfw_name'));
    }

    /**
     * 各级机构鉴定证书列表页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allLevelInscertificateList(Request $request)
    {
        return view('admin.identifyinfos.allLevelInscertificateList');  
    }
    /**
     * 获取各级机构鉴定证书分页数据
     * @param Request $request
     * @param Identifyinfo $identifyinfo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllLevelInscertificates(Request $request, Identifyinfo $identifyinfo)
    {
        $data = $request->all();
        $res = $identifyinfo->getAllLevelInscertificates($data);
        return ajaxSuccess($res['data'], $res['count']);
    }

    /**
     * 鉴定人员统计列表页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function AppraiserStatisticList(Request $request)
    {
        // 获取所有鉴定机构
        $institutioncodes = InstitutionCode::all();
        $institutioncodes = select_company('fj_jdjg_code', 'jdjg_code', $institutioncodes, '520000', '=', '1');
        // 获取鉴定业务范围
        $businesses = Business::all();
        // 获取我的用户单位级别
        $my_dwjb = $user = Auth::guard('admin')->user()->dwjb;
        return view('admin.identifyinfos.appraiserStatisticList', compact('institutioncodes', 'businesses','my_dwjb'));  
    }
    /**
     * 获取鉴定人员分页数据
     * @param Request $request
     * @param Identifyinfo $identifyinfo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAppraiserStatistics(Request $request, Identifyinfo $identifyinfo)
    {
        $data = $request->all();
        $res = $identifyinfo->getAppraiserStatistics($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 查看鉴定人员所得证书列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lookAppraiserStatistics(Request $request)
    {
        $jdry_id =  $request->route( 'id' );
        return view('admin.identifyinfos.lookAppraiserStatisticsList', compact('jdry_id'));  
    }
    /**
     * 获取查看的鉴定人员所得证书分页数据
     * @param Request $request
     * @param Identifyinfo $identifyinfo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLookAppraiserStatistics(Request $request, Identifyinfo $identifyinfo)
    {
        $data = $request->all();
        $res = $identifyinfo->getLookAppraiserStatistics($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
}
