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
        // 新品
        $newGoodsList = $goods->where('good_state', '=', 1)->order('good_id', 'desc')->paginate(10);

        $this->assign('newGoodsList', $newGoodsList);
        return $this->fetch();
    }
}
