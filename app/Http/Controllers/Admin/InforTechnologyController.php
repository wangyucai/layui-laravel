<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\InforTechnology;
use App\Service\InforTechnologyService;

class InforTechnologyController extends Controller
{
    /**
     * 信息化技术代码表列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function infortechList()
    {
        return view('admin.infortechnologies.infortechList');
    }
    /**
     * 获取信息化技术代码表分页数据
     * @param Request $request
     * @param InforTechnology $infortechnology
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfortech(Request $request, InforTechnology $infortechnology)
    {
        $data = $request->all();
        $res = $infortechnology->getInfortech($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加信息化技术代码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addInfortech(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
              
            ]);
            $inforTechnologyService = new InforTechnologyService();
            $re = $inforTechnologyService->addInfortech($request->all());
            if (!$re) return ajaxError($inforTechnologyService->getError(), $inforTechnologyService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            return view('admin.infortechnologies.addInfortech');
        }
    }
    /**
     * 编辑信息化技术代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editInfortech(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            
            ]);
            $inforTechnologyService = new InforTechnologyService();
            $re = $inforTechnologyService->editInfortech($request->all());
            if (!$re) return ajaxError($inforTechnologyService->getError(), $inforTechnologyService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$infortechnology = InforTechnology::find($request->id)->toArray();
            return view('admin.infortechnologies.editInfortech',compact('infortechnology'));
        }
    }
    /**
     * 删除信息化技术代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delInfortech(Request $request)
    {
        $inforTechnologyService = new InforTechnologyService();
        $re = $inforTechnologyService->delInfortech($request->id);
        if (!$re) return ajaxError($inforTechnologyService->getError(), $inforTechnologyService->getHttpCode());
        return ajaxSuccess();
    }
}
