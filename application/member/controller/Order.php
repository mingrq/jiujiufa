<?php

namespace app\member\controller;
use app\common\controller\MemberBase;
use think\Db;

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
        // 仓库
        $warehouseList = \db('warehouse')->select();
        $this->assign('warehouseList', $warehouseList);

        return $this->fetch();
    }
}