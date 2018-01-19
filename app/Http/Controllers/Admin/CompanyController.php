<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Company;
use App\Service\CompanyService;
use Auth;

class CompanyController extends Controller
{
    /**
     * 单位列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyList()
    {
        return view('admin.companies.companyList');
    }
    /**
     * 获取单位分页数据
     * @param Request $request
     * @param Company $company
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanies(Request $request, Company $company)
    {
        $data = $request->all();
        // 获取本单位代码
        $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
        $data['my_dwdm'] = $my_dwdm;
        $res = $company->getCompanies($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加单位
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addCompany(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                // 'jdjg_code' => 'required',
            ]);
            $companyService = new CompanyService();
            $re = $companyService->addCompany($request->all());
            if (!$re) return ajaxError($companyService->getError(), $companyService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            return view('admin.companies.addCompany');
        }
    }
    /**
     * 编辑司单位
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editCompany(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $companyService = new CompanyService();
            $re = $companyService->editCompany($request->all());
            if (!$re) return ajaxError($companyService->getError(), $companyService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$company = Company::find($request->id)->toArray();
            return view('admin.companies.editCompany',compact('company'));
        }
    }
    /**
     * 删除单位
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delCompany(Request $request)
    {
        $companyService = new CompanyService();
        $re = $companyService->delCompany($request->id);
        if (!$re) return ajaxError($companyService->getError(), $companyService->getHttpCode());
        return ajaxSuccess();
    }
}
