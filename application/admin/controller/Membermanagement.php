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
     * 特殊价格页面
     * @return mixed
     */
    public function specialprice()
    {
        $id = input("param.mid");
        $this->assign("mid", $id);
        return $this->fetch();
    }

    /**
     * 获取特殊价格商品列表
     */
    public function getgoodslist()
    {

        $mode = model('goods');
        $condition = array("good_state" => 1);
        $query = $mode->getGoodsList($condition);
        if ($query) {
            ds_json_encode(10000, "获取商品列表成功", $query);
        } else {
            ds_json_encode(10001, "获取商品列表失败");
        }
    }

    /**
     * 设置特殊价格
     */
    public function editspecialprice()
    {
        $mid = input('param.mid');
        $field = input('param.field');
        $kdId = input('param.kdId');
        $price = input('param.price');
        $qurey = db('goods')->where('kdId', $kdId)->value($field);
        if ($qurey != $price) {
            //需要设置特殊价格
            //1、查询特殊价格表中是否有这个商品
            //2、有：修改  无：新增
            $ishasspecial =db('special_price')->where('member_id',$mid)->where('kd_id',$kdId)->find();
           if ($ishasspecial){
               //修改特殊价格
           }else{
               //新增特殊价格
           }
            ds_json_encode(10000, "修改价格成功");
        }
        ds_json_encode(10001, "修改价格失败");
    }

    /**充值记录*/
    public function rechargerecord()
    {
        $member_id = input('param.mid');
        if (request()->isPost()) {
            $condition = array();
            $condition['recharge_member_id'] = $member_id;
            $member_mode = model('member');
            $query = $member_mode->getRechargeRecord($condition);
            if ($query) {
                ds_json_encode(10000, "获取充值记录成功", $query);
            } else {
                ds_json_encode(10001, "获取充值记录失败");
            }
        } else {

            $this->assign("mid", $member_id);
            return $this->fetch();
        }
    }

    /**
     * 搜索充值记录
     */
    public function serachrechargerecord()
    {
        $condition = array();
        $condition['recharge_member_id'] = input('param.mid');

        $serach_param = input('param.serach_param');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $serach_param = '%' . trim(str_replace($find, $replace, $serach_param)) . '%';
        if ($serach_param && trim($serach_param) != "") {
            $condition['recharge_trade_no'] = ['like', $serach_param];
        }

        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["recharge_time"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["recharge_time"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["recharge_time"] = ['<=', $dateend];
            }
        }

        $query = db('recharge_record')
            ->where($condition)
            ->order('recharge_time desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索充值记录成功", $query);
        } else {
            ds_json_encode(10001, "搜索充值记录失败");
        }
    }

    /**资金明细*/
    public function financialdetails()
    {
        $member_id = input('param.mid');
        if (request()->isPost()) {
            $condition = array();
            $condition['member_id'] = $member_id;
            $member_mode = model('member');
            $query = $member_mode->getMoneychangeRecord($condition);
            if ($query) {
                ds_json_encode(10000, "获取资金明细成功", $query);
            } else {
                ds_json_encode(10001, "获取资金明细失败");
            }
        } else {
            $this->assign("mid", $member_id);
            return $this->fetch();
        }

    }

    /**搜索资金明细列表*/
    public function serachfinancialdetailslist()
    {
        $condition = array();
        $condition['member_id'] = input('param.mid');

        $serach_param = input('param.serach_param');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $serach_param = '%' . trim(str_replace($find, $replace, $serach_param)) . '%';
        if ($serach_param && trim($serach_param) != "") {
            $condition['change_cause'] = ['like', $serach_param];
        }

        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["change_time"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["change_time"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["change_time"] = ['<=', $dateend];
            }
        }

        $query = db('moneychange_record')
            ->where($condition)
            ->order('change_time desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索变更明细成功", $query);
        } else {
            ds_json_encode(10001, "搜索变更明细失败");
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
        $query = db('rank')->where('rank_id', input('param.rankid'))->find();
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
        $query = db('rank')->where('rank_id', input('param.rankid'))->update(["invite_upgrade" => input('param.invite_upgrade'), "recharge_upgrade" => input('param.recharge_upgrade')]);
        if ($query) {
            ds_json_encode(10000, "设置会员等级配置成功");
        } else {
            ds_json_encode(10001, "设置会员等级配置失败");
        }
    }
}