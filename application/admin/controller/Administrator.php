<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/6
 * Time: 22:03
 */

namespace app\admin\controller;


class Administrator extends AdminBaseController
{

    public function index()
    {
        $admin_info = db('admin')->where('admin_id', session('admin_id'))->find();
        $this->assign('admin_info', $admin_info);
        return $this->fetch('admin_info');
    }

    /**
     * 修改手机号
     */
    public function updatemobile()
    {
       $query= db('admin')->where('admin_id', session('admin_id'))->setField('admin_phone', input('param.admin_phone'));
       if ($query){
           ds_json_encode(10000,"管理员信息修改成功");
       }else{
           ds_json_encode(10001,"管理员信息修改失败");
       }
    }

    /**
     * 修改密码
     */
    public function updatepw()
    {

        $query= db('admin')->where('admin_id', session('admin_id'))->where('admin_pw',md5(input('param.old_pw')))->setField('admin_pw', md5(input('param.admin_pw')));
        if ($query){
            ds_json_encode(10000,"管理员密码修改成功");
        }else{
            ds_json_encode(10001,"管理员密码修改失败");
        }
    }
}