<?php
/**
 * 首页
 */

namespace app\index\controller;

use app\common\controller\FrontendBase;
use app\common\model\Goods;
use app\common\model\Member;

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
        $MUserId = session('MUserId');
        $mrank = 0;
        $member = Member::get($MUserId);
        if (empty($MUserId) || empty($member)) {
            // 爆款
            $hotProductOne = $goods->where('good_state', '=', 1)->where('classId',14)->order('good_price', 'asc')->limit(0, 4)->select();
            $hotProductTwo = $goods->where('good_state', '=', 1)->where('classId',14)->order('good_price', 'asc')->limit(4, 4)->select();
            $hotProductThree = $goods->where('good_state', '=', 1)->where('classId',14)->order('good_price', 'asc')->limit(8, 4)->select();

            // 新品
            $wherenp['good_state'] = 1;
            $wherenp['classId'] = 14;

            $newGoodsList = $goods->where($wherenp)->order('kdId', 'desc')->paginate(10);
        } else {
            $mrank = $member['member_rank'];
            if ($mrank == 1) {
                $hotProductOne = $goods->getIndexSpecialGoodsList($MUserId, 0, 4, 'specialprice.good_special_price asc');
                $hotProductTwo = $goods->getIndexSpecialGoodsList($MUserId, 4, 4, 'specialprice.good_special_price asc');
                $hotProductThree = $goods->getIndexSpecialGoodsList($MUserId, 8, 4, 'specialprice.good_special_price asc');

                // $newGoodsList = $goods->getSpecialGoodsList($MUserId, 10);
                $newGoodsList = $goods->getIndexSpecialGoodsList($MUserId, 0, 10, 14);
            } else if ($mrank == 2) {
                $hotProductOne = $goods->getIndexSpecialGoodsList($MUserId, 0, 4, 'specialprice.good_special_vip_price asc');
                $hotProductTwo = $goods->getIndexSpecialGoodsList($MUserId, 4, 4, 'specialprice.good_special_vip_price asc');
                $hotProductThree = $goods->getIndexSpecialGoodsList($MUserId, 8, 4, 'specialprice.good_special_vip_price asc');

                // $newGoodsList = $goods->getSpecialGoodsList($MUserId, 10);
                $newGoodsList = $goods->getIndexSpecialGoodsList($MUserId, 0, 10, 14);
            } else {
                $hotProductOne = $goods->where('good_state', '=', 1)->order('good_price', 'asc')->limit(0, 4)->select();
                $hotProductTwo = $goods->where('good_state', '=', 1)->order('good_price', 'asc')->limit(4, 4)->select();
                $hotProductThree = $goods->where('good_state', '=', 1)->order('good_price', 'asc')->limit(8, 4)->select();

                $wherenp['good_state'] = 1;
                $wherenp['classId'] = 14;
                $newGoodsList = $goods->where($wherenp)->order('kdId', 'desc')->paginate(10);
            }
        }

        $this->assign('mrank', $mrank);

        $this->assign('hotProductOne', $hotProductOne);
        $this->assign('hotProductTwo', $hotProductTwo);
        $this->assign('hotProductThree', $hotProductThree);

        $this->assign('newGoodsList', $newGoodsList);

        $articlemode = model('article');
        //礼品资讯
        $present = $articlemode->articlelist(['ac_id' => 1], ' *', 5, 'article_time desc');
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

        //轮播图
        $advertmodel = model('advertising');
        $banner = $advertmodel->getadvertislist(['ad_class' => 1], '*', 4, 'ad_add_time desc');
        $this->assign('banner', $banner);

        //爆款
        $faddish = $advertmodel->getadvertislist(['ad_class' => 2], '*', 1, 'ad_add_time desc');
        $this->assign('faddish', $faddish);
        return $this->fetch();
    }
}
