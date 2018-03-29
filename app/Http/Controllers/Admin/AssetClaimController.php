<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\ImageUploadHandler;
use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AssetClaim;
use App\Model\EquipmentAsset;
use App\Model\Inventory;
use App\Model\DeviceIdentity;
use App\Model\Warehouse;
use App\Model\UserReceive;
use App\Model\Company;
use Auth;
use App\Service\AssetClaimService;
use App\Service\InventoryService;
use Illuminate\Support\Facades\DB;

class AssetClaimController extends Controller
{
    /**
     * 资产领用列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assetClaimList()
    {
        return view('admin.assetclaims.assetClaimList');
    }
    /**
     * 获取领用资产的分页数据
     * @param Request $request
     * @param Inventory $inventory
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssetClaim(Request $request, Inventory $inventory)
    {
        $data = $request->all();
        $data['my_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $data['my_bmdm'] = Auth::guard('admin')->user()->mechanism_code;
        $res = $inventory->getAssetClaim($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 设备身份列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deviceIdentityList($id)
    {
        return view('admin.assetclaims.deviceIdentityList',compact('id'));
    }
    /**
     * 获取设备身份的分页数据
     * @param Request $request
     * @param DeviceIdentity $deviceidentity
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDeviceIdentity(Request $request, DeviceIdentity $deviceidentity)
    {
        $data = $request->all();
        $res = $deviceidentity->getDeviceIdentity($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 编辑设备身份
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editDeviceIdentity(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
             // 
            ]);
            $data = $request->all();
            $assetClaimService = new AssetClaimService();
            $re = $assetClaimService->editDeviceIdentity($data);
            if (!$re) return ajaxError($assetClaimService->getError(), $assetClaimService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            $deviceIdentity = DeviceIdentity::find($request->id)->toArray();
            return view('admin.assetclaims.editDeviceIdentity',compact('deviceIdentity'));
        }
    }
    /**
     * 申领装备资产
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addAssetClaim(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
            	// 
            ]);
            $data = $request->all();
            $data['ly_uid'] = Auth::guard('admin')->user()->id;
            $data['ly_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        	$data['ly_bmdm'] = Auth::guard('admin')->user()->mechanism_code;
            $assetClaimService = new AssetClaimService();
            $re = $assetClaimService->addAssetClaim($data);
            if (!$re) return ajaxError($assetClaimService->getError(), $assetClaimService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	// 资产id
        	$zc_id = $request->route( 'id' );
        	// 获取仓库序号
        	$ck_id = Inventory::where('kc_zcid',$zc_id)->value('kc_ckid');
        	// 库存量
        	$kc_nums = Inventory::where('kc_zcid',$zc_id)->value('kc_nums');
        	// 归属门类表
        	$gsml_arr =  gsml();
            return view('admin.assetclaims.addAssetClaim', compact('gsml_arr','zc_id','ck_id','kc_nums'));
        }
    }
    /**
     * 我申领的资产列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myAssetClaimList()
    {
        return view('admin.assetclaims.myAssetClaimList');
    }
    /**
     * 获取我申领的资产的分页数据
     * @param Request $request
     * @param AssetClaim $assetclaim
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyAssetClaim(Request $request, AssetClaim $assetclaim)
    {
        $data = $request->all();
        $data['my_id'] = Auth::guard('admin')->user()->id;
        $res = $assetclaim->getMyAssetClaim($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 申领资产管理列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allAssetClaimList()
    {
        return view('admin.assetclaims.allAssetClaimList');
    }
    /**
     * 获取申领资产管理的分页数据
     * @param Request $request
     * @param AssetClaim $assetclaim
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllAssetClaim(Request $request, AssetClaim $assetclaim)
    {
        // 只统计本单位的
        $data = $request->all();
        $data['my_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
       	// $data['my_bmdm'] = Auth::guard('admin')->user()->mechanism_code;
        $res = $assetclaim->getAllAssetClaim($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    
    /**
     * 反馈信息给已申领物品的用户
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkAssetclaim(Request $request)
    {      
        $data = $request->all();
        $assetClaimService = new AssetClaimService();
        $re = $assetClaimService->checkAssetclaim($data);
        if (!$re) return ajaxError($assetClaimService->getError(), $assetClaimService->getHttpCode());
        return ajaxSuccess();
    }
    /**
     * 下载资产申领表
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadMyAssetClaim(Request $request, AssetClaim $assetclaim)
    {      
        $data = $request->all();
        $data['my_name'] = Auth::guard('admin')->user()->real_name;
        $res = $assetclaim->downloadMyAssetClaim($data);
        // var_dump($res);die();
        return  [
            'code' => 0,
            'msg' => $res['all_path'],
        ];
    }
    /**
     * 把设备身份报废
     */
    public function bfDeviceIdentity(Request $request)
    {
        $re = DeviceIdentity::where('id', $request->sbsf_id)->update(['if_bf' => $request->if_bf]);
        if (!$re) return ajaxError('修改失败', HttpCode::BAD_REQUEST);
        return ajaxSuccess();
    }

