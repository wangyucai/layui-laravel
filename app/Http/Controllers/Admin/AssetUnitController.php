<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\AssetUnit;
use App\Service\AssetUnitService;

class AssetUnitController extends Controller
{
    /**
     * 资产单位代码列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assetUnitList()
    {
        return view('admin.assetunits.assetUnitList');
    }
    /**
     * 获取资产单位代码分页数据
     * @param Request $request
     * @param MechanismCode $mechanismcode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssetUnit(Request $request, AssetUnit $assetunit)
    {
        $data = $request->all();
        $res = $assetunit->getAssetUnit($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加资产单位代码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addAssetUnit(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                // 'jdjg_code' => 'required',
            ]);
            $assetUnitService = new AssetUnitService();
            $re = $assetUnitService->addAssetUnit($request->all());
            if (!$re) return ajaxError($assetUnitService->getError(), $assetUnitService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            return view('admin.assetunits.addAssetUnit');
        }
    }
    /**
     * 编辑资产单位代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editAssetUnit(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $assetUnitService = new AssetUnitService();
            $re = $assetUnitService->editAssetUnit($request->all());
            if (!$re) return ajaxError($assetUnitService->getError(), $assetUnitService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$assetunit = AssetUnit::find($request->id)->toArray();
            return view('admin.assetunits.editAssetUnit',compact('assetunit'));
        }
    }
    /**
     * 删除资产单位代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delAssetUnit(Request $request)
    {
        $assetUnitService = new AssetUnitService();
        $re = $assetUnitService->delAssetUnit($request->id);
        if (!$re) return ajaxError($assetUnitService->getError(), $assetUnitService->getHttpCode());
        return ajaxSuccess();
    }
}

