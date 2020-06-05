<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/5/22
 * Time: 23:52
 */

/**
 * 支付回调
 */

namespace app\member\controller;

use think\Controller;
use malipay\malipay;

class Payment extends Controller
{
    /**
     * 异步通知地址
     */
    public function alipaynotify()
    {
        $alipaymode = model('alipaymodel');
        $notify_result = $alipaymode->verify_notify();
        if ($notify_result) {
            if ($notify_result['trade_status'] == 1) {
                //支付成功
                $out_trade_no = $notify_result['out_trade_no']; #商户订单号
                $trade_no = $notify_result['trade_no']; #交易凭据单号
                $total_fee = $notify_result['total_fee']; #涉及金额
                $order_type = $notify_result['order_type'];
                db('recharge_record')->where('recharge_trade_no', $out_trade_no)->update(['trade_no' => $trade_no, 'trade_body' => $order_type, 'recharge_state' => 2, 'recharge_time' => time()]);

            } else {
                //支付失败
            }
        }
    }

    /**
     * 跳转页面
     */
    public function alipayreturn()
    {
        $alipaymode = model('alipaymodel');
        $notify_result = $alipaymode->return_verify();
        if ($notify_result['trade_status'] == 1) {
            //支付成功
            $this->assign('tes', json_encode($notify_result));
            return $this->fetch();
        } else {
            //支付失败
        }
    }
}