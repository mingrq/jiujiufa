<?php
/**
 * 会员登录 退出
 */

namespace app\member\controller;

use app\common\model\Member;
use app\common\model\Rank;
use app\common\model\Sms;
use think\Controller;
use think\Loader;

class Login extends Controller
{
private function getwebsiteinfo(){
    $mode = model('websiteinfo');
    $query = $mode->getWebsiteInfo();
    foreach ($query as $info) {
        if ($info['config_code'] == 'website_name') {
            $this->assign('website_name', $info['config_value']);
        }
        if ($info['config_code'] == 'website_logo') {
            $this->assign('website_logo', $info['config_value']);
        }
        if ($info['config_code'] == 'website_keyword') {
            $this->assign('website_keyword', $info['config_value']);
        }
        if ($info['config_code'] == 'website_icp') {
            $this->assign('website_icp', $info['config_value']);
        }
        if ($info['config_code'] == 'website_copyright') {
            $this->assign('website_copyright', $info['config_value']);
        }
        if ($info['config_code'] == 'website_statistics') {
            $this->assign('website_statistics', $info['config_value']);
        }
    }
}
    /**
     * 会员注册
     */
    public function register()
    {

        // 邀请的会员ID
        $inviteUserId = preg_replace('/[^0-9]/', '', $this->request->param("m"));
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
                ds_json_encode(10002, "验证码错误", null);
            }
            if ($vscode != $value) {
                ds_json_encode(10002, "验证码错误", null);
            }
            // 判断手机号唯一性
            $memberMobi = Member::get(['member_mobile' => $mobile]);
            if (!empty($memberMobi) && $memberMobi['member_mobile'] == $mobile) {
                ds_json_encode(10002, "此手机号已使用，请更换其它手机号注册", null);
                exit();
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
                // 判断是否是邀请的会员 是 则 更新会员信息
                $inviteMember = Member::get($inviteUserId);
                if (!empty($inviteMember) && !empty($inviteMember['member_login_name'])) {
                    $imemberRank = $inviteMember['member_rank'];
                    $invite_count = $inviteMember['member_invite_count'] + 1;
                    // 判断下一个代理级别的人数 如果大于等于了则升级代理级别
                    $rank = Rank::get(($imemberRank + 1));
                    if (!empty($rank) && !empty($rank['rank_id']) && !empty($rank['rank_name'])) {
                        if ($invite_count >= $rank['invite_upgrade']) {
                            $inviteMember->member_rank = $rank['rank_id'];
                        }
                    }
                    // 将邀请人数更新
                    $inviteMember->member_invite_count = $invite_count;
                    $inviteMember->save();
                }
                ds_json_encode(10000, "注册成功");
            }
        } else {
            $this->getwebsiteinfo();
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

        if (session('MUserId')) {
            return $this->redirect("member/account/personinfo");
        }
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
                        ds_json_encode(10000, "登录成功");
                    } else {
                        ds_json_encode(10001, "账号或密码错误");
                    }
                } else {
                    ds_json_encode(10001, "账号或密码错误");
                }
            } else {
                ds_json_encode(10001, "账号或密码错误");
            }
        } else {
            $this-> getwebsiteinfo();
            return $this->fetch();
        }
    }

    /**
     * 找回密码
     */
    public function findPwd()
    {
        if ($this->request->isPost()) {
            $member_login_pw = trim($this->request->post("password"));
            $mobile = trim($this->request->post("mobile"));
            $vscode = trim($this->request->post("vscode"));

            // 首先判断是否有这个手机号
            $memberMobi = Member::get(['member_mobile' => $mobile]);
            if (!empty($memberMobi) && $memberMobi['member_mobile'] == $mobile && $memberMobi['member_id']) {
                $data = [
                    'member_login_pw' => $member_login_pw
                ];
                $validate = Loader::validate("Member");
                $result = $validate->scene('findpwd')->check($data);
                if ($result !== true) {
                    // $this->error($validate->getError(), url("member/login/login"));
                    ds_json_encode(10002, $validate->getError());
                }
                // 验证 验证码
                $value = rkcache($mobile);
                if (empty($value)) {
                    // 验证码错误
                    ds_json_encode(10002, "验证码错误", null);
                }
                if ($vscode != $value) {
                    ds_json_encode(10002, "验证码错误", null);
                }
                //更新数据库密码
                $res = $memberMobi->save([
                    'member_login_pw' => substr(md5($member_login_pw), 8, 16)
                ], ['member_id' => $memberMobi['member_id']]);
                if ($res > 0) {
                    // 成功
                    ds_json_encode(10000, "更新成功");
                } else {
                    // 失败
                    ds_json_encode(10001, "更新失败");
                }
            } else {
                // 没有这个手机号
                ds_json_encode(10002, "此手机号未注册");
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
        session('MUserId', null);
        session('MUserName', null);
        return $this->redirect("member/login/login");
    }
}