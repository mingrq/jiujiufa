<?php
/**
 * 财务管理
 */

namespace app\member\controller;
use app\common\controller\MemberBase;

class Finance extends MemberBase{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 充值记录
     */
    public function rechargeRecord()
    {
        return $this->fetch();
    }
}