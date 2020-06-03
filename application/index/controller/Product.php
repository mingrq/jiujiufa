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
        $ckid = $this->request->param("ckid") ? preg_replace('/[^0-9]/', '', $this->request->param("ckid")) : 0;
        // 排序
        $oby = $this->request->param("oby") ? preg_replace('/[^0-9]/', '', $this->request->param("oby")) : 0;

        $orderBy = ['kdId' => "desc"];
        if ($oby == 1) {
            // 价格最低排序
            $orderBy = ['good_price' => "asc"];
        } else if ($oby == 2) {
            // 价格最高排序
            $orderBy = ['good_price' => "desc"];
        } else {
            $oby = 0;
        }

        $whereGoods['good_state'] = 1;
        if (empty($ckid)) {
            $ckid = 0;
        }
        if ($ckid > 0) {
            $whereGoods['classId'] = $ckid;
        }


        // 仓库
        $warehouse = new Warehouse();
        $warehouselist = $warehouse->getWarehouselist();

        $goods = new Goods();
        // 新品
        $newGoodsList = $goods->where($whereGoods)->order($orderBy)->select();

        $this->assign('ckid', $ckid);
        $this->assign('oby', $oby);
        $this->assign('warehouselist', $warehouselist);
        $this->assign('newGoodsList', $newGoodsList);

        $articlemode = model('article');
        //礼品资讯
        $present = $articlemode->articlelist(['ac_id' => 1], '*', 5, 'article_time desc');
        $this->assign('present', $present);
        //电商资讯
        $commerce = $articlemode->articlelist(['ac_id' => 2], '*', 5, 'article_time desc');
        $this->assign('commerce', $commerce);
        //常见问题
        $question = $articlemode->articlelist(['ac_id' => 3], '*', 5, 'article_time desc');
        $this->assign('question', $question);
        return $this->fetch();
    }

    /**
     * 筛选
     */
    public function screening()
    {
        $ckid = $this->request->param("ckid") ? preg_replace('/[^0-9]/', '', $this->request->param("ckid")) : 0;
        $orderby = $this->request->param("orderby") ? preg_replace('/[^0-9]/', '', $this->request->param("orderby")) : 0;

    }
}