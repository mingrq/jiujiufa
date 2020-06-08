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
        //$baseordercount = db('base_order')->where('bo_state', 1)->count();

        $membercount = db('member')->count();
        $warehousecount = db('warehouse')->count();
        $orderpaycount = db('order')->where('order_state', 2)->count();
        $goodscount = db('goods')->count();
        $goodsasalecount = db('goods')->where('good_state', 1)->count();
        $paysum = db('recharge_record')->where('recharge_state', 2)->sum('recharge_money');
        //注册人数统计
        $todaymember = db('member')->whereTime('member_addtime', 'd')->count();
        $weekmember = db('member')->whereTime('member_addtime', 'w')->count();
        $monthmember = db('member')->whereTime('member_addtime', 'm')->count();
        $lastmonthmember = db('member')->whereTime('member_addtime', 'last month')->count();
        //订单数量统计
        $todayorder = db('order')->where('order_state','>',1)->whereTime('create_time', 'd')->count();
        $yesterdayorder = db('order')->where('order_state','>',1)->whereTime('create_time', 'yesterday')->count();
        $monthorder = db('order')->where('order_state','>',1)->whereTime('create_time', 'm')->count();
        $lastmonthorder = db('order')->where('order_state','>',1)->whereTime('create_time', 'last month')->count();

        //财务信息统计
        $todayrecharge = db('recharge_record')->where('recharge_state',2)->whereTime('recharge_time', 'd')->sum('recharge_money');
        $yesterdayrecharge = db('recharge_record')->where('recharge_state',2)->whereTime('recharge_time', 'yesterday')->sum('recharge_money');
        $monthrecharge = db('recharge_record')->where('recharge_state',2)->whereTime('recharge_time', 'm')->sum('recharge_money');
        $lastmonthrecharge = db('recharge_record')->where('recharge_state',2)->whereTime('recharge_time', 'last month')->sum('recharge_money');


        $this->assign('workordercount', $workordercount);
        $this->assign('warehousecount', $warehousecount);
        $this->assign('membercount', $membercount);
        $this->assign('orderpaycount', $orderpaycount);

        $this->assign('goodscount', $goodscount);
        $this->assign('goodsasalecount', $goodsasalecount);
        $this->assign('paysum', $paysum);

        $this->assign('todaymember', $todaymember);
        $this->assign('weekmember', $weekmember);
        $this->assign('monthmember', $monthmember);
        $this->assign('lastmonthmember', $lastmonthmember);

        $this->assign('todayorder', $todayorder);
        $this->assign('yesterdayorder', $yesterdayorder);
        $this->assign('monthorder', $monthorder);
        $this->assign('lastmonthorder', $lastmonthorder);

        $this->assign('todayrecharge', $todayrecharge);
        $this->assign('yesterdayrecharge', $yesterdayrecharge);
        $this->assign('monthrecharge', $monthrecharge);
        $this->assign('lastmonthrecharge', $lastmonthrecharge);
        return $this->fetch();
    }
}