<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/1
 * Time: 20:18
 */

namespace app\admin\controller;


class Home extends AdminBaseController
{
    public function home()
    {
        $workordercount = db('workorder')->where('wo_state', 1)->count();
        $membercount = db('member')->count();
        $warehousecount = db('warehouse')->count();
        $orderpaycount = db('order')->where('order_state', 2)->count();
        $ordernotpaycount = db('order')->where('order_state', 1)->count();
        $orderdeliverpaycount = db('order')->where('order_state', 3)->count();
        $goodscount = db('goods')->count();
        $goodsasalecount = db('goods')->where('good_state', 1)->count();
        $paysum = db('recharge_record')->sum('recharge_money');
        $noticecount = db('notice')->count();
        $articlecount = db('article')->count();

        $this->assign('workordercount', $workordercount);
        $this->assign('warehousecount', $warehousecount);
        $this->assign('membercount', $membercount);
        $this->assign('orderpaycount', $orderpaycount);
        $this->assign('ordernotpaycount', $ordernotpaycount);
        $this->assign('orderdeliverpaycount', $orderdeliverpaycount);
        $this->assign('goodscount', $goodscount);
        $this->assign('goodsasalecount', $goodsasalecount);
        $this->assign('paysum', $paysum);
        $this->assign('noticecount', $noticecount);
        $this->assign('articlecount', $articlecount);
        return $this->fetch();
    }
}