<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\RuleModel;
use app\back\model\AdminModel;
use app\back\model\RoseModel;
use app\back\validate\Vrule;
use think\exception\ValidateException;

/**
 * 后台 功能菜单
 */
class Rule extends BaseController
{
    protected $noNeedLogin = ['addBaseRule'];

    /**
     * @msg: 功能菜单列表页
     * @param {*}
     * @return {*}
     */   
    public function index()
    {
        if (request()->isAjax()) {
            //登录的管理员
            $adminModel = new AdminModel;
            $adminInfo = $adminModel->getInfo(['id' => session('adminid')]);

            //登录管理员的角色
            $roseModel = new RoseModel;
            $roseId = $adminInfo['rose_id'];
            $roseInfo = $roseModel->getInfo(['id' => $roseId]);

            //登录管理员拥有的权限
            $ruleModel = new RuleModel;
            if ($roseInfo['name'] == '超级管理员') {
                $list = $ruleModel->getAllList();
            } else {
                $ruleIds = explode(',', $roseInfo['rule']);
                $list = RuleModel::whereIn('id',$ruleIds)->select();
            }

            //权限转无限极，带前置表示的树形数组
            $listTrr = $this->catsList($list);

            //无限极数组转二维数组
            $listTwo = tree_to_two($listTrr);

            $data = ['code' => 200, 'data' => $listTwo];
            return json($data);
        }
        $hbtns = $this->show_btn(['add']);
        $mbtns = $this->show_btn(['edit', 'stop', 'start', 'del']);
        $isOperate = empty($mbtns) ? 0 : 1;

        return view('index',['hbtns'=>$hbtns,'mbtns'=>$mbtns,'isOperate'=>$isOperate]);
    }

    /**
     * @msg: 添加功能
     * @param {*}
     * @return {*}
     */
    function add ()
    {
        if (request()->isAjax()) {
            $params = request()->param();
            $params = trim_arr($params);

            try {
                validate(Vrule::class)->check([
                    'name'  => $params['name'],
                    'pid' => $params['pid'],
                    'url' => $params['url'],
                    'sort' => $params['sort'],
                    'type' => $params['type']
                ]);
            } catch (ValidateException $e) {
                return json(['code' => 400, 'msg' => $e->getError()]);
            }

            $ruleModel = new RuleModel;

            //一二级分类不能为按钮
            if ($params['pid'] == 0) {
                if ($params['type'] == 2) {
                    return json(['code' => 400, 'msg' => '一级分类不能为按钮']);
                }
            } else {
                // 选择的父级的详情
                $pinfo = $ruleModel->getInfo(['id' => $params['pid']]);
                if ($pinfo['pid'] == 0) {
                    if ($params['type'] == 2) {
                        return json(['code' => 400, 'msg' => '二级分类不能为按钮']);
                    }
                }
            }

            //功能名称，url是否已存在
            $exit1 = $ruleModel->getInfo(['name' => $params['name']]);

            p($pinfo);
        }

        $ruleModel = new RuleModel;
        $list = $ruleModel->getAllList(['type' => 1, 'status' => 1]);
        $listTrr = cats_tree($list);
        $html = build_html($listTrr);
        $showHtml = '<option value="0">顶级分类</option>'.$html;

        return view('add', ['showHtml'=>$showHtml]);
    }

    /**
     * 生成无限极，带前置表示的树形数组
     * @param array $cats
     * @param int $pid
     * @param int $level
     * @return array
     */
    /**
     * @msg: 生成无限极，带前置标识的树形数组
     * @param array $cats   二维数组
     * @param int   $pid    pid，默认0
     * @param int   $level  级别标识，用于添加前置标识
     * @return array  带前置标识的无限极数组
     */
    function catsList(array $cats, $pid = 0, $level = 1)
    {
        $result = [];
        foreach ($cats as $k => $v) {
            if ($v['pid'] == $pid) {
                if ($level == 1) {
                    $v['name'] = $v['name'];
                } else {
                    for ($i=1; $i < $level; $i++) {
                        $v['name'] = ' <i class="layui-icon layui-icon-subtraction"></i> '.$v['name'];
                    }
                }
                unset($cats[$k]);
                $v['children'] = $this->catsList($cats, $v['id'], $level+1);
                $result[] = $v;
            }
        }
        return $result;
    }