    /**
     * 入库资产列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function InboundAssetList()
    {
        // 获取我的用户单位级别
        $my_dwjb = Auth::guard('admin')->user()->dwjb;
        $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
        // 单位
        if($my_dwjb==2){
            $companies = Company::where('dwdm','!=','100000')->get();
            $danwei = select_company('sjdm', 'dwdm', $companies, '100000', '=', '1');
        }elseif($my_dwjb==3){
            $companies = Company::where('dwdm',$my_dwdm)->orwhere('sjdm',$my_dwdm)->get();
            $danwei = select_company('sjdm', 'dwdm', $companies, '520000', '=', '1');
        }else{
            $danwei = Company::where('dwdm',$my_dwdm)->get();
        }  
        return view('admin.assetclaims.InboundAssetList',compact('my_dwdm','danwei','my_dwjb'));
    }
    /**
     * 获取入库资产的分页数据
     * @param Request $request
     * @param Inventory $inventory
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInboundAsset(Request $request, Inventory $inventory)
    {
        $data = $request->all();
        $data['my_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $data['my_bmdm'] = Auth::guard('admin')->user()->mechanism_code;
        $res = $inventory->getInboundAsset($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 编辑入库资产
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editInboundAsset(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
             // 
            ]);
            $data = $request->all();
            $inventoryService = new InventoryService();
            $re = $inventoryService->editInboundAsset($data);
            if (!$re) return ajaxError($inventoryService->getError(), $inventoryService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            // 获取仓库的信息
            $ck = Warehouse::all()->toArray();
            $inventories = Inventory::find($request->id)->toArray();
            // 取入依据
            $qryj_arr =  qryj();
             // 资产状况
            $zczk_arr =  zczk();
            return view('admin.assetclaims.editInboundAsset',compact('inventories','qryj_arr','ck','zczk_arr'));
        }
    }
    /**
     * 删除入库资产
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delInboundAsset(Request $request)
    {
        $inventoryService = new InventoryService();
        $re = $inventoryService->delInboundAsset($request->id);
        if (!$re) return ajaxError($inventoryService->getError(), $inventoryService->getHttpCode());
        return ajaxSuccess();
    }
    /**
     * 审核入库的资产
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkInboundAsset(Request $request)
    {      
        $data = $request->all();
        $inventoryService = new InventoryService();
        $re = $inventoryService->checkInboundAsset($data);
        if (!$re) return ajaxError($inventoryService->getError(), $inventoryService->getHttpCode());
        return ajaxSuccess();
    }

    /**
     * 我申领的资产的设备列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myAssetDeviceList($id)
    {
        return view('admin.assetclaims.myAssetDeviceList',compact('id'));
    }
    /**
     * 获取我申领的资产的设备的分页数据
     * @param Request $request
     * @param UserReceive $userreceive
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyAssetDevice(Request $request, UserReceive $userreceive)
    {
        $data = $request->all();
        $data['my_id'] = Auth::guard('admin')->user()->id;
        $res = $userreceive->getMyAssetDevice($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 归还我申领的设备反馈信息给本单位管理员
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function backMyAssetDevice(Request $request)
    {      
        $data = $request->all();
        $assetClaimService = new AssetClaimService();
        $re = $assetClaimService->backMyAssetDevice($data);
        if (!$re) return ajaxError($assetClaimService->getError(), $assetClaimService->getHttpCode());
        return ajaxSuccess();
    }
    /**
     * 下载资产归还表
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadMyAssetDevice(Request $request, AssetClaim $assetclaim)
    {      
        $data = $request->all();
        $data['my_name'] = Auth::guard('admin')->user()->real_name;
        $res = $assetclaim->downloadMyAssetDevice($data);
        return  [
            'code' => 0,
            'msg' => $res['all_path'],
        ];
    }
    /**
     * 管理员获取我申领的资产的设备列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allAssetDeviceList($id)
    {
        return view('admin.assetclaims.allAssetDeviceList',compact('id'));
    }
    /**
     * 管理员获取我申领的资产的设备的分页数据
     * @param Request $request
     * @param UserReceive $userreceive
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllAssetDevice(Request $request, UserReceive $userreceive)
    {
        $data = $request->all();
        $res = $userreceive->getAllAssetDevice($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 确认该设备归还入库
     */
    public function backInbound(Request $request)
    {
        // 更改该设备的出库状态为入库
        $re = DeviceIdentity::where('sbsf_xh', $request->id)->update(['if_ck' => $request->if_ck]);
        $re1 = DB::table('user_receive')->where('sbsf_id', $request->id)->update(['if_back_inbound' => 1]);
        // 获取该资产的总库存量
        $kc_nums = Inventory::where('kc_zcid', $request->zc_id)->value('kc_nums');
        ++$kc_nums;
        // 库存量+1
        $re = Inventory::where('kc_zcid', $request->zc_id)->update(['kc_nums' => $kc_nums]);
        if (!$re || !$re1) return ajaxError('修改失败', HttpCode::BAD_REQUEST);
        return ajaxSuccess();
    }
    /**
     * 下载资产归还表
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downDeviceIdentity(Request $request, AssetClaim $assetclaim)
    {      
        $data = $request->all();
        // 申请报废日期
        $data['sqbfrq'] = date('Y-m-d H:i:s',time());
        // 申请报废人
        $data['sqbfr'] = Auth::guard('admin')->user()->real_name;
        $res = $assetclaim->downDeviceIdentity($data);
        return  [
            'code' => 0,
            'msg' => $res['all_path'],
        ];
    }
    
    // 上传扫描件
    public function uploadDeviceIdentity(Request $request,ImageUploadHandler $uploader)
    {
        $uid = $user = Auth::guard('admin')->user()->id;
        if ($request->file) {
            $result = $uploader->save($request->file, 'DeviceIdentity', $uid, 362);
            if ($result) {
                return [
                    'status' => 1,
                    'sbsf_pic' => $result['path'],
                ];
            }
        }
    }
}
