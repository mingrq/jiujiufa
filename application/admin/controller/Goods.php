<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/2
 * Time: 21:36
 */

namespace app\admin\controller;


class Goods extends AdminBaseController
{

    public function index()
    {
        return $this->fetch('goods');
    }

    /**
     * 获取商品列表
     */
    public function getgoodslist()
    {
        $mode = model('goods');
        $condition = array("good_state" => 1);
        $query = $mode->getGoodsList($condition);
        if ($query) {
            ds_json_encode(10000, "获取商品列表成功", $query);
        } else {
            ds_json_encode(10001, "获取商品列表失败");
        }
    }


    /**
     * 设置价格
     */
    public function editprice(){

    }


    public function test()
    {
        $mod = model('goodsupdate');
        $mod->goods_update();
    }


}