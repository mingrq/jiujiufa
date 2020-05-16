<?php
/**
 * 会员登录 退出
 */

namespace app\member\controller;

use app\common\model\Member;
use think\Controller;
use think\Loader;

class Login extends Controller
{

    /**
     * 会员登录
     */
    public function login()
    {
        if ($this->request->isPost()) {

            $muserid = trim($this->request->post('muserid'));
            $password = trim($this->request->post('password'));
            if (!empty($muserid) && !empty($password)) {
                // 验证账号密码
                $data = [
                    'member_login_name' => $muserid,
                    'member_login_pw' => $password
                ];
                $validate = Loader::validate("Member");
                $result = $validate->scene('login')->check($data);
                if ($result !== true){
                    $this->error($validate->getError(), url("member/login/login"));
                }
                $member = new Member();
                $memberInfo = $member->where('member_login_name', '=', $muserid)->field('member_id,member_login_pw')->find();
                if (!empty($memberInfo)){
                    $pwd = substr(md5($password), 8, 16);
                    if ($pwd == $memberInfo['member_login_pw']){
                        session('MUserId', $memberInfo['member_id']);
                        session('MUserName', $muserid);
                        return $this->redirect('member/index/index');
                    }else{
                        $this->error("账号或密码错误", url("member/login/login"));
                    }
                }else{
                    $this->error("账号或密码错误", url("member/login/login"));
                }
            }else{
                $this->error("账号或密码错误", url("member/login/login"));
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 退出登录
     */
    public function exitLogin(){
        session(null);
        return $this->redirect("member/login/login");
    }
}