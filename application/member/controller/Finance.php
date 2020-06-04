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
        $rechargeRecord = new RechargeRecord();
        $recordList = $rechargeRecord->where('recharge_member_id', '=', session('MUserId'))->paginate(1);
        $page = $recordList->render();
        $this->assign('recordList', $recordList);
        $this->assign('page', $page);
        return $this->fetch();
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