<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Email;
use App\Model\Company;
use App\Model\Mechanism;
use App\Model\Admin;
use App\Service\EmailService;
use Auth;
use Image;

class EmailController extends Controller
{
    /**
     * 内部邮件列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function emailList()
    {
        return view('admin.emails.emailList');
    }
    /**
     * 获取内部邮件分页数据
     * @param Request $request
     * @param Email $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEmail(Request $request, Email $email)
    {
        $data = $request->all();
        $res = $email->getEmail($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 添加内部邮件
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addEmail(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
            ]);
            $data = $request->all();
            $emailService = new EmailService();
            $re = $emailService->addEmail($data);
            if (!$re) return ajaxError($emailService->getError(), $emailService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {       	
    		$companies = $this->getCompanyUser();
            return view('admin.emails.addEmail',['companies' => json_encode($companies)]);
        }
    }

    /**
     * 添加内部邮件
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmailer(Request $request,Admin $admin)
    {
        $data = $request->all();
        $res = $admin->getEmailer($data);
        return ajaxSuccess($res['data']);
    }
    /**
     * 上传内部邮件附件
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadAttachment(Request $request)
    {
    	$files = $_FILES['file'];  //上传文件file
        $file = $request->file;  //上传文件file
        $originalName = $file->getClientOriginalName(); // 文件原名,
        $totalPieces = $request->totalPieces;  //上传文件切片总数
        $index = $request->index;  //上传文件当前切片
        $progress = round(($index/$totalPieces),2)*100;
        if($index == ($totalPieces - 1)){
            $progress = 100;  //进度条
        }
        $folder_name = "uploads/email/files/" . date("Ym", time()) . '/'.date("d", time()).'/';
        $upload_path = public_path() . '/' . $folder_name;
        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
        $filename = time() . '_' . str_random(10) . '.' . $extension;
        $savePath = $upload_path.$filename;
         // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);
        $return = [
                    'info' => '上传成功!',
                    'tolink' =>  config('app.url') . "/$folder_name".$filename,
                    'imgid' => $filename,
                    'code'      => 0,
                    'progress' => $progress,
                    'originalName' => $originalName,
                    'size' => $files['size'],
        ];
        return ajaxSuccess($return);
    }
    
    /**
     * 编辑内部邮件
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editEmail(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
            ]);
            $data = $request->all();
            $emailService = new EmailService();
            $re = $emailService->editEmail($data);
            if (!$re) return ajaxError($emailService->getError(), $emailService->getHttpCode());
            return ajaxSuccess([], '', 'success', HttpCode::CREATED);
        } else {
        	$email = Email::find($request->id)->toArray();
            $email_receivers_has = unserialize($email['email_receivers']);
        	$companies = $this->getCompanyUser();
        	foreach ($companies as $k => $company) {
        		if(in_array($company['dwdm'],$email_receivers_has)){
                    $company['checked'] = true;
                }
                $companies[$k] = $company;
        	}
            return view('admin.emails.editEmail', [
                    'companies' => json_encode($companies), 'email' => $email]);
        }
    }
    /**
     * 删除内部邮件
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delEmail(Request $request)
    {
        $emailService = new EmailService();
        $re = $emailService->delEmail($request->id);
        if (!$re) return ajaxError($emailService->getError(), $emailService->getHttpCode());
        return ajaxSuccess();
    }
     /**
     * 获取内部邮件的单位部门下的人员树
     */
    public function getCompanyUser()
    {
    	// 获取每个单位下的用户列表
        $companies = Company::select('dwdm','sjdm','dwqc')->get()->toArray();
        foreach ($companies as $k => $v) {
        	$v['sjdm'] = 100000;
        	$companies[$k] = $v;
        }
        $bumen = Mechanism::select('company_dwdm as sjdm','nsjgmc as dwqc','id as dwdm')->get()->toArray();
		$admins = Admin::where('tel_hm','!=',null)->select('mechanism_id as sjdm','username as dwqc','tel as dwdm')->get()->toArray();
		$companies = array_merge($companies,$admins);
		$companies = array_merge($companies,$bumen);
		return $companies;
    }

    /**
     * 我的邮件列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myEmailList()
    {
        return view('admin.emails.myEmailList');
    }
    /**
     * 获取我的邮件分页数据
     * @param Request $request
     * @param Email $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMyEmail(Request $request, Email $email)
    {
        $data = $request->all();
        $res = $email->getMyEmail($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    
    /*把我的邮件标记为已读*/
    public function readMyEmail(Request $request)
    {
        $emailService = new EmailService();
        $re = $emailService->readMyEmail($request->all());
        if (!$re) return ajaxError($emailService->getError(), $emailService->getHttpCode());
        return ajaxSuccess();
    }
    /**
     * 查看邮件详情
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myEmailShow(Email $email,$myemail)
    {   
        $myemaildetail = $email->myEmailDetail($myemail);
        return view('admin.emails.myEmailShow',compact('myemaildetail'));
    }
}
