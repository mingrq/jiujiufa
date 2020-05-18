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
        $moneychangeRecord = new MoneychangeRecord();
        $recordList = $moneychangeRecord->where("member_id", '=', session('MUserId'))->paginate(20);
        $page = $recordList->render();

        $this->assign('recordList', $recordList);
        $this->assign('page', $page);

        return $this->fetch();
    }
}