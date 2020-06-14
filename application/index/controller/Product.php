<?php
/**
 * 礼品商城
 */

namespace app\index\controller;

use app\common\controller\FrontendBase;
use app\common\model\Goods;
use app\common\model\Member;
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

        $orderBy = ['cost_price' => "asc"];
        if ($oby == 1) {
            // 价格最低排序
            $orderBy = ['cost_price' => "asc"];
        } else if ($oby == 2) {
            // 价格最高排序
            $orderBy = ['cost_price' => "desc"];
        } else {
            $oby = 1;
        }

        // 获取所有的仓库
        $warehouseList = array();
        $warehouseClassList = db("wasehouse_class")->order('class_id', "asc")->select();
        foreach ($warehouseClassList as $key => $warehouseClass) {
            $warehouseList[$key]['classId'] = $warehouseClass['class_id'];
            $warehouseList[$key]['className'] = $warehouseClass['class_name'];
            $warehouseList[$key]['classRemark'] = $warehouseClass['class_remark'];
            $warehouseTemp = db("warehouse")->where("wh_class", "=", $warehouseClass['class_id'])->select();
            if (!empty($warehouseTemp) && count($warehouseTemp) > 0) {
                $warehouseList[$key]['children'] = $warehouseTemp;
            } else {
                $warehouseList[$key]['children'] = [];
            }
        }

        $whereGoods['good_state'] = 1;
        if (empty($ckid)) {
            // $ckid = 0;
            $ckid = $warehouseClassList[0]['class_id'];
            $whereGoods['classId'] = $ckid;
        }
        if ($ckid > 0) {
            $whereGoods['classId'] = $ckid;
        }

        $goods = new Goods();
        // 新品
        // $newGoodsList = $goods->where($whereGoods)->order($orderBy)->select();
        $MUserId = session('MUserId');
        $MUserName = session('MUserName');
        $mrank = 0;
        if (empty($MUserId) || empty($MUserName)) {
            $newGoodsList = $goods->where($whereGoods)->order($orderBy)->select();
        } else {
            $member = Member::get($MUserId);
            if (!empty($member) && !empty($member['member_rank'])) {
                $mrank = $member['member_rank'];
            }
            $newGoodsList = $goods->FrontGetSpecialGoodsList($MUserId, $ckid, $orderBy);
        }

        $this->assign('ckid', $ckid);
        $this->assign('oby', $oby);
        $this->assign('mrank', $mrank);
        $this->assign('warehouseList', $warehouseList);
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
}