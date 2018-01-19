<?php

namespace App\Model;

use Illuminate\Foundation\Auth\User;

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

    // Admin-Company:One-One
    // public function company()
    // {
    //     return $this->hasOne('App\Model\Company', 'companies', 'company_id');
    // }

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
        $where = $param['cond'] ?? [];
        $sortfield = $param['sortField'] ?? 'id';
        $order = $param['order'] ?? 'asc';
        if ($where) $where = [['username', 'like', $where.'%']];
        $offset = ($page - 1) * $limit;
        $where1 =  $param['company_dwdm']!=100000 ? $param['company_dwdm'] : [];      
        $where2 =  $param['dwjb']==3 ? $param['next_companies_dwdm'] : [];
        if ($where1) $where1 = [['admins.company_dwdm', $where1]];
        $admins = $this->where('real_name','!=',null)
                           ->where($where1)
                           ->where(function ($where2) {
                                $where2 ?: $where->orwhereIn('admins.company_dwdm', '=', $where2);
                            })
                           ->where($where)
                           ->leftJoin('mechanisms', 'mechanisms.id', '=', 'admins.mechanism_id')
                           ->leftJoin('companies', 'mechanisms.company_dwdm', '=', 'companies.dwdm')
                           ->select('admins.id', 'admins.real_name', 'admins.tel',  'admins.perinfor_if_check','mechanisms.nsjgmc','companies.dwqc')
                           ->offset($offset)->limit($limit)->orderBy($sortfield, $order)
                           ->get()
                           ->toArray();
        $count = $this->where('real_name','!=',null)
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
}
