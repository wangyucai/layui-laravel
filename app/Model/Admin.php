<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User;
use Auth;

class Admin extends User
{
    protected $fillable = [
                'username', 'password','dwjb', 'tel', 
                'tel_hm', 'company_dwdm', 'mechanism_code','mechanism_id',
                'status','real_name', 'sex', 'birth',
                'nation','native_place','native_heath',
                'political_outlook','join_party_time',
                'join_work_time','id_number',
                'join_procuratorate_time',
                'join_technical_department_time',
                'if_work','education','academic_degree',
                'major_school','major_degree_school',
                'get_education_time','get_academic_degree_time',
                'procurator','administrative_duties',
                'administrative_level','technician_title',
                'resume','dwjb','face','register_if_check'   
            ];
    protected $rulesCacheKey = 'rules_cache_v1';

    const ACTIVE_STATUS = 1;
    const NO_ACTIVE_STATUS = 0;

    public $statusInfo = [
        Admin::NO_ACTIVE_STATUS => '未启用',
        Admin::ACTIVE_STATUS
    ];

    public function roles()
    {
        return $this->belongsToMany('App\Model\Role', 'admin_role', 'admin_id', 'role_id');
    }
    /**
     * 获取用户权限
     * @param $adminId
     * @return mixed
     */
    public function getAdminRules(int $adminId) : array
    {
        $rules = session($this->rulesCacheKey);
        if (!$rules) {
            $rules = $this->getRules($adminId);
            session([$this->rulesCacheKey => $rules]);
        }
        return $rules;
    }

    public function getRules(int $adminId) : array
    {
        // 获取该用户拥有的需要认证的权限
        $rules = $this->leftJoin('admin_role', 'admins.id', '=', 'admin_role.admin_id')
            ->leftJoin('roles', 'admin_role.role_id', '=', 'roles.id')
            ->leftJoin('role_rule', 'roles.id', '=', 'role_rule.role_id')
            ->join('rules', 'role_rule.rule_id', '=', 'rules.id')
            ->where('admins.id', $adminId)
            ->where('rules.check', 1)
            ->distinct('rule')
            ->pluck('rule')
            ->toArray();
        $index = array_search('', $rules);
        if ($index !== false) unset($rules[$index]);
        // 获取不需要认证的权限
        $suRules = Rule::where('check', 0)->distinct('rule')->pluck('rule')->toArray();
        $index = array_search('', $suRules);
        if ($index !== false) unset($suRules[$index]);
        $arr = array_merge($rules, $suRules);
        return $arr;
    }

