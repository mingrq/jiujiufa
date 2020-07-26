<?php
/**
 * 小礼品相关接口
 */

namespace app\api\controller;

use app\common\controller\ApiBase;

class Apigift extends ApiBase
{
    private $username = '';

    protected function _initialize()
    {
        parent::_initialize();
        $this->username = trim($this->request->post("username"));
    }

    /**
     * 获取小礼品
     * 加个分页 最多20条数据
     * pageNo 页码 从1开始
     * pageSize 返回礼品的数量，取值在1到20之间
     */
    public function giftList()
    {
        $pageNo = preg_replace("/[^0-9]/", "", $this->request->post("pageNo"));
        if (empty($pageNo)) {
            $pageNo = 1;
        }
        $pageSize = preg_replace("/[^0-9]/", "", $this->request->post("pageSize"));
        if (empty($pageSize)) {
            $pageNo = 20;
        }

        $member = db("member")->field('member_id')->where('member_login_name', '=', $this->username)->find();
        $whereGoods['vgoods.good_state'] = 1;
        $limit = ($pageNo - 1) * $pageSize . ',' . $pageNo * $pageSize;
        $specialpricelist = db('special_price')->where('member_id', $member['member_id'])->fetchSql(true)->select();
        $goodsList = db('v_goods')->alias('vgoods')->join('(' . $specialpricelist . ') `specialprice`', '`specialprice`.`kd_id` = `vgoods`.`kdId`', 'LEFT')->where($whereGoods)->field(['`vgoods`.*', '`specialprice`.`good_special_api_price`'])->order("vgoods.kdId asc")->limit($limit)->select();
        $total_count = db('v_goods')->where("good_state", "=", 1)->count('kdId');
        $item_count = count($goodsList);

        $items = array();
        foreach ($goodsList as $goods) {
            $costPrice = $goods['cost_price'] + $goods['good_api_price'];
            if (!empty($goods['good_special_api_price'])) {
                $costPrice = $goods['cost_price'] + $goods['good_special_api_price'];
            }
            array_push($items, array(
                "kdId" => $goods['kdId'],//快递id
                "goodId" => $goods['good_id'],//商品id
                "costPrice" => $costPrice,// 商品价格
                "goodsTitle" => $goods['goodsTitle'], // 商品标题
                "goodPic" => $goods['good_pic'], //商品主图
                "storeType" => $goods['wh_class'],//1 表示京东仓库，2 表示淘宝仓库，4 表示 pdd 仓库
                "kuaidiName" => $goods['wh_alias']//
            ));
        }
        $resultDate = array(
            'total_count' => $total_count,
            'item_count' => $item_count,
            'items' => $items
        );
        ds_json_encode(1, "成功", $resultDate);
    }
}