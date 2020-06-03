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

        //公告
        $noticemode = model('notice');
        $noticelist = $noticemode->noticelist(null, '*', 4, 'notice_create_time desc');
        $this->assign('noticelist', $noticelist);
        return $this->fetch();
    }
}
