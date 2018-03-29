<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\EquipmentAsset;
use App\Model\AssetUnit;
use App\Model\Warehouse;
use Auth;
use App\Service\EquipmentAssetService;

class EquipmentAssetController extends Controller
{
    /**
     * 装备资产列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function equipmentAssetList()
    {
        return view('admin.equipmentassets.equipmentAssetList');
    }
    /**
     * 获取装备资产分页数据
     * @param Request $request
     * @param EquipmentAsset $equipmentasset
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEquipmentAsset(Request $request, EquipmentAsset $equipmentasset)
    {
        $data = $request->all();
        $data['zc_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $data['zc_bmdm'] = Auth::guard('admin')->user()->mechanism_code;
        $res = $equipmentasset->getEquipmentAsset($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加装备资产
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addEquipmentAsset(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
            	// 
            ]);
            $data = $request->all();
            $data['zc_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        	$data['zc_bmdm'] = Auth::guard('admin')->user()->mechanism_code;
            $equipmentAssetService = new EquipmentAssetService();
            $re = $equipmentAssetService->addEquipmentAsset($data);
            if (!$re) return ajaxError($equipmentAssetService->getError(), $equipmentAssetService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	// 获取资产单位
        	$assetunits = AssetUnit::all()->toArray();
        	// 资产性质
        	$zcxz_arr =  zcxz();
        	// 判断装备资产表里是否有添加记录
            $has = EquipmentAsset::count();
            if ($has > 0) {
	            $zcbh = EquipmentAsset::orderBy('id','desc')->first();
	            $zcbh = $zcbh->zcbh;
	        }else{
	        	$zcbh = intval("100000");
	        }
	        // 该资产编号自增
	        $zcbh = ++$zcbh;
        	$zcbh_all = "GZJCYJSC+".$zcbh;
            return view('admin.equipmentassets.addEquipmentAsset', compact('assetunits','zcxz_arr','zcbh','zcbh_all'));
        }
    }
    /**
     * 编辑装备资产
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editEquipmentAsset(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
             // 
            ]);
            $data = $request->all();
            $equipmentAssetService = new EquipmentAssetService();
            $re = $equipmentAssetService->editEquipmentAsset($data);
            if (!$re) return ajaxError($equipmentAssetService->getError(), $equipmentAssetService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            $equipmentassets = EquipmentAsset::find($request->id)->toArray();
            // 资产单位
            $assetunits = AssetUnit::all()->toArray();
            // 资产性质
            $zcxz_arr =  zcxz();
            $zcbh_all = "GZJCYJSC+".$equipmentassets['zcbh'];
            return view('admin.equipmentassets.editEquipmentAsset',compact('equipmentassets','assetunits','zcxz_arr','zcbh_all'));
        }
    }
    /**
     * 删除装备资产
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delEquipmentAsset(Request $request)
    {
    	$equipmentAssetService = new EquipmentAssetService();
        $re = $equipmentAssetService->delEquipmentAsset($request->id);
        if (!$re) return ajaxError($equipmentAssetService->getError(), $equipmentAssetService->getHttpCode());
        return ajaxSuccess();
    }
    /**
     * 装备资产入库操作
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addInbound(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                // 
            ]);
            $data = $request->all();
            $data['kc_uid'] = Auth::guard('admin')->user()->id;
            $data['kc_username'] = Auth::guard('admin')->user()->real_name;
            $data['kc_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
            $data['kc_bmdm'] = Auth::guard('admin')->user()->mechanism_code;
            $equipmentAssetService = new EquipmentAssetService();
            $re = $equipmentAssetService->addInbound($data);
            if (!$re) return ajaxError($equipmentAssetService->getError(), $equipmentAssetService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            // 资产序号 $id
            $id = $request->route( 'id' );
            // 资产状况
            $zczk_arr =  zczk();
            // 取入依据
            $qryj_arr =  qryj();
            // 获取仓库的信息
            $ck = Warehouse::all()->toArray();
            return view('admin.equipmentassets.addInbound', compact('zczk_arr','qryj_arr','ck','id'));
        }
    }
    /**
     * 下载资产入库表
     * @param Request $request
     * @param EquipmentAsset $equipmentasset
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function downloadInbound(Request $request, EquipmentAsset $equipmentasset)
    {      
        $data = $request->all();
        $res = $equipmentasset->downloadInbound($data);
        return  [
            'code' => 0,
            'msg' => $res['all_path'],
        ];
    }
}
