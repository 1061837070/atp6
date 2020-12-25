<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\AdminModel;
use app\back\model\RoseModel;
use app\back\validate\Vadmin;
use think\exception\ValidateException;
use think\facade\Db;

/**
 * Class Admin 后台 管理员账号
 * @package app\back\controller
 */
class Admin extends BaseController
{
    protected $noNeedLogin = ['addSuperAdmin'];

    /**
     * @msg: 管理员列表
     * @param {*}
     * @return {*}
     */
    public function index()
    {
        if (request()->isAjax()) {
            $list = Db::table('tp6_admin')
                ->alias('a')
                ->where('a.id','<>',1)
                ->leftJoin('tp6_admin ca','a.created_id = ca.id')
                ->leftJoin('tp6_admin ua','a.updated_id = ua.id')
                ->leftJoin('tp6_rose r','a.rose_id = r.id')
                ->field('a.*')
                ->field('ca.nick_name as cname')
                ->field('ua.nick_name as uname')
                ->field('r.name as rose_name')
                ->order('a.id')
                ->select()
                ->toArray();
            return json(['code' => 200, 'data' => $list]);
        }
        $hbtns = $this->show_btn(['add']);
        $mbtns = $this->show_btn(['edit', 'setRose', 'resetPwd', 'stop', 'start', 'del']);
        $isOperate = empty($mbtns) ? 0 : 1;
    
        return view('index',['hbtns'=>$hbtns,'mbtns'=>$mbtns,'isOperate'=>$isOperate]);
    }

    /**
     * @msg: 添加管理员
     * @param {*}
     * @return {*}
     */
    public function add()
    {
        $roseModel = new RoseModel;
        if (request()->isAjax()) {
            $params = request()->param();
            $params = trim_arr($params);
            
            try {
                validate(Vadmin::class)->check([
                    'nick_name'  => $params['nick_name'],
                    'phone' => $params['phone'],
                    'rose_id' => $params['rose_id'],
                    'email' => $params['email'],
                ]);
            } catch (ValidateException $e) {
                return json(['code' => 400, 'msg' => $e->getError()]);
            }

            if (!preg_match('/^1\d{10}$/', $params['phone'])) {
                return json(['code' => 400, 'msg' => '手机号格式错误']);
            }
            
            $eixt1 = $roseModel->getInfo(['id' => $params['rose_id']]);
            if (empty($eixt1)) {
                return json(['code' => 400, 'msg' => '角色选择错误']);
            }
            $adminModel = new AdminModel;
            $eixt2 = $adminModel->getInfo(['nick_name' => $params['nick_name']]);
            if (!empty($eixt2)) {
                return json(['code' => 400, 'msg' => '昵称已存在']);
            }
            $eixt3 = $adminModel->getInfo(['phone' => $params['phone']]);
            if (!empty($eixt3)) {
                return json(['code' => 400, 'msg' => '手机号已存在']);
            }

            // 添加数据
            $params['created_at'] = time();
            $params['created_id'] = session('adminid');
            $pwd = '123456';
            $params['pwd_salt'] = bin2hex(random_bytes(3));
            $params['password'] = set_enc_word($params['pwd_salt'], $pwd, 'back');

            $res = $adminModel->addData($params);
            if ($res) {
                return json(['code' => 200, 'msg' => '添加成功']);
            } else {
                return json(['code' => 400, 'msg' => '添加失败']);
            }
        }

        // 管理员角色
        $roseList = $roseModel->getAllList([['name', '<>', '超级管理员']]);
        return view('add', ['roseList'=>$roseList]);
    }

    
    public function edit()
    {
        $adminModel = new AdminModel;
    }

    /**
     * @msg: 上传管理员头像接口
     * @param {*}
     * @return {*}
     */
    public function upload()
    {
        $file = request()->file('header_pic');
        try {
            validate(['file' => ['fileSize:1024000', 'fileExt:jpg,jpeg,png,gif']])->check(['file' => $file]);
        } catch (\think\exception\ValidateException $e) {
            return json(['code' => 400, 'msg' => $e->getMessage()]);
        }
        $savename = \think\facade\Filesystem::disk('public')->putFile( 'admin_header_pic', $file);
        return json(['code' => 200, 'path_info' => '/storage/'.str_replace('\\', '/', $savename)]);
    }

    /**
     * @msg: 添加超级管理员 初始化时直接浏览器地址操作 只能添加一次
     * @param {*}
     * @return {*}
     */
    public function addSuperAdmin()
    {
        $params['nick_name'] = 'admin';
        $params['true_name'] = 'admin';

        $adminModel = new AdminModel;

        $exit1 = $adminModel->getInfo(['nick_name' => $params['nick_name']]);
        if ($exit1) {
            return '超级管理员已存在';
        }

        $params['phone'] = '';
        $pwd = '123456';
        $params['pwd_salt'] = bin2hex(random_bytes(3));
        $params['password'] = set_enc_word($params['pwd_salt'], $pwd, 'back');
        $params['rose_id'] = 1;
        $params['created_at'] = time();
        $params['created_id'] = 1;

        $res = $adminModel->addData($params);
        if ($res) {
            return '超级管理员添加成功';
        } else {
            return '超级管理员添加失败';
        }
    }

}
