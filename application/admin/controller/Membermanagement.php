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
        return $this->fetch('memberlist');
    }

    /**
     * 获取会员列表
     */
    public function memberlist()
    {
        $member_mod = model('member');
        $memberList = $member_mod->getMemberList();
        ds_json_encode(10000, "获取会员列表成功", $memberList);
    }

    /**
     * 搜索用户
     */
    public function serachmemberlist()
    {
        $condition = array();
        $accountorqq = input('param.accountorqq');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $accountorqq = '%' . trim(str_replace($find, $replace, $accountorqq)) . '%';
        if ($accountorqq && trim($accountorqq) != "") {
            $condition['member_mobile|member_qq'] = ['like', $accountorqq];
        }

        $referrer = input('param.referrer');
        $member = db('member')->where("member_mobile", $referrer)->find();
        if ($member) {
            $condition["member_referrer"] = $member['member_id'];
        }

        $rank = input('param.rank');
        if ($rank) {
            $condition["member_rank"] = $rank;
        }

        $dateatart = input('param.dateatart');
        if ($dateatart) {
            $condition["member_addtime"] = ['EGT', $dateatart];
        }

        $dateend = input('param.dateend');
        if ($dateend) {
            $condition["member_addtime"] = ['ELT', $dateatart];
        }

        $query = db('v_member')
            ->where($condition)
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索用户成功", $query);
        } else {
            ds_json_encode(10001, "搜索用户失败");
        }
    }

    /**
     * 用户修改密码
     * @param $id
     * @param $pw
     */
    public function update_password()
    {
        $id=input("param.memberid");
        $pw=input("param.password");
        $model = model('member');
        $result = $model->update_password($id, $pw);
        if ($result) {
            ds_json_encode(10000, "密码修改成功");
        } else {
            ds_json_encode(10001, "密码修改失败");
        }
    }
}