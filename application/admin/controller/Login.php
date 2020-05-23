<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/1
 * Time: 21:17
 */

namespace app\admin\controller;

use think\Controller;

class Login extends Controller
{
    public function index()
    {
        if (session('admin_id')) {
            $this->redirect('/admin.html');
        }
        if (request()->isPost()) {

            $admin_name = input('param.admin_name');
            $admin_password = input('param.admin_password');

            $condition['admin_loginname'] = $admin_name;
            $condition['admin_pw'] = md5($admin_password);

            $admin_mod = model('admin');
            $admin_info = $admin_mod->getAdminInfo($condition,['admin_id','admin_loginname','create_time','admin_sex','admin_phone']);

            if (is_array($admin_info) and !empty($admin_info)) {

                //设置 session
                session('admin_id', $admin_info['admin_id']);
                session('admin_loginname', $admin_info['admin_loginname']);
                ds_json_encode(10000,'登录成功',$admin_info);
            } else {
                ds_json_encode(10001,'帐号密码错误');
            }
        } else {
            return $this->fetch('login');
        }
    }

}