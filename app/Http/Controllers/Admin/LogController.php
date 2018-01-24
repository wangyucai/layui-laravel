<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Log;
use App\Service\LogService;

class LogController extends Controller
{
    /**
     * 日志列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logList()
    {
        return view('admin.logs.logList');
    }
    /**
     * 获取日志分页数据
     * @param Request $request
     * @param Log $log
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogs(Request $request, Log $log)
    {
        $data = $request->all();
        $res = $log->getLogs($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
}
