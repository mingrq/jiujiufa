<?php

namespace app\member\controller;
use app\common\controller\MemberBase;
use app\common\model\Goods;
use app\common\model\Member;
use app\common\model\Warehouse;
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
        $classid = $this->request->param('classid') ? preg_replace('/[^0-9]/', '', $this->request->param('classid')): 0;
        $warehouse = Warehouse::get($classid);
        $goodsList = array();
        if (!empty($warehouse) && !empty($warehouse['wh_id'])){
            // 调用仓库快递数据
            $goods = new Goods();
            $whereGoods['classId'] = $classid;
            $whereGoods['good_state'] = 1;
            $goodsList = $goods->where($whereGoods)->select();
        }
        // 所有仓库
        $warehouseList = \db('warehouse')->select();

        $member = Member::get(session('MUserId'));

        $this->assign('classid', $classid);
        $this->assign('memberrank', $member['member_rank']);// 代理级别
        $this->assign('goodsList', $goodsList);
        $this->assign('warehouseList', $warehouseList);

        return $this->fetch();
    }
}