<?php
/**
 * 会员登录 退出
 */

namespace app\member\controller;

use app\common\model\Member;
use app\common\model\Sms;
use think\Controller;
use think\Loader;

class Login extends Controller
{

    /**
     * 会员注册
     */
    public function register()
    {
        if ($this->request->isPost()) {
            // 验证账号，密码，手机号格式
            $member_login_name = trim($this->request->post("muserid"));
            $member_login_pw = trim($this->request->post("password"));
            $mobile = trim($this->request->post("mobile"));
            $vscode = trim($this->request->post("vscode"));
            $data = [
                'member_login_name' => $member_login_name,
                'member_login_pw' => $member_login_pw,
                'member_mobile' => $mobile
            ];
            $validate = Loader::validate("Member");
            $result = $validate->scene('register')->check($data);
            if ($result !== true) {
                // $this->error($validate->getError(), url("member/login/login"));
                ds_json_encode(10002, $validate->getError());
            }
            // 验证 验证码
            $value = rkcache($mobile);
            if (empty($value)) {
                // 验证码错误
                ds_json_encode(10002, "验证码错误", $data);
            }
            if ($vscode != $value) {
                ds_json_encode(10002, "验证码错误", $data);
            }

            $ndata = [
                'member_login_name' => $member_login_name,
                'member_login_pw' => substr(md5($member_login_pw), 8, 16),
                'member_mobile' => $mobile,
                'member_rank' => 1,
                'member_addtime' => date("Y-m-d H:i:s", time())
            ];
            $member = Member::create($ndata);
            if (empty($member['member_id'])) {
                // 失败
                ds_json_encode(10001, "注册失败");
            } else {
                // 成功
                ds_json_encode(10000, "注册成功");
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 发送手机验证码
     */
    public function sendSMS()
    {
        if ($this->request->isPost()) {
            $mobile = trim($this->request->post("mobile"));
            $sms = new Sms();
            $res = $sms->sendverify($mobile, 'login');
            return $res;
        } else {
            ds_json_encode(10001, "发送失败");
        }
    }

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
                if ($result !== true) {
                    $this->error($validate->getError(), url("member/login/login"));
                }
                $member = new Member();
                $memberInfo = $member->where('member_login_name', '=', $muserid)->field('member_id,member_login_pw')->find();
                if (!empty($memberInfo)) {
                    $pwd = substr(md5($password), 8, 16);
                    if ($pwd == $memberInfo['member_login_pw']) {
                        session('MUserId', $memberInfo['member_id']);
                        session('MUserName', $muserid);
                        return $this->redirect('member/index/index');
                    } else {
                        $this->error("账号或密码错误", url("member/login/login"));
                    }
                } else {
                    $this->error("账号或密码错误", url("member/login/login"));
                }
            } else {
                $this->error("账号或密码错误", url("member/login/login"));
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     * 退出登录
     */
    public function exitLogin()
    {
        session(null);
        return $this->redirect("member/login/login");
    }
}