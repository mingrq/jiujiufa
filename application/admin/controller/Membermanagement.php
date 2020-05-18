<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/6
 * Time: 23:04
 */

/**
 * 用户管理
 */
namespace app\admin\controller;


class Membermanagement extends AdminBaseController
{

    public function index()
    {
//        $member_mod = model('member');
//        $field = ['member_id', 'member_login_name', 'member_balance', 'member_referrer', 'member_mobile', 'member_qq', 'member_invite_count', 'member_rank', 'member_addtime'];
//        $memberList = $member_mod->getMemberList(null, $field, 100);
//        $this->assign('memberlist', $memberList);
        return $this->fetch('memberlist');
    }

    /**
     * 获取会员列表
     */
    public function memberlist()
    {

        $member_mod = model('member');
        $memberList = $member_mod->getMemberList(null, '*', 100);
        ds_json_encode(10000, "获取会员列表成功", $memberList);
    }


}