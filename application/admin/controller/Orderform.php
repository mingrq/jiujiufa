<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/7
 * Time: 21:27
 */

namespace app\admin\controller;


class Orderform extends AdminBaseController
{
    public function index()
    {
        return $this->fetch('orderform');
    }

    /**
     * 获取订单列表
     */
    public function orderlist()
    {
        $order_mod = model('order');
        $orderList = $order_mod->getOrderlist();

        if ($orderList){
            ds_json_encode(10000, "获取订单列表成功", $orderList);
        }else{
            ds_json_encode(10001, "获取订单列表失败");
        }
    }

    /**
     * 获取订单列表
     */
    public function searchorderlist()
    {

    }

}