<?php

namespace app\member\controller;
use app\common\controller\MemberBase;

class Order extends MemberBase{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 礼品下单
     */
    public function payOrderPage()
    {
        return $this->fetch();
    }
}