<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/2
 * Time: 21:36
 */

namespace app\admin\controller;

use think\Db;

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
    public function editprice()
    {
        $field = input('param.field');
        $kdId = input('param.kdId');
        $price = input('param.price');
        $qurey = db('goods')->where('kdId', $kdId)->setField($field, $price);
        if ($qurey) {
            ds_json_encode(10000, "修改价格成功");
        }
        ds_json_encode(10001, "修改价格失败");
    }

    /**
     * 批量设置价格
     */

    public function batcheditprice()
    {

        $good_price = input('param.good_price');
        $good_vip_price = input('param.good_vip_price');
        $good_api_price = input('param.good_api_price');

        //$qurey = db('goods')->update(['good_price'=>$good_price,'good_vip_price'=>$good_vip_price,'good_api_price'=>$good_api_price]);
        //$qurey = db('goods')->setField(['good_price'=>3,'good_vip_price'=>2,'good_api_price'=>1]);
        // $qurey =Db::execute("UPDATE `lb_goods` SET `good_price` = ".$good_price.",`good_vip_price` = ".$good_vip_price.",`good_api_price` = ".$good_api_price);
        $qurey = Db::execute("update `lb_goods` set `good_price` = 3,`good_vip_price` = 2,`good_api_price` = 1");

        if ($qurey) {
            ds_json_encode(10000, "修改价格成功");
        }
        ds_json_encode(10001, "修改价格失败");
    }

    /**
     * 搜索礼品
     */
    public function serachgoodslist()
    {
        $condition = array();
        $goodtitle = input('param.goodtitle');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $goodtitle = '%' . trim(str_replace($find, $replace, $goodtitle)) . '%';
        if ($goodtitle && trim($goodtitle) != "") {
            $condition['goodsTitle'] = ['like', $goodtitle];
        }

        $warehouse = input('param.warehouse');
        if ($warehouse) {
            $condition["classId"] = $warehouse;
        }

        $query = db('v_goods')
            ->where($condition)
            ->order('kdId desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索商品成功", $query);
        } else {
            ds_json_encode(10001, "搜索商品失败");
        }
    }


    /**
     * 获取仓库信息列表
     */
    public function getwarehouselist()
    {
        $model = model('warehouse');
        $warehouses = $model->getWarehouselist();
        if ($warehouses) {
            ds_json_encode(10000, "获取仓库列表成功", $warehouses);
        } else {
            ds_json_encode(10001, "获取仓库列表失败");
        }
    }


    /**
     * 更新商品列表
     */
    public function goodsupdate()
    {
        $mod = model('goodsupdate');
        $mod->goods_update();
    }


}