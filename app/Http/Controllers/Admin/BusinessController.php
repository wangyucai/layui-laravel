<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Business;
use App\Service\BusinessService;

class BusinessController extends Controller
{
    /**
     * 司法鉴定业务范围列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function businessesList()
    {
        return view('admin.businesses.businessesList');
    }
    /**
     * 获取司法鉴定业务范围分页数据
     * @param Request $request
     * @param InstitutionCode $institutioncode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBusinesses(Request $request, Business $business)
    {
        $data = $request->all();
        $res = $business->getBusinesses($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加司法鉴定业务范围
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addBusiness(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'jdywfw_code' => 'required',
                'jdywfw_name' => 'required',
            ]);
            $businessService = new BusinessService();
            $re = $businessService->addBusiness($request->all());
            if (!$re) return ajaxError($businessService->getError(), $businessService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            return view('admin.businesses.addBusiness');
        }
    }
    /**
     * 编辑司法鉴定业务范围
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editBusiness(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
                'jdywfw_name' => 'required',
            ]);
            $businessService = new BusinessService();
            $re = $businessService->editBusiness($request->all());
            if (!$re) return ajaxError($businessService->getError(), $businessService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$business = Business::find($request->id)->toArray();
            return view('admin.businesses.editBusiness',compact('business'));
        }
    }
    /**
     * 删除司法鉴定业务范围
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delBusiness(Request $request)
    {
        $businessService = new BusinessService();
        $re = $businessService->delBusiness($request->id);
        if (!$re) return ajaxError($businessService->getError(), $businessService->getHttpCode());
        return ajaxSuccess();
    }
}
