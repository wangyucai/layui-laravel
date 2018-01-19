<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Register;
use App\Model\Company;
use App\Model\Mechanism;
use App\Model\Role;
use App\Model\Admin;
use App\Model\Nation;
use App\Service\AdminService;
use Auth;
use App\Handlers\ImageUploadHandler;

class RegisterController extends Controller
{
    /**
     * 注册页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $companies = Company::where('dwdm','!=','100000')->get();
        $danwei = select_company('sjdm', 'dwdm', $companies, '100000', '=', '1');
        return view('admin.register.index',compact('danwei'));
    }
    /**
     * 处理注册用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register(Request $request)
    {
        if($request->act == 'danwei'){
            $company_dwdm = $request->company_dwdm;
            $bumen = Mechanism::where('company_dwdm', $company_dwdm)->get();
            if(!$bumen->isEmpty()){
               return [
                    'error' => 0,
                    'bumen' => $bumen,
                ];
            }else{
                return [
                    'error' => 1,
                    'msg' => '该单位还未建立内设机构',
                ];
            }
        }
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'confirmPwd' => 'required',
            'tel' => 'required',
            'tel_hm' => 'required',
            'company_dwdm' => 'required',
            'mechanism_id' => 'required',
        ]);
        $service = new AdminService();
        $re = $service->confirmRegister($request->all());
        if ($re) {
            return ajaxSuccess();
        } else {
            return ajaxError($service->getError(), $service->getHttpCode());
        }
    }
    
    /**
     * 注册用户列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registerList()
    {
        return view('admin.register.registerUser');
    }
    /**
     * 获取后台注册用户分页数据
     * @param Request $request
     * @param Admin $admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegisterUsers(Request $request, Admin $admin)
    {
        $data = $request->all();
        $data['company_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $data['dwjb'] = Auth::guard('admin')->user()->dwjb;
        $next_companies = Company::where('sjdm',$data['company_dwdm'])->select('dwdm')->get()->toArray();
        $data['next_companies_dwdm'] = $next_companies;
        $res = $admin->getRegisterUsers($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 审核后台注册用户
     */
    public function activeRegisterUser(Request $request)
    {
        $re = Admin::where('id', $request->id)->update(['register_if_check' => $request->register_if_check]);
        if (!$re) return ajaxError('修改失败', HttpCode::BAD_REQUEST);
        return ajaxSuccess();
    }
    /**
     * 编辑注册用户
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editRegisterUser(Request $request)
    {
        if ($request->isMethod('put')) {
            $this->validate($request, [
                'username' => 'required'
            ]);
            if(isset($request->role_id) === false){
                $request['role_id'] = 0;
            }
            $adminService = new AdminService();
            $re = $adminService->editRegisterAdmin($request->all());
            if (!$re) return ajaxError($adminService->getError(), $adminService->getHttpCode());
            return ajaxSuccess();
        } else {
            $companies = Company::where('dwdm','!=','100000')->get();
            $danwei = select_company('sjdm', 'dwdm', $companies, '100000', '=', '1');
            $roles = Role::all()->toArray();
            $admin = Admin::with('roles')->find($request->id)->toArray();
            $hasRoles = array_column($admin['roles'], 'id');
            $my_user_id = Auth::guard('admin')->user()->id;
            $myRoles = Admin::with('roles')->find($my_user_id)->toArray();
            // 判断是否为超级管理员
            $roles = $my_user_id ==1 ? $roles : $myRoles['roles'];
            foreach ($roles as &$role) {
                if (in_array($role['id'], $hasRoles)) {
                    $role['checked'] = 1;
                } else {
                    $role['checked'] = 0;
                }
            }
            // 该单位的所有部门
            $bumen = Mechanism::where('company_dwdm',$admin['company_dwdm'])->get()->toArray();
            return view('admin.register.editRegisterUser', ['roles' => $roles, 'admin' => $admin, 'danwei'=>$danwei, 'bumen' => $bumen]);
        }
    }
    /**
     * 完善人事信息
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function completeUserInfo(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'real_name' => 'required',
                'sex' => 'required',
                'birth' => 'required',
                'nation' => 'required',
                'native_place' => 'required',
                'native_heath' => 'required',
                'political_outlook' => 'required',
                'join_party_time' => 'required',
                'join_work_time' => 'required',
                'id_number' => 'required',
                'join_procuratorate_time' => 'required',
                'join_technical_department_time' => 'required',
                'if_work' => 'required',
                'education' => 'required',
                'academic_degree' => 'required',
                'major_school' => 'required',
                'major_degree_school' => 'required',
                'get_education_time' => 'required',
                'get_academic_degree_time' => 'required',
                'procurator' => 'required',
                'administrative_duties' => 'required',
                'administrative_level' => 'required',
                'technician_title' => 'required',
                'resume' => 'required',        
            ]);
            $adminService = new AdminService();
            $re = $adminService->completeInfoAdmin($request->all());
            if (!$re) return ajaxError($adminService->getError(), $adminService->getHttpCode());
            return ajaxSuccess();
        } else {
            $company_dwdm = $user = Auth::guard('admin')->user()->company_dwdm;
            $uid = $user = Auth::guard('admin')->user()->id;
            $dwjb = Company::where('dwdm',$company_dwdm)->value('dwjb');
            $nations = Nation::all();
            $political_outlook = political_outlook();
            $education = education();
            $academic_degree = academic_degree();
            $procurator = procurator();
            $administrative_duties = administrative_duties();
            $administrative_level = administrative_level();
            $technician_title = technician_title();
            return view('admin.register.completeUserInfo', compact('dwjb','nations','political_outlook','education','academic_degree','procurator','administrative_duties','administrative_level','technician_title','uid'));
        }
    }
    // 上传头像
    public function uploadFace(Request $request,ImageUploadHandler $uploader)
    {
        $uid = $user = Auth::guard('admin')->user()->id;
        if ($request->file) {
            $result = $uploader->save($request->file, 'faces', $uid, 362);
            if ($result) {
                $data['face'] = $result['path'];
                $admin = Admin::find($uid);
                $admin->face = $data['face'];
                $re1 = $admin->save();
                return [
                    'status' => 1
                ];
            }
        }
    }
    /**
     * 完善人事信息用户列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function completeInfoUserList()
    {
        return view('admin.register.completeInfoUser');
    }
    /**
     * 获取后台完善信息用户分页数据
     * @param Request $request
     * @param Admin $admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompleteInfoUsers(Request $request, Admin $admin)
    {
        $data = $request->all();
        $data['company_dwdm'] = Auth::guard('admin')->user()->company_dwdm;
        $data['dwjb'] = Auth::guard('admin')->user()->dwjb;
        $next_companies = Company::where('sjdm',$data['company_dwdm'])->select('dwdm')->get()->toArray();
        $data['next_companies_dwdm'] = $next_companies;
        $res = $admin->getCompleteInfoUsers($data);
        return ajaxSuccess($res['data'], $res['count']);
    }
    /**
     * 审核后台完善信息用户
     */
    public function activeCompleteInfoUser(Request $request)
    {
        $re = Admin::where('id', $request->id)->update(['perinfor_if_check' => $request->perinfor_if_check]);
        if (!$re) return ajaxError('修改失败', HttpCode::BAD_REQUEST);
        return ajaxSuccess();
    }
    /**
     * 编辑完善人事信息用户
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editCompleteInfoUser(Request $request)
    {
        if ($request->isMethod('put')) {
            $adminService = new AdminService();
            $re = $adminService->editCompleteInfoAdmin($request->all());
            if (!$re) return ajaxError($adminService->getError(), $adminService->getHttpCode());
            return ajaxSuccess();
        } else {
            $admin = Admin::with('roles')->find($request->id)->toArray();
            $nations = Nation::all();
            $political_outlook = political_outlook();
            $education = education();
            $academic_degree = academic_degree();
            $procurator = procurator();
            $administrative_duties = administrative_duties();
            $administrative_level = administrative_level();
            $technician_title = technician_title();
            return view('admin.register.editCompleteInfoUser', compact('admin','nations','political_outlook','education','academic_degree','procurator','administrative_duties','administrative_level','technician_title'));
        }
    }
}
    