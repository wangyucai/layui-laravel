<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\MoreFilesUploadHandler;
use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Informatization;
use App\Model\Company;
use App\Service\InformatizationService;
use Auth;

class InformatizationController extends Controller
{
    /**
     * 我的信息化资格证书列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function informatizationList()
    {
        return view('admin.informatizations.informatizationList');
    }
    /**
     * 获取我的信息化资格证书分页数据
     * @param Request $request
     * @param Informatization $informatization
     * @return \Illuminate\Http\JsonResponse
     */
    public function getinformatization(Request $request, Informatization $informatization)
    {
        $data = $request->all();
        $res = $informatization->getinformatization($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加我的信息化资格证书是否超过五张
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myInforCarNum(Request $request)
    {
    	// 获取登录人员的id
        $my_id = Auth::guard('admin')->user()->id;
        // 获取我的证书的数量
        $myinforcarnum = Informatization::where('user_myid',$my_id)->count(); 
        if($myinforcarnum>=5){
            return ['msg' => "每人最多只能添加五张证书"];
        }else{
            return ['msg' => 1];
        }
    }
	/**
     * 添加我的信息化资格证书
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addInformatization(Request $request)
    {
    	if($request->isMethod('post')){
    		$this->validate($request, [
                // 'resume' => 'required',        
            ]);
            $data = $request->all();
            $data['user_myid'] = Auth::guard('admin')->user()->id;
            $data['info_mydwdm'] = Auth::guard('admin')->user()->company_dwdm;
            $informatizationService = new InformatizationService();
            $re = $informatizationService->addInformatization($data);
            if (!$re) return ajaxError($informatizationService->getError(), $informatizationService->getHttpCode());
            return ajaxSuccess();
    	}else{
        	// 获取登录人员的姓名
        	$my_name = Auth::guard('admin')->user()->real_name;
        	// 获取登录人员的id
        	$my_id = Auth::guard('admin')->user()->id;
        	// 获取鉴定人员单位代码
        	$my_dwdm = Auth::guard('admin')->user()->company_dwdm;
            return view('admin.informatizations.addInformatization', compact('my_name', 'my_dwdm', 'my_id'));
    	}
    }
    /**
     * 上传我的证书
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadMyInforCar(Request $request, MoreFilesUploadHandler $uploader)
    {
    	$files = $_FILES['file'];  //上传文件file
        $file = $request->file;  //上传文件file
        $totalPieces = $request->totalPieces;  //上传文件切片总数
        $index = $request->index;  //上传文件当前切片
    	$folder = 'informatizationCar';
    	if ($request->file) {
            $result = $uploader->save($files, $file, $totalPieces, $index, $folder);
        }
        return ajaxSuccess($result);
    }
    /**
     * 查看此鉴定证书详情
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lookMyInformatization(Request $request)
    {
        // 获取我的证书信息
        $myinformatization = Informatization::find($request->id)->toArray();
        return view('admin.informatizations.lookMyInformatization',compact('myinformatization'));
    }
    
    /**
     * 编辑我的信息化资格证书
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editInformatization(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $informatizationService = new InformatizationService();
            $re = $informatizationService->editInformatization($request->all());
            if (!$re) return ajaxError($informatizationService->getError(), $informatizationService->getHttpCode());
            return ajaxSuccess();
        } else {
            // 获取我的证书信息
            $myinformatization = Informatization::find($request->id)->toArray();
            return view('admin.informatizations.editInformatization',compact('myinformatization'));
        }
    }
    /**
     * 删除我的信息化资格证书
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delInformatization(Request $request)
    {
        $informatizationService = new InformatizationService();
        $re = $informatizationService->delInformatization($request->id);
        if (!$re) return ajaxError($informatizationService->getError(), $informatizationService->getHttpCode());
        return ajaxSuccess();
    }
    
    /**
     * 全部信息化资格证书列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function allInformatizationList()
    {
        // 获取我的用户单位级别
        $my_dwjb = Auth::guard('admin')->user()->dwjb;
        $my_dwdm = Auth::guard('admin')->user()->company_dwdm;
        // 单位
        if($my_dwjb==2){
            $companies = Company::where('dwdm','!=','100000')->get();
            $danwei = select_company('sjdm', 'dwdm', $companies, '100000', '=', '1');
        }elseif($my_dwjb==3){
            $companies = Company::where('dwdm',$my_dwdm)->orwhere('sjdm',$my_dwdm)->get();
            $danwei = select_company('sjdm', 'dwdm', $companies, '520000', '=', '1');
        }else{
            $danwei = Company::where('dwdm',$my_dwdm)->get();
            // $danwei = select_company('sjdm', 'dwdm', $companies, '520000', '=', '1');
        }
        return view('admin.informatizations.allInformatizationList', compact('danwei','my_dwjb'));
    }
    /**
     * 获取所有信息化资格证书分页数据
     * @param Request $request
     * @param Informatization $informatization
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllInformatizations(Request $request, Informatization $informatization)
    {
        $data = $request->all();
        $res = $informatization->getAllInformatizations($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
}