    /**
     * @msg: 初始化添加基础功能菜单
     * @param {*}
     * @return {*}
     */
    function addBaseRule ()
    {
        $data1 = ['pid'=>0,'name'=>'系统设置','url'=>'/','sort'=>1,'type'=>1,'created_at'=>time(),'created_id'=>1];

        $ruleModel = new RuleModel;

        $exit1 = $ruleModel->getInfo(['name' => $data1['name']]);
        if ($exit1) {
            return '基础功能菜单已添加';
        }

        $data2 = ['pid'=>1,'name'=>'功能菜单','url'=>'/back/rule/index','sort'=>1,'type'=>1,'created_at'=>time(),'created_id'=>1];
        $data3 = ['pid'=>1,'name'=>'角色管理','url'=>'/back/rose/index','sort'=>2,'type'=>1,'created_at'=>time(),'created_id'=>1];
        $data4 = ['pid'=>1,'name'=>'管理员管理','url'=>'/back/admin/index','sort'=>3,'type'=>1,'created_at'=>time(),'created_id'=>1];

        //功能菜单
        $data21 = ['pid'=>2,'name'=>'添加功能','url'=>'/back/rule/add','sort'=>1,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data22 = ['pid'=>2,'name'=>'编辑功能','url'=>'/back/rule/edit','sort'=>2,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data23 = ['pid'=>2,'name'=>'禁用功能','url'=>'/back/rule/stop','sort'=>3,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data24 = ['pid'=>2,'name'=>'启用功能','url'=>'/back/rule/start','sort'=>4,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data25 = ['pid'=>2,'name'=>'删除功能','url'=>'/back/rule/del','sort'=>5,'type'=>2,'created_at'=>time(),'created_id'=>1];
        //角色管理
        $data31 = ['pid'=>3,'name'=>'添加角色','url'=>'/back/rose/add','sort'=>1,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data32 = ['pid'=>3,'name'=>'编辑角色','url'=>'/back/rose/edit','sort'=>2,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data33 = ['pid'=>3,'name'=>'分配权限','url'=>'/back/rose/setRule','sort'=>3,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data34 = ['pid'=>3,'name'=>'删除角色','url'=>'/back/rose/del','sort'=>4,'type'=>2,'created_at'=>time(),'created_id'=>1];
        //管理员管理
        $data41 = ['pid'=>4,'name'=>'添加管理员','url'=>'/back/admin/add','sort'=>1,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data42 = ['pid'=>4,'name'=>'编辑管理员','url'=>'/back/admin/edit','sort'=>2,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data43 = ['pid'=>4,'name'=>'分配角色','url'=>'/back/admin/setRose','sort'=>3,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data44 = ['pid'=>4,'name'=>'重置密码','url'=>'/back/admin/resetPwd','sort'=>4,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data45 = ['pid'=>4,'name'=>'禁用','url'=>'/back/admin/stop','sort'=>5,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data46 = ['pid'=>4,'name'=>'启用','url'=>'/back/admin/start','sort'=>6,'type'=>2,'created_at'=>time(),'created_id'=>1];
        $data47 = ['pid'=>4,'name'=>'删除管理员','url'=>'/back/admin/del','sort'=>7,'type'=>2,'created_at'=>time(),'created_id'=>1];


        $data = [$data1,$data2,$data3,$data4,$data21,$data22,$data23,$data24,$data25,$data31,$data32,$data33,$data34,$data41,$data42,$data43,$data44,$data45,$data46,$data47];

        $res = $ruleModel->addDatas($data);
        if ($res) {
            return '基础功能菜单添加成功';
        } else {
            return '基础功能菜单添加失败';
        }
    }

}
