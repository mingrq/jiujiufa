<?php
/**
 * 财务管理
 */

namespace app\member\controller;

use app\common\controller\MemberBase;
use app\common\model\MoneychangeRecord;
use app\common\model\RechargeRecord;

class Finance extends MemberBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 充值记录
     */
    public function rechargeRecord()
    {
        if (request()->isPost()) {
            $condition = array();
            $condition['recharge_state'] = 2;
            $condition['recharge_member_id'] = session('MUserId');
            $member_mode = model('member');
            $query = $member_mode->getRechargeRecord($condition);
            if ($query) {
                ds_json_encode(10000, "获取充值记录成功", $query);
            } else {
                ds_json_encode(10001, "获取充值记录失败");
            }
        } else {
            $code = $this->request->param("code");
            if ($code == 200) {
                $this->assign("code", 200);
            } else if ($code == 400) {
                $this->assign("code", 400);
            } else {
                $this->assign("code", 404);
            }
            return $this->fetch();
        }
    }


    /**
     * 搜索充值记录
     */
    public function serachrechargerecord()
    {
        $condition = array();
        $condition['recharge_state'] = 2;
        $condition['recharge_member_id'] = session('MUserId');

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


    /**
     * 资金明细
     */

    public function cashRecord()
    {
        if (request()->isPost()) {
            $condition = array();
            $condition['member_id'] = session('MUserId');
            $member_mode = model('member');
            $query = $member_mode->getMoneychangeRecord($condition);
            if ($query) {
                ds_json_encode(10000, "获取资金明细成功", $query);
            } else {
                ds_json_encode(10001, "获取资金明细失败");
            }
        } else {
            return $this->fetch();
        }
    }

    /**
     *搜索资金明细
     */
    public function serachcashrecord()
    {
        if (request()->isPost()) {
            $condition = array();
            $dateatart = input('post.dateatart');
            $dateend = input('post.dateend');

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
                ds_json_encode(10000, "搜索资金明细成功", $query);
            } else {
                ds_json_encode(10001, "搜索资金明细失败");
            }
        } else {
            ds_json_encode(10001, "搜索资金明细失败");
        }
    }
}