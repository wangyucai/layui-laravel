<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Warehouse;
use App\Service\WarehouseService;
use Auth;

class WarehouseController extends Controller
{
    /**
     * 仓库列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function warehouseList()
    {
        return view('admin.warehouses.warehouseList');
    }
    /**
     * 获取仓库分页数据
     * @param Request $request
     * @param Warehouse $warehouse
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWarehouse(Request $request, Warehouse $warehouse)
    {
        $data = $request->all();
        $res = $warehouse->getWarehouse($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加仓库
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addWarehouse(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
            ]);
            $data = $request->all();
            $data['ck_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
            $warehouseService = new WarehouseService();
            $re = $warehouseService->addWarehouse($data);
            if (!$re) return ajaxError($warehouseService->getError(), $warehouseService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	// 判断仓库表里是否有添加记录
            $has = Warehouse::count();
            if ($has > 0) {
	            $ckbh = Warehouse::orderBy('id','desc')->first();
	            $ckbh = $ckbh->ckbh;
	        }else{
	        	$ckbh = intval("100");
	        }
	        // 该仓库编号自增
	        $ckbh = ++$ckbh;
        	$ckbh_all = "JSCCK+".$ckbh;
            return view('admin.warehouses.addWarehouse',compact('ckbh','ckbh_all'));
        }
    }
    /**
     * 编辑仓库
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editWarehouse(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $warehouseService = new WarehouseService();
            $re = $warehouseService->editWarehouse($request->all());
            if (!$re) return ajaxError($warehouseService->getError(), $warehouseService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$warehouse = Warehouse::find($request->id)->toArray();
        	$ckbh_all = "JSCCK+".$warehouse['ckbh'];
            return view('admin.warehouses.editWarehouse',compact('warehouse','ckbh_all'));
        }
    }
    /**
     * 删除仓库
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delWarehouse(Request $request)
    {
        $warehouseService = new WarehouseService();
        $re = $warehouseService->delWarehouse($request->id);
        if (!$re) return ajaxError($warehouseService->getError(), $warehouseService->getHttpCode());
        return ajaxSuccess();
    }
}
