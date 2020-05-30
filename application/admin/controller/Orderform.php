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
     * 搜索订单列表
     */
    public function searchorderlist()
    {
        $condition = array();

        $serach_param = input('param.searchparam');
        $find = array('\\', '/', '%', '_', '&');
        $replace = array('\\\\', '\\/', '\%', '\_', '\&');
        $serach_param = '%' . trim(str_replace($find, $replace, $serach_param)) . '%';
        if ($serach_param && trim($serach_param) != "") {
            $condition['order_no|member_mobile|tracking_number|consignee_name'] = ['like', $serach_param];
        }

        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');

        if ($dateatart && $dateend) {
            $condition["create_time"] = ['between', [$dateatart, $dateend]];
        } else {
            if ($dateatart) {
                $condition["create_time"] = ['>=', $dateatart];
            }

            if ($dateend) {
                $condition["create_time"] = ['<=', $dateend];
            }
        }

        $query = db('v_order')
            ->where($condition)
            ->order('create_time desc')
            ->paginate(100);
        if ($query) {
            ds_json_encode(10000, "搜索订单列表成功", $query);
        } else {
            ds_json_encode(10001, "搜索订单列表失败");
        }
    }

}