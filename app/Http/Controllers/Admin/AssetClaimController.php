<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AssetClaim;
use App\Model\EquipmentAsset;
use App\Model\Inventory;
use App\Model\DeviceIdentity;
use App\Model\Warehouse;
use App\Model\UserReceive;
use Auth;
use App\Service\AssetClaimService;
use App\Service\InventoryService;

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
        $data = $request->all();
        $data['my_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
       	$data['my_bmdm'] = Auth::guard('admin')->user()->mechanism_code;
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
        return view('admin.assetclaims.InboundAssetList');
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
}