    public function cacheRules(int $adminId)
    {
        $rules = $this->getRules($adminId);
        session([$this->rulesCacheKey => $rules]);
    }
    /**
     * 获取系统管理员分页数据
     * @return array
     */
    public function getAdmins(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['username', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        // 获取系统管理员
        $admins = $this->where('tel_hm',null)
                       ->where($where)
                       ->leftJoin('companies', 'admins.company_dwdm', '=', 'companies.dwdm')
                       ->select('admins.id', 'admins.username','admins.dwjb','admins.status', 'companies.dwqc')
                       ->offset($offset)->limit($limit)->orderBy($sortfield, $order)
                       ->get()
                       ->toArray();
        $count = $this->where('tel_hm',null)
                       ->where($where)
                       ->leftJoin('companies', 'admins.company_dwdm', '=', 'companies.dwdm')
                       ->select('admins.id', 'admins.username','admins.dwjb','admins.status', 'companies.dwqc')->count();
        return [
            'count' => $count,
            'data' => $admins
        ];

    }
    /**
     * 获取注册用户分页数据
     * @return array
     */
    public function getRegisterUsers(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['username', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $where1 =  $param['company_dwdm']!=100000 ? $param['company_dwdm'] : [];      
        $where2 =  $param['dwjb']==3 ? $param['next_companies_dwdm'] : [];
        if ($where1) $where1 = [['admins.company_dwdm', $where1]];
        $admins = $this->where('tel_hm','!=',null)
                   ->where($where1)
                   ->where(function ($where2) {
                        $where2 ?: $where->orwhereIn('admins.company_dwdm', '=', $where2);
                    })
                   ->where($where)
                   ->leftJoin('mechanisms', 'mechanisms.id', '=', 'admins.mechanism_id')
                   ->leftJoin('companies', 'mechanisms.company_dwdm', '=', 'companies.dwdm')
                   ->select('admins.id', 'admins.username', 'admins.tel', 'admins.tel_hm', 'admins.register_if_check','mechanisms.nsjgmc','companies.dwqc')
                   ->offset($offset)->limit($limit)->orderBy($sortfield, $order)
                   ->get()
                   ->toArray();     
        $count = $this->where('tel_hm','!=',null)
                  ->where($where1)
                   ->where(function ($where2) {
                        $where2 ?: $where->orwhereIn('admins.company_dwdm', '=', $where2);
                    })
                   ->where($where)
                   ->leftJoin('mechanisms', 'mechanisms.id', '=', 'admins.mechanism_id')
                   ->leftJoin('companies', 'mechanisms.company_dwdm', '=', 'companies.dwdm')
                   ->count();
        return [
            'count' => $count,
            'data' => $admins
        ];

    }
    /**
     * 获取完善信息用户分页数据
     * @return array
     */
    public function getCompleteInfoUsers(array $param) : array
    {
        $page = $param['page'];
        $limit = $param['limit'];
        $where = $param['sex'] ?? [];
        $where1 = $param['nation'] ?? [];
        $where2 = $param['political_outlook'] ?? [];
        $where3 = $param['join_work_time'] ?? [];
        $where4 = $param['join_procuratorate_time'] ?? [];
        $where5 = $param['if_work'] ?? [];
        $where6 = $param['education'] ?? [];
        $where7 = $param['academic_degree'] ?? [];
        $where8 = $param['procurator'] ?? [];
        $where9 = $param['administrative_level'] ?? [];
        $wherea = $param['technician_title'] ?? [];
        $whereb = $param['start_time'] ?? [];
        $wherec = $param['end_time'] ?? [];
        $whered = $param['danwei'] ?? [];
        $wheree = $param['my_dwjb'] ?? [];
        $wheref = $param['like_search'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where)  $where   = [['admins.sex', $where]];
        if ($where1) $where1  = [['admins.nation', $where1]];
        if ($where2) $where2  = [['admins.political_outlook', $where2]];
        if ($where3) $where3  = [['admins.join_work_time', '>=', strtotime($where3)]];
        if ($where4) $where4  = [['admins.join_procuratorate_time', '>=', strtotime($where4)]];
        if ($where5) $where5  = [['admins.if_work', $where5]];
        if ($where6) $where6  = [['admins.education', $where6]];
        if ($where7) $where7  = [['admins.academic_degree', $where7]];
        if ($where8) $where8  = [['admins.procurator', $where8]];
        if ($where9) $where9  = [['admins.administrative_level', $where9]];
        if ($wherea) $wherea  = [['admins.technician_title', $wherea]];
        if ($whereb) $whereb  = [['admins.join_technical_department_time','>=', strtotime($whereb)]];
        if ($wherec) $wherec  = [['admins.join_technical_department_time','<=', strtotime($wherec)]];
        if ($whered) $whered  = [['admins.company_dwdm', $whered]];
        // if ($wheref) $wheref  = [['admins.major_school', 'like', '%'.$wheref.'%']];$whereg  = [['admins.real_name', 'like', '%'.$wheref.'%']];
        // 获取本单位完善人员信息用户列表
        $my_dwdm = $user = Auth::guard('admin')->user()->company_dwdm;
        // 判断是否包含下辖单位查询(分别是本单位级别为省级和市级的情况)
        if ($wheree==2 && $param['danwei']!=520000) $wheree = $children_dwdm = Company::where('sjdm',$param['danwei'])->orwhere('dwdm',$param['danwei'])->pluck('dwdm')->toArray();
        if ($wheree==2 && $param['danwei']==520000) $wheree = $children_dwdm = Company::pluck('dwdm')->toArray();
        if ($wheree == 3) $wheree = $children_dwdm = Company::where('sjdm',$my_dwdm)->orwhere('dwdm',$my_dwdm)->pluck('dwdm')->toArray();
        $offset = ($page - 1) * $limit;
        $query = $this->where('real_name','!=',null)
                      ->leftJoin('mechanisms', 'mechanisms.id', '=', 'admins.mechanism_id')
                      ->leftJoin('companies', 'mechanisms.company_dwdm', '=', 'companies.dwdm');
        $query->where($where);
        $query->where($where1);
        $query->where($where2);
        $query->where($where3);
        $query->where($where4);
        $query->where($where5);
        $query->where($where6);
        $query->where($where7);
        $query->where($where8);
        $query->where($where9);
        $query->where($wherea);
        $query->where($whereb);
        $query->where($wherec);
        ($wheref) &&  $query->where(function ($query) use ($wheref) {
            $query->where('admins.real_name', 'like', '%'.$wheref.'%')->orwhere('admins.major_school', 'like', '%'.$wheref.'%');
        });
        // 选择单位和子单位同时存在
        ($whered && $wheree) && $query->where(function ($query) use ($whered,$wheree) {
            $query->whereIn('admins.company_dwdm',$wheree)->orwhere($whered);
        });
        (!$whered && !$wheree && $my_dwdm!=100000) && $query->where('admins.company_dwdm','=', $my_dwdm);
        ($whered && !$wheree) && $query->where($whered);
        (!$whered && $wheree) && $query->whereIn('admins.company_dwdm',$wheree);
        $count = $query->select('admins.id', 'admins.real_name', 'admins.tel',  'admins.perinfor_if_check','mechanisms.nsjgmc','companies.dwqc');
        $admins = $count->offset($offset)
                        ->limit($limit)
                        ->orderBy($sortfield, $order)
                        ->get()
                        ->toArray();
        $count = $count->count();
        return [
            'count' => $count,
            'data' => $admins
        ];

    }
    /**
     * 获取导出的完善信息用户数据
     * @return array
     */
    public function exportCompleteInfoUser(array $param) : array
    {
        $where = $param['sex'] ?? [];
        $where1 = $param['nation'] ?? [];
        $where2 = $param['political_outlook'] ?? [];
        $where3 = $param['join_work_time'] ?? [];
        $where4 = $param['join_procuratorate_time'] ?? [];
        $where5 = $param['if_work'] ?? [];
        $where6 = $param['education'] ?? [];
        $where7 = $param['academic_degree'] ?? [];
        $where8 = $param['procurator'] ?? [];
        $where9 = $param['administrative_level'] ?? [];
        $wherea = $param['technician_title'] ?? [];
        $whereb = $param['start_time'] ?? [];
        $wherec = $param['end_time'] ?? [];
        $whered = $param['danwei'] ?? [];
        $wheree = $param['my_dwjb'] ?? [];
        $wheref = $param['like_search'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where)  $where   = [['admins.sex', $where]];
        if ($where1) $where1  = [['admins.nation', $where1]];
        if ($where2) $where2  = [['admins.political_outlook', $where2]];
        if ($where3) $where3  = [['admins.join_work_time', '>=', strtotime($where3)]];
        if ($where4) $where4  = [['admins.join_procuratorate_time', '>=', strtotime($where4)]];
        if ($where5) $where5  = [['admins.if_work', $where5]];
        if ($where6) $where6  = [['admins.education', $where6]];
        if ($where7) $where7  = [['admins.academic_degree', $where7]];
        if ($where8) $where8  = [['admins.procurator', $where8]];
        if ($where9) $where9  = [['admins.administrative_level', $where9]];
        if ($wherea) $wherea  = [['admins.technician_title', $wherea]];
        if ($whereb) $whereb  = [['admins.join_technical_department_time','>=', strtotime($whereb)]];
        if ($wherec) $wherec  = [['admins.join_technical_department_time','<=', strtotime($wherec)]];
        if ($whered) $whered  = [['admins.company_dwdm', $whered]];
        // 获取本单位完善人员信息用户列表
        $my_dwdm = $user = Auth::guard('admin')->user()->company_dwdm;
        // 判断是否包含下辖单位查询(分别是本单位级别为省级和市级的情况)
        if ($wheree==2 && $param['danwei']!=520000) $wheree = $children_dwdm = Company::where('sjdm',$param['danwei'])->orwhere('dwdm',$param['danwei'])->pluck('dwdm')->toArray();
        if ($wheree==2 && $param['danwei']==520000) $wheree = $children_dwdm = Company::pluck('dwdm')->toArray();
        if ($wheree == 3) $wheree = $children_dwdm = Company::where('sjdm',$my_dwdm)->orwhere('dwdm',$my_dwdm)->pluck('dwdm')->toArray();
        $query = $this->where('real_name','!=',null)
                      ->leftJoin('mechanisms', 'mechanisms.id', '=', 'admins.mechanism_id')
                      ->leftJoin('companies', 'mechanisms.company_dwdm', '=', 'companies.dwdm');
        $query->where($where);
        $query->where($where1);
        $query->where($where2);
        $query->where($where3);
        $query->where($where4);
        $query->where($where5);
        $query->where($where6);
        $query->where($where7);
        $query->where($where8);
        $query->where($where9);
        $query->where($wherea);
        $query->where($whereb);
        $query->where($wherec);
        ($wheref) &&  $query->where(function ($query) use ($wheref) {
            $query->where('admins.real_name', 'like', '%'.$wheref.'%')->orwhere('admins.major_school', 'like', '%'.$wheref.'%');
        });
        // 选择单位和子单位同时存在
        ($whered && $wheree) && $query->where(function ($query) use ($whered,$wheree) {
            $query->whereIn('admins.company_dwdm',$wheree)->orwhere($whered);
        });
        (!$whered && !$wheree && $my_dwdm!=100000) && $query->where('admins.company_dwdm','=', $my_dwdm);
        ($whered && !$wheree) && $query->where($whered);
        (!$whered && $wheree) && $query->whereIn('admins.company_dwdm',$wheree);
        $count = $query->select('admins.*','mechanisms.nsjgmc','companies.dwqc');
        $admins = $count->orderBy($sortfield, $order)
                        ->get()
                        ->toArray();
        $count = $count->count();
        return [
            'count' => $count,
            'data' => $admins
        ];

    }

    /**
     * 获取菜单
     * @return array
     */
    public function getMenu(int $adminId) : array
    {
        // 拥有权限的菜单
        $hasMenu = $this->leftJoin('admin_role', 'admins.id', '=', 'admin_role.admin_id')
            ->leftJoin('roles', 'admin_role.role_id', '=', 'roles.id')
            ->leftJoin('role_rule', 'roles.id', '=', 'role_rule.role_id')
            ->leftJoin('rules', 'role_rule.rule_id', '=', 'rules.id')
            ->where([
                ['admins.id', '=', $adminId],
                ['rules.check', '=', 1],
                ['rules.status', '=', 1]
            ])
            ->whereIn('rules.level', [1, 2])
            ->select('rules.title', 'rules.icon', 'rules.href', 'rules.id', 'rules.pid', 'rules.sort')
            ->distinct('rules.id')
            ->get()->toArray();

        // 不需要验证的菜单
        $suMenu = Rule::where([
                ['check', '=', 0],
                ['status', '=', 1]
            ])->whereIn('level', [1, 2])
            ->select('rules.title', 'rules.icon', 'rules.href', 'rules.id', 'rules.pid', 'rules.sort')
            ->distinct('rules.id')
            ->get()->toArray();

        $menu = $this->mergeMenu($hasMenu, $suMenu);
        unset($hasMenu);
        unset($suMenu);
        $menu = $this->makeMenu($menu);
        return $menu;
    }

    /**
     * 合并菜单并去重排序
     * @param $menu1
     * @param $menu2
     * @return array
     */
    public function mergeMenu(array $menu1, array $menu2) : array
    {
        $arr1 = array_column($menu1, 'id');
        foreach ($menu2 as $key => $row) {
            if (in_array($row['id'], $arr1)) {
                unset($menu2[$key]);
            }
        }
        $mergeArr = array_merge($menu1, $menu2);
        unset($menu1);
        unset($menu2);
        usort($mergeArr, function ($a, $b) {
            if ($a['sort'] == $b['sort']) {
                return 0;
            }
            return ($a['sort'] < $b['sort']) ? -1 : 1;
        });
        return $mergeArr;

    }

    /**
     * 组装菜单数据
     * @param $rules
     * @param int $pid
     * @return array
     */
    public function makeMenu(array $rules, int $pid = 0) : array
    {
        $menu = [];
        foreach ($rules as $k => $v){
            if ($v['pid'] == $pid) {
                $v['spread'] = false;
                unset($rules[$k]);
                $v['children'] = $this->makeMenu($rules, $v['id']);
                $menu[] = $v;
            }
        }
        return $menu;
    }

    // 用户收到的通知
    public function notices()
    {
        return $this->belongsToMany(\App\Model\Notice::class,'user_notice','user_id','notice_id')->withPivot(['user_id','notice_id','if_read','if_down']);
    }
    // 用户收到的邮件
    public function emails()
    {
        return $this->belongsToMany(\App\Model\Email::class,'user_email','user_id','email_id')->withPivot(['user_id','email_id','if_read','if_down']);
    }
    // 用户收到的培训
    public function trains()
    {
        return $this->belongsToMany(\App\Model\Train::class,'user_train','user_id','train_id')->withPivot(['user_id','train_id']);
    }

    // 给用户增加通知
    public function addNotice($notice)
    {
        return $this->notices()->save($notice);
    }
    // 删除用户通知
    public function deleteNotice($notice)
    {
      return $this->notices()->detach($notice);
    }
    // 给用户发送邮件
    public function addEmail($email)
    {
        return $this->emails()->save($email);
    }
    // 删除用户邮件
    public function deleteEmail($email)
    {
      return $this->emails()->detach($email);
    }
    // 给用户发送培训
    public function addTrain($train)
    {
        return $this->trains()->save($train);
    }
    // 删除用户培训
    public function deleteTrain($train)
    {
      return $this->trains()->detach($train);
    }

}
