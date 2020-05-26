<?php
/**
 * 首页
 */

namespace app\index\controller;

use app\common\controller\FrontendBase;
use app\common\model\Goods;

class Index extends FrontendBase
{
    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 首页
     */
    public function index()
    {
        $goods = new Goods();
        // 爆款
        $hotProductOne = $goods->where('good_state', '=', 1)->order('good_price', 'asc')->limit(0, 4)->select();
        $hotProductTwo = $goods->where('good_state', '=', 1)->order('good_price', 'asc')->limit(4, 4)->select();
        $hotProductThree = $goods->where('good_state', '=', 1)->order('good_price', 'asc')->limit(8, 4)->select();
        // 新品
        $newGoodsList = $goods->where('good_state', '=', 1)->order('kdId', 'desc')->paginate(10);

        $this->assign('hotProductOne', $hotProductOne);
        $this->assign('hotProductTwo', $hotProductTwo);
        $this->assign('hotProductThree', $hotProductThree);

        $this->assign('newGoodsList', $newGoodsList);
        return $this->fetch();
    }
}
