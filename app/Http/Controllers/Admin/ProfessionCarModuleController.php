<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ProfessionCarModule;
use App\Model\ProfessionCarCode;
use App\Model\Business;
use Auth;
use App\Service\ProfessionCarModuleService;

class ProfessionCarModuleController extends Controller
{
    /**
     * 职业资格证书模板列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function carModuleList()
    {
        return view('admin.professioncarmodules.carModuleList');
    }
    /**
     * 获取职业资格证书模板分页数据
     * @param Request $request
     * @param ProfessionCarModule $professioncarmodule
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCarModule(Request $request, ProfessionCarModule $professioncarmodule)
    {
        $data = $request->all();
        $data['dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $res = $professioncarmodule->getCarModule($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加职业资格证书模板
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addCarModule(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
            	// 
            ]);
            $data = $request->all();
            $data['my_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
            $professionCarModuleService = new ProfessionCarModuleService();
            $re = $professionCarModuleService->addCarModule($data);
            if (!$re) return ajaxError($professionCarModuleService->getError(), $professionCarModuleService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	// 获取职业资格证书代码表中证书名称
        	$professioncarcodes = ProfessionCarCode::all();
            // 获取鉴定业务范围
            $businesses = Business::all();
            return view('admin.professioncarmodules.addCarModule',compact('businesses','professioncarcodes'));
        }
    }
    /**
     * 编辑职业资格证书模板
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editCarModule(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
             // 
            ]);
            $professionCarModuleService = new ProfessionCarModuleService();
            $re = $professionCarModuleService->editCarModule($data);
            if (!$re) return ajaxError($professionCarModuleService->getError(), $professionCarModuleService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            $professioncarmodule = ProfessionCarModule::find($request->id)->toArray();
            // 获取鉴定业务范围
            $businesses = Business::all()->toArray();
            $hasbusinesses = explode(',', $professioncarmodule['ywlb']);
            foreach ($businesses as &$business) {
                if (in_array($business['ywlb'], $hasbusinesses)) {
                    $business['checked'] = 1;
                } else {
                    $business['checked'] = 0;
                }
            }
            return view('admin.professioncarmodules.editCarModule',compact('professioncarmodule','businesses'));
        }
    }
    /**
     * 删除职业资格证书模板
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delCarModule(Request $request)
    {
    	$professionCarModuleService = new ProfessionCarModuleService();
        $re = $professionCarModuleService->delCarModule($request->id);
        if (!$re) return ajaxError($professionCarModuleService->getError(), $professionCarModuleService->getHttpCode());
        return ajaxSuccess();
    }
}
