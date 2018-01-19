<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\NoticeType;
use App\Service\NoticeTypeService;

class NoticeTypeController extends Controller
{
    /**
     * 通知类型列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function noticeTypeList()
    {
        return view('admin.noticetypes.noticeTypeList');
    }
    /**
     * 获取通知类型分页数据
     * @param Request $request
     * @param NoticeType $noticetype
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNoticeType(Request $request, NoticeType $noticetype)
    {
        $data = $request->all();
        $res = $noticetype->getNoticeType($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加通知类型
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addNoticeType(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
            ]);
            $noticeTypeService = new NoticeTypeService();
            $re = $noticeTypeService->addNoticeType($request->all());
            if (!$re) return ajaxError($noticeTypeService->getError(), $noticeTypeService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            return view('admin.noticetypes.addNoticeType');
        }
    }
    /**
     * 编辑通知类型
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editNoticeType(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $noticeTypeService = new NoticeTypeService();
            $re = $noticeTypeService->editNoticeType($request->all());
            if (!$re) return ajaxError($noticeTypeService->getError(), $noticeTypeService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$noticetype = NoticeType::find($request->id)->toArray();
            return view('admin.noticetypes.editNoticeType',compact('noticetype'));
        }
    }
    /**
     * 删除通知类型
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delNoticeType(Request $request)
    {
        $noticeTypeService = new NoticeTypeService();
        $re = $noticeTypeService->delNoticeType($request->id);
        if (!$re) return ajaxError($noticeTypeService->getError(), $noticeTypeService->getHttpCode());
        return ajaxSuccess();
    }
}
