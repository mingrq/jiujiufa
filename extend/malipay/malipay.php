<?php
/**
 * Created by PhpStorm.
 * User: ming
 * Date: 2020/3/17
 * Time: 20:08
 */
require_once dirname(__FILE__) . '/pagepay/service/AlipayTradeService.php';
require_once dirname(__FILE__) . '/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';
require_once dirname(__FILE__) . '/pagepay/buildermodel/AlipayTradeQueryContentBuilder.php';
require_once dirname(__FILE__) . '/pagepay/buildermodel/AlipayTradeRefundContentBuilder.php';
class malipay
{

    private $config;

    public function __construct()
    {
        $this->config = array(
            //应用ID,您的APPID。
            'app_id' => "2016101700710228",

            //商户私钥
            'merchant_private_key' => "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCIrkC7FClgGnJNMqwyxjOrZGdE9MPtAzHk9/RNJjY1EKW11IecGOoxsb8oTUPjocBZYsljK54Wd2pVNgg0n7edEcLoVjzoZVXjkinnVo7JFJX0UMePmWG4nF5JME1karRsN83VwjE7If1gGoHueYCIZOMpb/6aQOfftkG8M1L8B6Syf+6CTgMAhAMK6nZMe02ASjWFfjdMHpw5yryE6c8KDzw+7sOHAfr8Bil8CZhVWjshd4Q1oQrVeK3c5NqCgHw5wUxieaMznaswSg4cvmjlCDa0rVNbG98pOp5TTm2szzVvys7YMiJqBrN+xkKdGE8jmFgQNMAfMssEr3KOddSrAgMBAAECggEAL+Ntxp5PyN47QhUJBFkxbVGmZSClLPu7lY/Sxt0mjP0iMk0ennCUTYkLguFfcfgQXsEf8mEr6I86cFSYF2gGez/n3GOqv3oR18Q0bluDd6yAxbMv2H5TIM2Ys2f7Fb3VLT14HvcFLMTB89QVxipIAIHonXIh8IXhOB6xdXE+GBNKaRmkzIxz8eWEd7ZTDSbZkotSFQiLVC2AvUFee8nBTrZgaFzZ0qKba3zDR8DNgoWXzkxBEh6yBjAPVlyu9Ey5OBpWW3q/DUOV3Qihk+nyqX1pfCqpjfoNEZ1KT27VXk+L48drDy05z4cADxneKVFwmSQnEKtVEeNl2/tYZKPzoQKBgQC9FJIj2kqDBcYAPKDARUme756it7sOihG527ci7Tn2Qt0M/v5Lk+sUhlrlWTDHNrWNmGbONheK3Tc8bjw2r0CKRJNTYaVVvo435TdtudrwBEdniZq1SCrk9rKHOI68WaLnADCucQ15ip/OPvBYz4o/UKD7s4GIgVI4J4QGEVon8wKBgQC5DhA+Ik16bGZPXMiktWg8fy3I+7X/4NWNrB4JyB1n0A0/QFOf2IzmKzFPuaU6asFfEixYRtkIwaAUcjQvxuaDr7Rx7P6NAXCLWHfZ7jtz2E0vTGJEm8OziihYQBWvnhVK/MEX972cZlxl3IW9LAjmnId39R9uUlmE7054uvVGaQKBgDDoeUS82jk57RVymUIiqgBqiuYcEE6aeCtTIfPu2OdSNEuASdbS1CPi/PAGOg/NnviZSz5bz4sj3X3MJdcfTdp2EoWm5FVhjPf4WnYPdQpQkQe/GD18BVxkU5mWj5U4umJ2MiFtLMcbjGqU7SaLyH7IJFv2+rKMgO/1iLjpFCDxAoGAWCKfj8gsmGr6S0AIe5G+pFl6B+gCJW0CqKfZ4pTBlIjdVkufyFiNuq3FnY/wZqMjl9EC98Q+Z9I4GKTPwBV+Aifzy/KwxZ1y6Zrn8g5pmGHjWOyLPNvm2CKr1mQnL+4dfApnOLAQSWXjCcx+kbtgPd09E9/V6WglJJAJEq0QVkkCgYAlVXST5XTVLhrWl2zwb4hPIK/vlK1IcOGQSTrSV22fhxU1DVRNXuZSARkgqQ3ADWHfXKo05bDh4gaCzhUpKaBVbQ3AECSf7yhDA0Zrj9IR8hG9DsF3Hx7ArMZZuX/f11kZ13h+l5nLQDZtGZxw0EU7QqNlYx7yhGWp1++ChpQ/2A==",

            //异步通知地址
            'notify_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/member/payment/alipaynotify',

            //同步跳转
            'return_url' => 'http://' . $_SERVER['HTTP_HOST'] . '/member/payment/alipayreturn',

            //编码格式
            'charset' => "UTF-8",

            //签名方式
            'sign_type' => "RSA2",

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAlXIEKcnI9b18eoc0Sej67Ca8Ek5ft67WzqZeqLLwefvvj324FdLFcD1Kes1OFTATsezhapGY1nzFrYCWuYUwmyQpjM+SGAXjB1OpbRxuy2Qxninb7DHqKGz/U3ZIXS5G9LaE4sL4/KneKKtGqa8R+j7mUe4OoWv0UOeRvhxQRtdWKjC3Bb2ilGcUbcujgtrieIsYjj9v3feWL4SCxk4zaL+kvpbuKX0RnOCmsLyADgeGG/urP96+mMnh0zBvu4mA/0setrgGTN07khRSwHVcbmuF9FKGZgjd8QCy4mZkuMXnvgKj61ejmuSTC3FrTHbxdeTW3VbkJNiNfwJ1AsBmRwIDAQAB",
        );
    }

