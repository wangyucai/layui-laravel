<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\InstitutionCode;
use App\Service\InstitutionCodeService;

class InstitutionCodeController extends Controller
{
    /**
     * 司法鉴定机构代码列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function institutionCodesList()
    {
        return view('admin.institutioncodes.institutionCodesList');
    }
    /**
     * 获取司法鉴定机构代码分页数据
     * @param Request $request
     * @param InstitutionCode $institutioncode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInstitutionCodes(Request $request, InstitutionCode $institutioncode)
    {
        $data = $request->all();
        $res = $institutioncode->getInstitutionCodes($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加司法鉴定机构代码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addInstitutionCode(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'jdjg_code' => 'required',
                'jdjg_dwdm' => 'required',
                'jdjg_name' => 'required',
                'fj_jdjg_code' => 'required',
                'jdjg_level' => 'required',
            ]);
            $institutionCodeService = new InstitutionCodeService();
            $re = $institutionCodeService->addInstitutionCode($request->all());
            if (!$re) return ajaxError($institutionCodeService->getError(), $institutionCodeService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$institutioncodes = InstitutionCode::where('jdjg_level','<=',2)->get();
        	$sjdwdm = select_company('fj_jdjg_code', 'jdjg_code', $institutioncodes, '520000', '=', '1');
            return view('admin.institutioncodes.addInstitutionCode',compact('sjdwdm'));
        }
    }
    /**
     * 编辑司法鉴定机构代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editInstitutionCodes(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
                'jdjg_code' => 'required',
                'jdjg_dwdm' => 'required',
                'jdjg_name' => 'required',
                'fj_jdjg_code' => 'required',
                'jdjg_level' => 'required',
            ]);
            $institutionCodeService = new InstitutionCodeService();
            $re = $institutionCodeService->editInstitutionCode($request->all());
            if (!$re) return ajaxError($institutionCodeService->getError(), $institutionCodeService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$institutioncodes = InstitutionCode::where('jdjg_level','<=',2)->get();
        	$sjdwdm = select_company('fj_jdjg_code', 'jdjg_code', $institutioncodes, '520000', '=', '1');
        	$institutioncode = InstitutionCode::find($request->id)->toArray();
            return view('admin.institutioncodes.editInstitutionCode',compact('institutioncode','sjdwdm'));
        }
    }
    /**
     * 删除司法鉴定机构代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delInstitutionCodes(Request $request)
    {
        $institutionCodeService = new InstitutionCodeService();
        $re = $institutionCodeService->delInstitutionCode($request->id);
        if (!$re) return ajaxError($institutionCodeService->getError(), $institutionCodeService->getHttpCode());
        return ajaxSuccess();
    }
}
