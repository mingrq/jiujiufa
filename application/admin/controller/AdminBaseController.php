<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/1
 * Time: 20:19
 */

namespace app\admin\controller;


use think\Controller;

class AdminBaseController extends Controller
{
    /**
     * 管理员资料 name id group
     */
    protected $admin_info;

    public function _initialize() {
        $this->admin_info = $this->systemLogin();
    }

    /**
     * 取得当前管理员信息
     *
     * @param
     * @return 数组类型的返回结果
     */
    protected final function getAdminInfo() {
        return $this->admin_info;
    }

    /**
     * 系统后台登录验证
     *
     * @param
     * @return array 数组类型的返回结果
     */
    protected final function systemLogin() {
        $admin_info = array(
            'admin_id' => session('admin_id'),
            'admin_loginname' => session('admin_loginname')
        );
        if (empty($admin_info['admin_id']) || empty($admin_info['admin_loginname'])) {
            session(null);
            $this->redirect('/admin/login.html');
        }

        return $admin_info;
    }
}