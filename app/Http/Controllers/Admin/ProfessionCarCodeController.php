<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ProfessionCarCode;
use App\Service\ProfessionCarCodeService;

class ProfessionCarCodeController extends Controller
{
    /**
     * 职业资格证书代码列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function carCodeList()
    {
        return view('admin.professioncarcodes.carCodeList');
    }
    /**
     * 获取职业资格证书代码分页数据
     * @param Request $request
     * @param ProfessionCarCode $professioncarcode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCarCode(Request $request, ProfessionCarCode $professioncarcode)
    {
        $data = $request->all();
        $res = $professioncarcode->getCarCode($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加职业资格证书代码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addCarCode(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                // 'jdjg_code' => 'required',
            ]);
            $professionCarCodeService = new ProfessionCarCodeService();
            $re = $professionCarCodeService->addCarCode($request->all());
            if (!$re) return ajaxError($professionCarCodeService->getError(), $professionCarCodeService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            return view('admin.professioncarcodes.addCarCode');
        }
    }
    /**
     * 编辑职业资格证书代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editCarCode(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $professionCarCodeService = new ProfessionCarCodeService();
            $re = $professionCarCodeService->editCarCode($request->all());
            if (!$re) return ajaxError($professionCarCodeService->getError(), $professionCarCodeService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$professioncarcode = ProfessionCarCode::find($request->id)->toArray();
            return view('admin.professioncarcodes.editCarCode',compact('professioncarcode'));
        }
    }
    /**
     * 删除职业资格证书代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delCarCode(Request $request)
    {
        $professionCarCodeService = new ProfessionCarCodeService();
        $re = $professionCarCodeService->delCarCode($request->id);
        if (!$re) return ajaxError($professionCarCodeService->getError(), $professionCarCodeService->getHttpCode());
        return ajaxSuccess();
    }
}
