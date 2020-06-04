<?php
/**
 * Created by PhpStorm.
 * User: 002
 * Date: 2020/5/26
 * Time: 15:09
 */

namespace app\common\model;


use malipay\malipay;
use think\Model;

/**
 * Class Alipay 阿里支付
 * @package app\common\model
 */
class AlipayModel extends Model
{


    /**
     * 支付的请求地址
     * @param null $out_trade_no 商户订单号，商户网站订单系统中唯一订单号，必填
     * @param null $api_pay_amount 付款金额，必填
     * @param null $subject 订单名称，必填
     * @param null $order_type 商品描述，可空
     * @return int
     */
    public function payform($out_trade_no = null, $api_pay_amount = null, $subject = null, $order_type = null)
    {
        if (!trim($out_trade_no) || !trim($api_pay_amount) || !trim($subject) || !trim($order_type)) {
            return 10004;
        }
        $order_info = array();
        $order_info['out_trade_no'] = $out_trade_no;
        $order_info['api_pay_amount'] = $api_pay_amount;
        $order_info['subject'] = $subject;
        $order_info['order_type'] = $order_type;
        $payment_info = array();
        $app_id = db('config')->where('config_code', 'alipay_app_id')->value('config_value');
        $merchant_private_key = db('config')->where('config_code', 'alipay_merchant_private_key')->value('config_value');
        $alipay_public_key = db('config')->where('config_code', 'alipay_public_key')->value('config_value');
        if (!$app_id || !$merchant_private_key || !$alipay_public_key) {
            return 10003;
        }
        $payment_info['app_id'] = $app_id;
        $payment_info['merchant_private_key'] = $merchant_private_key;
        $payment_info['alipay_public_key'] = $alipay_public_key;
        $malipay = new malipay($payment_info);
        $malipay->pay($order_info);
    }


    /**
     * 同步返回验证
     * @return array
     */
    public function return_verify()
    {

    }
}