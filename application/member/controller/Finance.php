<?php
/**
 * 财务管理
 */

namespace app\member\controller;

use app\common\controller\MemberBase;
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
        $recordList = $rechargeRecord->where('recharge_member_id', '=', session('MUserId'))->select();
        $this->assign('recordList', $recordList);
        return $this->fetch();
    }

    /**
     * 资金明细
     */
    public function cashRecord()
    {
        return $this->fetch();
    }
}