<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\FixedAsset;
use App\Model\Mechanism;
use Auth;
use App\Service\FixedAssetService;

class FixedAssetController extends Controller
{
    /**
     * 我的固定资产列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myFixedAssetList()
    {
        return view('admin.fixedassets.myFixedAssetList');
    }
    /**
     * 获取我的固定资产分页数据
     * @param Request $request
     * @param FixedAsset $fixedasset
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyFixedAsset(Request $request, FixedAsset $fixedasset)
    {
        $data = $request->all();
        $data['gdzc_uid'] = Auth::guard('admin')->user()->id;
        $data['gdzc_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $data['gdzc_bmdm'] = Auth::guard('admin')->user()->mechanism_code;
        $res = $fixedasset->getMyFixedAsset($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加我的固定资产
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addMyFixedAsset(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
            	// 
            ]);
            $data = $request->all();
            $data['gdzc_uid'] = Auth::guard('admin')->user()->id;
            $data['gdzc_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        	$data['gdzc_bmdm'] = Auth::guard('admin')->user()->mechanism_code;
            $fixedAssetService = new FixedAssetService();
            $re = $fixedAssetService->addMyFixedAsset($data);
            if (!$re) return ajaxError($fixedAssetService->getError(), $fixedAssetService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	// 固定资产的发放部门
        	$ffbm_arr =  ffbm();
        	// 判断固定资产表里是否有添加记录
            $has = FixedAsset::count();
            if ($has > 0) {
	            $gdzc_bh = FixedAsset::orderBy('id','desc')->first();
	            $gdzc_bh = $gdzc_bh->gdzc_bh;
	        }else{
	        	$gdzc_bh = intval("100000");
	        }
	        // 该资产编号自增
	        $gdzc_bh = ++$gdzc_bh;
        	$gdzc_bh_all = "GZJCYZC+".$gdzc_bh;
            return view('admin.fixedassets.addMyFixedAsset', compact('ffbm_arr','gdzc_bh','gdzc_bh_all'));
        }
    }
    // 上传扫描件
    public function uploadPic(Request $request,ImageUploadHandler $uploader)
    {
        $uid = $user = Auth::guard('admin')->user()->id;
        if ($request->file) {
            $result = $uploader->save($request->file, 'MyFixedAsset', $uid, 362);
            if ($result) {
                return [
                    'status' => 1,
                    'gdzc_pic' => $result['path'],
                ];
            }
        }
    }
    /**
     * 归还我的固定资产
     */
    public function backMyFixedAsset(Request $request)
    {
        $re = FixedAsset::where('id', $request->id)->update(['if_back' => $request->if_back,'gdzc_ghrq'=>time()]);
        if (!$re) return ajaxError('修改失败', HttpCode::BAD_REQUEST);
        return ajaxSuccess();
    }
    /**
     * 查看我的固定资产
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lookMyFixedAsset(Request $request)
    {
        $myFixedAsset = FixedAsset::find($request->id)->toArray();
        // 固定资产的发放部门
    	$ffbm_arr =  ffbm();
        $gdzc_bh_all = "GZJCYZC+".$myFixedAsset['gdzc_bh'];
        return view('admin.fixedassets.lookMyFixedAsset',compact('myFixedAsset','ffbm_arr','gdzc_bh_all'));
    }
    /**
     * 编辑我的固定资产
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editMyFixedAsset(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
             // 
            ]);
            $data = $request->all();
            $fixedAssetService = new FixedAssetService();
            $re = $fixedAssetService->editMyFixedAsset($data);
            if (!$re) return ajaxError($fixedAssetService->getError(), $fixedAssetService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            $myFixedAsset = FixedAsset::find($request->id)->toArray();
            // 固定资产的发放部门
        	$ffbm_arr =  ffbm();
            $gdzc_bh_all = "GZJCYZC+".$myFixedAsset['gdzc_bh'];
            return view('admin.fixedassets.editMyFixedAsset',compact('myFixedAsset','ffbm_arr','gdzc_bh_all'));
        }
    }
    /**
     * 删除我的固定资产
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delMyFixedAsset(Request $request)
    {
    	$fixedAssetService = new FixedAssetService();
        $re = $fixedAssetService->delMyFixedAsset($request->id);
        if (!$re) return ajaxError($fixedAssetService->getError(), $fixedAssetService->getHttpCode());
        return ajaxSuccess();
    }

    /**
     * 固定资产管理列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function fixedAssetList()
    {
    	$my_dwdm = Auth::guard('admin')->user()->company_dwdm;
    	// 获取本单位下所有的部门
    	$bm_arr = Mechanism::where('company_dwdm',$my_dwdm)->select('mechanism_code','nsjgmc')->get()->pluck('nsjgmc', 'mechanism_code')->toArray();
        return view('admin.fixedassets.fixedAssetList',compact('bm_arr'));
    }
    /**
     * 获取固定资产管理分页数据
     * @param Request $request
     * @param FixedAsset $fixedasset
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFixedAsset(Request $request, FixedAsset $fixedasset)
    {
        $data = $request->all();
        $data['gdzc_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $res = $fixedasset->getFixedAsset($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
}
