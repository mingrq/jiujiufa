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
        if ($memberList) {
            ds_json_encode(10000, "获取会员列表成功", $memberList);
        } else {
            ds_json_encode(10001, "获取会员列表失败");
        }

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
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["member_addtime"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["member_addtime"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["member_addtime"] = ['<=', $dateend];
            }
        }


        $query = db('v_member')
            ->where($condition)
            ->order('member_id desc')
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
    public function updatepassword()
    {
        $id = input("param.memberid");
        $pw = input("param.password");
        $model = model('member');
        $result = $model->update_password($id, $pw);
        if ($result) {
            ds_json_encode(10000, "密码修改成功");
        } else {
            ds_json_encode(10001, "密码修改失败");
        }
    }

    /**
     * 奖罚
     *
     */
    public function rewardpunish()
    {
        $id = input("param.memberid");
        $rewardsmoney = input("param.rewardsmoney");
        $rewardscause = input("param.rewardscause");
        $model = model('member');
        $result = $model->money_change($id, $rewardsmoney, $rewardscause);
        if ($result) {
            ds_json_encode(10000, "奖罚成功");
        } else {
            ds_json_encode(10001, "奖罚失败");
        }
    }

    /**
     * 特殊价格
     * @return mixed
     */
    public function specialprice()
    {
        return $this->fetch();
    }

    /**充值记录*/
    public function rechargerecord()
    {
        return $this->fetch();
    }

    /**资金明细*/
    public function financialdetails()
    {
        return $this->fetch();
    }

    /**获取资金明细列表*/
    public function getfinancialdetailslist()
    {
        $member_id = input('param.member_id');
        $financialdetailslist = db('member')->where('member_id0', $member_id)->select();
        if ($financialdetailslist) {
            ds_json_encode(10000, "获取资金明细成功", $financialdetailslist);
        } else {
            ds_json_encode(10001, "获取资金明细失败");
        }
    }

    /**
     * 会员配置
     */
    public function memberconfigure()
    {
        if (!request()->isPost()) {
            return $this->fetch();
        } else {

        }
    }

    /**
     * 会员等级
     */
    public function memberrank()
    {
        $member_mod = model('member');
        $rankList = $member_mod->getmemberrank();
        if ($rankList) {
            ds_json_encode(10000, "获取会员等级列表成功", $rankList);
        } else {
            ds_json_encode(10001, "获取会员等级列表失败");
        }
    }

    /**
     * 搜索会员等级
     */
    public function serachmemberrank()
    {
        $condition = array();
        $serachtitle = input('param.serachtitle');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $serachtitle = '%' . trim(str_replace($find, $replace, $serachtitle)) . '%';
        if ($serachtitle && trim($serachtitle) != "") {
            $condition['rank_id|rank_name'] = ['like', $serachtitle];
        }

        $query = db('rank')
            ->where($condition)
            ->order('rank_id')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索会员等级成功", $query);
        } else {
            ds_json_encode(10001, "搜索会员等级失败");
        }
    }

    /**
     * 查询会员等级
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getrankinfo()
    {
        $query=db('rank')->where('rank_id',input('param.rankid'))->find();
        if ($query) {
            ds_json_encode(10000, "搜索会员等级成功", $query);
        } else {
            ds_json_encode(10001, "搜索会员等级失败");
        }
    }

    /**
     * 设置会员等级
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function setrankinfo()
    {
        $query=db('rank')->where('rank_id',input('param.rankid'))->update(["invite_upgrade"=>input('param.invite_upgrade'),"recharge_upgrade"=>input('param.recharge_upgrade')]);
        if ($query) {
            ds_json_encode(10000, "设置会员等级配置成功");
        } else {
            ds_json_encode(10001, "设置会员等级配置失败");
        }
    }
}