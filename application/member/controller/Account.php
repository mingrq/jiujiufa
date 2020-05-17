<?php

namespace app\member\controller;

use app\common\controller\MemberBase;
use app\common\model\Member;
use app\common\model\Rank;
use think\Loader;

class Account extends MemberBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 个人信息
     */
    public function personInfo()
    {
        $memeber = Member::get(session('MUserId'));
        $this->assign('member', $memeber);
        return $this->fetch();
    }

    /**
     * 修改个人中心 个人信息
     */
    public function updatePersonInfo()
    {
        if ($this->request->isPost()){
            $password = trim($this->request->post('password'));
            $qqnum = trim($this->request->post('qqnum'));
            if (empty($password)){
                // 不修改密码
                $data = [
                    'member_qq' => $qqnum
                ];
                $validate = Loader::validate("Member");
                $result = $validate->scene('edit')->check($data);
                if ($result !== true){
                    $this->error($validate->getError(), url("member/account/personinfo"));
                }
                $memeber = new Member;
                $memeber->save($data,['member_id'=>session('MUserId')]);

                return $this->success("更新成功", 'member/account/personinfo');
            }else{
                // 修改密码
                $data = [
                    'member_login_pw' => $password,
                    'member_qq' => $qqnum
                ];
                $validate = Loader::validate('Member');
                $result = $validate->scene('edit_pwd')->check($data);
                if ($result !== true){
                    $this->error($validate->getError(), url("member/account/personinfo"));
                }
                $member = Member::get(session('MUserId'));
                $member->member_qq = $qqnum;
                $member->member_login_pw = substr(md5($password), 8, 16);
                $member->save();

                return $this->success("更新成功", 'member/account/personinfo');
            }
        }else{
            $member = Member::get(session('MUserId'));
            $this->assign('member', $member);
            return $this->fetch();
        }
    }

    /**
     * 邀请链接
     */
    public function mySpreadLine()
    {
        return $this->fetch();
    }

    /**
     * 升级代理
     */
    public function upMyVip()
    {
        $member = Member::get(session('MUserId'));
        //等级列表
        $rank = new Rank();
        $rankList = $rank->where('rank_id', '>', $member['member_rank'])->order('rank_id', 'asc')->select();

        $this->assign('member', $member);
        $this->assign('rankList', $rankList);
        return $this->fetch();
    }
}