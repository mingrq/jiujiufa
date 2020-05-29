<?php

namespace app\member\controller;

use app\common\controller\MemberBase;
use app\common\model\Goods;
use app\common\model\Member;
use app\common\model\Warehouse;
use think\Db;

class Order extends MemberBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 礼品下单
     */
    public function payOrderPage()
    {
        $kdId = $this->request->param('gid') ? preg_replace('/[^0-9]/', '', $this->request->param('gid')) : 0;
        $goods = Goods::get($kdId);
        $goodsList = array();
        $classid = 0;
        if (!empty($goods) && !empty($goods['classId'])) {
            // $classid = $this->request->param('classid') ? preg_replace('/[^0-9]/', '', $this->request->param('classid')) : 0;
            $classid = $goods['classId'];
            $warehouse = Warehouse::get($classid);
            if (!empty($warehouse) && !empty($warehouse['wh_id'])) {
                // 调用仓库快递数据
                $goodsT = new Goods();
                $whereGoods['classId'] = $classid;
                $whereGoods['good_state'] = 1;
                $goodsList = $goodsT->where($whereGoods)->select();
            }else{

            }
        }else{
            $kdId = 0;
        }

        // 所有仓库
        $warehouseList = \db('warehouse')->select();

        $member = Member::get(session('MUserId'));

        $this->assign('classid', $classid);
        $this->assign('kdId', $kdId);
        $this->assign('memberrank', $member['member_rank']);// 代理级别
        $this->assign('goodsList', $goodsList);
        $this->assign('warehouseList', $warehouseList);

        return $this->fetch();
    }

    /**
     * 根据classid（仓库ID）获取商品
     */
    public function getGoodsByCkId()
    {
        $goodsList = array();
        if ($this->request->isPost()) {
            $ckid = $this->request->post('ckid') ? preg_replace('/[^0-9]/', '', $this->request->post('ckid')) : 0;
            if (!empty($ckid)) {
                $goods = new Goods();
                $whereGoods['classId'] = $ckid;
                $whereGoods['good_state'] = 1;
                $goodsList = $goods->where($whereGoods)->select();
            }
        }
        $member = Member::get(session('MUserId'));
        if (empty($member) || empty($member['member_rank'])){
            ds_json_encode(10001, "获取错误");
        }else{
            $result = array(
                "mrank" => $member['member_rank'],
                "goodsList" => $goodsList
            );
            ds_json_encode(10000, "商品获取成功", $result);
        }
    }
}