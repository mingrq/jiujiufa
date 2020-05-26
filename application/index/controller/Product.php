<?php
/**
 * 礼品商城
 */

namespace app\index\controller;

use app\common\controller\FrontendBase;
use app\common\model\Goods;
use app\common\model\Warehouse;

class Product extends FrontendBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 商城首页
     */
    public function index()
    {
        // 仓库
        $warehouse = new Warehouse();
        $warehouselist = $warehouse->getWarehouselist();

        $goods = new Goods();
        // 新品
        $newGoodsList = $goods->where('good_state', '=', 1)->order('kdId', 'desc')->select();

        $this->assign('warehouselist', $warehouselist);
        $this->assign('newGoodsList', $newGoodsList);
        return $this->fetch();
    }
}