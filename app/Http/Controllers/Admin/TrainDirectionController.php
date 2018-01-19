<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\TrainDirection;
use App\Service\TrainDirectionService;

class TrainDirectionController extends Controller
{
    /**
     * 培训方向代码列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trainDirectionList()
    {
        return view('admin.traindirections.trainDirectionList');
    }
    /**
     * 获取培训方向代码分页数据
     * @param Request $request
     * @param TrainDirection $traindirection
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrainDirection(Request $request, TrainDirection $traindirection)
    {
        $data = $request->all();
        $res = $traindirection->getTrainDirection($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加培训方向代码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addTrainDirection(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
            ]);
            $trainDirectionService = new TrainDirectionService();
            $re = $trainDirectionService->addTrainDirection($request->all());
            if (!$re) return ajaxError($trainDirectionService->getError(), $trainDirectionService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            return view('admin.traindirections.addTrainDirection');
        }
    }
    /**
     * 编辑培训方向代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editTrainDirection(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $trainDirectionService = new TrainDirectionService();
            $re = $trainDirectionService->editTrainDirection($request->all());
            if (!$re) return ajaxError($trainDirectionService->getError(), $trainDirectionService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$traindirection = TrainDirection::find($request->id)->toArray();
            return view('admin.traindirections.editTrainDirection',compact('traindirection'));
        }
    }
    /**
     * 删除培训方向代码
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delTrainDirection(Request $request)
    {
        $trainDirectionService = new TrainDirectionService();
        $re = $trainDirectionService->delTrainDirection($request->id);
        if (!$re) return ajaxError($trainDirectionService->getError(), $trainDirectionService->getHttpCode());
        return ajaxSuccess();
    }
}
