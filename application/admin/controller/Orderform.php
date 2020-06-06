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

        if ($orderList) {
            ds_json_encode(10000, "获取订单列表成功", $orderList);
        } else {
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

    /**
     * 导出订单数据
     */
    public function deriveorderlist()
    {
        $dateatart = input('param.dateatart');
        $dateend = input('param.dateend');
        $condition = array();
        $condition["order_state"] = ['>', 1];
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
        $order_list = db('v_order')->field(['member_mobile',
            'goodsTitle',
            'order_pay',
            'express_name',
            'merchant_order_no',
            'tracking_number',
            'shipping_address',
            'consignee_name',
            'consignee_phone',
            'create_time',
            'order_state'])->where($condition)->select();
        $orders=array();
        for ($i = 0; $i < count($order_list); $i++) {
            $order=$order_list[$i];
            if ($order['order_state'] == 2) {
                $order['order_state'] = '已付款';
            }
            if ($order['order_state'] == 3) {
                $order['order_state'] = '已发货';
            }
            if ($order['order_state'] == 4) {
                $order['order_state'] = '已取消';
            }
            $orders[]=$order;
        }
        if ($orders) {
            ds_json_encode(10000, "导出会员数据成功", $orders);
        } else {
            ds_json_encode(10001, "导出会员数据失败");
        }
    }

}