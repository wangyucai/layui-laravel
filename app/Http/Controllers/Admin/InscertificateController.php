<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Inscertificate;
use App\Model\InstitutionCode;
use App\Model\Company;
use App\Model\Business;
use Auth;
use App\Service\InscertificateService;

class InscertificateController extends Controller
{
    /**
     * 司法鉴定机构证书列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function inscertificatesList()
    {
        return view('admin.inscertificates.inscertificatesList');
    }
    /**
     * 获取司法鉴定机构证书分页数据
     * @param Request $request
     * @param Inscertificate $inscertificate
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInscertificates(Request $request, Inscertificate $inscertificate)
    {
        $data = $request->all();
        $data['dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $res = $inscertificate->getInscertificates($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加司法鉴定机构证书
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addInscertificate(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'jdjg_dm' => 'required',
                'zsbh' => 'required',
                'ssdwqc' => 'required',
                'jdjg_fzr' => 'required',
                'jdjg_ywfw' => 'required',
                'fzdw' => 'required',
                'fzrq' => 'required',
                'zgsh_yxqz' => 'required',
            ]);
            $data = $request->all();
            $inscertificateService = new InscertificateService();
            $re = $inscertificateService->addInscertificate($data);
            if (!$re) return ajaxError($inscertificateService->getError(), $inscertificateService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	// 获取所有鉴定机构
        	$institutioncodes = InstitutionCode::all();
        	$institutioncodes = select_company('fj_jdjg_code', 'jdjg_code', $institutioncodes, '520000', '=', '1');
        	// 获取登录管理员的所属单位全称
        	$my_company_id = Auth::guard('admin')->user()->company_id;
        	$ssdwqc = Company::where('id',$my_company_id)->value('dwqc');
            // 获取鉴定业务范围
            $businesses = Business::all();
            return view('admin.inscertificates.addInscertificate',compact('institutioncodes','ssdwqc','businesses'));
        }
    }
    /**
     * 编辑司法鉴定机构证书
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editInscertificate(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
                'jdjg_dm' => 'required',
                'zsbh' => 'required',
                'ssdwqc' => 'required',
                'jdjg_fzr' => 'required',
                'jdjg_ywfw' => 'required',
                'fzdw' => 'required',
                'fzrq' => 'required',
                'zgsh_yxqz' => 'required',
            ]);
            $inscertificateService = new InscertificateService();
            $re = $inscertificateService->editInscertificate($request->all());
            if (!$re) return ajaxError($inscertificateService->getError(), $inscertificateService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            $inscertificate = Inscertificate::find($request->id)->toArray();
            // 获取所有鉴定机构
            $institutioncodes = InstitutionCode::all();
            $institutioncodes = select_company('fj_jdjg_code', 'jdjg_code', $institutioncodes, '520000', '=', '1');
            // 获取鉴定业务范围
            $businesses = Business::all()->toArray();
            $hasbusinesses = explode(',', $inscertificate['jdjg_ywfw']);
            foreach ($businesses as &$business) {
                if (in_array($business['jdywfw_code'], $hasbusinesses)) {
                    $business['checked'] = 1;
                } else {
                    $business['checked'] = 0;
                }
            }
            return view('admin.inscertificates.editInscertificate',compact('inscertificate','institutioncodes','businesses'));
        }
    }
    /**
     * 删除司法鉴定机构证书
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delInscertificate(Request $request)
    {
    	$inscertificateService = new InscertificateService();
        $re = $inscertificateService->delInscertificate($request->id);
        if (!$re) return ajaxError($inscertificateService->getError(), $inscertificateService->getHttpCode());
        return ajaxSuccess();
    }
}