    /**
     * @param $order
     * @throws Exception
     * 支付
     */
    public function pay($order)
    {

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = trim($order['order_sn']);
        //商品描述，可空
        $body = "";
        if ($order['order_type'] == 1) {
            //订单名称，必填
            $subject = trim($order['integral_name'] . '  有效期:' . $order['use_duration'] . '天' . '  免费查询' . $order['integral_free_count'] . '次/日');
        } else {
            $subject = trim( $order['integral_quantity'] . '积分');
        }

        //付款金额，必填
        $total_amount = trim($order['order_amount']);


        //构造参数
        $payRequestBuilder = new AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new AlipayTradeService($this->config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->pagePay($payRequestBuilder, $this->config['return_url'], $this->config['notify_url']);
    }

    /**
     * 交易查询
     */
    public function query()
    {
//商户订单号，商户网站订单系统中唯一订单号
        $out_trade_no = trim($_POST['WIDTQout_trade_no']);

        //支付宝交易号
        $trade_no = trim($_POST['WIDTQtrade_no']);
        //请二选一设置
        //构造参数
        $RequestBuilder = new AlipayTradeQueryContentBuilder();
        $RequestBuilder->setOutTradeNo($out_trade_no);
        $RequestBuilder->setTradeNo($trade_no);

        $aop = new AlipayTradeService($this->config);

        /**
         * alipay.trade.query (统一收单线下交易查询)
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @return $response 支付宝返回的信息
         */
        $response = $aop->Query($RequestBuilder);
    }

    /**
     * 交易关闭
     */
    public function close()
    {
        //商户订单号，商户网站订单系统中唯一订单号
        $out_trade_no = trim($_POST['WIDTCout_trade_no']);

        //支付宝交易号
        $trade_no = trim($_POST['WIDTCtrade_no']);
        //请二选一设置

        //构造参数
        $RequestBuilder = new AlipayTradeCloseContentBuilder();
        $RequestBuilder->setOutTradeNo($out_trade_no);
        $RequestBuilder->setTradeNo($trade_no);

        $aop = new AlipayTradeService($this->config);

        /**
         * alipay.trade.close (统一收单交易关闭接口)
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @return $response 支付宝返回的信息
         */
        $response = $aop->Close($RequestBuilder);
    }

    /**
     * 同步返回函数
     */
    public function return_url()
    {
        $arr=$_GET;
        $alipaySevice = new AlipayTradeService($this->config);
        $result = $alipaySevice->check($arr);
        $alipaySevice->writeLog(var_export($_GET,true).'    %%%%%%%%%%%----------------'.$result);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 异步返回函数
     */
    public function notify_url(){
        $arr=$_POST;
        $notify_result = array(
            'trade_status' => '0',
        );
        $alipaySevicea = new AlipayTradeService($this->config);

        $result = $alipaySevicea->check($arr);
        $alipaySevicea->writeLog(var_export($_POST,true).'    %%%%%%%%%%%//////////////////////////'.$result);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            if ($arr['trade_status'] == 'TRADE_SUCCESS') {
                $notify_result = array(
                    'out_trade_no' => input('post.out_trade_no'), #商户订单号
                    'trade_no' => input('post.trade_no'), #交易凭据单号
                    'total_fee' => input('post.total_amount'), #涉及金额
                    'order_type' => input('post.body'),
                    'trade_status' => '1',
                );
            }
        }
        return $notify_result;
    }
}