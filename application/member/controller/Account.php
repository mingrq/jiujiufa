<?php

namespace app\member\controller;

use app\common\controller\MemberBase;
use app\common\model\Advertising;
use app\common\model\Member;
use app\common\model\Rank;
use think\Loader;
use think\Request;

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
        //轮播图 上
        $advertmodel = new Advertising();
        $banner = $advertmodel->getadvertislist(['ad_class' => 4], '*', 4, 'ad_add_time desc');
        $this->assign('banner', $banner);

        //轮播图 下
        $advertmodel = new Advertising();
        $bannerc = $advertmodel->getadvertislist(['ad_class' => 5], '*', 4, 'ad_add_time desc');
        $this->assign('bannerc', $bannerc);

        $memeber = Member::get(session('MUserId'));
        $this->assign('member', $memeber);
        return $this->fetch();
    }

    /**
     * 修改个人中心 个人信息
     */
    public function updatePersonInfo()
    {
        if ($this->request->isPost()) {
            $password = trim($this->request->post('password'));
            $qqnum = trim($this->request->post('qqnum'));
            if (empty($password)) {
                // 不修改密码
                $data = [
                    'member_qq' => $qqnum
                ];
                $validate = Loader::validate("Member");
                $result = $validate->scene('edit')->check($data);
                if ($result !== true) {
                    $this->error($validate->getError(), url("member/account/personinfo"));
                }
                $memeber = new Member;
                $memeber->save($data, ['member_id' => session('MUserId')]);

                ds_json_encode(10000, "更新成功");
                // return $this->success("更新成功", 'member/account/personinfo');
            } else {
                // 修改密码
                $data = [
                    'member_login_pw' => $password,
                    'member_qq' => $qqnum
                ];
                $validate = Loader::validate('Member');
                $result = $validate->scene('edit_pwd')->check($data);
                if ($result !== true) {
                    $this->error($validate->getError(), url("member/account/personinfo"));
                }
                $member = Member::get(session('MUserId'));
                $member->member_qq = $qqnum;
                $member->member_login_pw = substr(md5($password), 8, 16);
                $member->save();

                ds_json_encode(10000, "更新成功");
                //return $this->success("更新成功", 'member/account/personinfo');
            }
        } else {
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
        $request = Request::instance();
        $domain = $request->domain();
        $member = Member::get(session('MUserId'));
        $this->assign('member', $member);
        $this->assign('domain', $domain);

        $rank = new Rank();
        $rankList = $rank->where('rank_id', '>', $member['member_rank'])->order('rank_id', 'asc')->select();
        $this->assign('rankList', $rankList);
        return $this->fetch();
    }

    /**
     * 升级代理
     */
    public function upMyVip()
    {
        $member = db('v_member')->where('member_id', session('MUserId'))->find();
        //等级列表
        $rank = new Rank();
        $rankList = $rank->where('rank_id', '>', $member['member_rank'])->order('rank_id', 'asc')->select();

        $this->assign('member', $member);
        $this->assign('rankList', $rankList);

        $request = Request::instance();
        $domain = $request->domain();
        $this->assign('domain', $domain);
        return $this->fetch();
    }

    /**
     * 余额充值
     */
    public function recharge()
    {
        if (request()->isPost()) {
            $money = input('post.money');
            $memberid = session('MUserId');
            $orderno = 'C' . chr(rand(65, 90)) . $memberid . time() . random(3, [111, 999]);
            $condition = array();
            $condition['recharge_trade_no'] = $orderno;
            $condition['recharge_member_id'] = $memberid;
            $condition['recharge_money'] = $money;

            $query = db('recharge_record')->insert($condition);
            if ($query) {
                $alipaymode = model('alipaymodel');
                $alipaymode->payform($orderno,$money,'余额充值','账号余额充值');
            }

        } else {

            return $this->fetch();
        }
    }

}