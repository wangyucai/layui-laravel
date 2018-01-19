<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\MechanismCode;
use App\Model\Mechanism;
use App\Service\MechanismCodeService;
use App\Service\MechanismService;

class MechanismCodeController extends Controller
{
    /**
     * 内设机构代码(部门代码)列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mechanismCodesList()
    {
        return view('admin.mechanismcodes.mechanismCodesList');
    }
    /**
     * 获取内设机构代码(部门代码)分页数据
     * @param Request $request
     * @param MechanismCode $mechanismcode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMechanismCodes(Request $request, MechanismCode $mechanismcode)
    {
        $data = $request->all();
        $res = $mechanismcode->getMechanismCodes($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加内设机构代码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addMechanismCode(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                // 'jdjg_code' => 'required',
            ]);
            $mechanismCodeService = new MechanismCodeService();
            $re = $mechanismCodeService->addMechanismCode($request->all());
            if (!$re) return ajaxError($mechanismCodeService->getError(), $mechanismCodeService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            return view('admin.mechanismcodes.addMechanismCode');
        }
    }
    /**
     * 编辑内设机构代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editMechanismCode(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $mechanismCodeService = new MechanismCodeService();
            $re = $mechanismCodeService->editMechanismCode($request->all());
            if (!$re) return ajaxError($mechanismCodeService->getError(), $mechanismCodeService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$mechanismcode = MechanismCode::find($request->id)->toArray();
            return view('admin.mechanismcodes.editMechanismCode',compact('mechanismcode'));
        }
    }
    /**
     * 删除内设机构代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delMechanismCode(Request $request)
    {
        $mechanismCodeService = new MechanismCodeService();
        $re = $mechanismCodeService->delMechanismCode($request->id);
        if (!$re) return ajaxError($mechanismCodeService->getError(), $mechanismCodeService->getHttpCode());
        return ajaxSuccess();
    }

    /**
     * 本单位内设机构代码(部门代码)列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myMechanismCodesList()
    {
        return view('admin.mechanismcodes.myMechanismCodesList');
    }
    /**
     * 获取本单位内设机构代码(部门代码)分页数据
     * @param Request $request
     * @param Mechanism $mechanism
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyMechanismCodes(Request $request, Mechanism $mechanism)
    {
        $data = $request->all();
        $res = $mechanism->getMyMechanismCodes($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加本单位内设机构代码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addMyMechanismCode(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                // 'jdjg_code' => 'required',
            ]);
            $mechanismService = new MechanismService();
            $re = $mechanismService->addMyMechanismCode($request->all());
            if (!$re) return ajaxError($mechanismService->getError(), $mechanismService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            $mechanismcodes = MechanismCode::all()->toArray();
            return view('admin.mechanismcodes.addMyMechanismCode',compact('mechanismcodes'));
        }
    }
    /**
     * 编辑本单位内设机构代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editMyMechanismCode(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $mechanismService = new MechanismService();
            $re = $mechanismService->editMyMechanismCode($request->all());
            if (!$re) return ajaxError($mechanismService->getError(), $mechanismService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            $mechanism = Mechanism::find($request->id)->toArray();
            return view('admin.mechanismcodes.editMyMechanismCode',compact('mechanism'));
        }
    }
    /**
     * 删除本单位内设机构代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delMyMechanismCode(Request $request)
    {
        $mechanismService = new MechanismService();
        $re = $mechanismService->delMyMechanismCode($request->id);
        if (!$re) return ajaxError($mechanismService->getError(), $mechanismService->getHttpCode());
        return ajaxSuccess();
    }
}
