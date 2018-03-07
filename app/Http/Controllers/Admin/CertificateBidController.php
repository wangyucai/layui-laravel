<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CertificateBid;
use App\Model\ProfessionCarModule;
use App\Model\ProfessionCarCode;
use App\Model\Business;
use Auth;
use App\Service\CertificateBidService;

class CertificateBidController extends Controller
{
    /**
     * 需申办的职业资格证书列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function carBidList()
    {
        return view('admin.certificatebids.carBidList');
    }
    /**
     * 获取需申办的职业资格证书分页数据
     * @param Request $request
     * @param ProfessionCarModule $professioncarmodule
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCarBid(Request $request, CertificateBid $certificatebid)
    {
        $data = $request->all();
        $data['dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $res = $certificatebid->getCarBid($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 申办职业资格证书
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addCarBid(Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            $data['user_id'] = Auth::guard('admin')->user()->id;
            $certificateBidService = new CertificateBidService();
            $re = $certificateBidService->addCarBid($data);
            if (!$re) return ajaxError($certificateBidService->getError(), $certificateBidService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$id =  $request->route('id');
        	// 我的信息
        	$my_info =  Auth::guard('admin')->user();
        	// 获取该证书模板的信息
        	$thisModule = ProfessionCarModule::find($id);
        	// 获取证书名称
        	$thisZsmc = ProfessionCarCode::where('car_code',$thisModule->zsmc)->value('car_name');
        	// 获取鉴定业务范围
            $businesses = Business::all()->toArray();
            $thisYwlb = explode(',', $thisModule->ywlb);
            foreach ($businesses as &$business) {
                if (in_array($business['jdywfw_code'], $thisYwlb)) {
                    $ywlb[$business['jdywfw_code']] = $business['jdywfw_name'];
                }
            }
            // 判断证书表里是否有申报记录
            $has = CertificateBid::where('zsmc', $thisZsmc)->count();
            if ($has > 0) {
	            $zsbh = CertificateBid::where('zsmc', $thisZsmc)->orderBy('id','desc')->first();
	            $thisZsbh = intval($zsbh->zsbh);
	        }else{
	        	$thisZsbh = intval($thisModule->zsbh);
	        }
	        // 该证书编号自增
        	$thisZsbh++;
            return view('admin.certificatebids.addCarBid',compact('thisModule','ywlb','my_info','thisZsmc','thisZsbh'));
        }
    }
    /**
     * 已申办的职业资格证书列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function certificateList()
    {
        $zsmc_arr = ProfessionCarCode::all()->toArray();
    	$admin_dwdm = Auth::guard('admin')->user()->company_dwdm;
        $admin_dwjb = Auth::guard('admin')->user()->dwjb;
        return view('admin.certificatebids.certificateList', compact('admin_dwdm','zsmc_arr','admin_dwjb'));
    }
    /**
     * 获取(本级单位或者下级上报的)已申办的职业资格证书分页数据
     * @param Request $request
     * @param ProfessionCarModule $professioncarmodule
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCertificate(Request $request, CertificateBid $certificatebid)
    {
        $data = $request->all();
        $data['dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $res = $certificatebid->getCertificate($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 编辑申办的职业资格证书
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editCertificate(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
             // 
            ]);
            $data = $request->all();
            $certificateBidService = new CertificateBidService();
            $re = $certificateBidService->editCertificate($data);
            if (!$re) return ajaxError($certificateBidService->getError(), $certificateBidService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
            // 获取职业资格证书代码表中证书名称
            $professioncarcodes = ProfessionCarCode::all();
            $certificateBid = CertificateBid::find($request->id)->toArray();
            // 获取该模板鉴定业务范围
            $car_code = ProfessionCarCode::where('car_name',$certificateBid['zsmc'])->value('car_code');
            $modulebusinesses = ProfessionCarModule::where('zsmc',$car_code)->value('ywlb');
            $modulebusinesses = explode(',', $modulebusinesses);
            $businesses = Business::all()->toArray();
            $hasbusinesses = explode(',', $certificateBid['ywlb']);
            foreach ($businesses as &$business) {
                if (in_array($business['jdywfw_code'], $modulebusinesses)) {
                    $ywlb[$business['jdywfw_code']] = $business['jdywfw_name'];
                }
            }
            return view('admin.certificatebids.editCertificate',compact('certificateBid','businesses','professioncarcodes','hasbusinesses','ywlb'));
        }
    }
    /*把申报的证书上报*/
    public function reportingCertificate(Request $request)
    {
    	$data = $request->all();
    	// 我的单位级别
    	$admin_dwjb = Auth::guard('admin')->user()->dwjb;
    	$data['admin_dwjb'] = $admin_dwjb;
        $certificateBidService = new CertificateBidService();
        $re = $certificateBidService->reportingCertificate($data);
        if (!$re) return ajaxError($certificateBidService->getError(), $certificateBidService->getHttpCode());
        return ajaxSuccess();
    }
    /**
     * 反馈信息给已申办证书用户
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkCertificate(Request $request)
    {      
        $data = $request->all();
        $certificateBidService = new CertificateBidService();
        $re = $certificateBidService->checkCertificate($data);
        if (!$re) return ajaxError($certificateBidService->getError(), $certificateBidService->getHttpCode());
        return ajaxSuccess();
    }
}
